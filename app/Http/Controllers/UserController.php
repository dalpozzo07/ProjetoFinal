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

    public function deleteProfile(Request $request)
    {

        // Para excluir o usuário, ele precisa confirmar a senha
        $validatedData = $request->validate([
            'password' => 'required'
        ]); 

        // verfica se é a mesma senha que ta no BD

        if (!Hash::check($validatedData['password'], $request->user()->password)){
            return response()->json('Senha incorreta', 401);
        }

        // se for deleta o usuário
        
        $request->user()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Perfil deletado com sucesso'
        ]);

    }
}
