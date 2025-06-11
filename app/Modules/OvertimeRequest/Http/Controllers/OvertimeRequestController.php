<?php

namespace App\Modules\OvertimeRequest\Http\Controllers;

use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\OvertimeRequest\Entities\OvertimeRequest;
use App\Modules\OvertimeRequest\Repositories\OvertimeRequestInterface;
use App\Modules\Setting\Entities\OtRateSetup;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OvertimeRequestController extends Controller
{
    private $employee;
    private $organization;
    private $branch;
    private $overtimeRequest;


    public function __construct(
        EmployeeInterface $employee,
        OrganizationInterface $organization,
        BranchInterface $branch,
        OvertimeRequestInterface $overtimeRequest
    ) {
        $this->employee = $employee;
        $this->organization = $organization;
        $this->branch = $branch;
        $this->overtimeRequest = $overtimeRequest;
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

        $data['employeeList'] = $this->employee->getList();
        $data['statusList'] =  OvertimeRequest::STATUS;
        if(in_array(auth()->user()->user_type, ['super_admin','hr', 'division_hr'])){
            unset($data['statusList'][2]);
        }
        $data['organizationList'] = $this->organization->getList();
        $data['overtimeRequests'] = $this->overtimeRequest->findAll(25, $filter, $sort);
        return view('overtimerequest::overtime-request.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['employees'] = $this->employee->getList();
        $data['id'] = null;
        return view('overtimerequest::overtime-request.create', $data);
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
            $overtimeRequest = $this->overtimeRequest->save($data);
            $overtimeRequest['enable_mail'] = setting('enable_mail');
            $this->overtimeRequest->sendMailNotification($overtimeRequest);

            toastr('Overtime Request Updated Successfully', 'success');
        } catch (\Throwable $th) {
            toastr('Error While Adding Overtime Request', 'error');
        }
        return redirect()->route('overtimeRequest.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */

    public function viewDetail($id)
    {
        $data['overtimeRequest'] = $this->overtimeRequest->find($id);
        return view('overtimerequest::overtime-request.show', $data);
    }

    public function claim($id)
    {
        $data['overtimeRequest'] = $this->overtimeRequest->find($id);
        return view('overtimerequest::overtime-request.claim-view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['employees'] = $this->employee->getList();
        $data['overtimeRequest'] = $this->overtimeRequest->find($id);
        $data['id'] = $id;
        return view('overtimerequest::overtime-request.edit', $data);
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

            $this->overtimeRequest->update($id, $data);
            toastr('Overtime Request Updated Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Updating Overtime Request', 'error');
        }
        return redirect()->route('overtimeRequest.index');
    }

    public function updateStatus(Request $request)
    {
        try {
            $data = $request->except('_token');
            unset($data['employee_id']);
            if($data['status'] == 2){
                $data['forwarded_remarks'] = $data['status_update_remarks'];
                $data['forwarded_by'] = auth()->user()->id;
                $data['forwarded_date'] = Carbon::now();

            }elseif ($data['status'] == 3) {
                $data['approved_remarks'] = $data['status_update_remarks'];
                $data['approved_by'] = auth()->user()->id;
                $data['approved_date'] = Carbon::now();

            }elseif ($data['status'] == 4) {
                $data['rejected_remarks'] = $data['status_update_remarks'];
                $data['rejected_by'] = auth()->user()->id;
                $data['rejected_date'] = Carbon::now();
            }
            $data['reject_note'] = $data['status_update_remarks'];
            $this->overtimeRequest->update($data['id'], $data);

            $overtimeRequest = $this->overtimeRequest->find($data['id']);
            $overtimeRequest['enable_mail'] = setting('enable_mail');
            $this->overtimeRequest->sendMailNotification($overtimeRequest);
            toastr('Overtime Request Status Updated Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Updating Overtime Request Status', 'error');
        }
        return redirect()->back();
    }

    public function updateClaimStatus(Request $request)
    {
        try {
            $data = $request->except('_token');
            $this->overtimeRequest->update($data['id'], $data);

            $overtimeRequest = $this->overtimeRequest->find($data['id']);
            $overtimeRequest['enable_mail'] = setting('enable_mail');
            $overtimeRequest['is_claim'] = 11;
            $this->overtimeRequest->sendMailNotification($overtimeRequest);
            toastr('Overtime Request Claim Status Updated Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Updating Overtime Request Claim Status', 'error');
        }
        return redirect()->route('overtimeRequest.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->overtimeRequest->delete($id);
            toastr('Overtime Request Deleted Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Deleting Overtime Request', 'error');
        }
        return redirect()->route('overtimeRequest.index');
    }

    public function teamRequests(Request $request)
    {
        if (auth()->user()->user_type != 'supervisor') {
            toastr('User have to be supervisor', 'error');
            return redirect()->back();
        }
        $filter = $request->all();

        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['title'] = 'Team Overtime Requests';
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employee->getList();
        $data['statusList'] = OvertimeRequest::STATUS;
        unset($data['statusList'][5]);

        $data['overtimeRequests'] = $this->overtimeRequest->findTeamOvertimeRequests(20, $filter, $sort);
        return view('overtimerequest::overtime-request.team-requests', $data);
    }

    public function cancelRequest(Request $request)
    {
        try {
            $data = $request->except('_token');
            $this->overtimeRequest->update($data['id'], $data);

            $overtimeRequest = $this->overtimeRequest->find($data['id']);
            $overtimeRequest['enable_mail'] = setting('enable_mail');
            $this->overtimeRequest->sendMailNotification($overtimeRequest);
            toastr('Overtime Request Cancelled Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Cancelling Overtime Request', 'error');
        }
        return redirect()->back();
    }

    public function checkMinOtTime(Request $request){
        try {
            $employee = $this->employee->find($request->employee_id);
            $otRateSetup = OtRateSetup::where('organization_id', $employee->organization_id)->first();

            if(isset($otRateSetup) && !empty($otRateSetup)){
                if(isset($otRateSetup->is_min_ot_requirement) && $otRateSetup->is_min_ot_requirement == 11){
                    if($request->difference_in_mins < $otRateSetup->min_ot_time){
                        $msg = 'Your minimum time to request OT is '. $otRateSetup->min_ot_time . ' minutes. So, you cannot request this Overtime.';
                    }
                }
            }
            return json_encode($msg);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function viewReport(Request $request)
    {
        $filter = $request->all();
        $data['otRequests'] = $this->overtimeRequest->findAll(25, $filter, null);

        if (auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'division_hr') {
            $data['employeeList'] = $this->employee->getList();
            $data['organizationList'] = $this->organization->getList();
        }
        return view('user::overtime-request.report', $data);
    }
}
