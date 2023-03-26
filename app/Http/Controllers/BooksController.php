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
    public function addCategory(Request $request)
    {
        //Research if the category already exists
        $category = Category::where('name', '=', $request['category'])->first(['name']);

        //If it's a new category, creation of this category in the table categories
        if ($category == null) {
//                $user_id = Auth::id();
            $user_id = 1;
            Category::create([
                'name' => $request['category'],
                'user_id' => $user_id,
            ]);

            return response()->json([
                'okay' => 'La category a été créé.',
            ], 200);

        } else {
            return response()->json([
                'notification' => 'La categorie existe déjà.',
                'category' => $category
            ], 200);
        }

    }

    public function addBook(Request $request)
    {
        //Validate the form data
        $validateData = Validator::make($request->all(), [
            'category' => 'required|string',
            'title' => 'required|string',
            'author' => 'required|string',
            'isbn' => 'sometimes|string',
            'reading_status' => 'required|string',
            'current_page' => 'sometimes|integer'
        ]);

        //If the data is wrong, return to the add book page with the errors made
        if ($validateData->fails()) {
            return response()->json([
                'errors' => $validateData->errors()
            ], 422);
        } else {
            //Creation of the url for the call of the Google Books Api according to the existence of the isbn in the user's request
            if ($request['isbn'] != null) {
                $request_uri = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' . $request['isbn'];
            } else {
                $request_uri = 'https://www.googleapis.com/books/v1/volumes?q=' . $request['title'] . '+inauthor:' . $request['author'];
            }

            Log::info(print_r('request_api : ' . $request_uri, true));

            try {
                //Use Guzzle to send the http request
                $client = new Client([
                    'verify' => storage_path('app/cacert.pem'),
                    'curl' => [
                        CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
                        CURLOPT_SSL_CIPHER_LIST => 'AES256-SHA256',
                    ],
                ]);
                $response = $client->get($request_uri);
                $statContent = $response->getBody()->getContents();

                //Retrieving data from the response to the request
                $data = json_decode($statContent, true);

                //If the Google Books API does not return anything, you will be returned to the add book page with an error message
                if (!isset($data['items'][0])) {
                    return response()->json([
                        'errors' => 'Le livre recherché n\'a pas été trouvé'
                    ], 422);
                } else {
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

                    //Check if the book already exists in the database
                    $id_book = Book::where('google_books_id', '=', $google_books_id)->first(['google_books_id']);

                    if ($id_book == null) {
                        //Creation of a book
                        $book = Book::create([
                            'google_books_id' => $google_books_id,
                            'title' => $title,
                            'author' => $author,
                            'description' => $description,
                            'publisher' => $publisher,
                            'page_count' => $page_count,
                            'publication_date' => $publication_date,
                            'isbn' => $isbn,
                            'genre' => $genre,
                            'cover_image' => $cover_image
                        ]);
                    } else {
                        //Retrieve the book from the database
                        $book = Book::all()->where('google_books_id', '=', $google_books_id);
                    }

                    //Research the category of the user in the categories database
                    $category = Category::where('name', '=', $request['category'])->first(['id']);;

                    //If the category doesn't exist, creation of a category and the link between this category and the book
                    if ($category == null) {
//                        $user_id = Auth::id();
                        $user_id = 1;
                        Category::create([
                            'name' => $request['category'],
                            'user_id' => $user_id,
                        ]);

                        $lastId = DB::getPdo()->lastInsertId();

                        BookCategory::create([
                            'google_books_id' => $google_books_id,
                            'category_id' => $lastId
                        ]);
                    } else {
                        //Creation of a category and the link between this category and the book
                        BookCategory::create([
                            'google_books_id' => $google_books_id,
                            'category_id' => $category->id
                        ]);
                    }

                    //Retrieve with the last three reviews and comments of the book from other users
                    $reviews = DB::table('reviews')
                        ->select('review', 'rating')
                        ->where('google_books_id', $google_books_id)
                        ->orderByDesc('created_at')
                        ->limit(3)
                        ->pluck('rating', 'reviews');

                    $review = $reviews->keys();
                    $rating = $reviews->values();

                    //Retrieve the status of the user's book
                    $status = $request['reading_status'];

                    //Depending on the reading status of the book, the actual number of pages will change
                    if ($status == 'read') {
                        $page = $page_count;
                    } elseif ($status == 'to_read') {
                        $page = 0;
                    } else {
                        $page = $request['current_page'];
                    }

                    //Insert into status table
                    Status::create([
                        'google_books_id' => $google_books_id,
//                        'user_id' => Auth::id(),
                        'user_id' => 1,
                        'status' => $status,
                        'page' => $page,
                    ]);

                    //Return the review, rating and the book selected
                    return response()->json([
                        'reviews' => $review,
                        'rating' => $rating,
                        'books' => $book
                    ]);
                }
            } catch (GuzzleException $e) {
                Log::error($e->getMessage());
                return response()->json([
                    'errors' => 'Une erreur s\'est produite'
                ], 500);
            }

        }
    }

    public function addRewiew(Request $request)
    {

        $review = Review::where('google_books_id', $request['google_books_id'])
//                ->where('user_id',Auth::id())
            ->where('user_id', 1)
            ->first(['id']);

        if ($review != null) {
            Review::where('google_books_id', $request['google_books_id'])
//                ->where('user_id',Auth::id())
                ->where('user_id', 1)
                ->update([
                    'review' => $request['review'],
                    'rating' => $request['rating'],
                ]);

            return response()->json([
                'modification' => 'votre commentaire et votre note ont bien été modifiés.'
            ], 200);
        } else {
            Review::create([
                'review' => $request['review'],
                'rating' => $request['rating'],
                //'user_id' => Auth::id(),
                'user_id' => 1,
                'google_books_id' => $request['google_books_id']
            ]);

            return response()->json([
                'modification' => 'votre commentaire et votre note ont bien été crées.'
            ], 200);

        }
    }

    public function deleteBook(Request $request)
    {
        $google_books_id = $request->input('google_books_id');

        DB::beginTransaction();

        try {
            // Delete entries in book_categories table
            BookCategory::where('google_books_id', $google_books_id)->delete();

            // Delete entries in reviews table
            Review::where('google_books_id', $google_books_id)->delete();

            // Delete entries in status table
            Status::where('google_books_id', $google_books_id)->delete();

            // Delete entry in books table
            $book = Book::findOrFail($google_books_id);
            $book->delete();

            DB::commit();

            return response()->json(['message' => 'Le livre a été supprimé avec succès.']);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return response()->json(['message' => 'Une erreur s\'est produite lors de la suppression du livre. Veuillez réessayer.']);
        }
    }

}
