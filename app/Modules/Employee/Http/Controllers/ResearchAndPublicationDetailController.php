<?php

namespace App\Modules\Employee\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Employee\Repositories\ResearchAndPublicationDetailInterface;

class ResearchAndPublicationDetailController extends Controller
{
    protected $researchAndPublicationDetail;

    public function __construct(ResearchAndPublicationDetailInterface $researchAndPublicationDetail)
    {
        $this->researchAndPublicationDetail = $researchAndPublicationDetail;
    }

    public function appendAll(Request $request)
    {
        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['researchAndPublication_details'] = $this->researchAndPublicationDetail->findAll($request->emp_id);
        return view('employee::employee.partial.ajaxlayouts.researchAndPublicationDetailTable', $data)->render();
    }


    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $this->researchAndPublicationDetail->save($data);
            return ["status" => 1, "message" =>  "Research And Publication Detail Created Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Creating Research And Publication Detail!"];
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        try {
            $this->researchAndPublicationDetail->update($request->id, $data);
            return ["status" => 1, "message" =>  "Research And Publication Detail Updated Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Updating Research And Publication Detail!"];
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->researchAndPublicationDetail->delete($request->id);
            return ["status" => 1, "message" =>  "Research And Publication Detail Deleted Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while deleting Research And Publication Detail!"];
        }
    }
}
