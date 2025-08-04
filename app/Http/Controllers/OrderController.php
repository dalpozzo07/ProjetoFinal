<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\Product;

class OrderController extends Controller

    // ver todos seus pedidos
{
    public function order(Request $request)
    {

     $order = Order::where('user_id', Auth::user()->id)->get();

     if(!$order) {  
        return response()->json([
            'status' => false,
            'message' => 'Pedido não encontrado'
        ]);
      }

     return response()->json($order);
       
    }

    public function getOrder(Request $request, $id)
    {

      $order = Order::where('id', $id)->first();

      if (!$order) {
        return response()->json([
          'status' => false,
          'message' => 'Pedido não encontrado'
        ]);
      }
      
      return response()->json($order);
       
    }

        // fazer o pedido
    public function createOrder(Request $request)
{
    $user = Auth::user();
    $cart = $user->carts;

    if (!$cart) {
        return response()->json([
            'status' => false,
            'message' => 'Carrinho não encontrado.'
        ], 404);
    }

    if ($cart->cartItems->count() == 0) {
        return response()->json([
            'status' => false,
            'message' => 'Carrinho está vazio.'
        ], 404);
    }
    
    // basicamente essa função vai pegar todos os produtos que estão no carrinho
    $products = $cart->cartItems->pluck('product_id')->toArray();

    foreach($products as $product) {
        $product = Product::find($product);
        $stock = $product->stock;
        $quantity = $cart->cartItems->where('product_id', $product->id)->first()->quantity;
        
        if($stock <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Produto está esgotado.'
            ], 404);
        }
        
        $stock -= $quantity;

        $product->update([
            'stock' => $stock
        ]);

    }

    $validated = $request->validate([
        'coupon_id' => 'nullable|exists:coupons,id',
        'address_id' => 'required|exists:addresses,id',
    ]);


    $order = Order::create([
        'user_id' => $user->id,
        'address_id' => $validated['address_id'],
        'status' => 'PENDING',
        'totalAmount' => 0,
        'coupon_id' => $validated['coupon_id'] ?? null,
        'orderDate' => now(),
    ]);

    $totalAmount = 0;

    foreach ($cart->cartItems as $item) {

    $product = Product::find($item->product_id);
    $unitPrice = $product->price;



     $discount = Product::find($item->product_id)->activeDiscounts()->first();

      if ($discount) {
        $unitPrice -= ($unitPrice * ($discount->discountPercentage / 100));
    }

    OrderItems::create([
        'order_id' => $order->id,
        'product_id' => $item->product_id,
        'quantity' => $item->quantity,
        'unitPrice' => $unitPrice,
    ]);

    $totalAmount += $item->quantity * $unitPrice;
}


$order->totalAmount = $totalAmount;

    // Aplicar desconto do cupom (se houver)
    if ($order->coupon_id) {
        $coupon = \App\Models\Coupon::find($order->coupon_id);
        if ($coupon) {
            $order->totalAmount -= ($order->totalAmount * ($coupon->discountPercentage / 100));
        }
    }

    $order->save();
    CartItem::where('cart_id', $cart->id)->delete();

    return response()->json([
        'status' => true,
        'message' => 'Pedido criado com sucesso.',
        'order' => $order
    ]);
}




    // CANCELAR O PEDIDO
    public function cancelOrder(Request $request)
    {
        $user = Auth::user();
    $order = Order::find($request->id);

    if (!$order || $order->user_id !== $user->id) {
        return response()->json([
            'status' => false,
            'message' => 'Pedido não encontrado ou não autorizado.'
        ], 404);
    }

    if ($order->status !== 'PENDING') {
        return response()->json([
            'status' => false,
            'message' => 'Pedido já foi processado e não pode ser cancelado.'
        ], 400);
    }

    $orderItems = OrderItems::where('order_id', $request->id)->get();

    foreach($orderItems as $orderItem) {
        $product = Product::find($orderItem->product_id);
        $stock = $product->stock;
        $quantity = $orderItem->quantity;
        $stock += $quantity;

        $product->update([
            'stock' => $stock
        ]);
    }

    $order->status = 'CANCELED';
    $order->save();

    return response()->json([
        'status' => true,
        'message' => 'Pedido cancelado com sucesso.'
    ]);

    }
}
