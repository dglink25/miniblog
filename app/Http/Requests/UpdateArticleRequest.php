<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:1',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:20480',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mp3,pdf,mov,webm,avi|max:1024000',
        ];
    }

}
