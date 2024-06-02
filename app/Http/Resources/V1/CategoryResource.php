<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class CategoryResource extends JsonResource
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
            'slug' => $this->slug,
            'description' => $this->description,
            'storageUrl' => Storage::url($this->photo),
            'filePath' => $this->photo,
            'editUrl' => URL::route('categories.edit', $this->id),
            'courses' => $this->courses->map(function($item){
                return [
                    'id' => $item->id,
                    'slug' => $item->slug,
                    'name' => $item->name,
                    'description' => $item->description,
                    'price' => $item->price,
                    'cover' => $item->cover,
                ];
            })
        ];
    }
}
