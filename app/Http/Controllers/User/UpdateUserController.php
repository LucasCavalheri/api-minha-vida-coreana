<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UpdateUserController extends Controller
{
    use HttpResponses;

    public function __invoke(Request $request, string $id)
    {
        $updateUserRequest = new UpdateUserRequest();

        $validator = Validator::make($request->all(), $updateUserRequest->rules());

        if ($validator->fails()) {
            return $this->error('Erro de validação', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $user = User::find($id);

        if (!$user) {
            return $this->error('Usuário não encontrado', Response::HTTP_NOT_FOUND);
        }

        if (Auth::check() && ($user->user_id !== Auth::id())) {
            return $this->error('Você não tem permissão para atualizar este usuário', Response::HTTP_FORBIDDEN);
        }

        $user->update($validator->validated());

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = $file->getClientOriginalName();
            $filePath = 'avatars/' . $user->id . '/' . $fileName;
            $disk = env('APP_ENV') === 'production' ? 's3' : 'local';
            Storage::disk($disk)->put($filePath, file_get_contents($file), 'public');
            $user->update(['avatar' => $filePath]);
        }

        return $this->response('Usuário atualizado com sucesso', Response::HTTP_OK, $user);
    }
}
