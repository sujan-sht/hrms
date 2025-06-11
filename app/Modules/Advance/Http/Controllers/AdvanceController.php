<?php

namespace App\Modules\Advance\Http\Controllers;

use App\Modules\Advance\Entities\Advance;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Advance\Repositories\AdvanceInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Advance\Repositories\AdvanceSettlementPaymentInterface;
use App\Modules\Setting\Entities\PayrollCalenderTypeSetting;
use App\Modules\Setting\Repositories\SettingInterface;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogTrait;
class AdvanceController extends Controller
{
    use LogTrait;
    protected $organizationObj;
    protected $employeeObj;
    protected $advanceObj;
    protected $advanceSettlementPaymentObj;
    protected $settingObj;

    /**
     * Constructor
     */
    public function __construct(
        OrganizationInterface $organizationObj,
        EmployeeInterface $employeeObj,
        AdvanceInterface $advanceObj,
        AdvanceSettlementPaymentInterface $advanceSettlementPaymentObj,
        SettingInterface $settingObj
    ) {
        $this->organizationObj = $organizationObj;
        $this->employeeObj = $employeeObj;
        $this->advanceObj = $advanceObj;
        $this->advanceSettlementPaymentObj = $advanceSettlementPaymentObj;
        $this->settingObj = $settingObj;
    }

