<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:255', 
            'email' => 'required|email|unique:users,email', 
            'password' => 'required|string|min:8', 
        ];
    }

    public function messages(): array { 
        return [
            'name.required' => 'The user name is required.',
            'name.string' => 'The name field must be a valid string.', 
            'name.max' => 'The name may not be greater than 255 characters.',
            'email.required' => 'The user email is required.',
            'email.email' => 'Thie email field must be a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a valid string.', 
            'password.min' => 'The password must be at least 8 characters long.'
        ]; 
    }
}
