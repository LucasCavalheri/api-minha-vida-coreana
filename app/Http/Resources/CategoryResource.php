<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $posts = $this->posts->isEmpty() ? null : $this->posts->map(function ($post) {
            return [
                'id'       => $post->id,
                'title'    => $post->title,
            ];
        });

        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'posts'     => $posts
        ];
    }
}
