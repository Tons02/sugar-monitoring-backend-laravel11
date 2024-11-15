<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailySugarResource extends JsonResource
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
            'user' => [
                'id' => $this->user->id,
                'name' => trim($this->user->first_name . ' ' . $this->user->middle_name . ' ' . $this->user->last_name), 
            ],
            'mgdl' => $this->mgdl,
            'description' => $this->description,
            'date' => $this->date,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at
        ];
    }
}
