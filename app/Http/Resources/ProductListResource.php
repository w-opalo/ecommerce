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
            'slung' => string,
            'price' => number,
            'quantity' => number,
            'image' => string,
            'user' => [
                'id' => number,
                'name' => string,
            ],
            'department' => [
                'id' => number,
                'name' => string,
            ],
        ];
    }
}
