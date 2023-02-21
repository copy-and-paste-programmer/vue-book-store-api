<?php

namespace App\Repositories;

use Throwable;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $data = $request->only(
            [
                'name',
                'email',
                'price',
                'description',
                'author_id',
                'publisher',
                'published_at'
            ]
        );

        DB::beginTransaction();

        try {
            $book = Book::query()->create($data);
            $image = $this->imageService->upload($request->file('image'));
            $book->image()->save($image);
            $book->categories()->attach($request->categories);
            $book->load(['image', 'categories', 'author']);

            DB::commit();

            return $book;
        } catch (Throwable $e) {

            Log::error(__FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());

            Storage::delete($image?->path);

            DB::rollBack();

            abort(500, 'The book is not created.');
        }
    }

    public function show($id)
    {
        return Book::with(['image', 'author', 'categories'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $data = $request->only(
            [
                'name',
                'email',
                'price',
                'description',
                'author_id',
                'publisher',
                'published_at'
            ]
        );


        DB::beginTransaction();

        try {
            $book->query()->update($data);

            if ($request->hasFile('image')) {
                Storage::delete($book->image->path);
                $book->image()->delete();
                $image = $this->imageService->upload($request->file('image'));
                $book->image()->save($image);
            }

            $book->categories()->sync($request->categories);

            DB::commit();

            $book->load(['image', 'categories', 'author']);

            return $book;
        } catch (Throwable $e) {
            Log::error(__FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());

            Storage::delete($image?->path);

            DB::rollBack();

            abort(500, 'The book is not updated.');
        }
    }

    public function destroy($id)
    {
        $book = Book::with(['image'])->findOrFail($id);
        $path = $book->image->path;

        DB::beginTransaction();

        try {
            $book->image()->delete();
            $book->delete();

            DB::commit();
        } catch (Throwable $e) {

            Log::error(__FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());

            DB::rollBack();

            abort(500, 'The book is not deleted.');
        }

        Storage::delete($path);
    }
}
