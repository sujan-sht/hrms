<?php

namespace App\Modules\Employee\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Employee\Repositories\VisaAndImmigrationDetailInterface;

class VisaAndImmigrationDetailController extends Controller
{
    protected $visaAndImmigrationDetail;

    public function __construct(VisaAndImmigrationDetailInterface $visaAndImmigrationDetail)
    {
        $this->visaAndImmigrationDetail = $visaAndImmigrationDetail;
    }

    public function appendAll(Request $request)
    {
        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['visaAndImmigration_details'] = $this->visaAndImmigrationDetail->findAll($request->emp_id);
        return view('employee::employee.partial.ajaxlayouts.visaAndImmigrationDetailTable', $data)->render();
    }


    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $this->visaAndImmigrationDetail->save($data);
            return ["status" => 1, "message" =>  "Visa/Immigration Doc Detail Created Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Creating Visa/Immigration Doc Detail!"];
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        try {
            $this->visaAndImmigrationDetail->update($request->id, $data);
            return ["status" => 1, "message" =>  "Visa/Immigration Doc Detail Updated Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Updating Visa/Immigration Doc Detail!"];
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->visaAndImmigrationDetail->delete($request->id);
            return ["status" => 1, "message" =>  "Visa/Immigration Doc Detail Deleted Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while deleting Visa/Immigration Doc Detail!"];
        }
    }
}
