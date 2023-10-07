<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Traits\HttpResponses;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

class ShowPostController extends Controller
{
    use HttpResponses;

    public function __invoke(string $id)
    {
        $post = Post::with(['user', 'comments'])->find($id);

        if (!$post) {
            return $this->error('Post não encontrado', Response::HTTP_NOT_FOUND);
        }

        $cacheKey = 'post_views_' . Request::ip() . '_' . $post->id;

        if (!Cache::has($cacheKey)) {
            $post->increment('view_count');
            Cache::put($cacheKey, true);
        }

        return $this->response('Post encontrado com sucesso', Response::HTTP_OK, new PostResource($post));
    }
}
