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
            'name.required' => 'El campo name es requerido.',
            'name.string' => 'El campo name debe ser un string válido.', 
            'name.max' => 'La longitud máxima del campo name es de 255 carácteres.',
            'email.required' => 'El campo email es requrido.',
            'email.email' => 'El campo email debe ser una dirección de correo válida.',
            'email.unique' => 'El email ya se encuentra registrado.',
            'password.required' => 'La contraseña es requerida.',
            'password.string' => 'El campo password debe ser un string válido.', 
            'password.min' => 'El campo password debe contener un mínimo de 8 carácteres.' 
        ]; 
    }
}
