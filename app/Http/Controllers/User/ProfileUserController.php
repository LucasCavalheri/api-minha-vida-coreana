<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class ProfileUserController extends Controller
{
    public function __invoke(Request $request)
    {
        return UserResource::make($request->user());
    }
}
