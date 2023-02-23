<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\BookRating;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class BookRepository
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        return Book::with(['image', 'categories', 'author'])
            ->filter($request->search)
            ->paginate(10);
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
                'published_at',
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
        $book = Book::with(['image'])->findOrFail($id);

        $data = $request->only(
            [
                'name',
                'email',
                'price',
                'description',
                'author_id',
                'publisher',
                'published_at',
            ]
        );

        DB::beginTransaction();

        try {
            $book->query()->update($data);
            $book->categories()->sync($request->categories);

            if ($request->hasFile('image')) {
                $book->image()->delete();
                $image = $this->imageService->upload($request->file('image'));
                $book->image()->save($image);
                Storage::delete($book->image->path);
            }

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

        DB::beginTransaction();

        try {
            $book->image()->delete();
            $book->delete();
            Storage::delete($book->image->path);

            DB::commit();
        } catch (Throwable $e) {
            Log::error(__FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());

            DB::rollBack();

            abort(500, 'The book is not deleted.');
        }
    }

    public function rate(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $userBookRating = BookRating::where('book_id', $id)
                ->where('user_id', $request->user()->id)->first();

            $book = Book::where('id', $id)->first();

            if ($userBookRating) {
                $oldRating = $userBookRating->rating;
                $book->decrement('star' . $oldRating . '_count', 1);

                $userBookRating->update([
                    'rating' => $request->star,
                ]);
            } else {
                BookRating::create([
                    'book_id' => $id,
                    'user_id' => Auth::user()->id,
                    'rating' => $request->star,
                ]);
            }

            $book->increment('star' . $request->star . '_count', 1);

            $star1 = $book->star1_count;
            $star2 = $book->star2_count;
            $star3 = $book->star3_count;
            $star4 = $book->star4_count;
            $star5 = $book->star5_count;

            $totalCount = $star1 + $star2 + $star3 + $star4 + $star5;
            $averageRating = ($star1 + 2 * $star2 + 3 * $star3 + 4 * $star4 + 5 * $star5) / $totalCount;

            Book::where('id', $id)->update([
                'average_rating' => round($averageRating),
            ]);

            DB::commit();
        } catch (Throwable $e) {
            Log::error(__FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());

            DB::rollBack();

            abort(500, 'The book rating failed');
        }
    }
}
