<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    //function index() 
    //{
    //    return view('auth.login');
    //}
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
            'email' => 'required|email',
            'password' => 'required|same:konfirmasi_password',
            'konfirmasi_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();
        $input['passowrd'] = bcrypt($request->password);
        unset($input['konfirmasi_password']);
        $Member = Member::create($input);

        return response()->json([
            'message' => 'success',
            'data' => $Member
        ]);    
    }

    function login_member(Request $request) 
    {
        $email = $request->email;
        $password = $request->password;
    }
}
