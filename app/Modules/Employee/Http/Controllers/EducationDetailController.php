<?php

namespace App\Modules\Employee\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Modules\Employee\Jobs\EmployeeJob;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Employee\Entities\RequestChanges;
use App\Modules\Employee\Entities\EducationDetail;
use App\Modules\Employee\Repositories\EducationDetailInterface;

class EducationDetailController extends Controller
{
    protected $educationDetail;

    public function __construct(EducationDetailInterface $educationDetail)
    {
        $this->educationDetail = $educationDetail;
    }

    public function appendAll(Request $request)
    {
        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['education_details'] = $this->educationDetail->findAll($request->emp_id)
            ->transform(function ($item) {
                if (isset($item->equivalent_certificates) && !is_null($item->equivalent_certificates)) {
                    foreach (json_decode($item->equivalent_certificates, true) as $key => $value) {
                        $equivalent_certificates[$key] = asset('uploads/education/' . $value);
                    }
                }
                if (isset($item->degree_certificates) && !is_null($item->degree_certificates)) {
                    foreach (json_decode($item->degree_certificates, true) as $key => $value) {
                        $degree_certificates[$key] = asset('uploads/education/' . $value);
                    }
                }
                if (isset($item->is_foreign_board_file) && !is_null($item->is_foreign_board_file)) {
                    $is_foreign_board_file = asset('uploads/education/' . $item->is_foreign_board_file);
                }

                return [
                    'course_name' => $item->course_name ?? null,
                    'id' => $item->id,
                    'level' => $item->level ?? null,
                    'score' => $item->score ?? null,
                    'division' => $item->division ?? null,
                    'faculty' => $item->faculty ?? null,
                    'specialization' => $item->specialization ?? null,
                    'university_name' => $item->university_name ?? null,
                    'major_subject' => $item->major_subject ?? null,
                    'type_of_institution' => $item->type_of_institution ?? null,
                    'institution_name' => $item->institution_name ?? null,
                    'affiliated_to' => $item->affiliated_to ?? null,
                    'attended_from' => $item->attended_from ?? null,
                    'attended_to' => $item->attended_to ?? null,
                    'passed_year' =>  $item->passed_year ?? null,
                    'note' =>  $item->note ?? null,
                    'is_foreign_board' =>  $item->is_foreign_board == 1  ? 'Yes' : 'No',
                    'is_foreign_board_file' =>  $is_foreign_board_file ?? null,
                    'equivalent_certificates' =>  $equivalent_certificates ?? null,
                    'degree_certificates' =>  $degree_certificates ?? null
                ];
            });

        return view('employee::employee.partial.ajaxlayouts.educationDetailTable', $data)->render();
    }


    public function store(Request $request)
    {
        // return response()->json($request->all());
        if ($request->ajax()) {
            // dd($request->all());
            DB::beginTransaction();
            try {
                $data = $request->all();
                if (isset($request->is_foreign_board) && $request->is_foreign_board == "1") {
                    if (!empty($request->is_foreign_board_file) && $request->hasFile('is_foreign_board_file')) {
                        $fileName = time() . rand(1, 99) . '.' . $request->is_foreign_board_file->extension();
                        $request->is_foreign_board_file->move(public_path('uploads/education/'), $fileName);
                        $data['is_foreign_board_file'] = $fileName;
                    }
                }
                $data['is_foreign_board'] = $request->is_foreign_board == "1" ? 1 : 0;
                if ($request->has('equivalent_certificates') && !empty($request->equivalent_certificates) && $request->file('equivalent_certificates')) {
                    $data['equivalent_certificates'] = [];
                    foreach ($request->file('equivalent_certificates') as $file) {
                        $fileName = time() . rand(1, 99) . '.' . $file->extension();
                        $file->move(public_path('uploads/education/'), $fileName);
                        $data['equivalent_certificates'][] = $fileName;
                    }
                    $data['equivalent_certificates'] = json_encode($data['equivalent_certificates']);
                }
                if ($request->has('degree_certificates') && !empty($request->degree_certificates) && $request->file('degree_certificates')) {
                    $data['degree_certificates'] = [];
                    foreach ($request->file('degree_certificates') as $file) {
                        $fileName = time() . rand(1, 99) . '.' . $file->extension();
                        $file->move(public_path('uploads/education/'), $fileName);
                        $data['degree_certificates'][] = $fileName;
                    }
                    $data['degree_certificates'] = json_encode($data['degree_certificates']);
                }
                // return response()->json($data);
                $this->educationDetail->save($data);
                DB::commit();
                return ["status" => 1, "message" =>  "Education Detail Created Successfully!"];
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error("Store Education Detail Error: " . $th->getMessage());
                return ["status" => 0, "message" =>  $th->getMessage() . 'Line no: ' . $th->getLine()];
            }
        }
    }


