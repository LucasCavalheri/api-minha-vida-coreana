<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(Request $request)
    {
        $loginRequest = new LoginRequest();

        $validator = Validator::make($request->all(), $loginRequest->rules());

        if ($validator->fails()) {
            return $this->error('Erro de validação', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        if (!Auth::attempt($validator->validated())) {
            return $this->error('Credenciais inválidas', Response::HTTP_UNAUTHORIZED);
        }

        return $this->response('Login realizado com sucesso', Response::HTTP_OK, [
            'token' => Auth::user()->createToken('auth_token')->plainTextToken
        ]);
    }

    // Accept = application/json
    public function logout()
    {
        auth('sanctum')->user()->tokens()->delete();

        return $this->response('Logout realizado com sucesso', Response::HTTP_OK);
    }

    public function register(Request $request)
    {
        $registerRequest = new RegisterRequest();

        $validator = Validator::make($request->all(), $registerRequest->rules());

        if ($validator->fails()) {
            return $this->error('Erro de validação', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $user = User::create($validator->validated());

        if (!$user) {
            return $this->error('Erro ao criar usuário', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        event(new Registered($user));

        // if ($request->hasFile('avatar')) {
        //     $file = $request->file('avatar');
        //     $fileName = $file->getClientOriginalName();
        //     $filePath = 'avatars/' . $user->id . '/' . $fileName;
        //     $disk = env('APP_ENV') === 'production' ? 's3' : 'local';
        //     Storage::disk($disk)->put($filePath, file_get_contents($file), 'public');
        //     $user->update(['avatar' => $filePath]);
        // }


        return $this->response('Usuário criado com sucesso', Response::HTTP_CREATED, new UserResource($user));
    }
}
