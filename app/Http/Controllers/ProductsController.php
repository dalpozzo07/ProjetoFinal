<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Discount;

class ProductsController extends Controller
{
    public function products(Request $request)
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function createProduct(Request $request)
    {
       $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        
        
       
       $product = Product::create($validated);
        
   
       $product->save();

        return response()->json([
            'status' => true,
            'message' => 'Produto criado com sucesso'
        ]);
    }

    public function updateProduct(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);
        
        $product = Product::find($request->id);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'stock' => $request->stock,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Produto atualizado com sucesso'
        ]);
    }

    public function deleteProduct(Request $request)
    {
        $product = Product::find($request->id);

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Produto deletado com sucesso'
        ]);
    }
}
