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


    public function alterProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        try {


            $user = AuthenticatedUser::get();

            if (is_null($user)) {
                throw new UserNotFoundException('Usuario não encontrado!', 404);
            }

            $userFoto = new UserPhoto();
            if (!$request->hasFile('profile_picture')) {

                return response()->json('error', 400);
            }

            $path = $request->file('profile_picture')->store('fotos_perfil', 'public');

            $fotoPerfil = $userFoto->where('user_id',  $user->id)->where('is_profile_picture', 1)->first();

            if ($fotoPerfil) {
                $fotoPerfil->delete();
            }


            $userFoto::create(
                [
                    'user_id' =>  $user->id,
                    'path_photo' => $path,
                    'is_profile_picture' => 1
                ]
            );

            return response()->json($path, 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json('erro', 400);
        }
    }


    public function viewProfilePhoto($id)
    {


        $userFoto = new UserPhoto();

        $fotoPerfil = $userFoto->where('user_id',  $id)->where('is_profile_picture', 1)->first();

        if(!$fotoPerfil){
            return response()->json(['error' => 'Arquivo não encontrado'], 200);
        }

        $path = $fotoPerfil->path_photo;

        if (!Storage::exists('public/' . $path)) {
            return response()->json(['error' => 'Arquivo não encontrado'], 200);
        }

        return Storage::response('public/' . $path);
    }

    public function follow(Request $request)
    {
        $request->validate([
            'followed_id' => 'required|exists:users,id',
        ]);

        try {
            $user = AuthenticatedUser::get();

            if (is_null($user)) {
                throw new UserNotFoundException('Usuário não encontrado!', 404);
            }

            if ($request->followed_id == $user->id) {
                return response()->json('Você não pode seguir a si mesmo.', 400);
            }

            $seguidor = Followers::where('followed_id', $request->followed_id)
                ->where('follower_id', $user->id)
                ->first();

            if ($seguidor) {
                $seguidor->delete();
            } else {
                Followers::create(
                    [
                        'followed_id' => $request->followed_id,
                        'follower_id' => $user->id
                    ]
                );
            }

            return response()->json("Registro alterado com sucesso", 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json('Erro', 400);
        }
    }




    public function profile(Request $request)
    {

        try {

            $userAuth = AuthenticatedUser::get();

            if (is_null($userAuth)) {
                throw new UserNotFoundException('Usuario não encontrado!', 404);
            }


            $user = ModelsUser::select(
                'id',
                'name',
                'email',
                'description',
                'private'
            )->with([
                'user_profile_picture',
                'following:id,name',
                'followers:id,name',
                'following.user_profile_picture',
                'followers.user_profile_picture'
            ])->where('id', $userAuth->id)->first();

            return response()->json($user);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json('erro', 400);
        }

    }

    public function profileFindUser(Request $request, $user_id)
    {
        try {

            $user = ModelsUser::select(
                'id',
                'name',
                'email',
                'description',
                'private'
            )->with([
                'user_profile_picture',
                'following:id,name',
                'followers:id,name',
                'following.user_profile_picture',
                'followers.user_profile_picture'
            ])->where('id', $user_id)->first();

            return response()->json($user);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json('erro', 400);
        }
    }


    public function likedPosts(Request $request, $user_id)
    {
        try {
            $posts = LikePost::select('posts.*')->where('like_post.user_id', $user_id);

            if (isset($request->type_like_id)) {
                $posts = $posts->where('type_like_id', $request->type_like_id);
            }

            if (isset($request->initial_date) && isset($request->final_date)) {
                $initialDate = Carbon::parse($request->initial_date)->startOfDay();
                $finalDate = Carbon::parse($request->final_date)->endOfDay();

                $posts = $posts->whereBetween('like_post.created_at', [$initialDate, $finalDate]);
            }

            $posts = $posts->join('posts', 'like_post.post_id', 'posts.id')->get();

            return response()->json($posts);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json('erro', 400);
        }
    }
}
