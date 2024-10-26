<?php

declare(strict_types=1);

namespace App\Features\Auth\Requests;

use App\Features\User\ValueObjects\Email;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function getEmail(): Email
    {
        return new Email($this->get('email'));
    }
}
