<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // pendiente auth para la API
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
            'title' => 'required|string|max:255', 
            'description' => ['required', 'string'],
            'status' => 'required|in:pending,in_progress,completed',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after_or_equal:today', 
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El campo title es requerido.',
            'title.string' => 'El campo title debe ser un string válido.', 
            'title.max' => 'La longitud máxima del campo title es de 255 carácteres.',
            'assigned_to.exists' => 'El usuario asignado no existe',
            'status.in' => 'El campo status sólo puede contener los valorees: pending, in_progress y completed.',
            'description.required' => 'El campo description es requerido.', 
            'description.string' => 'El campo description debe ser un string válido.', 
            'due_date.date' => 'El due date debe ser una fecha válida (aaaa-mm-dd).',
            'due_date.after_or_equal' => 'El campo due date no puede ser una fecha inferior a la de hoy.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
        ];
    }

    /**
     * Valida que no se hayan enviado campos adicionales no permitidos.
     * Este método se ejecuta automáticamente después de que las reglas de
     * validación definidas en el Form Request hayan sido aplicadas.
     * @throws \Illuminate\Validation\ValidationException
     */
    public function passedValidation()
    {
        // return parent::passedValidation();
        $allowedFields = array_keys($this->rules()); 
        $sentFields = array_keys($this->all()); 
        $notAllowed = array_diff($sentFields, $allowedFields);
        if(!empty($notAllowed)) throw ValidationException::withMessages(['invalid_fields'=> 'fields: '.implode(',',$notAllowed)]);
    }
}
