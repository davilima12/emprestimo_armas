<?php

declare(strict_types=1);

namespace App\Features\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'max:254', 'unique:users,email'],
            'password' => ['required'],
            'confirm_password' => ['required', 'same:password'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O e-mail fornecido não é válido.',
            'email.unique' => 'O e-mail fornecido já esta cadastrado.',
            'email.max' => 'O e-mail não pode exceder 254 caracteres.',
            'password.required' => 'O campo senha é obrigatório.',
            'confirm_password.required' => 'O campo confirmação de senha é obrigatório.',
            'confirm_password.same' => 'A confirmação de senha não coincide com a senha.',
            'codigo_indicacao.exists' => 'O código de indicação fornecido não existe.',
        ];
    }
}
