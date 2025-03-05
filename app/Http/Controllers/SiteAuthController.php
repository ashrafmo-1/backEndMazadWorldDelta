<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
class SiteAuthController extends Controller
{
    public function getAllClients()
    {
        $clients = Client::all();

        return response()->json([
            'status' => 'success',
            'data' => $clients
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|integer',
        ]);

        $client = Client::where('email', $request->email)->where('otp', $request->otp)->first();

        if (!$client) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP'
            ], 400);
        }

        $client->update([
            'is_verified' => true,
            'otp' => null
        ]);

        $token = $client->createToken('site-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Account verified successfully',
            'token' => $token,
            'user' => $client
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $otp = rand(100000, 999999);

        $client = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'is_verified' => false
        ]);

        $emailData = [
            'otp' => $otp,
            'name' => $request->name
        ];

        try {
            Mail::send('emails.otp', $emailData, function($message) use ($request) {
                $message->to($request->email)->subject('OTP Verification');
            });
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'okay',
                'message' => 'we will check your account data and active your account',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent to your email. Please verify your account.',
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $client = Client::where('email', $request->email)->first();

        if (!$client) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (!$client->is_verified) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please verify your account first'
            ], 403);
        }

        if (!Hash::check($request->password, $client->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $client->createToken('site-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'token' => $token,
            'user' => $client
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }
}
