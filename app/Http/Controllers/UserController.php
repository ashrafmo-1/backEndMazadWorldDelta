<?php

namespace App\Http\Controllers;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'created successfully',
            'user' => $user
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function showSingleUser(string $id)
    {
        $user = User::find($id);

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->id,
            'password' => 'nullable|string|min:8',
        ]);

        $user = User::where('id', $request->id)->first();
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json([
            'message' => 'updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        User::find($id)->delete();
        return response()->json([
            'message' => 'deleted successfully'
        ]);
    }
}
