<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:categories,name',
        ];
    }

    public function messages(): array { 
        return [
            'name.required' => 'El campo name de categoría es requerido.',
            'name.string' => 'El nombre de la categoría debe ser un string válido.',
            'name.max' => 'La longitud máxima del campo name es de 255 carácteres.',
            'name.unique' => 'El nombre de esta categoría ya existe.', 
        ]; 
    }
}
