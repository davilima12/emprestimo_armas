<?php

declare(strict_types=1);

namespace App\Features\Auth\Services;

use App\Features\Auth\Exceptions\InvalidActionException;
use App\Features\Auth\Exceptions\PasswordAlreadySavedException;
use App\Features\Auth\Exceptions\UserNotFoundException;
use App\Features\Auth\Mail\ResetPasswordEmail;
use App\Features\Auth\Models\PasswordResetToken;
use App\Features\Email\Dtos\SendEmailDto;
use App\Features\Email\Services\SendEmailService;
use App\Features\User\Models\User;
use App\Features\User\ValueObjects\Email;

readonly class AuthService
{
    public function __construct(
        private SendEmailService $sendEmailService
    ) {
    }

    /**
     * @throws PasswordAlreadySavedException|UserNotFoundException
     */
    public function createUserPassword(string $token, string $password): void
    {
        $user = User::findBytoken($token);
        if ($user->isVerified()) {
            throw new PasswordAlreadySavedException();
        }

        $user->update([
            'password' => bcrypt($password),
            'email_verified_at' => now(),
        ]);
    }

    /**
     * @throws InvalidActionException
     * @throws UserNotFoundException
     */
    public function sendForgotPasswordEmail(Email $email): void
    {
        $user = User::findByEmail($email);
        // if (!$user->isVerified()) {
        //     throw new InvalidActionException('Ação invalida, por favor confirme seu email!');
        // }

        $passwordResetToken = $user->createPasswordResetToken();
        $this->sendEmailService->sendEmail(new SendEmailDto(
            to: new Email($user->email),
            name: 'teste',
            body: new ResetPasswordEmail($passwordResetToken),
        ));
    }

    /**
     * @throws UserNotFoundException
     */
    public function resetPassword(string $token, string $password): void
    {
        /** @var PasswordResetToken $passwordResetToken */
        $passwordResetToken = PasswordResetToken::query()
            ->where('token', $token)
            ->first();

        if (is_null($passwordResetToken)) {
            throw new UserNotFoundException('Não autorizado', 400);
        }
        if (!$passwordResetToken->isValid()) {
            throw new UserNotFoundException('Não autorizado', 400);
        }

        $passwordResetToken->user->update([
            'password' => bcrypt($password),
        ]);
    }

    /**
     * @throws UserNotFoundException
     */
    public function verifyToken(string $token): void
    {
        /** @var PasswordResetToken $passwordResetToken */
        $passwordResetToken = PasswordResetToken::query()
            ->where('token', $token)
            ->first();

        if (is_null($passwordResetToken)) {
            throw new UserNotFoundException('Token invalido', 400);
        }

        if (!$passwordResetToken->isValid()) {
            throw new UserNotFoundException('Token expirado', 400);
        }
    }
}
