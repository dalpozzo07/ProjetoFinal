<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminController extends Controller
{
    public function registerModerator(Request $request)
    {
        $validateData = $request->validate([
            'email' => 'required|unique:users,email|email',
            'name' => 'required',
            'password' => 'required|confirmed',
        ]);

        // Salvando os dados
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'MODERATOR'
        ]);

        // retorna o status e a mensagem

        return response()->json([
            'status' => true,
            'message' => 'Registro bem sucedido'
        ]);
    }
}