    public function edit(Request $request)
    {
        if ($request->ajax()) {
            $item = $this->educationDetail->findOne($request->id);

            if (isset($item->equivalent_certificates) && !is_null($item->equivalent_certificates)) {
                foreach (json_decode($item->equivalent_certificates, true) as $key => $value) {
                    $equivalent_certificates[$key] = asset('uploads/education/' . $value);
                }
            }
            if (isset($item->degree_certificates) && !is_null($item->degree_certificates)) {
                foreach (json_decode($item->degree_certificates, true) as $key => $value) {
                    $degree_certificates[$key] = asset('uploads/education/' . $value);
                }
            }

            if (isset($item->is_foreign_board_file) && !is_null($item->is_foreign_board_file)) {
                $is_foreign_board_file = asset('uploads/education/' . $item->is_foreign_board_file);
            }


            $mapData = [
                'course_name' => $item->course_name ?? null,
                'passed_year' => $item->passed_year ?? null,
                'id' => $item->id,
                'level' => $item->level ?? null,
                'score' => $item->score ?? null,
                'division' => $item->division ?? null,
                'faculty' => $item->faculty ?? null,
                'specialization' => $item->specialization ?? null,
                'university_name' => $item->university_name ?? null,
                'major_subject' => $item->major_subject ?? null,
                'type_of_institution' => $item->type_of_institution ?? null,
                'institution_name' => $item->institution_name ?? null,
                'affiliated_to' => $item->affiliated_to ?? null,
                'attended_from' => $item->attended_from ?? null,
                'attended_to' => $item->attended_to ?? null,
                'passed_year' =>  $item->passed_year ?? null,
                'note' =>  $item->note ?? null,
                'is_foreign_board' =>  $item->is_foreign_board ?? null,
                'is_foreign_board_file' =>  $is_foreign_board_file ?? null,
                'equivalent_certificates' =>  $equivalent_certificates ?? null,
                'degree_certificates' =>  $degree_certificates ?? null
            ];
            return response()->json(['status' => true, 'data' => $mapData]);
        }
    }


    public function update(Request $request)
    {

        if ($request->ajax()) {
            $data = $request->all();
            $educationDetail = EducationDetail::findOrFail($request->id);

            // if (isset($request->is_foreign_board) && $request->is_foreign_board == "1") {
            //     if (!is_null($request->is_foreign_board_file) && $request->hasFile('is_foreign_board_file')) {
            //         $fileName = time() . rand(1, 99) . '.' . $request->is_foreign_board_file->extension();
            //         $request->is_foreign_board_file->move(public_path('uploads/education/'), $fileName);
            //         $data['is_foreign_board_file'] = $fileName;
            //     }
            // } else {
            //     if ($educationDetail->is_foreign_board_file != null) {
            //         $educationDetail->update([
            //             'is_foreign_board_file' => null,
            //             'is_foreign_board' => 0
            //         ]);
            //     }
            // }
            if (isset($request->is_foreign_board) && $request->is_foreign_board == "1") {
                if (!empty($request->is_foreign_board_file) && $request->hasFile('is_foreign_board_file')) {
                    $fileName = time() . rand(1, 99) . '.' . $request->is_foreign_board_file->extension();
                    $request->is_foreign_board_file->move(public_path('uploads/education/'), $fileName);
                    $data['is_foreign_board_file'] = $fileName;
                }
            }
            $data['is_foreign_board'] = $request->is_foreign_board == "1" ? 1 : 0;
            if ($request->has('equivalent_certificates') && !empty($request->equivalent_certificates) && $request->file('equivalent_certificates')) {
                $data['equivalent_certificates'] = [];
                foreach ($request->file('equivalent_certificates') as $file) {
                    $fileName = time() . rand(1, 99) . '.' . $file->extension();
                    $file->move(public_path('uploads/education/'), $fileName);
                    $data['equivalent_certificates'][] = $fileName;
                }
                $data['equivalent_certificates'] = json_encode($data['equivalent_certificates']);
            }
            if ($request->has('degree_certificates') && !empty($request->degree_certificates) && $request->file('degree_certificates')) {
                $data['degree_certificates'] = [];
                foreach ($request->file('degree_certificates') as $file) {
                    $fileName = time() . rand(1, 99) . '.' . $file->extension();
                    $file->move(public_path('uploads/education/'), $fileName);
                    $data['degree_certificates'][] = $fileName;
                }
                $data['degree_certificates'] = json_encode($data['degree_certificates']);
            }
            try {

                $old_data = $educationDetail;
                if (!$old_data) {
                    return ['status' => 0, 'message' => 'Family detail not found.'];
                }
                if (in_array(auth()->user()->user_type, ['super_admin', 'hr'])) {
                    $this->educationDetail->update($request->id, $data);
                    return ["status" => 1, "message" =>  "Family Detail Updated Successfully!"];
                }

                $data['status'] = 'pending';
                $new = EducationDetail::create($data);
                $old_request = RequestChanges::where('employee_id', $request->employee_id)->where('entity', 'EducationDetail')->where('old_entity_id', $request->id)->where('status', 'pending')->first();
                $old_request ? $old_request->update(['change_date' => now()]) : '';

                $changes = $data;
                $changes['employee_id'] = $old_data->employee_id;
                $changes['old_entity_id'] = $old_data->id;
                $changes['new_entity_id'] = $new->id ?? null;
                $changes['entity'] = "EducationDetail";
                $changes['change_date'] = now();
                // dd($changes,$data);
                $change = RequestChanges::create($changes);

                EmployeeJob::dispatch($change, $old_data->employee_id);
                return ["status" => 1, "message" =>  "Education Detail Requested for Verification !!"];

                // $this->educationDetail->update($request->id, $data);
                return ["status" => 1, "message" =>  "Education Detail Updated Successfully!"];
            } catch (Exception $e) {
                return ["status" => 0, "message" =>  "Error while Updating Education Detail!"];
            }
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->educationDetail->delete($request->id);
            return ["status" => 1, "message" =>  "Education Detail Deleted Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while deleting Education Detail!"];
        }
    }
}
