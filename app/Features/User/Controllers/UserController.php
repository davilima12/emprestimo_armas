<?php

declare(strict_types=1);

namespace App\Features\User\Controllers;

use App\Features\Auth\Exceptions\BadPermissionException;
use App\Features\Auth\Exceptions\UnauthorizedException;
use App\Features\Auth\Exceptions\UserNotFoundException;
use App\Features\Auth\Singletons\AuthenticatedUser;
use App\Features\Shared\Requests\BasicFilterRequest;
use App\Features\User\Requests\CreateUserRequest;
use App\Features\User\Services\UserService;
use App\Http\Controllers\Controller;
use App\Models\Followers;
use App\Models\LikePost;
use App\Models\User as ModelsUser;
use App\Models\UserPhoto;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}


    /**
     * @throws BadPermissionException
     * @throws UnauthorizedException
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $users = ModelsUser::query()
        ->where('email','like', "%$search%")
        ->orWhere('name','like', "%$search%")
        ->get();

        return response()->json($users);

    }


    /**
     * @throws UnauthorizedException
     * @throws BadPermissionException
     * @throws UserNotFoundException
     */
    public function destroy(string $token): void
    {
        $this->userService->delete($token);
    }

    /**
     * @throws UserNotFoundException
     */
    public function update(Request $request)
    {
        $user = AuthenticatedUser::get();

        if (is_null($user)) {
            throw new UserNotFoundException('Usuario não encontrado!', 404);
        }

        $request = $request->all();


        $updateUser = new ModelsUser();
        $updateUser = $updateUser->find($user->id);
        $updateUser->update(
            $request
        );

        return response()->json($updateUser);
    }




    public function indexSample(Request $request): JsonResponse
    {

        $users = ModelsUser::select(
            'id',
            'name',
            'token',
            'email',
        )->get();

        return response()->json($users);
    }


    public function authUser(Request $request): JsonResponse
    {

        $user = AuthenticatedUser::get();

        return response()->json($user);
    }

    public function createAccount(CreateUserRequest $request): JsonResponse
    {
        $user = ModelsUser::create(
            [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'indicado_codigo_indicacao' => $request->input('codigo_indicacao'),
                'user_type_id' => 1,
            ]
        );

        return response()->json(['message' => 'User created successfully!', 'user' => $user], 201);
    }

}
