<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DeletePostController extends Controller
{
    use HttpResponses;

    public function __invoke(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->error('Post não encontrado', Response::HTTP_NOT_FOUND);
        }

        if (Auth::check() && ($post->user_id !== Auth::id() && !Auth::user()->is_admin)) {
            return $this->error('Você não tem permissão para deletar este post', Response::HTTP_FORBIDDEN);
        }

        $post->delete();
        return $this->response('Post deletado com sucesso', Response::HTTP_NO_CONTENT);
    }
}
