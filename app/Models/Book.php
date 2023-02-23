<?php

namespace App\Models;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'price',
        'description',
        'author_id',
        'publisher',
        'published_at'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function rating()
    {
        return $this->hasMany(BookRating::class);
    }
}
