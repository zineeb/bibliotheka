<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'status';

    protected $fillable = [
        'google_books_id',
        'user_id',
        'status',
        'page',
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
