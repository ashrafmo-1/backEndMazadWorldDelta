<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginRequest $request)
    {

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => 'Error Has Occurred',
                'message' => 422,
                'data' => 'User not found'
            ], 422);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'Error Has Occurred',
                'message' => 422,
                'data' => 'Invalid password'
            ], 422);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('Api Token of ' . $user->name)->plainTextToken
        ]);
    }


    public function register(StoreUserRequest $request)
    {
        $request->validated($request->all());
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);
        $token = $user->createToken('Api Token of ' . $user->name)->plainTextToken;
        return $this->sucess([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout()
    {
        request()->user()->currentAccessToken()->delete();
        return $this->success([
            'message' => 'you have been logged out'
        ]);
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json([
            'user' => $user,
            'token' => $user->createToken('Api Token of ' . $user->name)->plainTextToken
        ]);
    }
}
