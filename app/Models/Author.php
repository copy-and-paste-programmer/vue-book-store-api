<?php

namespace App\Models;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','email','description'
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function image(){
        return $this->morphOne(Image::class , 'imageable');
    }
}
