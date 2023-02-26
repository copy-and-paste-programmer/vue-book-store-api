<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderRepository
{
    public function index(Request $request)
    {
        return Order::query()
            ->with(['orderBooks.image'])
            ->where('user_id', $request->user()->id)
            ->get();
    }

    public function show(Request $request, $id)
    {
        return Order::query()
            ->with(['orderBooks.image'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);
    }

    public function store(Request $request)
    {
        $orderBooks = $request->collect('books');

        $books = Book::query()->select(['id', 'name', 'price'])
            ->whereIn('id', $orderBooks->pluck('id'))
            ->get()
            ->map(function ($book) use ($orderBooks) {
                $quantity = $orderBooks->firstWhere('id', $book->id)['quantity'];
                $book->quantity = $quantity;
                $book->total_price = $book->price * $quantity;
                return $book;
            });

        $totalPrice = $books->sum('total_price');

        DB::beginTransaction();
        try {
            $newOrder = Order::query()->create([
                'user_id' => $request->user()->id,
                'phone_no' => $request->phone_no,
                'address' => $request->address,
                'total_price' => $totalPrice,
                'status' => 1,
            ]);

            foreach ($books as $book) {
                $newOrder->books()->attach($book->id, ['quantity' => $book->quantity]);
            }
            DB::commit();
            $newOrder->load(['user', 'orderBooks']);
            return $newOrder;
        } catch (Throwable $e) {
            Log::error(__FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());

            DB::rollBack();

            abort(500, 'The order is failed.');
        }
    }
}
