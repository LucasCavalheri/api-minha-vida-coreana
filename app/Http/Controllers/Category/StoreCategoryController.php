<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StoreCategoryController extends Controller
{
    use HttpResponses;

    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories',
        ]);

        if ($validator->fails()) {
            return $this->error('Erro de validação', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        if (Auth::check() && !Auth::user()->is_admmin) {
            return $this->error('Você não tem permissão para criar uma categoria', Response::HTTP_FORBIDDEN);
        }

        $category = Category::create([
            'name' => $request->name,
        ]);

        return $this->response('Categoria criada com sucesso', Response::HTTP_CREATED, new CategoryResource($category));
    }
}
