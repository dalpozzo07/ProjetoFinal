<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;

class CartItemController extends Controller
{
    public function cartItem(Request $request)
    {

      $cartItem = CartItem::all();
      return response()->json($cartItem);
       
    }


}
