<?php

namespace App\Modules\Employee\Http\Controllers;

use App\Modules\Employee\Entities\AwardDetail;
use App\Modules\Employee\Entities\EmergencyDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Support\Facades\DB;

class AwardDetailController extends Controller
{

    public function appendAll(Request $request)
    {
        $data['employeeModel'] = Employee::find($request->emp_id);

        $data['award_details'] = AwardDetail::where('employee_id', $request->emp_id)
            ->get()
            ->transform(function ($q) use ($data) {
                return [
                    'id' => $q->id,
                    'employee_id' => $q->employee_id,
                    'title' => $q->title,
                    'date' => $q->date,
                    'attachment' => !is_null($q->attachment) ?
                        collect(json_decode($q->attachment, true))->map(function ($a) {
                            return [
                                'name' => $a,
                                'url' => asset('uploads/award/' . $a)
                            ];
                        }) : null,
                    'status' => $q->date > $data['employeeModel']->nepali_join_date ? 'After Joining Date' : 'Before Joining Date'
                ];
            });

        return view('employee::employee.partial.ajaxlayouts.awardDetailTable', $data)->render();
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            if ($request->has('attachment') && !empty($request->attachment) && $request->file('attachment')) {
                $data['attachment'] = [];
                foreach ($request->file('attachment') as $file) {
                    $fileName = time() . rand(1, 99) . '.' . $file->extension();
                    $file->move(public_path('uploads/award/'), $fileName);
                    $data['attachment'][] = $fileName;
                }
                $data['attachment'] = json_encode($data['attachment']);
            }
            AwardDetail::create($data);
            DB::commit();
            return ["status" => 1, "message" =>  "Award Detail Created Successfully!"];
        } catch (\Throwable $th) {
            DB::rollBack();
            return ["status" => 0, "message" =>  $th->getMessage() . " Line No: " . $th->getLine()];
        }
    }

    public function update(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $data = $request->all();
            $awardDetail = AwardDetail::find($request->id);
            if ($request->has('attachment') && !empty($request->attachment) && $request->file('attachment')) {
                $data['attachment'] = [];
                foreach ($request->file('attachment') as $file) {
                    $fileName = time() . rand(1, 99) . '.' . $file->extension();
                    $file->move(public_path('uploads/award/'), $fileName);
                    $data['attachment'][] = $fileName;
                }
                $data['attachment'] = json_encode($data['attachment']);
            }
            $awardDetail->update($data);
            DB::commit();
            return ["status" => 1, "message" =>  "Award Detail Updated Successfully!"];
        } catch (\Throwable $th) {
            DB::rollBack();
            return ["status" => 0, "message" =>  $th->getMessage() . " Line No: " . $th->getLine()];
        }
    }

    public function destroy(Request $request)
    {
        $awardDetail = AwardDetail::find($request->id);
        if (!$awardDetail) return ["status" => 0, "message" => 'Award Detail is not found.'];
        if (!is_null($awardDetail->attachment)) {
            foreach (json_decode($awardDetail->attachment, true) as $value) {
                unlink(public_path() . '/uploads/award/' . $value);
            }
        }
        $awardDetail->delete();
        return ["status" => 1, "message" => "Deleted Successfully."];
    }
}
