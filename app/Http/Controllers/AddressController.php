<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    public function address(Request $request)
    {

      $address = Address::all();
      return response()->json($address);
       
    }

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

    public function deleteAddress(Request $request)
    {
        $address = Address::find($request->id);

        $address->delete();

        return response()->json([
            'status' => true,
            'message' => 'Endereço deletado com sucesso'
        ]);
        
    }   
}
