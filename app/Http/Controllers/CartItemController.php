<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Discount;

class CartItemController extends Controller
{
    public function cartItem(Request $request)
    {

      $cartItem = CartItem::all();
      return response()->json($cartItem);
       
    }

    public function addItemCart(Request $request)
    {
        $user = Auth::user();
        

       $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

      $cart = $user->cart;
     
      $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
        $cart = Cart::create([
        'user_id' => $user->id
        ]);
    }

     $product = Product::find($validated['product_id']);
      $unitPrice = $product->price;

      $discount = Discount::where('product_id', $product->id)
            ->where('startDate', '<=', now())
            ->where('endDate', '>=', now())
            ->first();

        if ($discount) {
            $unitPrice -= ($unitPrice * ($discount->discountPercentage / 100));
        }        

        CartItem::create([
        'product_id' => $validated['product_id'],
        'quantity' => $validated['quantity'],
        'unitPrice' => $unitPrice,
        'cart_id' => $cart->id, 
    ]);
        

        return response()->json([
            'status' => true,
            'message' => 'Item adicionado ao carrinho com sucesso',
            'unitPrice' => number_format($unitPrice, 2, '.', ''),
        ]);
    }

    public function updateItemCart(Request $request)
    {
        $user = Auth::user();
        $cart = $user->carts;
        $cartItem = CartItem::find($request->id);

       $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',

       ]);

       $product = Product::find($validated['product_id']);
        $unitPrice = $product->price;
       
         $discount = Discount::where('product_id', $product->id)
            ->where('startDate', '<=', now())
            ->where('endDate', '>=', now())
            ->first();
        
        if ($discount) {
            $unitPrice -= ($unitPrice * ($discount->discountPercentage / 100));
        }

       $cartItem->update([
           'product_id' => $validated['product_id'],
           'quantity' => $validated['quantity'],
           'unitPrice' => number_format($unitPrice, 2, '.', ''),
       ]);

       return response()->json([
           'status' => true,
           'message' => 'Item atualizado com sucesso'
       ]);
   }

    public function deleteItemCart(Request $request)
    {
        $user = Auth::user();
        $cart = $user->cart;
        $cartItem = CartItem::find($request->id);

      $cartItem->delete();

      return response()->json([
          'status' => true,
          'message' => 'Item removido com sucesso'
      ]);
  }

  public function cleanCartItems(Request $request)
  {

     $user = Auth::user();
     $cart = $user->carts;

     
    if (!$cart) {
        return response()->json([
            'status' => false,
            'message' => 'Carrinho não encontrado.'
        ], 404);
    }

     CartItem::where('cart_id', $cart->id)->delete();
       return response()->json([
        'status' => true,
        'message' => 'Carrinho esvaziado com sucesso.'
    ]);


 }
}
