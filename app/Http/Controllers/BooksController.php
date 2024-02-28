<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Category;
use App\Models\Review;
use App\Models\Status;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BooksController extends Controller
{

    public function searchBooks(Request $request) {
        $query = $request->query('query');

        if (empty($query)) {
            return response()->json([]);
        }

        $request_uri = 'https://www.googleapis.com/books/v1/volumes?q=' . urlencode($query);
        $request_uri .= '&maxResults=5';

        try {
            $client = new Client([
                'verify' => base_path('certs/cacert.pem'),
                'curl' => [
                    CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
                    CURLOPT_SSL_CIPHER_LIST => 'AES256-SHA256',
                ],
            ]);


            // Make the API request and get the response.
            $response = $client->get($request_uri);
            $data = json_decode($response->getBody()->getContents(),true);

            return response()->json($data['items'] ?? []);
        } catch (GuzzleException $e) {
            return response()->json(['error' => 'Une erreur est survenue lors de la recherche du livre.'], 500);
        }
    }

    /**
     * This function is used to retrieve book information using the Google Books API.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function retrieveBook(Request $request)
    {
        // Validate the request data.
        $validateData = Validator::make($request->all(), [
            'title' => 'required|string',
            'author' => 'required|string',
        ]);

        // If validation fails, return a JSON response with errors.
        if ($validateData->fails()) {
            return response()->json([
                'errors' => $validateData->errors()
            ], 422);
        } else {
            // Build the API request URL based on ISBN or title and author.
            $request_uri = 'https://www.googleapis.com/books/v1/volumes?q=' . $request['title'] . '+inauthor:' . $request['author'];


            try {
                // Create a new Guzzle client with the appropriate SSL settings.
                $client = new Client([
                    'verify' => base_path('certs/cacert.pem'),
                    'curl' => [
                        CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
                        CURLOPT_SSL_CIPHER_LIST => 'AES256-SHA256',
                    ],
                ]);

                // Make the API request and get the response.
                $response = $client->get($request_uri);
                $statContent = $response->getBody()->getContents();

                // Decode the JSON response.
                $data = json_decode($statContent, true);

                // Check if the book was found.
                if (!isset($data['items'][0])) {
                    return response()->json([
                        'errors' => 'Le livre recherché n\'a pas été trouvé'
                    ], 422);
                } else {
                    // Extract book information from the response.
                    $bookData = $data['items'][0]['volumeInfo'];
                    $google_books_id = $data['items'][0]['id'];
                    $title = isset($bookData['title']) ? $bookData['title'] : "";
                    $author = isset($bookData['authors']) ? implode(',', $bookData['authors']) : "";
                    $description = isset($bookData['description']) ? $bookData['description'] : "";
                    $publisher = isset($bookData['publisher']) ? $bookData['publisher'] : "";
                    $page_count = isset($bookData['pageCount']) ? $bookData['pageCount'] : "";
                    $publication_date = isset($bookData['publishedDate']) ? $bookData['publishedDate'] : "";
                    $genre = isset($bookData['categories']) ? implode(',', $bookData['categories']) : "";
                    $isbn = isset($bookData['industryIdentifiers'][0]['identifier']) ? $bookData['industryIdentifiers'][0]['identifier'] : "";
                    $cover_image = isset($bookData['imageLinks']['thumbnail']) ? $bookData['imageLinks']['thumbnail'] : "";

                    // Prepare the book data for the JSON response.
                    $book = [
                        'google_books_id' => $google_books_id,
                        'title' => $title,
                        'author' => $author,
                        'description' => $description,
                        'publisher' => $publisher,
                        'page_count' => $page_count,
                        'publication_date' => $publication_date,
                        'genre' => $genre,
                        'isbn' => $isbn,
                        'coverimage' => $cover_image,
                    ];

                    // Retrieve the latest 3 reviews for the book from the database.
                    $reviews = DB::table('reviews')
                        ->select('review', 'rating')
                        ->where('google_books_id', $google_books_id)
                        ->orderByDesc('created_at')
                        ->limit(3)
                        ->pluck('rating', 'reviews');

                    // Separate review and rating arrays.
                    $review = $reviews->keys();
                    $rating = $reviews->values();

                    $user = Auth::user();
                    $user_id = auth()->user()->id;

                    $categories = DB::table('categories')
                        ->select('name')
                        ->where('user_id',$user_id)
                        ->get();

                    // Return a JSON response with the book and review information.
                    return response()->json([
                        'status' => 'success',
                        'book' => $book,
                        'review' => $review,
                        'rating' => $rating,
                        'categories' => $categories
                    ]);
                }
            } catch (GuzzleException $e) {
                // Log the exception and return an error response.
                Log::error($e->getMessage());
                return response()->json([
                    'errors' => 'Une erreur s\'est produite'
                ], 500);
            }

        }

    }

    /**
     * This function is used to add a book to the user's book collection.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addBook(Request $request)
    {
        $user_id = auth()->user()->id;

        $google_books_id = $request->input('book.id');
        $title = $request->input('book.volumeInfo.title');
        $description = $request->input('book.volumeInfo.description');
        $authorsArray = $request->input('book.volumeInfo.authors');
        $publisher = $request->input('book.volumeInfo.publisher');
        $publishedDate = $request->input('book.volumeInfo.publishedDate');
        $pageCount = $request->input('book.volumeInfo.pageCount');
        $industryIdentifiers = $request->input('book.volumeInfo.industryIdentifiers');
        $cover = $request->input('book.volumeInfo.imageLinks.thumbnail');
        $categoriesArray = $request->input('book.volumeInfo.categories');
        $readingStatus = $request->input('readingStatus');
        $rating = $request->input('rating');
        $review = $request->input('comment');
        $currentPage = $request->input('currentPage');

        $author = $authorsArray[0] ?? 'Auteur inconnu';
        $category = $categoriesArray[0] ?? 'Catégorie inconnue';

        // Trouve le premier ISBN-13
        $isbn = collect($industryIdentifiers)->firstWhere('type', 'ISBN_13')['identifier'] ?? 'ISBN inconnu';

        // Check if the book with the provided Google Books ID already exists in the database.
        $book = Book::where('google_books_id','=',$google_books_id)->first(['google_books_id']);

        // If the book does not exist, create a new entry for it.
        if (!isset($book)) {
            Book::create([
                'google_books_id' => $google_books_id,
                'title' => $title,
                'author' => $author,
                'description' => $description,
                'publisher' => $publisher,
                'page_count' => $pageCount,
                'publication_date' => $publishedDate,
                'genre' => $category,
                'isbn' => $isbn,
                'cover_image' => $cover
            ]);
        }

        $reviewData = [
            'review' => $review,
            'user_id' => $user_id,
            'google_books_id' => $google_books_id
        ];

        if ($rating !== null) {
            $reviewData['rating'] = $rating;
        }

        Review::create($reviewData);

        switch ($readingStatus) {
            case 'read':
                $page = $pageCount;
                break;
            case 'to_read':
                $page = 0;
                break;
            case 'reading':
                $page = $currentPage;
                break;
        }

        Status::create([
            'google_books_id' => $google_books_id,
            'user_id' => $user_id,
            'status' => $readingStatus,
            'page' => $page
        ]);

        return response()->json([
            'status' => 200,
        ]);

    }

    public function updateBookData(Request $request)
    {

        $user_id = auth()->user()->id;

        $google_books_id = $request->input('google_books_id');
        $pageCount = $request->input('page_count');
        $currentPage = $request->input('page');
        $readingStatus = $request->input('status');
        $rating = $request->input('rating');
        $review = $request->input('review');

        $reviewData = [
            'review' => $review,
            'user_id' => $user_id,
            'google_books_id' => $google_books_id
        ];

        if ($rating !== null) {
            $reviewData['rating'] = $rating;
        }

        Review::updateOrCreate(
            ['google_books_id' => $google_books_id, 'user_id' => $user_id],
            $reviewData
        );

        switch ($readingStatus) {
            case 'read':
                $page = $pageCount;
                break;
            case 'to_read':
                $page = 0;
                break;
            case 'reading':
                $page = $currentPage;
                break;
            default:
                $page = null;
        }

        Status::updateOrCreate(
            ['google_books_id' => $google_books_id, 'user_id' => $user_id],
            ['google_books_id' => $google_books_id, 'user_id' => $user_id, 'status' => $readingStatus, 'page' => $page]
        );
    }

    public function deleteBook(Request $request)
    {
        $google_books_id = $request->input('google_books_id');

        DB::beginTransaction();

        try {
            BookCategory::where('google_books_id', $google_books_id)->delete();

            // Delete entries in reviews table
            Review::where('google_books_id', $google_books_id)->delete();

            // Delete entries in status table
            Status::where('google_books_id', $google_books_id)->delete();

            // Delete entry in books table
            $book = Book::findOrFail($google_books_id);
            $book->delete();

            DB::commit();

            return response()->json([
                'status' => 200,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return response()->json(['message' => 'Une erreur s\'est produite lors de la suppression du livre. Veuillez réessayer.']);
        }
    }

}
