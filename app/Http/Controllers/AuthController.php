<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function update(StoreUserRequest $request)
    {
        $request->validated($request->all());
        $user = User::where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return $this->sucess([
            'user' => $user,
        ]);
    }

    public function remove(Request $request)
    {
        DB::table('users')->where('id', '=', $request->user_id)->delete();
    }

    public function login(LoginRequest $request)
    {
        $request->validated($request->all());
        $token = Auth::attempt($request->only(['email', 'password']));
        $user = User::where('email', $request->email)->first();
        return $this->sucess([
            'user' => $user,
            'token' => $user->createToken('Api Token of' . $user->name)->plainTextToken
        ]);
    }


    public function register(StoreUserRequest $request)
    {
        $request->validated($request->all());
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $token = Auth::attempt([
            'email' => $user->email,
            'password' => $request->password,
        ]);
        return $this->sucess([
            'user' => $user,
            'token' => $token
        ]);
    }
    
    public function logout()
    {
        Auth::logout()->currentAccessToken()->delte;
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
