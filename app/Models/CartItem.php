<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'quantity',
        'user_id'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class)
            ->select([
                'books.id',
                'books.name',
                'books.price'
            ]);
    }
}
