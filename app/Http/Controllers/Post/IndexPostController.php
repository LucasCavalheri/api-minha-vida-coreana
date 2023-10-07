<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class IndexPostController extends Controller
{
    use HttpResponses;

    public function __invoke(Request $request)
    {
        $posts = Post::with(['user', 'comments'])->get();

        return $this->response('Posts listados com sucesso', 200, PostResource::collection($posts));
    }
}