    /**
     * 
     */
    public function index(Request $request)
    {
        $inputData = $request->all();

        $data['title'] = 'Advances';
        $currentUserModel = Auth::user();
        if ($currentUserModel->user_type == 'supervisor' || $currentUserModel->user_type == 'employee') {
            $inputData['employee'] = $currentUserModel->emp_id;
        }
        $data['approvalstatusList'] = Advance::approvalStatusList();
        $data['employeeList'] = $this->employeeObj->getList();
        $data['advanceModels'] = $this->advanceObj->findAll(20, $inputData);

        return view('advance::advance.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['title'] = 'Advance';
        $currentUserModel = Auth::user();
        // dd($currentUserModel->user_type);
        if ($currentUserModel->user_type == 'supervisor' || $currentUserModel->user_type == 'employee') {
            $data['organizationId'] = optional($currentUserModel->userEmployer)->organization_id;
            $data['employeeId'] = $currentUserModel->emp_id;
            $data['approvalStatus'] = '1';
        }
        $data['approvalstatusList'] = Advance::approvalStatusList();
        $data['employeeList'] = $this->employeeObj->getList();
        $data['organizationList'] = $this->organizationObj->getList();
        $data['monthList'] =  Attendance::MONTHS;
        $data['nepaliMonthList'] =  Attendance::NEPALI_MONTHS;


        return view('advance::advance.create', $data);
    }
    
    public function clone(Request $request){
        $data = $request->all();
        return response()->json([
            'data' => view('advance::advance.partial.append')->render(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData = $request->all();
        if (setting('calendar_type') == 'BS'){
            if(!is_null($inputData['from_date'])){
                $inputData['from_date'] = date_converter()->nep_to_eng_convert($inputData['from_date']);
            }
        }

        try {
            $advance_data = $this->advanceObj->create($inputData);
            $this->advanceObj->sendMailNotification($advance_data);
            $logData=[
                'title'=>'New advance created',
                'action_id'=>@$advance_data->id,
                'action_model'=>get_class($advance_data),
                'route'=>route('advance.view',$advance_data->id)
            ];
            $this->setActivityLog($logData);
            toastr()->success('Data Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        return redirect(route('advance.index'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data['title'] = 'Advance';
        $data['advanceModel'] = $this->advanceObj->findOne($id);

        return view('advance::advance.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $currentUserModel = Auth::user();
        if ($currentUserModel->user_type == 'supervisor' || $currentUserModel->user_type == 'employee') {
            $data['organizationId'] = optional($currentUserModel->userEmployer)->organization_id;
            $data['employeeId'] = $currentUserModel->emp_id;
            $data['approvalStatus'] = '1';
        }
        $data['title'] = 'Advance';
        $data['advanceModel'] = $this->advanceObj->findOne($id);
        if (setting('calendar_type') == 'BS'){
            if(!is_null($data['advanceModel']['from_date'])){
                $data['advanceModel']['from_date'] = date_converter()->eng_to_nep_convert($data['advanceModel']['from_date']);
            }
        }
        $data['organizationList'] = $this->organizationObj->getList();
        $data['payrollSetting'] = PayrollCalenderTypeSetting::where('organization_id',$data['advanceModel']->organization_id)->first();
        $data['calendarType'] = $data['payrollSetting']->calendar_type;
        $data['employeeList'] = $this->employeeObj->getList();
        $data['monthList'] =  Attendance::MONTHS;

        return view('advance::advance.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $inputData = $request->all();
        if (setting('calendar_type') == 'BS'){
            if(!is_null($inputData['from_date'])){
                $inputData['from_date'] = date_converter()->nep_to_eng_convert($inputData['from_date']);
            }
        }
        try {
            $this->advanceObj->update($id, $inputData);
            $advance_data=$this->advanceObj->findOne($id);
            $logData=[
                'title'=>'Advance updated',
                'action_id'=>@$advance_data->id,
                'action_model'=>get_class($advance_data),
                'route'=>route('advance.view',$advance_data->id)
            ];
            $this->setActivityLog($logData);
            toastr()->success('Data Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        return redirect(route('advance.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->advanceObj->delete($id);
             $logData=[
                'title'=>'Advance deleted',
                'action_id'=>null,
                'action_model'=>null,
                'route'=>route('advance.index')
            ];
            $this->setActivityLog($logData);
            toastr()->success('Data Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }
    public function updateStatus(request $request){
        $inputData = $request->all();
        // dd($inputData);
        try {
            switch ($inputData['approval_status']) {
                case '2':
                    $inputData['forward_by'] = auth()->user()->id;
                    $inputData['forward_message'] = $inputData['status_message'];
                    break;
                case '3':
                    $inputData['accept_by'] = auth()->user()->id;
                    break;
                case '4':
                    $inputData['reject_by'] = auth()->user()->id;
                    $inputData['reject_message'] = $inputData['status_message'];
                    break;
                default:
                    // do nothing
                    break;
            }
            $result = $this->advanceObj->updateStatus($inputData['id'], $inputData);
            $model = $this->advanceObj->findOne($inputData['id']);
            $this->advanceObj->sendMailNotification($model);
            // if ($result) {
            //     Leave::where('parent_id', $inputData['id'])->update(['status' => $inputData['status']]);
            //     $this->leave->sendMailNotification($model);
            //     if ($inputData['status'] == '4') {
            //         $inputData['employee_id'] = $model->employee_id;
            //         $inputData['leave_type_id'] = $model->leave_type_id;
            //         $inputData['numberOfDays'] = $model->leave_kind == '1' ? 0.5 : (count($model->childs) + 1);
            //         EmployeeLeave::updateRemainingLeave($inputData, 'ADD');
            //     }
            // }
             $logData=[
                'title'=>'Advance status updated',
                'action_id'=>@$model->id,
                'action_model'=>get_class($model),
                'route'=>route('advance.view',$model->id)
            ];
            $this->setActivityLog($logData);
            toastr()->success('Approval Status Updated Successfully');
            // $model['updated_status'] = $inputData['status'];
            // $current_user_id = Auth::user()->id;
            // $this->sendMailNotification($current_user_id, $model['employee_id'], $model, 'updateLeaveStatus');
        } catch (\Throwable $e) {
            // dd($e);
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    public function printPreview(Request $request,$id){
        $data['setting'] = $this->settingObj->getData();
        $data['advanceModel'] = $this->advanceObj->findOne($id);
        return view('advance::advance.print-preview',$data);
    }
}
