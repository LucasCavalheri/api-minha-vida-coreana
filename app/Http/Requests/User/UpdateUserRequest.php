<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'email'         => 'sometimes|string|email|unique:users,email,' . $this->id,
            'password'      => 'sometimes|string|min:4|max:255',
            'username'      => 'sometimes|string|max:255|unique:users,username,' . $this->id,
            'name'          => 'sometimes|string|max:255',
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
