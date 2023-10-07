<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DeleteUserController extends Controller
{
    use HttpResponses;

    public function __invoke(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->error('Usuário não encontrado', Response::HTTP_NOT_FOUND);
        }

        if (Auth::check() && ($user->user_id !== Auth::id() && !Auth::user()->is_admin)) {
            return $this->error('Você não tem permissão para deletar este usuário', Response::HTTP_FORBIDDEN);
        }

        $user->delete();
        return $this->response('Usuário deletado com sucesso', Response::HTTP_NO_CONTENT);
    }
}
