<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'body' => 'required|string|min:10',
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'Le commentaire est obligatoire.',
            'body.min' => 'Le commentaire doit contenir au moins 10 caractères.',
        ];
    }
    
}
