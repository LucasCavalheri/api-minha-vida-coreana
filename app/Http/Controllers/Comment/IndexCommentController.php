<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IndexCommentController extends Controller
{
    use HttpResponses;

    public function __invoke()
    {
        $comments = Comment::with(['user', 'post', 'likes'])->get();

        return $this->response('Comentários listados com sucesso', Response::HTTP_OK, CommentResource::collection($comments));
    }
}
