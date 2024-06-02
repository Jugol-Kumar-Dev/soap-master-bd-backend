<?php

namespace App\Http\Resources\V1;

use App\Models\Lesson;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'payment_method' => $this->payment_method,
//            'pay_amount' => $this->pay_amount,
            'pay_amount' => $this->amount,
            'currency' => $this->currency,
            'is_show' => $this->is_show,
            'created' => $this->created_at->format('d m Y'),
            "course" => $this->whenLoaded('course'),
            "mentor" => new UserResource($this->user),
            'totalLessons' => $this->whenCounted('chapters'),
            'lessons' => ChapterResource::collection($this->whenLoaded('chapters'))
        ];
    }
}
