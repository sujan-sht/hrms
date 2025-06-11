<?php

namespace App\Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\SkillDetail;
use Illuminate\Support\Facades\DB;

class SkillDetailController extends Controller
{

    public function appendAll(Request $request)
    {
        $data['employeeModel'] = Employee::find($request->emp_id);

        $data['skill_details'] = SkillDetail::where('employee_id', $request->emp_id)
            ->get()
            ->map(function ($skillDetail) {
                $skillDetail->rating_number = $skillDetail->rating;
                $skillDetail->rating = SkillDetail::getSkillMetric($skillDetail->rating);
                return $skillDetail;
            });


        return view('employee::employee.partial.ajaxlayouts.skillDetailTable', $data)->render();
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            SkillDetail::create($data);
            DB::commit();
            return ["status" => 1, "message" =>  "Skill Detail Created Successfully!"];
        } catch (\Throwable $th) {
            DB::rollBack();
            return ["status" => 0, "message" =>  $th->getMessage() . " Line No: " . $th->getLine()];
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $skillDetail = SkillDetail::find($request->id);
            $skillDetail->update($data);
            DB::commit();
            return ["status" => 1, "message" =>  "Skill Detail Updated Successfully!"];
        } catch (\Throwable $th) {
            DB::rollBack();
            return ["status" => 0, "message" =>  $th->getMessage() . " Line No: " . $th->getLine()];
        }
    }

    public function destroy(Request $request)
    {
        $skillDetail = SkillDetail::find($request->id);
        if (!$skillDetail) return ["status" => 0, "message" => 'Skill Detail is not found.'];
        $skillDetail->delete();
        return ["status" => 1, "message" => "Deleted Successfully."];
    }
}
