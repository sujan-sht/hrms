<?php

namespace App\Modules\Employee\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\FamilyDetail;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Employee\Repositories\EmergencyDetailInterface;

class EmergencyDetailController extends Controller
{
    protected $emergencyDetail;

    public function __construct(EmergencyDetailInterface $emergencyDetail)
    {
        $this->emergencyDetail = $emergencyDetail;
    }

    public function appendAll(Request $request)
    {
        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['emergency_details'] = FamilyDetail::with([
            'province',
            'district',
            'employeeAddress.permanentProvinceModel',
            'employeeAddress.permanentDistrictModel'
        ])
            ->where('employee_id', $request->emp_id)
            ->where('is_emergency_contact', 1)
            ->get();

        return view('employee::employee.partial.ajaxlayouts.emergencyDetailTable', $data)->render();
    }


    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $this->emergencyDetail->save($data);
            return ["status" => 1, "message" =>  "Emergency Detail Created Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Creating Emergency Detail!"];
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        try {
            $this->emergencyDetail->update($request->id, $data);
            return ["status" => 1, "message" =>  "Emergency Detail Updated Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Updating Emergency Detail!"];
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->emergencyDetail->delete($request->id);
            return ["status" => 1, "message" =>  "Emergency Detail Deleted Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while deleting Emergency Detail!"];
        }
    }
}
