<?php

declare(strict_types=1);

namespace App\Features\Auth\Controllers;

use App\Features\Auth\Exceptions\InvalidActionException;
use App\Features\Auth\Exceptions\PasswordAlreadySavedException;
use App\Features\Auth\Exceptions\UnauthorizedException;
use App\Features\Auth\Exceptions\UserNotFoundException;
use App\Features\Auth\Requests\ConfirmPasswordRequest;
use App\Features\Auth\Requests\LoginRequest;
use App\Features\Auth\Services\AuthService;
use App\Features\Auth\Singletons\AuthenticatedUser;
use App\Features\User\Models\User;
use App\Features\User\Presenters\CreateUserPresenter;
use App\Features\User\UseCases\LoginUseCase;
use App\Features\User\UseCases\LoginUseCaseCliente;
use App\Features\User\ValueObjects\Email;
use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    /**
     * @throws PasswordAlreadySavedException
     * @throws UserNotFoundException
     */
    public function confirmPassword(string $token, ConfirmPasswordRequest $request): Response
    {
        $this->authService->createUserPassword($token, $request->password);

        return response()->noContent();
    }

    /**
     * @throws InvalidActionException
     * @throws UserNotFoundException
     */
    public function sendForgotPasswordEmail(Request $request): JsonResponse|Response
    {
        try {
            $this->authService->sendForgotPasswordEmail(new Email($request->get('email')));
            return response()->json('email enviado');

        } catch (\Throwable $th) {
            // Log::error('error'.$th);
            return response()->json(['message' => $th->getMessage(), 'trace' => $th->getTrace()], 400);
        }
    }

    /**
     * @throws UserNotFoundException
     */
    public function resetPassword(string $token, ConfirmPasswordRequest $request): Response
    {
        $this->authService->resetPassword($token, $request->get('password'));

        return response()->noContent();
    }

    /**
     * @throws UserNotFoundException
     */
    public function verifyToken(string $token): Response
    {
        $this->authService->verifyToken($token);

        return response()->noContent();
    }

    /**
     * @throws InvalidActionException
     * @throws UserNotFoundException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->whereIn('user_type_id', [1, 3])->first();

        if (is_null($user)) {
            throw new UserNotFoundException('Email ou senha incorretos!');
        }

        $passwordMatch = Hash::check($request->password, $user->password);
        if (!$passwordMatch) {
            throw new UserNotFoundException('Email ou senha incorretos!');
        }

        return response()->json(['token' => $user->generateBearerToken()->token]);
    }


    /**
     * @throws InvalidActionException
     * @throws UserNotFoundException
     */
    public function loginCustomer(LoginRequest $request): JsonResponse
    {
        $output = app(LoginUseCaseCliente::class)->execute($request->getEmail(), $request->get('password'));

        return response()->json(['token' => $output]);
    }

}
