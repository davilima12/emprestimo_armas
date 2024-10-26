<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BroadcastChatRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'message' => 'required|string',
            'sender_id' => 'required|integer|exists:users,id',
            'recipient_id' => 'required|integer|exists:users,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
