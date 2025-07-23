<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    // ver os endereços que o usuario autenticado possui
    public function address(Request $request)
    {

        $address = Address::where('user_id', $request->user()->id)->get();
        return response()->json($address);
    }

    // ver um endereço específico
    public function getAddress(Request $request, $id)
    {

      $address = Address::where('user_id', $request->user()->id)
      ->where('id', $id)
      ->first();

      if (!$address) {
        return response()->json([
          'status' => false,
          'message' => 'Endereço não encontrado'
        ]);
      }
      
      return response()->json($address);
       
    }

    // criar um novo endereço
    public function createAddress(Request $request)
    {
        $request->validate([
            'street' => 'required',
            'number' => 'required',
            'zip' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);

        Address::create([
            'user_id' => $request->user()->id,
            'street' => $request->street,
            'number' => $request->number,
            'zip' => $request->zip,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Endereço criado com sucesso'
        ]);
        
    }

    // atualizar um endereço
    public function updateAddress(Request $request)
    {
        $request->validate([
            'street' => 'required',
            'number' => 'required',
            'zip' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);

        $address = Address::find($request->id);

        $address->update([
            'street' => $request->street,
            'number' => $request->number,
            'zip' => $request->zip,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Endereço atualizado com sucesso'
        ]);
        
    }

    // deletar um endereço
    public function deleteAddress(Request $request, $id)
    {
        
        $address = Address::where('user_id', $request->user()->id)
        ->where('id', $id)
        ->first();

        $address->delete();

        return response()->json([
            'status' => true,
            'message' => 'Endereço deletado com sucesso'
        ]);
        
    }   
}
