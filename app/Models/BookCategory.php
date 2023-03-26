<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookCategory extends Model
{
    protected $fillable = [
        'google_books_id',
        'category_id',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'google_books_id', 'google_books_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
