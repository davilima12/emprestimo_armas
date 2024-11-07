<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostLikedRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type_like_id' => 'required|exists:type_like,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
