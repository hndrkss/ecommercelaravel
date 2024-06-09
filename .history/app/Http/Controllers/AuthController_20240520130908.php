<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    function index() 
    {
            
    }
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (auth()->attempt($credentials)) {
            Auth::guard('api')->attempt($credentials);
            return redirect('/dashboard');
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
