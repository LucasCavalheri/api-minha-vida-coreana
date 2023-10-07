<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
      /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $created_at         = Carbon::parse($this->created_at)->format('d/m/Y H:i:s');
        $updated_at         = Carbon::parse($this->updated_at)->format('d/m/Y H:i:s');
        $time_since_created = Carbon::parse($this->created_at)->diffForHumans();
        $time_since_updated = Carbon::parse($this->updated_at)->diffForHumans();

        $comments = $this->comments->map(function ($comment) {
            return [
                'id'         => $comment->id,
                'content'    => $comment->content,
                'user'       => $comment->user->name,
                'user_id'    => $comment->user_id,
                'created_at' => Carbon::parse($comment->created_at)->diffForHumans(),
                'updated_at' => Carbon::parse($comment->updated_at)->diffForHumans(),
            ];
        });
        $comments = $comments->isEmpty() ? null : $comments;

        $categories = $this->categories->map(function ($category) {
            return [
                'id'   => $category->id,
                'name' => $category->name,
            ];
        });
        $categories = $categories->isEmpty() ? null : $categories;

        return [
            'id'                 => $this->id,
            'title'              => $this->title,
            'slug'               => $this->slug,
            'view_count'         => $this->view_count,
            'content'            => $this->content,
            'image'              => $this->image,
            'user'               => $this->user->name,
            'user_id'            => $this->user_id,
            'comments_count'     => $this->comments->count(),
            'comments'           => $comments,
            'categories_count'   => $this->categories->count(),
            'categories'         => $categories,
            'time_since_created' => $time_since_created,
            'time_since_updated' => $time_since_updated,
            'created_at'         => $created_at,
            'updated_at'         => $updated_at,
        ];
    }
}
