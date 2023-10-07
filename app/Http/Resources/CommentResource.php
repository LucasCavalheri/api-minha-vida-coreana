<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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

        $replies = $this->replies->map(function ($reply) {
            return [
                'id'        => $reply->id,
                'content'   => $reply->content,
                'post_id'   => $reply->post_id,
                'post'      => $reply->post->title,
                'user_id'   => $reply->user_id,
                'user'      => $reply->user->name,
            ];
        });

        return [
            'id'                    => $this->id,
            'content'               => $this->content,
            'replies'               => $replies,
            'post_id'               => $this->post_id,
            'post'                  => $this->post->title,
            'user_id'               => $this->user_id,
            'user'                  => $this->user->name,
            'likes'                 => $this->likes->count(),
            'liked_by_users'        => $this->likes->pluck('user.name'),
            'time_since_created'    => $time_since_created,
            'time_since_updated'    => $time_since_updated,
            'created_at'            => $created_at,
            'updated_at'            => $updated_at,
        ];
    }
}
