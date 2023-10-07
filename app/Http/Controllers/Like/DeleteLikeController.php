<?php

namespace App\Http\Controllers\Like;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DeleteLikeController extends Controller
{
    use HttpResponses;

    public function __invoke(string $id)
    {
        $like = Like::where('id', $id)
                ->first();

        if (!$like) {
            return $this->error('Like não encontrado', Response::HTTP_NOT_FOUND);
        }

        if (Auth::check() && ($like->user_id !== Auth::id())) {
            return $this->error('Você não tem permissão para remover este like', Response::HTTP_FORBIDDEN);
        }

        $like->delete();

        return $this->response('Like removido com sucesso', Response::HTTP_OK);
    }
}
