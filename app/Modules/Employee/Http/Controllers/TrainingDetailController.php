<?php

namespace App\Modules\Employee\Http\Controllers;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Repositories\TrainingDetailInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TrainingDetailController extends Controller
{
    protected $trainingDetail;

    public function __construct(TrainingDetailInterface $trainingDetail)
    {
        $this->trainingDetail = $trainingDetail;
    }

    public function appendAll(Request $request)
    {
        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['training_attendance'] = $this->trainingDetail->findAll($request->emp_id);
        // dd($data['training_attendance']->toArray());
        return view('employee::employee.partial.ajaxlayouts.trainingDetailTable', $data)->render();
    }

    public function update(Request $request)
    {
        $data = $request->all();
        try {
            $this->trainingDetail->update($request->id, $data);
            return ["status" => 1, "message" =>  "Training Detail Updated Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Updating Training Detail!"];
        }
    }
}
