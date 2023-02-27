<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'phone_no' => $this->phone_no,
            'total_price' => $this->total_price,
            'address' => $this->address,
            'user' => $this->whenLoaded('user'),
            'order_books' => OrderBookResource::collection($this->whenLoaded('orderBooks')),
        ];
    }
}
