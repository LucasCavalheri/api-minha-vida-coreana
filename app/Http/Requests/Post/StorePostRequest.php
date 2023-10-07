<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title'         => 'required|string|min:4|max:255',
            'content'       => 'required|string|min:4',
            'image'         => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'category_id'   => 'required|array',
            'category_id.*' => 'required|uuid|exists:categories,id|distinct'
        ];
    }
}
