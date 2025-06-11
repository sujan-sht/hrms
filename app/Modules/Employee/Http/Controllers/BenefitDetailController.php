<?php

namespace App\Modules\Employee\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Employee\Repositories\BenefitDetailInterface;

class BenefitDetailController extends Controller
{
    protected $benefitDetail;

    public function __construct(BenefitDetailInterface $benefitDetail)
    {
        $this->benefitDetail = $benefitDetail;
    }

    public function appendAll(Request $request)
    {
        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['benefit_details'] = $this->benefitDetail->findAll($request->emp_id);
        return view('employee::employee.partial.ajaxlayouts.benefitDetailTable', $data)->render();
    }


    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $this->benefitDetail->save($data);
            return ["status" => 1, "message" =>  "Benefit Detail Created Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Creating Benefit Detail!"];
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        try {
            $this->benefitDetail->update($request->id, $data);
            return ["status" => 1, "message" =>  "Benefit Detail Updated Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Updating Benefit Detail!"];
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->benefitDetail->delete($request->id);
            return ["status" => 1, "message" =>  "Benefit Detail Deleted Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while deleting Benefit Detail!"];
        }
    }
}
