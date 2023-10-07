<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IndexCategoryController extends Controller
{
    use HttpResponses;

    public function __invoke()
    {
        $categories = Category::all();

        return $this->response('Categorias listadas com sucesso', Response::HTTP_OK, CategoryResource::collection($categories));
    }
}
