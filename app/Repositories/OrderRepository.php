<?php

namespace App\Repositories;

use Throwable;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderRepository
{
    public function index()
    {
        return Order::query()
            ->with([
                'orderDetail:quantity,order_id,book_id',
                'orderDetail.book:id,name,price'
            ])
            ->where('user_id', request()->user()->id)
            ->get();
    }

    public function show($id)
    {
        return Order::with([
            'orderDetail',
            'orderDetail.book',
            'orderDetail.book.image'
        ])->findOrFail($id);
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
            $createdOrder = Order::create([
                'user_id' => $request->user()->id,
                'phone_no' => $request->phone_no,
                'address' => $request->address,
                'total_price' => $totalPrice,
                'status' => 1
            ]);

            foreach ($books as $book) {
                OrderDetail::create([
                    'order_id' => $createdOrder->id,
                    'book_id' => $book->id,
                    'quantity' => $book->qty,
                ]);
            }
            DB::commit();
            $order = $createdOrder->select(['id', 'phone_no', 'address', 'total_price', 'status', 'created_at'])->first();
            $order->user_name = $request->user()->name;
            return [
                'order' => $order,
                'order_detail' => $books,
            ];
        } catch (Throwable $e) {
            Log::error(__FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());

            DB::rollBack();

            abort(500, 'The book order failed.');
        }
    }
}
