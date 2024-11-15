<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;
    
    public function index(Request $request){

        $status = $request->query('status');
        
        $user = User::
        when($status === "inactive", function ($query) {
            $query->onlyTrashed();
        })
        ->orderBy('created_at', 'desc')
        ->useFilters()
        ->dynamicPaginate();
        
        $is_empty = $user->isEmpty();

        if ($is_empty) {
            return $this->responseNotFound('No Data Found', 'No Data Found');
        }
            return $this->responseSuccess('User Display successfully', $user);
    }

    public function store(UserRequest $request){

        $create_user = User::create(attributes: [
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name,
            "last_name" => $request->last_name,
            "gender" => $request->gender,
            "mobile_number" => $request->mobile_number,
            "email" => $request->email,
            "username" => $request->username,
            "password" => $request->password,
        ]);

        return $this->responseSuccess('User Created successfully', $create_user);
    }

    public function update(UserRequest $request, $id)
    {   
        $user_id = User::find($id);

        if (!$user_id) {
            return $this->responseUnprocessable('ID not found', 'ID not found');
        }

        $user_id->first_name = $request['first_name'];
        $user_id->middle_name = $request['middle_name'];
        $user_id->last_name = $request['last_name'];
        $user_id->gender = $request['gender'];
        $user_id->mobile_number = $request['mobile_number'];
        $user_id->email = $request['email'];
        $user_id->username = $request['username'];

        if (!$user_id->isDirty()) {
            return $this->responseSuccess('No Changes', $user_id);
        }

        $user_id->save();
        
        return $this->responseSuccess('User Updated successfully', $user_id);
    }

    public function archived(Request $request, $id)
    {
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return $this->responseUnprocessable('ID not found', 'ID not found');
        }
        
        if ($user->deleted_at) {

            $user->restore();
            return $this->responseSuccess('User restored successfully', $user);
        }


        if (!$user->deleted_at) {

            $user->delete();
            return $this->responseSuccess('User archived successfully', $user);

        } 
    }
}
