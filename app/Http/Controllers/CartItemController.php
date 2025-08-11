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

        // Ver os itens do carrinho
      $cartItem = CartItem::all();
      return response()->json($cartItem);
       
    }

    
    // Adicionar item ao carrinho
    public function addItemCart(Request $request)
    {
        $user = Auth::user();
        
        
       $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    // Acessa o carrinho do usuário e verifica se ele existe
      $cart = $user->cart;
     
      $cart = Cart::where('user_id', $user->id)->first();

      // Se não existir, cria um novo carrinho
        if (!$cart) {
        $cart = Cart::create([
        'user_id' => $user->id
        ]);
    }

    // Aqui verifica se o produto existe, 
   // e eu criei uma váriavel para armazenar o preço do produto "UnitPrice"

     $product = Product::find($validated['product_id']);
      $unitPrice = $product->price;

      // Aqui verifica se existe um desconto para o produto, e se ele é válido
      $discount = Discount::where('product_id', $product->id)
            ->where('startDate', '<=', now())
            ->where('endDate', '>=', now())
            ->first();

            // se tiver um desconto, aplica-o ao preço unitário
        if ($discount) {
            $unitPrice -= ($unitPrice * ($discount->discountPercentage / 100));
        }        

        // Cria um novo item de carrinho
        CartItem::create([
        'product_id' => $validated['product_id'],
        'quantity' => $validated['quantity'],
        'unitPrice' => $unitPrice,
        'cart_id' => $cart->id, 
    ]);
        
    // O desconto é aplicado aqui para o usuário ver o produto que tá sendo descontado
        return response()->json([
            'status' => true,
            'message' => 'Item adicionado ao carrinho com sucesso',
            'unitPrice' => number_format($unitPrice, 2, '.', ''),
        ]);
    }

    // Atualizar um item de carrinho
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

   // Remover um item de carrinho
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

    // Ele remove todos os itens do carrinho, com base no ID do usuário e carrinho
     CartItem::where('cart_id', $cart->id)->delete();
       return response()->json([
        'status' => true,
        'message' => 'Carrinho esvaziado com sucesso.'
    ]);


 }
}
