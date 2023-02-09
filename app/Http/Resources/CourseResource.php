<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'description' => $this->description,
            'max_students' => $this->max_students,
            'price' => $this->price,
            'area' => $this->whenLoaded('area'),
            'enrollments' => $this->whenLoaded('enrollments'),
            'ratings' => $this->whenLoaded('ratings'),
            'user' => $this->whenLoaded('user'),
        ];
    }
}
