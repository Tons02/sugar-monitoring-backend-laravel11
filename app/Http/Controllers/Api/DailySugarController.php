<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DailySugarRequest;
use App\Http\Resources\DailySugarResource;
use App\Models\DailySugar;
use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Http\Request;

class DailySugarController extends Controller
{
    use ApiResponse;
    
    public function index(Request $request){

        $status = $request->query('status');
        
        $dailySugar = DailySugar::
        when($status === "inactive", function ($query) {
            $query->onlyTrashed();
        })
        ->orderBy('created_at', 'desc')
        ->useFilters()
        ->dynamicPaginate();
        
        $is_empty = $dailySugar->isEmpty();

        if ($is_empty) {
            return $this->responseNotFound('No Data Found', 'No Data Found');
        }
            return $this->responseSuccess('Daily Sugar Display successfully',  DailySugarResource::collection($dailySugar));
    }

    public function store(DailySugarRequest $request){

        $createDailyRecord = DailySugar::create(attributes: [
            "user_id" =>  auth('sanctum')->user()->id,
            "mgdl" => $request->mgdl,
            "description" => $request->description,
            "date" => $request->date,
        ]);

        return $this->responseSuccess('Daily Record Created successfully', $createDailyRecord);
    }

    public function update(DailySugarRequest $request, $id)
    {   
        $DailySugarID = DailySugar::find($id);

        if (!$DailySugarID) {
            return $this->responseUnprocessable('ID not found', 'ID not found');
        }

        $DailySugarID->mgdl = $request['mgdl'];
        $DailySugarID->description = $request['description'];
        $DailySugarID->date = $request['date'];

        if (!$DailySugarID->isDirty()) {
            return $this->responseSuccess('No Changes', $DailySugarID);
        }

        $DailySugarID->save();
        
        return $this->responseSuccess('Daily Record Updated successfully', $DailySugarID);
    }

    public function archived(Request $request, $id)
    {
        $dailySugar = DailySugar::withTrashed()->find($id);

        if (!$dailySugar) {
            return $this->responseUnprocessable('ID not found', 'ID not found');
        }
        
        if ($dailySugar->deleted_at) {

            $dailySugar->restore();
            return $this->responseSuccess('Daily Sugar restored successfully', $dailySugar);
        }


        if (!$dailySugar->deleted_at) {

            $dailySugar->delete();
            return $this->responseSuccess('Daily Sugar archived successfully', $dailySugar);

        } 
    }
}
