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

      $order = Order::all();
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
    

    $validated = $request->validate([
        'coupon_id' => 'nullable|exists:coupons,id',
        'address_id' => 'required|exists:addresses,id',
        'orderDate' => 'required|date',
        'status' => 'required|string',
    ]);


    $order = Order::create([
        'user_id' => $user->id,
        'address_id' => $validated['address_id'],
        'status' => $validated['status'],
        'totalAmount' => 0,
        'coupon_id' => $validated['coupon_id'] ?? null,
        'orderDate' => $validated['orderDate'],
    ]);

    $totalAmount = 0;

    foreach ($cart->cartItems as $item) {

    $product = Product::find($item->product_id);
    $unitPrice = $product->price;

     $discount = \App\Models\Discount::where('product_id', $product->id)
        ->where('startDate', '<=', now())
        ->where('endDate', '>=', now())
        ->first();

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

    $order->status = 'CANCELED';
    $order->save();

    return response()->json([
        'status' => true,
        'message' => 'Pedido cancelado com sucesso.'
    ]);

    }
}
