<?php

namespace App\Modules\Employee\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Employee\Repositories\ContractDetailInterface;

class ContractDetailController extends Controller
{
    protected $contractDetail;

    public function __construct(ContractDetailInterface $contractDetail)
    {
        $this->contractDetail = $contractDetail;
    }

    public function appendAll(Request $request)
    {
        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['contract_details'] = $this->contractDetail->findAll($request->emp_id);
        return view('employee::employee.partial.ajaxlayouts.contractDetailTable', $data)->render();
    }


    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $this->contractDetail->save($data);
            return ["status" => 1, "message" =>  "Contract Detail Created Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Creating Contract Detail!"];
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        try {
            $this->contractDetail->update($request->id, $data);
            return ["status" => 1, "message" =>  "Contract Detail Updated Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Updating Contract Detail!"];
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->contractDetail->delete($request->id);
            return ["status" => 1, "message" =>  "Contract Detail Deleted Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while deleting Contract Detail!"];
        }
    }
}
