<?php

namespace App\Modules\Employee\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\User\Entities\User;
use App\Modules\Employee\Jobs\EmployeeJob;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Employee\Entities\RequestChanges;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Employee\Entities\PreviousJobDetail;
use App\Modules\Employee\Repositories\PreviousJobDetailInterface;

class PreviousJobDetailController extends Controller
{
    protected $previousJobDetail;

    public function __construct(PreviousJobDetailInterface $previousJobDetail)
    {
        $this->previousJobDetail = $previousJobDetail;
    }

    public function appendAll(Request $request)
    {
        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['previous_job_details'] = $this->previousJobDetail->findAll($request->emp_id);
        return view('employee::employee.partial.ajaxlayouts.previousJobDetailTable', $data)->render();
    }


    public function store(Request $request)
    {
        $data = $request->all();
        try {
            if (in_array(auth()->user()->user_type, ['super_admin', 'hr'])) {
                $data['approved_by_hr'] = 1;
                $this->previousJobDetail->save($data);
                return ["status" => 1, "message" =>  "Family Detail Created Successfully!"];
            }

            $this->previousJobDetail->save($data);
            $new = PreviousJobDetail::create($data);
            $old_request = RequestChanges::where('employee_id', $request->employee_id)->where('entity', 'PreviousJobDetail')->where('old_entity_id', $request->id)->where('status', 'pending')->first();
            $old_request ? $old_request->update(['change_date' => now()]) : '';

            $changes = $data;
            $changes['employee_id'] = $old_data->employee_id ?? $request->employee_id;
            $changes['old_entity_id'] = $old_data->id ?? null;
            $changes['new_entity_id'] = $new->id ?? null;
            $changes['entity'] = "PreviousJobDetail";
            $changes['change_date'] = now();
            // dd($changes,$data);
            $change = RequestChanges::create($changes);

            EmployeeJob::dispatch($change, $old_data->employee_id ?? $request->employee_id);

            // $hrLists = User::where('user_type', 'hr')->where('active', 1)->get();
            // foreach ($hrLists as $hrList) {
            //     $notificationData['creator_user_id'] = auth()->user()->id;
            //     $notificationData['notified_user_id'] = $hrList->id;
            //     $notificationData['message'] = "Previous Job Detail has been created by " . optional(auth()->user()->userEmployer)->full_name;
            //     $notificationData['link'] = route('employee.pendingPreviousJobDetail');
            //     $notificationData['type'] = 'Previous Job Detail';
            //     $notificationData['type_id_value'] = null;
            //     Notification::create($notificationData);
            // }

            return ["status" => 1, "message" =>  "Your Previous Job Detail has been forwarded to hr for verification!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Creating Previous Job Detail!"];
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        try {
            // $this->previousJobDetail->update($request->id, $data);
            // $hrLists = User::where('user_type', 'hr')->where('active', 1)->get();
            // foreach ($hrLists as $hrList) {
            //     $notificationData['creator_user_id'] = auth()->user()->id;
            //     $notificationData['notified_user_id'] = $hrList->id;
            //     $notificationData['message'] = "Previous Job Detail has been updated by " . optional(auth()->user()->userEmployer)->full_name;
            //     $notificationData['link'] = route('employee.pendingPreviousJobDetail');
            //     $notificationData['type'] = 'Previous Job Detail';
            //     $notificationData['type_id_value'] = null;
            //     Notification::create($notificationData);
            // }

            $old_data = PreviousJobDetail::find($request->id);
            if (!$old_data) {
                return ['status' => 0, 'message' => 'Family detail not found.'];
            }
            if (in_array(auth()->user()->user_type, ['super_admin', 'hr'])) {
                $this->previousJobDetail->update($request->id, $data);
                return ["status" => 1, "message" =>  "Family Detail Updated Successfully!"];
            }

            $data['approved_by_hr'] = 0;
            $new = PreviousJobDetail::create($data);
            $old_request = RequestChanges::where('employee_id', $request->employee_id)->where('entity', 'PreviousJobDetail')->where('old_entity_id', $request->id)->where('status', 'pending')->first();
            $old_request ? $old_request->update(['change_date' => now()]) : '';

            $changes = $data;
            $changes['employee_id'] = $old_data->employee_id;
            $changes['old_entity_id'] = $old_data->id;
            $changes['new_entity_id'] = $new->id ?? null;
            $changes['entity'] = "PreviousJobDetail";
            $changes['change_date'] = now();
            // dd($changes,$data);
            $change = RequestChanges::create($changes);

            EmployeeJob::dispatch($change, $old_data->employee_id);
            return ["status" => 1, "message" =>  "Your Previous Job Detail has been forwarded to hr for verification!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Updating Previous Job Detail!"];
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->previousJobDetail->delete($request->id);
            return ["status" => 1, "message" =>  "Previous Job Detail Deleted Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while deleting Previous Job Detail!"];
        }
    }

    public function pendingJobDetail(Request $request)
    {
        $search = $request->all();
        $data['statusList'] = [
            '0' => 'Pending',
            '1' => 'Approved',
        ];
        $data['previous_job_details'] = $this->previousJobDetail->findAll($search['emp_id'] ?? null);
        return view('employee::employee.partial.previous-pending-job-detail', $data);
    }

    public function updatePendingPreviousJobDetail(Request $request)
    {
        try {
            $data['approved_by_hr'] = $request->status;
            $item = PreviousJobDetail::find($request->id);
            $this->previousJobDetail->update($request->id, $data);
            $notificationData['creator_user_id'] = auth()->user()->id;
            $notificationData['notified_user_id'] = optional(optional($item->employee)->user)->id;
            $notificationData['message'] = "Previous Job Detail has been approved by " . optional(auth()->user()->userEmployer)->full_name;
            $notificationData['link'] = route('employee.viewSelfProfile', $item->employee_id);
            $notificationData['type'] = 'Previous Job Detail';
            $notificationData['type_id_value'] = null;
            Notification::create($notificationData);
            toastr('Previous Job Detail updated Successfully!', 'success');
            return back();
        } catch (Exception $e) {
            toastr('Error while updating Previous Job Detail!', 'error');
            return back();
        }
    }
}
