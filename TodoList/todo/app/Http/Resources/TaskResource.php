<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title ?? '',
            'description' => $this->description ?? '',
            'is_completed' => (bool) $this->is_completed,
            'due_date' => $this->due_date ? $this->due_date->toDateString() : null,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : null,
        ];
    }
}
