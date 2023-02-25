<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderRepository
{
    public function index(Request $request)
    {
        return Order::query()
            ->with([
                'orderDetail:quantity,order_id,book_id',
                'orderDetail.book:id,name,price',
            ])
            ->where('user_id', $request->user()->id)
            ->get();
    }

    public function show(Request $request, $id)
    {
        return Order::query()->with([
            'orderDetail',
            'orderDetail.book',
            'orderDetail.book.image',
        ])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);
    }

    public function order(Request $request)
    {
        $orderBooks = $request->collect('books');

        $books = Book::select(['id', 'name', 'price'])
            ->whereIn('id', $orderBooks->pluck('id'))
            ->get()
            ->map(function ($book) use ($orderBooks) {
                $qty = $orderBooks->firstWhere('id', $book->id)['qty'];
                $book->qty = $qty;
                $book->total_price = $book->price * $qty;
                return $book;
            });

        $totalPrice = $books->sum('total_price');
        DB::beginTransaction();
        try {
            $newOrder = Order::create([
                'user_id' => $request->user()->id,
                'phone_no' => $request->phone_no,
                'address' => $request->address,
                'total_price' => $totalPrice,
                'status' => 1,
            ]);

            foreach ($books as $book) {
                OrderDetail::create([
                    'order_id' => $newOrder->id,
                    'book_id' => $book->id,
                    'quantity' => $book->qty,
                ]);
            }
            DB::commit();
            $newOrder->user_name = $request->user()->name;
            return [
                'order' => $newOrder,
                'order_detail' => $books,
            ];
        } catch (Throwable $e) {
            Log::error(__FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());

            DB::rollBack();

            abort(500, 'The order is failed.');
        }
    }
}
