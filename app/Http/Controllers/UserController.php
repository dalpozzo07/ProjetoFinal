<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class userController extends Controller
{
    public function profile(Request $request)
    {

        return response()->json([
            'user' => $request->user()
        ]);
    }
}
