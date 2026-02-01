<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'news_id' => $this->news_id,
            'title' => $this->title,
            'content' => $this->content,
            'category_id' => $this->category_id,
            'category_name' => $this->category_name,
            'release_date' => $this->release_date?->format('Y-m-d'),
            'picture_url' => $this->picture_url,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}

