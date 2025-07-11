<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    public function profile(Request $request)
    {

        return response()->json([
            'user' => $request->user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->user()->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Perfil atualizado com sucesso'
        ]);

        

        
    }
}
