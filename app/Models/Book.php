<?php

namespace App\Models;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function scopeFilter($query, $filter)
    {
        $query->when($filter ?? false, function ($query, $search) {
            $query->where('name', 'LIKE', '%' . $search . '%')
                ->orWhereHas('author', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('categories', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhere('publisher', 'LIKE', '%' . $search . '%')
                ->orWhere('price', $search);
        });
    }
}
