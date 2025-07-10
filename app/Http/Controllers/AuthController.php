<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
   

    public function login(Request $request)
    {

        // data validation igual meu mano indiano falou,
        // vai validar os dados que estão no request

        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // aqui ele vai buscar se está dentro do BD, se estiver vai retornar algo, senão vai retornar null.

        $user = User::where('email', $validatedData['email'])->first(); 


        // vai verificar a senha do usuário, o bom da mensagem é que cobre a senha invalida 
        // e o usuario inexistente

        if (!$user || !Hash::check($validatedData['password'], $user->password)){
            // se a senha não existir dentro do BD, retorna erro.
            return response()->json('Usuário inválido', 401);
        }


        // retorna o usuario e o token gerado pelo sanctum

            return response()->json([
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken,
            'message' => 'Login bem sucedido'

        ]);
   

    }

    public function register(Request $request)
    {
        // Validando os dados recebidos
        $validateData = $request->validate([
            'email' => 'required|unique:users,email|email',
            'name' => 'required',
            'password' => 'required|confirmed',
        ]);

        // Salvando os dados
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // retorna o status e a mensagem

        return response()->json([
            'status' => true,
            'message' => 'Registro bem sucedido'
        ]);
    }
}
