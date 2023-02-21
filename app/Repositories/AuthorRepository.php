<?php

namespace App\Repositories;

use Throwable;
use App\Models\Author;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AuthorRepository
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    /**
     * get author
     */
    public function index()
    {
        return Author::with(['image'])->paginate(10);
    }

    /**
     * create author
     */
    public function create(Request $request)
    {
        DB::beginTransaction();

        try {
            $author = Author::create([
                'name' => $request->name,
                'email' => $request->email,
                'description' => $request->description,
            ]);
            $image = $this->imageService->upload($request->file('image'), 'author');
            $author->image()->save($image);
            DB::commit();
            return $author;
        } catch (Throwable $e) {
            DB::rollback();
            abort(500);
        }
    }

    /**
     * update author
     */
    public function update(Request $request, $id)
    {
        $author = Author::with(['image'])->findOrFail($id);
        DB::beginTransaction();
        try {
            $author->query()->update([
                'name' => $request->name,
                'email' => $request->email,
                'description' => $request->description,
            ]);

            if ($request->hasFile('image')) {
                $image = $this->imageService->upload($request->file('image'));
                $author->image()->save($image);
                $author->image->delete();
                Storage::delete($author->image->path);
            }

            DB::commit();

            return $author;
        } catch (Throwable $e) {
            Log::error(__FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());
            DB::rollback();
            abort(500);
        }
    }

    /**
     * delete author
     */
    public function destroy($id)
    {
        $author = Author::findOrFail($id);

        DB::beginTransaction();

        try {
            $author->image()->delete();
            $author->delete();

            DB::commit();
        } catch (Throwable $e) {
            Log::error(__FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());
            DB::rollBack();
            abort(500);
        }
    }
}
