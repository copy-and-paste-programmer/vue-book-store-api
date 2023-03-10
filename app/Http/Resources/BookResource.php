<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'publisher' => $this->publisher,
            'author' => new AuthorResource($this->whenLoaded('author')),
            'image' => new ImageResource($this->whenLoaded('image')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'average_rating' => $this->average_rating,
            'created_at' => $this->created_at,
        ];
    }
}
