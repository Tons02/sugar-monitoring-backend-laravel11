<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgetPasswordRequest;
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

        if($login->userType === 'admin'){
            $token = $login->createToken('admin', ['user:crud', 'daily_monitoring:crud'])->plainTextToken;
        }
        if($login->userType === 'user'){
            $token = $login->createToken('user', ['daily_monitoring:crud'])->plainTextToken;
        }

        $cookie = cookie('authcookie',$token);

        return response()->json([
            'message' => 'Successfully Logged In',
            'token' => $token,
            'data' => $login ,
            
        ], 200)->withCookie($cookie);
        
    }

    public function Logout(Request $request){
        
        auth('sanctum')->user()->currentAccessToken()->delete();
        return $this->responseSuccess('Logout successfully');

    }

    public function resetPassword(Request $request, $id){
        $user = User::where('id', $id)->first();

        if (!$user) {
            return $this->responseUnprocessable('ID not found', 'ID not found');
        }
        
        $user->update([
            'password' => $user->username,
        ]);
        return $this->responseSuccess('Reset password successfully', $user);
    }

    public function changedPassword(ChangePasswordRequest $request){

        $user = auth('sanctum')->user();

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
    
        return $this->responseSuccess('Change password successfully', $user);
    }

    public function forgetPassword(ForgetPasswordRequest $request, $mobileNumber){

    
        $id = User::where('contact_details', $mobileNumber)->first();

        if (!$id) {
            return $this->responseUnprocessable('ID not found', 'ID not found');
        }
        
        $id->update([
            'password' => Hash::make($request->new_password),
        ]);
    
        return $this->responseSuccess('Forget password successfully', $id);
    }
}
