<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    function index() 
    {
        return view('auth.login');
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

    function register(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'nama_member' => 'required',
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'detail_alamat' => 'required',
            'no_hp' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();
        
        $Member->update($input);

        return response()->json([
            'message' => 'success',
            'data' => $Member
        ]);
    }    
    }
}
