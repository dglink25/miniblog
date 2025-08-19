<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:5',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,webm,avi|max:102400',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.min' => 'Le titre doit contenir au moins 5 caractères.',
            'content.required' => 'Le contenu est obligatoire.',
            'image.required' => "L'image est obligatoire.",
            'image.image' => "Le fichier doit être une image.",
        ];
    }
}
