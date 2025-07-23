<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponsController extends Controller
{
    public function cupons(Request $request)
    {

      $cupons = Coupon::all();
      return response()->json($cupons);
       
    }

    public function getCupons(Request $request, $id)
    {

      $cupons = Coupon::where('id', $id)->first();

      if (!$cupons) {
        return response()->json([
          'status' => false,
          'message' => 'Cupons não encontrado'
        ]);
      }
      
      return response()->json($cupons);
       
    }
    
    public function createCupons(Request $request)
    {
        $validateData = $request->validate([
            'code' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'discountPercentage' => 'required',
        ]);

        Coupon::create([
            'code' => $request->code,
            'startDate' => $request->startDate,
            'endDate' => $request->endDate,
            'discountPercentage' => $request->discountPercentage,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cupons criado com sucesso'
        ]);
        
    }
    public function updateCupons(Request $request)
    {
        $validateData = $request->validate([
            'code' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'discountPercentage' => 'required',
        ]);

        $cupons = Coupon::find($request->id);

        $cupons->update([
            'code' => $request->code,
            'startDate' => $request->startDate,
            'endDate' => $request->endDate,
            'discountPercentage' => $request->discountPercentage,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cupons atualizado com sucesso'
        ]);
        
    }

    public function deleteCupons(Request $request)
    {
        $cupons = Coupon::find($request->id);

        $cupons->delete();

        return response()->json([
            'status' => true,
            'message' => 'Cupons deletado com sucesso'
        ]);
        
    }   
}
