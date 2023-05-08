<?php

namespace App\Http\Requests\Api\Post;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'content' => 'required|string',
            'images' => 'nullable|array',
            'deleteImageIds' => 'nullable|array',
            'deleteImageUrls' => 'nullable|array',
        ];
    }
}
