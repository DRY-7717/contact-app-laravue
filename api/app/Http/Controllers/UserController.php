<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //

    public function register(UserRegisterRequest $request): JsonResponse
    {

        $data = $request->validated();

        if (User::where('username', $data['username'])->count() == 1) {
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => [
                        "username already registered!"
                    ]
                ]
            ], 409));
        }

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function login(UserLoginRequest $request): UserResource
    {
        $data = $request->validated();

        $user = User::where('username', $data['username'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'username or password wrong!.'
                    ]
                ]
            ], 401));
        }

        $user->token = Str::uuid()->toString();
        $user->save();

        return new UserResource($user);
    }

    public function getUserCurrent(Request $request): UserResource
    {

        $user = Auth::user();
        return new UserResource($user);
    }


    public function update(UpdateUserRequest $request): UserResource
    {

        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = Auth::user();
        $user->update($data);

        return new UserResource($user);
    }


    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->token = null;
        $user->save();

        return response()->json([
            "message" => "logout success"
        ], 200);
    }
}
