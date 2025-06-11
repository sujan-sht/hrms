<?php

namespace App\Modules\Employee\Http\Controllers;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Repositories\InsuranceDetailInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class InsuranceDetailController extends Controller
{
    protected $insuranceDetail;

    public function __construct(InsuranceDetailInterface $insuranceDetail)
    {
        $this->insuranceDetail = $insuranceDetail;
    }

    public function appendAll(Request $request)
    {
        $data['employeeModel'] = $employee = Employee::find($request->emp_id);
        $data['insurance_details'] = $this->insuranceDetail->findAll($request->emp_id);
        return view('employee::employee.partial.ajaxlayouts.insuranceDetailTable', $data)->render();
    }


    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $this->insuranceDetail->save($data);
            return ["status" => 1, "message" =>  "Insurance Detail Created Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Creating Insurance Detail!"];
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        try {
            $this->insuranceDetail->update($request->id, $data);
            return ["status" => 1, "message" =>  "Insurance Detail Updated Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Updating Insurance Detail!"];
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->insuranceDetail->delete($request->id);
            return ["status" => 1, "message" =>  "Insurance Detail Deleted Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while deleting Insurance Detail!"];
        }
    }
}
