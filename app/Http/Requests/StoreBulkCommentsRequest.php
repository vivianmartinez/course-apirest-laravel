<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBulkCommentsRequest extends FormRequest
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
            'comments' => 'required|array|min:1', 
            'comments.*.content' => 'required|string',
        ];
    }

    public function messages(): array { 
        return [
            'comments.required' => 'El campo content es requerido.',
            'comments.array' => 'El campo comments debe ser de tipo array.', 
            'comments.min' => 'Al menos un comentario es requerido.', 
            'comments.*.content.required' => 'Cada comentario debe incluir un content.', 
            'comments.*.content.string' => 'Cada comentario debe ser un string válido.'
        ]; 
    }

}
