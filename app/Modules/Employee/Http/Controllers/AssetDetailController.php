<?php

namespace App\Modules\Employee\Http\Controllers;

use App\Modules\Asset\Entities\AssetAllocate;
use App\Modules\Asset\Repositories\AssetAllocateInterface;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Repositories\AssetDetailInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AssetDetailController extends Controller
{
    protected $assetDetail;
    protected $assetAllocate;

    public function __construct(AssetDetailInterface $assetDetail, AssetAllocateInterface $assetAllocate)
    {
        $this->assetDetail = $assetDetail;
        $this->assetAllocate = $assetAllocate;
    }

    public function appendAll(Request $request)
    {
        $data['employeeModel'] = Employee::find($request->emp_id);

        $filter['employee_id'] = $request->emp_id;
        $data['assetAllocateModels'] = $this->assetAllocate->findAll(null, $filter);

        // $data['asset_details'] = $this->assetDetail->findAll($request->emp_id);
        return view('employee::employee.partial.ajaxlayouts.assetDetailTable', $data)->render();
    }


    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $this->assetDetail->save($data);
            return ["status" => 1, "message" =>  "Asset Detail Created Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Creating Asset Detail!"];
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        try {
            $this->assetDetail->update($request->id, $data);
            return ["status" => 1, "message" =>  "Asset Detail Updated Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Updating Asset Detail!"];
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->assetDetail->delete($request->id);
            return ["status" => 1, "message" =>  "Asset Detail Deleted Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while deleting Asset Detail!"];
        }
    }
}
