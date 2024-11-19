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
        
        DailySugarResource::collection($dailySugar);

        return $this->responseSuccess('Daily Sugar Display successfully',  DailySugarResource::collection($dailySugar));
    }

    public function store(DailySugarRequest $request){

        
        if ($request->mgdl > 125) {
            $status = 'high';  // For mg/dL above 126
        } elseif ($request->mgdl < 70) {
            $status = 'low';   // For mg/dL below 70
        } elseif ($request->mgdl >= 70 && $request->mgdl <= 125) {
            $status = 'normal'; // For mg/dL between 70 and 125
        }

        $createDailyRecord = DailySugar::create(attributes: [
            "user_id" =>  auth('sanctum')->user()->id,
            "mgdl" => $request->mgdl,
            "description" => $request->description,
            "status" => $status,
            "date" => $request->date,
        ]);

        return $this->responseSuccess('Daily Record Created successfully', $createDailyRecord);
    }

    public function update(DailySugarRequest $request, $id)
    {   
        if ($request->mgdl > 125) {
            $status = 'high';  // For mg/dL above 126
        } elseif ($request->mgdl < 70) {
            $status = 'low';   // For mg/dL below 70
        } elseif ($request->mgdl >= 70 && $request->mgdl <= 125) {
            $status = 'normal'; // For mg/dL between 70 and 125
        }

        $DailySugarID = DailySugar::find($id);

        if (!$DailySugarID) {
            return $this->responseUnprocessable('ID not found', 'ID not found');
        }

        $DailySugarID->mgdl = $request['mgdl'];
        $DailySugarID->description = $request['description'];
        $DailySugarID->status = $status;
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
