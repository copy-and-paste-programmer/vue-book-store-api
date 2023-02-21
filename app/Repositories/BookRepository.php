<?php

namespace App\Repositories;

use Throwable;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\Storage;

class BookRepository
{
    protected $imageService;
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        return Book::with(['image', 'categories', 'author'])->paginate(10);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $book = Book::query()->create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'author_id' => $request->author_id,
                'publisher' => $request->publisher,
                'published_at' => $request->published_at,
            ]);

            $image = $this->imageService->upload($request->file('image'));
            $book->image()->save($image);
            $book->categories()->attach($request->categories);
            $book->load(['image', 'categories', 'author']);

            DB::commit();
            return new BookResource($book);
        } catch (Throwable $th) {
            Storage::delete($image);
            DB::rollBack();
            return abort(500, 'Book stores failed');
        }
    }

    public function show($id)
    {
        return Book::with(['image','author','categories'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        DB::beginTransaction();
        try {
            $book->query()->update([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'author_id' => $request->author_id,
                'publisher' => $request->publisher,
                'published_at' => $request->published_at,
            ]);
            if ($request->hasFile('image')) {
                Storage::delete($book->image->path);
                $book->image()->where('imageable_id',$id)->delete();
                $image = $this->imageService->upload($request->file('image'));
                $book->image()->save($image);
            }
            $book->categories()->sync($request->categories);
            $book->load(['image', 'categories', 'author']);
            DB::commit();

            return $book;
        } catch (Throwable $th) {
            Storage::delete($image);
            DB::rollBack();
            
            return abort(500, 'Book update failed');
        }
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        Storage::delete($book->image->path);
        $book->image()->where('imageable_id',$id)->delete();
        $book->delete();
    }
}
