<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;


class ModeratorController extends Controller
{
    public function updateOrderStatus(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|string',
        ]);

        $order = Order::find($validated['order_id']);
        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Pedido não encontrado.'
            ], 404);
        }


        $order->status = $validated['status'];
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'Status do pedido alterado com sucesso.'
        ]);
    }
}
