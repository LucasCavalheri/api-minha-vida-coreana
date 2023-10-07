<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Traits\HttpResponses;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DeleteCommentController extends Controller
{
    use HttpResponses;

    public function __invoke(string $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return $this->error('Comentário não encontrado', Response::HTTP_NOT_FOUND);
        }

        if (Auth::check() && ($comment->user_id !== Auth::id() && !Auth::user()->is_admin)) {
            return $this->error('Você não tem permissão para deletar este comentário', Response::HTTP_FORBIDDEN);
        }

        $comment->delete();
        return $this->response('Comentário deletado com sucesso', Response::HTTP_OK);
    }
}
