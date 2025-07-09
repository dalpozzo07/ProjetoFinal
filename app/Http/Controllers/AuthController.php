<?php

namespace App\Http\Controllers;

use illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class AuthController extends Controller
{
   

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $validatedData['email']);

        if (!$user || !Hash::check($validatedData['password'], $user->password)){
            return response()->json('', 401);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken
        ]);
    }

    public function register(Request $request)
    {
        $validateData = $request->validate([
            'email' => 'required|unique:users,email|email',
            'name' => 'required',
            'password' => ['required|confirmed']
        ]);

        $newUser = User::create($validateData);

        return response()->json($newUser);
    }
}
