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
            'comments.required' => 'The comment content is required.',
            'comments.array' => 'Comments must be sent as an array.', 
            'comments.min' => 'At least one comment is required.', 
            'comments.*.content.required' => 'Each comment must include content.', 
            'comments.*.content.string' => 'Each comment must be a valid string.'
        ]; 
    }

}
