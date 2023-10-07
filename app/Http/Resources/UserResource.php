<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $created_at = Carbon::parse($this->created_at)->format('d/m/Y H:i:s');
        $updated_at = Carbon::parse($this->updated_at)->format('d/m/Y H:i:s');
        $time_since_created = Carbon::parse($this->created_at)->diffForHumans();
        $time_since_updated = Carbon::parse($this->updated_at)->diffForHumans();

        $posts = $this->posts->map(function ($post) {
            return [
                'id'            => $post->id,
                'title'         => $post->title,
                'content'       => $post->content,
                'image'         => $post->image,
                'created_at'    => Carbon::parse($post->created_at)->diffForHumans(),
                'updated_at'    => Carbon::parse($post->updated_at)->diffForHumans(),
            ];
        });
        $posts = $posts->isEmpty() ? null : $posts;

        return [
            'id'                    => $this->id,
            'email'                 => $this->email,
            'username'              => $this->username,
            'name'                  => $this->name,
            'avatar'                => $this->avatar,
            'posts'                 => $posts,
            'time_since_created'    => $time_since_created,
            'time_since_updated'    => $time_since_updated,
            'created_at'            => $created_at,
            'updated_at'            => $updated_at,
        ];
    }
}
