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

    public function index($request)
    {
        $search = $request->search;
        return Book::with(['image', 'categories', 'author'])
                    ->filter($search)
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
        $book = Book::with(['image'])->findOrFail($id);

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

    public function years()
    {
        $LastYear = config('constants.LAST_YEAR');
        $currentYear = date('Y');
        $lastNumberOfYear = substr($currentYear, 3);
        $decadeYear = $currentYear - $lastNumberOfYear;
        $years = [];
        $firstArr = [];
        if ($lastNumberOfYear <= 3) {
            for ($currentYear; $currentYear > $decadeYear; $currentYear--) {
                array_push($firstArr , $currentYear);
            }
        }
        else {
            for ($currentYear; $currentYear > $decadeYear+$decadeYear; $currentYear--) {
                array_push($firstArr , $currentYear);
            }
        }
        for ($decadeYear; $decadeYear >= $LastYear; $decadeYear-=10) {
            array_push($years, $decadeYear);
        }
        return array_merge($firstArr , $years);
    }

    public function booksOfYear($year)
    {   
        $years = [];
        $endOfThisYear = $year - 9;
        if (substr($year,3) == 0) {
            for ($year; $year >= $endOfThisYear; $year--) {
                $years[] = $year;
            }
            foreach($years as $year){
                $books = Book::whereYear('published_at' , $year)->get();
            }
            return $books;
        }
        else {
            $books = Book::whereYear('published_at' , $year)->get();
            return $books;
        }
    }
}
