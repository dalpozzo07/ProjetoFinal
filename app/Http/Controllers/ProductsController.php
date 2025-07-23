<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;



class ProductsController extends Controller
{
    public function products(Request $request)
    {
        $product = Product::all();  
       
    
        return response()->json($product);
    }

    public function getProduct(Request $request, $id)
    {

      $product = Product::where('id', $id)->first();

      if (!$product) {
        return response()->json([
          'status' => false,
          'message' => 'Produto não encontrado'
        ]);
      }
      
      return response()->json($product);
       
    }

    public function getProductsByCategory(Request $request, $id)
    {
      $product = Product::where('category_id', $id)->get();

      if (!$product) {
        return response()->json([
          'status' => false,
          'message' => 'Produto não encontrado'
        ]);
      }

      return response()->json($product);
    }

    public function createProduct(Request $request)
    {
       $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|file|mimes:jpg,bmp,png|max:2048',
        ]);

        
        
       
       $product = Product::create([
           'name' => $request->name,
           'price' => $request->price,
           'category_id' => $request->category_id,
           'stock' => $request->stock,  
       ]);
        
       $precoOriginal = $product->price;
   
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
            'image' => 'nullable|file|mimes:jpg,bmp,png|max:2048',
        ]);
        
        $product = Product::find($request->id);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'stock' => $request->stock,
            'image' => $request->image,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Produto atualizado com sucesso'
        ]);
    }

    public function updateStock(Request $request)
    {
        $product = Product::find($request->product_id);

        $product->update([
            'stock' => $request->stock,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Stock atualizado com sucesso'
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
