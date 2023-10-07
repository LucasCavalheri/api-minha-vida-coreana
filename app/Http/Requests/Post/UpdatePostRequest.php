<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'title'         => 'sometimes|string|min:4|max:255',
            'content'       => 'sometimes|string|min:4',
            'image'         => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'category_id'   => 'sometimes|uuid|exists:categories,id',
        ];
    }
}
