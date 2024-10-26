<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'comment' => 'required',
            'post_comment_id' => 'exists:post_comment,id'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
