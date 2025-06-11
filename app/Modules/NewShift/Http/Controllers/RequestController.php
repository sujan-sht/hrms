<?php

namespace App\Modules\NewShift\Http\Controllers;

use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\NewShift\Entities\NewShiftEmployeeDetail;
use App\Modules\NewShift\Entities\NewShiftRequest;
use App\Modules\NewShift\Repositories\ShiftInterface;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\OvertimeRequest\Entities\OvertimeRequest;
use App\Modules\Setting\Entities\OtRateSetup;
use App\Modules\Shift\Repositories\ShiftGroupInterface;
use App\Modules\User\Entities\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RequestController extends Controller
{
    private $employee;
    private $organization;
    private $branch;
    private $newShift;
    private $shiftGroup;


    public function __construct(
        EmployeeInterface $employee,
        OrganizationInterface $organization,
        BranchInterface $branch,
        ShiftInterface $newShift,
        ShiftGroupInterface $shiftGroup
    ) {
        $this->employee = $employee;
        $this->organization = $organization;
        $this->branch = $branch;
        $this->newShift = $newShift;
        $this->shiftGroup = $shiftGroup;
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];

        // $data['employeeList'] = $this->employee->getList();
        $data['statusList'] =  NewShiftRequest::STATUS;
        // if(in_array(auth()->user()->user_type, ['super_admin','hr', 'division_hr'])){
        //     unset($data['statusList'][2]);
        // }
        // $data['organizationList'] = $this->organization->getList();
        $data['rosterRequests'] = $this->newShift->findAllRequests(25, $filter, $sort);
        return view('newshift::requests.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        // $data['employees'] = $this->employee->getList();
        $data['shiftGroupLists'] = $this->shiftGroup->getList();
        $data['id'] = null;
        return view('newshift::requests.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $data = $request->except('_token');

            $data['date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['nepali_date']) : $data['date'];
            $data['nepali_date'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['date']) : $data['nepali_date'];
            $data['status'] = 1;
            $rosterRequest = $this->newShift->saveRequest($data);

            if($rosterRequest){
                // check for all hr roles
                $employeeModel = Employee::find($rosterRequest['employee_id']);
                $hrs = User::where('user_type', 'hr')->pluck('id');
                if (isset($hrs) && !empty($hrs)) {
                    foreach ($hrs as $hr) {
                        // create notification for hr
                        $notificationData['creator_user_id'] = auth()->user()->id;
                        $notificationData['notified_user_id'] = $hr;
                        $notificationData['message'] = $employeeModel->full_name . "'s roster request has been created.";
                        $notificationData['link'] = route('rosterRequest.index');
                        $notificationData['type'] = 'Roster';
                        $notificationData['type_id_value'] = $rosterRequest->id;
                        Notification::create($notificationData);
                    }
                }
            }

            toastr('Roster Request Updated Successfully', 'success');
        } catch (\Throwable $th) {
            toastr('Error While Adding Roster Request', 'error');
        }
        return redirect()->route('rosterRequest.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */

    // public function viewDetail($id)
    // {
    //     $data['overtimeRequest'] = $this->newShift->find($id);
    //     return view('newshift::requests.show', $data);
    // }

    // public function claim($id)
    // {
    //     $data['overtimeRequest'] = $this->newShift->find($id);
    //     return view('newshift::requests.claim-view', $data);
    // }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        // $data['employees'] = $this->employee->getList();
        $data['shiftGroupLists'] = $this->shiftGroup->getList();
        $data['rosterRequest'] = $this->newShift->findRequest($id);
        $data['id'] = $id;
        return view('newshift::requests.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            $data['date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['nepali_date']) : $data['date'];
            $data['nepali_date'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['date']) : $data['nepali_date'];

            $this->newShift->updateRequest($id, $data);
            toastr('Roster Request Updated Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Updating Roster Request', 'error');
        }
        return redirect()->route('rosterRequest.index');
    }

    public function updateStatus(Request $request)
    {
        try {
            $data = $request->except('_token');
            unset($data['employee_id']);
            // if($data['status'] == 2){
            //     $data['forwarded_remarks'] = $data['status_update_remarks'];
            //     $data['forwarded_by'] = auth()->user()->id;
            //     $data['forwarded_date'] = Carbon::now();

            // }elseif ($data['status'] == 3) {
            //     $data['approved_remarks'] = $data['status_update_remarks'];
            //     $data['approved_by'] = auth()->user()->id;
            //     $data['approved_date'] = Carbon::now();

            // }elseif ($data['status'] == 4) {
            //     $data['rejected_remarks'] = $data['status_update_remarks'];
            //     $data['rejected_by'] = auth()->user()->id;
            //     $data['rejected_date'] = Carbon::now();
            // }
            $data['remarks'] = $data['status_update_remarks'];
            $update = $this->newShift->updateRequest($data['id'], $data);
            if($update){
                $request = $this->newShift->findRequest($data['id']);
                if(isset($request['shift_group_id'])){
                    $newShiftEmp = NewShiftEmployee::where('emp_id', $request['employee_id'])->where('eng_date', $request['date'])->first();
                    if(isset($newShiftEmp) && !empty($newShiftEmp)){
                        $newShiftEmpId = $newShiftEmp->id;
                    }else{
                        $data = [
                            'emp_id' => $request['employee_id'],
                            'eng_date' => $request['date']
                        ];
                        $newShiftEmpData = NewShiftEmployee::create($data);
                        $newShiftEmpId = $newShiftEmpData->id;
                    }

                    if(isset($newShiftEmpId)){
                        $finalArr = [
                            'new_shift_employee_id' => $newShiftEmpId,
                            'type' => "S",
                            'shift_group_id' => $request['shift_group_id']
                        ];
                        $newShiftEmpDetail = NewShiftEmployeeDetail::where('new_shift_employee_id', $newShiftEmpId)->first();
                        if(!empty($newShiftEmpDetail)){
                            if($newShiftEmpDetail['type'] == $finalArr['type'] && $newShiftEmpDetail['shift_group_id'] == $finalArr['shift_group_id']){
                                //do nothing
                            }else{
                                NewShiftEmployeeDetail::where('new_shift_employee_id', $newShiftEmpId)->delete();
                                NewShiftEmployeeDetail::create($finalArr);
                            }

                        }else{
                            NewShiftEmployeeDetail::create($finalArr);
                        }
                    }
                    $employeeModel = Employee::find($request['employee_id']);

                    // create notification for hr
                    $notificationData['creator_user_id'] = auth()->user()->id;
                    $notificationData['notified_user_id'] = optional($employeeModel->user)->id;
                    $notificationData['message'] = $employeeModel->full_name . "'s roster request has been ". $request->getStatus();
                    $notificationData['link'] = route('rosterRequest.index');
                    $notificationData['type'] = 'Roster';
                    $notificationData['type_id_value'] = $data['id'];
                    Notification::create($notificationData);
                       

                }
            }
            toastr('Roster Request Status Updated Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Updating Roster Request Status', 'error');
        }
        return redirect()->back();
    }

    // public function updateClaimStatus(Request $request)
    // {
    //     try {
    //         $data = $request->except('_token');
    //         $this->newShift->update($data['id'], $data);

    //         $overtimeRequest = $this->newShift->find($data['id']);
    //         $overtimeRequest['enable_mail'] = setting('enable_mail');
    //         $overtimeRequest['is_claim'] = 11;
    //         $this->newShift->sendMailNotification($overtimeRequest);
    //         toastr('Roster Request Claim Status Updated Successfully', 'success');
    //     } catch (Exception $e) {
    //         toastr('Error While Updating Roster Request Claim Status', 'error');
    //     }
    //     return redirect()->route('overtimeRequest.index');
    // }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->newShift->deleteRequest($id);
            toastr('Roster Request Deleted Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Deleting Roster Request', 'error');
        }
        return redirect()->route('rosterRequest.index');
    }

    // public function teamRequests(Request $request)
    // {
    //     if (auth()->user()->user_type != 'supervisor') {
    //         toastr('User have to be supervisor', 'error');
    //         return redirect()->back();
    //     }
    //     $filter = $request->all();

    //     $sort = [
    //         'by' => 'id',
    //         'sort' => 'DESC'
    //     ];
    //     $data['title'] = 'Team Roster Requests';
    //     $data['organizationList'] = $this->organization->getList();
    //     $data['employeeList'] = $this->employee->getList();
    //     $data['statusList'] = OvertimeRequest::STATUS;
    //     unset($data['statusList'][5]);

    //     $data['overtimeRequests'] = $this->newShift->findTeamOvertimeRequests(20, $filter, $sort);
    //     return view('newshift::requests.team-requests', $data);
    // }

    // public function cancelRequest(Request $request)
    // {
    //     try {
    //         $data = $request->except('_token');
    //         $this->newShift->update($data['id'], $data);

    //         $overtimeRequest = $this->newShift->find($data['id']);
    //         $overtimeRequest['enable_mail'] = setting('enable_mail');
    //         $this->newShift->sendMailNotification($overtimeRequest);
    //         toastr('Roster Request Cancelled Successfully', 'success');
    //     } catch (Exception $e) {
    //         toastr('Error While Cancelling Roster Request', 'error');
    //     }
    //     return redirect()->back();
    // }

    // public function checkMinOtTime(Request $request){
    //     try {
    //         $employee = $this->employee->find($request->employee_id);
    //         $otRateSetup = OtRateSetup::where('organization_id', $employee->organization_id)->first();

    //         if(isset($otRateSetup) && !empty($otRateSetup)){
    //             if(isset($otRateSetup->is_min_ot_requirement) && $otRateSetup->is_min_ot_requirement == 11){
    //                 if($request->difference_in_mins < $otRateSetup->min_ot_time){
    //                     $msg = 'Your minimum time to request OT is '. $otRateSetup->min_ot_time . ' minutes. So, you cannot request this Overtime.';
    //                 }
    //             }
    //         }
    //         return json_encode($msg);

    //     } catch (\Throwable $th) {
    //         //throw $th;
    //     }
    // }

    // public function viewReport(Request $request)
    // {
    //     $filter = $request->all();
    //     $data['otRequests'] = $this->newShift->findAll(25, $filter, null);

    //     if (auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'division_hr') {
    //         $data['employeeList'] = $this->employee->getList();
    //         $data['organizationList'] = $this->organization->getList();
    //     }
    //     return view('user::requests.report', $data);
    // }
}
