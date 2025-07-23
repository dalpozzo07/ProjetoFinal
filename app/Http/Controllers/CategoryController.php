<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    
    public function category(Request $request)
    {
      $category = Category::all();
      return response()->json($category);
      
    }

    public function getCategory(Request $request, $id)
    {

      $category = Category::where('id', $id)->first();

      if (!$category) {
        return response()->json([
          'status' => false,
          'message' => 'Categoria não encontrada'
        ]);
      }

      return response()->json($category);
       
    }

    public function createCategory(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Categoria criada com sucesso'
        ]);
        
    }

    public function updateCategory(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $category = Category::find($request->id);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Categoria atualizada com sucesso'
        ]);
}

    public function deleteCategory(Request $request)
    {
        $category = Category::find($request->id);

        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Categoria deletada com sucesso'
        ]);
        
    }
}
