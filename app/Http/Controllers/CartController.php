<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    public function cart(Request $request)
    {

      $user = Auth::user();

      if(!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Usuário não encontrado'
        ], 404);
      }
      
      $cart = Cart::where('user_id', Auth::user()->id)->first();
      return response()->json($cart);
       
    }
}
