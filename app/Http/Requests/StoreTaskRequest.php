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
            'body' => $this->isMethod('post') ? ['required', 'string'] : ['sometimes','string'],
            'user_id' => $this->isMethod('post') ? ['required', 'exists:users,id'] : ['nullable','exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'La descripción de la tarea es obligatoria.', 
            'body.string' => 'La descripción debe ser un texto válido.', 
            'user_id.required' => 'Debes indicar el usuario asignado.', 
            'user_id.exists' => 'El usuario asignado no existe.',
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
        if(!empty($notAllowed)) throw ValidationException::withMessages(['campos_no_validos'=> 'Campos inválidos: '.implode(',',$notAllowed)]);
    }
}
