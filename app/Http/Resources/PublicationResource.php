<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicationResource extends JsonResource
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
            'pub_id' => $this->pub_id,
            'title' => $this->title,
            'image' => $this->image,
            'dl' => $this->dl,
            'date' => $this->date?->format('Y-m-d'),
            'abstract' => $this->abstract,
            'size' => $this->size,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}

