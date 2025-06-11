<?php

namespace App\Modules\Employee\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Employee\Jobs\EmployeeJob;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Employee\Entities\FamilyDetail;
use App\Modules\Employee\Entities\RequestChanges;
use App\Modules\Employee\Entities\EmergencyDetail;
use App\Modules\Employee\Repositories\FamilyDetailInterface;

class FamilyDetailController extends Controller
{
    protected $familyDetail;

    public function __construct(FamilyDetailInterface $familyDetail)
    {
        $this->familyDetail = $familyDetail;
    }

    public function appendAll(Request $request)
    {
        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['family_details'] = $this->familyDetail->findAll($request->emp_id);
        return view('employee::employee.partial.ajaxlayouts.familyDetailTable', $data)->render();
    }


    public function store(Request $request)
    {
        $data = $request->all();
        if (!is_null($request->employee_same)) {
            $data['same_as_employee'] = (int) $request->employee_id ?? null;
        }
        if (!is_null($request->employee_different)) {
            $data['province_id'] = !is_null($request->province_id) ? $request->province_id : null;
            $data['district_id'] = !is_null($request->district_id) ? $request->district_id : null;
            $data['municipality'] = !is_null($request->municipality) ? $request->municipality : null;
            $data['ward_no'] = !is_null($request->ward_no) ? $request->ward_no : null;
            $data['family_address'] = !is_null($request->address) ? $request->address : null;
        }
        if (!is_null($request->is_emergency_contact)) {
            EmergencyDetail::create([
                'employee_id' => $request->employee_id,
                'name' => $request->name,
                'phone1' => $request->contact_no ?? null,
                'address' => json_encode([
                    'province_id' => !is_null($request->province_id) ? $request->province_id : null,
                    'district_id' => !is_null($request->district_id) ? $request->district_id : null,
                    'municipality' => !is_null($request->municipality) ? $request->municipality : null,
                    'ward_no' => !is_null($request->ward_no) ? $request->ward_no : null,
                    'address' => !is_null($request->address) ? $request->address : null
                ]),
                'relation' => $request->relation,
            ]);
        }
        try {
            if (in_array(auth()->user()->user_type, ['super_admin', 'hr'])) {
                $this->familyDetail->save($data);
                return ["status" => 1, "message" =>  "Family Detail Created Successfully!"];
            }

            $data['status'] = 'pending';
            $new = FamilyDetail::create($data);
            $old_request = RequestChanges::where('employee_id', $request->employee_id)->where('entity', 'FamilyDetail')->where('old_entity_id', $request->id)->where('status', 'pending')->first();
            $old_request ? $old_request->update(['change_date' => now()]) : '';

            $changes = $data;
            $changes['employee_id'] = $old_data->employee_id ?? $request->employee_id;
            $changes['old_entity_id'] = $old_data->id ?? null;
            $changes['new_entity_id'] = $new->id ?? null;
            $changes['entity'] = "FamilyDetail";
            $changes['change_date'] = now();
            // dd($changes,$data);
            $change = RequestChanges::create($changes);

            EmployeeJob::dispatch($change, $old_data->employee_id ?? $request->employee_id);
            return ["status" => 1, "message" =>  "Family Detail Create Requested !!"];


            return ["status" => 1, "message" =>  "Family Detail Created Successfully!"];
        } catch (Exception $e) {
            // dd($e);
            return ["status" => 0, "message" =>  "Error while Creating Family Detail!"];
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $data['dob']  = $request->dob ?? '';

        $familyDetail = FamilyDetail::findOrFail($request->id);

        if (!is_null($request->employee_same)) {
            $data['same_as_employee'] = (int) $request->employee_id ?? null;
        }
        // $data['province_id'] = !is_null($request->province_id) ? $request->province_id : null;
        // $data['district_id'] = !is_null($request->district_id) ? $request->district_id : null;
        // $data['municipality'] = !is_null($request->municipality) ? $request->municipality : null;
        // $data['ward_no'] = !is_null($request->ward_no) ? $request->ward_no : null;
        // $data['family_address'] = !is_null($request->address) ? $request->address : null;

        if (!is_null($request->is_emergency_contact)) {
            $emergencyDetail = EmergencyDetail::where("employee_id", $request->employee_id)->first();
            if ($emergencyDetail) {
                $emergencyDetail->update([
                    'employee_id' => $request->employee_id,
                    'name' => $request->name,
                    'phone1' => $request->contact ?? null,
                    'address' => json_encode([
                        'province_id' => !is_null($request->province_id) ? $request->province_id : null,
                        'district_id' => !is_null($request->district_id) ? $request->district_id : null,
                        'municipality' => !is_null($request->municipality) ? $request->municipality : null,
                        'ward_no' => !is_null($request->ward_no) ? $request->ward_no : null,
                        'address' => !is_null($request->address) ? $request->address : null
                    ]),
                    'relation' => $request->relation,
                ]);
            } else {
                EmergencyDetail::create([
                    'employee_id' => $request->employee_id,
                    'name' => $request->name,
                    'phone1' => $request->contact_no ?? null,
                    'address' => json_encode([
                        'province_id' => !is_null($request->province_id) ? $request->province_id : null,
                        'district_id' => !is_null($request->district_id) ? $request->district_id : null,
                        'municipality' => !is_null($request->municipality) ? $request->municipality : null,
                        'ward_no' => !is_null($request->ward_no) ? $request->ward_no : null,
                        'address' => !is_null($request->address) ? $request->address : null
                    ]),
                    'relation' => $request->relation,
                ]);
            }
        }
        try {
            // $familyDetail->update($data);
            $old_data = FamilyDetail::find($request->id);
            if (!$old_data) {
                return ['status' => 0, 'message' => 'Family detail not found.'];
            }
            if (in_array(auth()->user()->user_type, ['super_admin', 'hr'])) {
                $this->familyDetail->update($request->id, $data);
                return ["status" => 1, "message" =>  "Family Detail Updated Successfully!"];
            }

            $data['status'] = 'pending';
            $new = FamilyDetail::create($data);
            $old_request = RequestChanges::where('employee_id', $request->employee_id)->where('entity', 'FamilyDetail')->where('old_entity_id', $request->id)->where('status', 'pending')->first();
            $old_request ? $old_request->update(['change_date' => now()]) : '';

            $changes = $data;
            $changes['employee_id'] = $old_data->employee_id;
            $changes['old_entity_id'] = $old_data->id;
            $changes['new_entity_id'] = $new->id ?? null;
            $changes['entity'] = "FamilyDetail";
            $changes['change_date'] = now();
            // dd($changes,$data);
            $change = RequestChanges::create($changes);

            EmployeeJob::dispatch($change, $old_data->employee_id);
            return ["status" => 1, "message" =>  "Family Detail Requested for Verification !!"];

            return ["status" => 1, "message" =>  "Family Detail Updated Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Updated Family Detail!"];
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->familyDetail->delete($request->id);
            return ["status" => 1, "message" =>  "Family Detail Deleted Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while deleting Family Detail!"];
        }
    }
}
