<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UpdatePostController extends Controller
{
    use HttpResponses;

    public function __invoke(Request $request, string $id)
    {
        $updatePostRequest = new UpdatePostRequest();

        $validator = Validator::make($request->all(), $updatePostRequest->rules());

        if ($validator->fails()) {
            return $this->error('Erro de validação', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $post = Post::find($id);

        if (!$post) {
            return $this->error('Post não encontrado', Response::HTTP_NOT_FOUND);
        }

        if (Auth::check() && ($post->user_id !== Auth::id())) {
            return $this->error('Você não tem permissão para atualizar este post', Response::HTTP_FORBIDDEN);
        }

        $post->update($validator->validated());

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $filePath = 'posts/' . $post->id . '/' . $fileName;
            $disk = env('APP_ENV') === 'production' ? 's3' : 'local';
            Storage::disk($disk)->put($filePath, file_get_contents($file), 'public');
            $post->update(['image' => $filePath]);
        }

        return $this->response('Post atualizado com sucesso', Response::HTTP_OK, new PostResource($post));
    }
}
