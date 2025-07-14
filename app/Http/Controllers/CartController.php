<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function cart(Request $request)
    {

      $cart = Cart::all();
      return response()->json($cart);
       
    }
}
