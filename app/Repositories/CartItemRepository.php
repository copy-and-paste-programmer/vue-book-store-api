<?php
namespace App\Repositories;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemRepository
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return CartItem::with(['book'])->where('user_id', $request->user()->id)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cartItem = $request->only(['book_id', 'quantity']);
        $cartItem['user_id'] = $request->user()->id;
        $newCartItem = CartItem::create($cartItem);
        $newCartItem->load('book');

        return $newCartItem;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cartItem = $request->only(['book_id', 'quantity']);
        
        $updateCartItem = CartItem::where('user_id',$request->user()->id)->findOrFail($id);
        
        $updateCartItem->update($cartItem);

        $updateCartItem->load('book');
        
        return $updateCartItem;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
