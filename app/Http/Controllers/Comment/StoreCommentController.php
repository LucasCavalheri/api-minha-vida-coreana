<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StoreCommentController extends Controller
{
    use HttpResponses;

    public function __invoke(Request $request)
    {
        $storeCommentRequest = new StoreCommentRequest();

        $validator = Validator::make($request->all(), $storeCommentRequest->rules());

        if ($validator->fails()) {
            return $this->error('Erro de validação', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $validatedData = $validator->validated();
        $validatedData['user_id'] = Auth::id();

        if (isset($validatedData['parent_id'])) {
            $parentComment = Comment::find($validatedData['parent_id']);
            if (!$parentComment) {
                return $this->error('Comentário pai não encontrado', Response::HTTP_NOT_FOUND);
            }

            if ($parentComment->parent_id !== null) {
                return $this->error('Não é possível responder a uma resposta', Response::HTTP_BAD_REQUEST);
            }
        }

        $comment = Comment::create($validatedData);

        return $this->response('Comentário criado com sucesso', Response::HTTP_CREATED, new CommentResource($comment));
    }
}
