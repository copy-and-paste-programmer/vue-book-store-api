<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_no',
        'address',
        'total_price',
        'status',
        'reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class)->withTimestamps();
    }

    public function orderBooks()
    {
        return $this->belongsToMany(Book::class)
            ->select([
                'books.id',
                'books.name',
                'books.price',
                'book_order.quantity as quantity',
            ]);
    }
}
