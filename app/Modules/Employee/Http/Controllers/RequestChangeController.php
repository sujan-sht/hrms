<?php

namespace App\Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\RequestChanges;

class RequestChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data['changes'] = RequestChanges::where('status', 'pending')->whereIn('id', function ($query) {
            $query->selectRaw('MAX(id)')
                ->from('request_changes')
                ->groupBy('employee_id');
        })->orderBy('created_at', 'desc')->paginate(10);
        $data['title'] = "Changes Requested";
        // dd($data);
        return view('employee::employee.request-changes', $data);
    }
    public function view($id)
    {
        // dd($id);
        $requestChanges = RequestChanges::with('employee', 'approved_info')->where('employee_id', $id)->whereNull('entity')->get();
        $settings = Setting::first();
        $detail_changes = RequestChanges::with('employee')
            ->where('employee_id', $id)
            ->whereNotNull('entity')
            ->orderBy('entity', 'ASC')
            ->get()
            ->groupBy('entity');

        // $detail_changes = RequestChanges::with('employee')->where('employee_id', $id)->whereNotNull('entity')->orderBy('entity','ASC')->get();
        $employeeModel = Employee::find($id);
        $entities = $detail_changes->keys();
        // dd($detail_changes,$requestChanges);
        return view('employee::employee.request-change', with([
            "changes" => $requestChanges,
            "detail_changes" => $detail_changes,
            'entities' => $entities,
            'settings' => $settings,
            'employeeModel' => $employeeModel
        ]));
    }


    public function approvedChanges($status, $id)
    {
        $data = RequestChanges::find($id);
        if (!$data) {
            return ["status" => 0, "message" => "Request not found!"];
        }
        if ($status == '0') {
            $data->update(['status' => 'rejected']);
            return back()->with(["status" => 1, "message" => "Employee Details Changes Rejected !!!"]);
        }
        if ($data->entity === null) {
            $update = $this->handleEmployeeUpdate($status, $data);
        } else {
            $update = $this->handleEntityUpdate($status, $data);
        }
        if ($update == true) {
            $data->update([
                'status' => 'approved',
                'approved_by' => auth()->user()->id,
                'approved_date' => now(),
            ]);
        }
        return back()->with(["status" => 1, "message" => "Employee Details Changes Approved Successfully!"]);
    }


    private function handleEmployeeUpdate($status, $data)
    {
        $employee = Employee::find($data->employee_id);
        if (!$employee) {
            return ["status" => 0, "message" => "Employee not found!"];
        }
        $fields = [
            'first_name',
            'middle_name',
            'last_name',
            'mobile',
            'phone',
            'personal_email',
            'permanentaddress',
            'temporaryaddress',
            'national_id',
            'passport_no',
            'telephone',
            'official_email',
            'marital_status',
            'citizenship_no',
            'blood_group',
            'ethnicity',
            'language'
        ];

        $updateData = [];
        foreach ($fields as $field) {
            $newField = "new_{$field}";
            if (!is_null($data->$newField)) {
                $updateData[$field] = $data->$newField;
            }
        }
        if (!empty($updateData)) {
            $employee->update($updateData);
            return true;
        }
        return false;
    }



    private function handleEntityUpdate($status, $data)
    {
        $modelClass = "\App\Modules\Employee\Entities\\" . $data->entity;
        if (!class_exists($modelClass)) {
            return ["status" => 0, "message" => "Data does not exist!"];
        }
        $oldModel = $modelClass::find($data->old_entity_id);
        $newModel = $modelClass::find($data->new_entity_id);
        if (!$oldModel || !$newModel) {
            return ["status" => 0, "message" => "Data not found!"];
        }
        $oldModel->delete();
        $data->update(['status' => 'approved']);
        return true;

        $newModel->update(['status' => 'approved']);
        return back()->with(["status" => 1, "message" => "Employee Details Changes Approved Successfully!"]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Fetch all employees for the dropdown
        $employees = Employee::all();
        return view('employee::request-changes.create', compact('employees'));
    }

    public function store(Request $request)
    {
        dd($request->all());
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'old_first_name' => 'nullable|string',
            'old_middle_name' => 'nullable|string',
            'old_last_name' => 'nullable|string',
            'old_mobile' => 'nullable|string',
            'old_phone' => 'nullable|string',
            'old_personal_email' => 'nullable|email',
            'old_permanent_address' => 'nullable|string',
            'old_temporary_address' => 'nullable|string',
            'new_first_name' => 'nullable|string',
            'new_middle_name' => 'nullable|string',
            'new_last_name' => 'nullable|string',
            'new_mobile' => 'nullable|string',
            'new_phone' => 'nullable|string',
            'new_personal_email' => 'nullable|email',
            'new_permanent_address' => 'nullable|string',
            'new_temporary_address' => 'nullable|string',
            'entity' => 'required|string',
            'old_entity_id' => 'nullable|integer',
            'new_entity_id' => 'nullable|integer',
            'status' => 'required|in:pending,approved,rejected',
            'approved_by' => 'nullable|integer',
        ]);

        RequestChanges::create($validated);

        return redirect()->route('request-changes.index')->with('success', 'Request Change created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modules\Employee\Entities\RequestChanges  $requestChange
     * @return \Illuminate\Http\Response
     */
    public function show(RequestChanges $requestChange)
    {
        return view('employee::request-changes.show', compact('requestChange'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Modules\Employee\Entities\RequestChanges  $requestChange
     * @return \Illuminate\Http\Response
     */
    public function edit(RequestChanges $requestChange)
    {
        $employees = Employee::all();
        return view('employee::request-changes.edit', compact('requestChange', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modules\Employee\Entities\RequestChanges  $requestChange
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RequestChanges $requestChange)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'old_first_name' => 'nullable|string',
            'old_middle_name' => 'nullable|string',
            'old_last_name' => 'nullable|string',
            'old_mobile' => 'nullable|string',
            'old_phone' => 'nullable|string',
            'old_personal_email' => 'nullable|email',
            'old_permanent_address' => 'nullable|string',
            'old_temporary_address' => 'nullable|string',
            'new_first_name' => 'nullable|string',
            'new_middle_name' => 'nullable|string',
            'new_last_name' => 'nullable|string',
            'new_mobile' => 'nullable|string',
            'new_phone' => 'nullable|string',
            'new_personal_email' => 'nullable|email',
            'new_permanent_address' => 'nullable|string',
            'new_temporary_address' => 'nullable|string',
            'entity' => 'required|string',
            'old_entity_id' => 'nullable|integer',
            'new_entity_id' => 'nullable|integer',
            'status' => 'required|in:pending,approved,rejected',
            'approved_by' => 'nullable|integer',
        ]);

        $requestChange->update($validated);

        return redirect()->route('request-changes.index')->with('success', 'Request Change updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modules\Employee\Entities\RequestChanges  $requestChange
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequestChanges $requestChange)
    {
        $requestChange->delete();
        return redirect()->route('request-changes.index')->with('success', 'Request Change deleted successfully.');
    }
}
