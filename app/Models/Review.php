<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'review',
        'rating',
        'user_id',
        'google_books_id',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'google_books_id', 'google_books_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
