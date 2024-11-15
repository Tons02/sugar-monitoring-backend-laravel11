<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    use ApiResponse;
    
    public function login(Request $request){
        
        $username=$request->username;
        $password=$request->password;
        
        $login=User::where('username',$username)->get()->first();

        if (! $login || ! hash::check($password, $login->password)) {
            return $this->responseBadRequest('Invalid Credentials.', 'Invalid Credentials');
        }

        $token = $login->createToken('myapptoken')->plainTextToken;
        $cookie = cookie('authcookie',$token);

        return response()->json([
            'message' => 'Successfully Logged In',
            'token' => $token,
            'data' => $login ,
            
        ], 200)->withCookie($cookie);
        
    }
}
