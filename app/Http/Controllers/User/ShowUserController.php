<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Response;

class ShowUserController extends Controller
{
    use HttpResponses;

    public function __invoke(string $id)
    {
        $user = User::with('posts')->find($id);

        if (!$user) {
            return $this->error('Usuário não encontrado', Response::HTTP_NOT_FOUND);
        }

        return $this->response('Usuário encontrado com sucesso', Response::HTTP_OK, new UserResource($user));
    }
}
