<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array {
        return [
            'title'    => 'required|string|min:2',
            'content'  => 'required|string',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:20480', // 20 Mo
            'media.*'  => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,webm,avi|max:102400', // 100 Mo
        ];
    }

    public function messages(): array {
        return [
            'title.required'     => 'Le titre est obligatoire.',
            'title.min'          => 'Le titre doit contenir au moins 10 caractères.',
            'content.required'   => 'Le contenu est obligatoire.',
            'image.image'        => "Le fichier principal doit être une image.",
            'media.*.file'       => 'Chaque élément doit être un fichier valide.',
            'media.*.mimes'      => 'Types autorisés : images (jpg,jpeg,png,gif,webp) et vidéos (mp4,mov,webm,avi).',
            'media.*.max'        => 'Chaque fichier ne doit pas dépasser 100 Mo.',
        ];
    }
    
}
