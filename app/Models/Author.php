<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{

    protected $fillable = ['id', 'first_name', 'middle_name', 'last_name'];

    public function book()
    {
        return $this->belongsToMany(Book::class, 'book_author', 'author_id', 'book_id');
    }
}