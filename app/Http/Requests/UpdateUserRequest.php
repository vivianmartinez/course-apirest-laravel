<?php

namespace App\Http\Requests;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255', 
            'email' => 'sometimes|email|unique:users,email,' . $this->user->id, 
            'password' => 'sometimes|string|min:8',
        ];
    }

    public function messages(): array { 
        return [
            'name.string' => 'The name field must be a valid string.', 
            'name.max' => 'The name may not be greater than 255 characters.',
            'email.email' => 'Thie email field must be a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.string' => 'The password must be a valid string.', 
            'password.min' => 'The password must be at least 8 characters long.'
        ]; 
    }
}
