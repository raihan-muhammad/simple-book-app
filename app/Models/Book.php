<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = ['id', 'title', 'total_pages', 'rating', 'isbn', 'published_date'];

    public function author()
    {
        return $this->belongsToMany(Author::class, 'book_author', 'book_id', 'author_id');
    }
}