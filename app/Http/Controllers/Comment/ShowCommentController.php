<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShowCommentController extends Controller
{
    use HttpResponses;

    public function __invoke(string $id)
    {
        $comment = Comment::with(['user', 'post', 'likes'])->find($id);

        if (!$comment) {
            return $this->error('Comentário não encontrado', Response::HTTP_NOT_FOUND);
        }

        return $this->response('Comentário listado com sucesso', Response::HTTP_OK, new CommentResource($comment));
    }
}
