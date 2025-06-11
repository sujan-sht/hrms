<?php

namespace App\Modules\Employee\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Employee\Http\Requests\CreateMedicalRequest;
use App\Modules\Employee\Repositories\MedicalDetailInterface;

class MedicalDetailController extends Controller
{
    protected $medicalDetail;

    public function __construct(MedicalDetailInterface $medicalDetail)
    {
        $this->medicalDetail = $medicalDetail;
    }

    public function appendAll(Request $request)
    {
        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['medical_details'] = $this->medicalDetail->findAll($request->emp_id);
        return view('employee::employee.partial.ajaxlayouts.medicalDetailTable', $data)->render();
    }


    public function store(Request $request)
    {
        $data = $request->all();

        try {
            $this->medicalDetail->save($data);
            return ["status" => 1, "message" =>  "Medical Detail Created Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Creating Medical Detail!"];
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        try {
            $this->medicalDetail->update($request->id, $data);
            return ["status" => 1, "message" =>  "Medical Detail Updated Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Updating Medical Detail!"];
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->medicalDetail->delete($request->id);
            return ["status" => 1, "message" =>  "Medical Detail Deleted Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while deleting Medical Detail!"];
        }
    }
}
