<?php

declare(strict_types=1);

namespace App\Features\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'name' => ['required', 'string'],
            'role' => ['required', 'string'],
            'email' => ['required', 'email', 'max:254'],
            'password' => ['string'],
        ];
    }
}
