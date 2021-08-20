<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'price' => $this->price,
            'composition' => $this->composition,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'stock' => $this->stock,
            'category' => $this->category,
            'image' => $this->image,
            'history' => $this->history,
        ];
    }
}
