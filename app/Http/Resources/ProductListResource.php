<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'images' => ProductImagesResource::collection($this->images),
            'image' => new ProductImagesResource($this->images->first()),
            'price' => $this->price,
            'quantity' => $this->quantity,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
