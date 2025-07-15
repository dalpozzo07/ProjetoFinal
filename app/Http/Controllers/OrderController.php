<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

class OrderController extends Controller
{
    public function order(Request $request)
    {

      $order = Order::all();
      return response()->json($order);
       
    }

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

    $validated['orderDate'] = date('Y-m-d H:i:s', strtotime($validated['orderDate']));

    $order = Order::create([
        'user_id' => $user->id,
        'address_id' => $validated['address_id'],
        'status' => $validated['status'],
        'totalAmount' => 0,
        'coupon_id' => $validated['coupon_id'] ?? null,
        'orderDate' => $validated['orderDate'],
    ]);

    foreach ($cart->cartItems as $item) {
        OrderItems::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'unitPrice' => $item->unitPrice,
        ]);

        $order->totalAmount += $item->quantity * $item->unitPrice;
    }

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
