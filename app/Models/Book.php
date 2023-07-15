<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Book extends Model
{
    protected $primaryKey = 'google_books_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'google_books_id',
        'title',
        'author',
        'description',
        'publisher',
        'page_count',
        'publication_date',
        'isbn',
        'genre',
        'cover_image',
    ];

    /**
     * Retrieve all books and their related information for the given user.
     * @param $userId
     * @return \Illuminate\Support\Collection
     */
    public static function allBooks($userId)
    {
        return DB::table('books')
            ->select('books.*', 'status.status', 'status.page', 'categories.name as category', 'reviews.review', 'reviews.rating')
            ->join('status', function ($join) use ($userId) {
                $join->on('books.google_books_id', '=', 'status.google_books_id')
                    ->where('status.user_id', '=', $userId);
            })
            ->leftJoin('reviews', function ($join) use ($userId) {
                $join->on('books.google_books_id', '=', 'reviews.google_books_id')
                    ->where('reviews.user_id', '=', $userId);
            })
            ->leftJoin('book_categories', 'books.google_books_id', '=', 'book_categories.google_books_id')
            ->leftJoin('categories', function ($join) use ($userId) {
                $join->on('book_categories.category_id', '=', 'categories.id')
                    ->where('categories.user_id', '=', $userId);
            })
            ->get();
    }

}
