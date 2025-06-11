<?php

namespace App\Modules\Employee\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Employee\Jobs\EmployeeJob;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Employee\Entities\BankDetail;
use App\Modules\Employee\Entities\RequestChanges;
use App\Modules\Employee\Repositories\BankDetailInterface;

class BankDetailController extends Controller
{
    protected $bankDetail;

    public function __construct(BankDetailInterface $bankDetail)
    {
        $this->bankDetail = $bankDetail;
    }

    public function appendAll(Request $request)
    {
        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['bank_details'] = $this->bankDetail->findAll($request->emp_id);
        return view('employee::employee.partial.ajaxlayouts.bankDetailTable', $data)->render();
    }


    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $this->bankDetail->save($data);
            return ["status" => 1, "message" =>  "Bank Detail Created Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Creating Bank Detail!"];
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        try {
            // $this->bankDetail->update($request->id, $data);
            $old_data = BankDetail::find($request->id);
            if (!$old_data) {
                return ['status' => 0, 'message' => 'Bank detail not found.'];
            }
            $data['employee_id'] = $old_data->employee_id;
            $data['status'] = 'pending';
            $new = BankDetail::create($data);
            $old_request = RequestChanges::where('employee_id', $old_data->employee_id)->where('entity', 'BankDetail')->where('old_entity_id', $request->id)->where('status', 'pending')->first();
            $old_request ? $old_request->update(['change_date' => now()]) : '';

            $changes = $data;
            $changes['employee_id'] = $old_data->employee_id;
            $changes['old_entity_id'] = $old_data->id;
            $changes['new_entity_id'] = $new->id ?? null;
            $changes['entity'] = "BankDetail";
            $changes['change_date'] = now();
            // dd($changes,$data);
            $change = RequestChanges::create($changes);

            EmployeeJob::dispatch($change, $old_data->employee_id);
            // $this->bankDetail->update($request->id, $data);
            return ["status" => 1, "message" =>  "Bank Detail Change Requested !!!"];

            return ["status" => 1, "message" =>  "Bank Detail Updated Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Updating Bank Detail!"];
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->bankDetail->delete($request->id);
            return ["status" => 1, "message" =>  "Bank Detail Deleted Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while deleting Bank Detail!"];
        }
    }
}
