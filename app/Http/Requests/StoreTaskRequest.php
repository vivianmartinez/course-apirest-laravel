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
            'due_date' => 'nullable|date|after_or_equal:today', 
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title field must be a valid string.', 
            'title.max' => 'The title may not be greater than 255 characters.',
            'status.in' => 'The status must be one of: pending, in_progress, or completed.',
            'description.required' => 'The description field is required', 
            'description.string' => 'The description field must be a valid string.', 
            'due_date.date' => 'The due date must be a valid date.',
            'due_date.after_or_equal' => 'The due date cannot be earlier than today.',
            'category_id.exists' => 'The selected category does not exist.',
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
