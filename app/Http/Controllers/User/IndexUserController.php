<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class IndexUserController extends Controller
{
    use HttpResponses;

    public function __invoke()
    {
        $users = User::with('posts')->get();

        return $this->response('Usuários encontrados com sucesso', 200, UserResource::collection($users));
    }
}
