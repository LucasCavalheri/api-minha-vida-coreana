<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    use HttpResponses;

    public function forgotPasswordPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return $this->response('Link de redefinição de senha enviado com sucesso', Response::HTTP_OK);
        } else {
            return $this->response('Erro ao enviar link de redefinição de senha', Response::HTTP_BAD_REQUEST);
        }
    }

    public function resetPasswordPost(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email|exists:users',
            'password'              => 'required|string|min:4|max:255|confirmed',
            'password_confirmation' => 'required'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password'      => bcrypt($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return $this->response('Senha redefinida com sucesso', Response::HTTP_OK);
        } else {
            return $this->response('Token ou Email inválido', Response::HTTP_BAD_REQUEST);
        }
    }
}
