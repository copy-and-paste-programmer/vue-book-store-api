<?php

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Resources\BookResource;

class BookRepository 
{   
    protected $imageService;
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $books = Book::all();
        return BookResource::collection($books);
    }

    public function store(Request $request)
    {
        $book = Book::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'author_id' => $request->author_id,
            'publisher' => $request->publisher,
            'published_at' => $request->published_at
        ]);
        $this->imageService->upload($request->file('image'));
        $book->categories()->attach($request->categories);

        // return($book->with(['categories','image'])->first()) ;
    }
}

