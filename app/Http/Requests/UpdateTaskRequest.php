<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|in:pending,in_progress,completed',
            'assigned_to' => 'sometimes|exists:users,id',
            'due_date' => 'sometimes|nullable|date|after_or_equal:today',
            'category_id' => 'sometimes|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'El campo title debe ser un string válido.', 
            'title.max' => 'La longitud máxima del campo title es de 255 carácteres.',
            'description.string' => 'El campo description debe ser un string válido.', 
            'status.in' => 'El campo status sólo puede contener los valorees: pending, in_progress y completed.',
            'assigned_to.exists' => 'El usuario asignado no existe',
            'due_date.date' => 'El due date debe ser una fecha válida (aaaa-mm-dd).',
            'due_date.after_or_equal' => 'El campo due date no puede ser una fecha inferior a la de hoy.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
        ];
    }
}
