<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function registerModerator(Request $request)
    {
        // vai validar os dados inseridos
        Auth::user();

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

        // isso daqui foi lindo de fazer
        
    }

    
}
