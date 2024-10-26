<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'photos' => 'required|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif',
            'type_post_id' => 'required|exists:post_type,id',
            'name' => 'required'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
