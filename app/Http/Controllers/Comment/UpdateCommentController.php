<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UpdateCommentController extends Controller
{
    use HttpResponses;

    public function __invoke(Request $request, string $id)
    {
        $updateCommentRequest = new UpdateCommentRequest();

        $validator = Validator::make($request->all(), $updateCommentRequest->rules());

        if ($validator->fails()) {
            return $this->error('Erro de validação', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $comment = Comment::find($id);

        if (!$comment) {
            return $this->error('Comentário não encontrado', Response::HTTP_NOT_FOUND);
        }

        if (Auth::check() && ($comment->user_id !== Auth::id())) {
            return $this->error('Você não tem permissão para atualizar este comentário', Response::HTTP_FORBIDDEN);
        }

        $comment->update($validator->validated());
        return $this->response('Comentário atualizado com sucesso', Response::HTTP_OK, new CommentResource($comment));
    }
}
