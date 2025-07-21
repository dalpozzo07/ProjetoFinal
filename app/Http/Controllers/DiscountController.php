<?php

namespace App\Http\Controllers;
use App\Models\Discount;
use Illuminate\Http\Request;
use App\Models\Product;



class DiscountController extends Controller
{
    public function discount(Request $request)
    {
        
        $discount = Discount::all();
        return response()->json($discount);
    }

    public function createDiscount(Request $request)
    {
        $validated = $request->validate([
            'startDate' => 'required',
            'endDate' => 'required',
            'description' => 'required',
            'discountPercentage' => 'required',
            'product_id' => 'nullable|numeric',
        ]);
        
        $discount = Discount::create($validated);

        $product = Product::find($validated['product_id']);
        
        $precoOriginal = $product->price;
        $precoFinal = $precoOriginal - ($precoOriginal * ($validated['discountPercentage'] / 100));
        $product->update([
            'price' => $precoFinal
            ]);

        $discount->save();

           



        return response()->json([
            'status' => true,
            'message' => 'Desconto criado com sucesso'
        ]);
    }

    public function updateDiscount(Request $request, $id)
{
    $validated = $request->validate([
        'startDate' => 'required|date',
        'endDate' => 'required|date',
        'description' => 'required|string',
        'discountPercentage' => 'required|numeric',
        'product_id' => 'nullable|numeric',
    ]);

    $discount = Discount::findOrFail($id);
    $discount->update($validated);

    return response()->json([
        'status' => true,
        'message' => 'Desconto atualizado com sucesso'
    ]);
}
   public function deleteDiscount($id)
{
    $discount = Discount::findOrFail($id);
    $discount->delete();

    return response()->json([
        'status' => true,
        'message' => 'Desconto deletado com sucesso'
    ]);
}
}
