<?php

namespace App\Http\Controllers\Like;

use App\Http\Controllers\Controller;
use App\Http\Resources\LikeResource;
use App\Models\Like;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StoreLikeController extends Controller
{
    use HttpResponses;

    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment_id' => 'required|uuid|exists:comments,id',
        ]);

        if ($validator->fails()) {
            return $this->error('Erro de validação', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $like = Like::firstOrCreate(
            ['user_id' => Auth::id(), 'comment_id' => $request->comment_id],
        );

        if ($like->wasRecentlyCreated) {
            return $this->response('Like criado com sucesso', Response::HTTP_CREATED, new LikeResource($like));
        } else {
            return $this->response('Você já deu like neste comentário', Response::HTTP_BAD_REQUEST);
        }
    }
}
