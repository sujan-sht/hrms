<?php

namespace App\Modules\Leave\Http\Controllers;

use PDF;
use Exception;
use Carbon\Carbon;
use App\Exports\LeaveReport;
use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\User\Entities\User;
use App\Modules\Leave\Jobs\LeaveJob;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\Leave\Entities\Leave;
use Illuminate\Support\Facades\Config;
use function PHPUnit\Framework\isEmpty;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Payroll\Entities\IncomeSetup;
use App\Modules\Holiday\Entities\HolidayDetail;
use App\Modules\Leave\Entities\LeaveAttachment;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\User\Repositories\UserInterface;
use App\Modules\Employee\Entities\EmployeeDayOff;
use App\Modules\Leave\Exports\LeaveHistoryReport;
use App\Modules\Leave\Http\Requests\LeaveRequest;
use App\Modules\Leave\Entities\LeaveEncashmentLog;
use App\Modules\Leave\Repositories\LeaveInterface;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Holiday\Repositories\HolidayInterface;
use App\Modules\Leave\Repositories\LeaveTypeInterface;
use App\Modules\Setting\Repositories\SettingInterface;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Setting\Entities\GrossSalarySetupSetting;
use App\Modules\Employee\Entities\EmployeeSubstituteLeave;
use App\Modules\Leave\Entities\LeaveEncashmentLogActivity;
use App\Modules\Payroll\Entities\IncomeReferenceSetupDetail;
use App\Modules\Payroll\Repositories\EmployeeSetupRepository;
use App\Modules\Notification\Repositories\NotificationInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\BulkUpload\Service\Import\PreviousLeaveDetailImport;

use App\Modules\LeaveYearSetup\Repositories\LeaveYearSetupInterface;
use App\Modules\Employee\Repositories\EmployeeSubstituteLeaveInterface;

class LeaveController extends Controller
{
    private $leave;
    private $organization;
    private $employeeObj;
    private $leaveTypeObj;
    private $user;
    private $notification;
    private $holidayObj;
    private $branch;
    protected $setting;
    protected $leaveYearSetup;
    protected $employeeSubstituteLeave;


    /**
     * LeaveController constructor.
     * @param LeaveInterface $leave
     * @param DropdownInterface $dropdown
     * @param EmploymentInterface $employment
     * @param FieldInterface $field
     */
    public function __construct(
        LeaveInterface $leave,
        OrganizationInterface $organization,
        EmployeeInterface $employeeObj,
        LeaveTypeInterface $leaveTypeObj,
        UserInterface $user,
        NotificationInterface $notification,
        HolidayInterface $holidayObj,
        BranchInterface $branch,
        SettingInterface $setting,
        LeaveYearSetupInterface $leaveYearSetup,
        EmployeeSubstituteLeaveInterface $employeeSubstituteLeave
    ) {
        $this->leave = $leave;
        $this->organization = $organization;
        $this->employeeObj = $employeeObj;
        $this->leaveTypeObj = $leaveTypeObj;
        $this->user = $user;
        $this->notification = $notification;
        $this->holidayObj = $holidayObj;
        $this->branch = $branch;
        $this->setting = $setting;
        $this->leaveYearSetup = $leaveYearSetup;
        $this->employeeSubstituteLeave = $employeeSubstituteLeave;
    }

    public function getCurrentUserDetail()
    {
        return User::where('id', Auth::user()->id)->first();
    }

    /**
     *
     */
    public function index(Request $request)
    {
        // return view('admin::mail.leave_test');

        $filter = $request->all();
        $filter['isParent'] = true;
        $filter['authUser'] = auth()->user();

        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        // dd($filter);
        $data['leaveYearList'] = $this->leaveYearSetup->getLeaveYearList();
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        $data['leaveTypeList'] = $this->leaveTypeObj->getList();
        $data['leaveKindList'] = Leave::leaveKindList();
        $data['statusList'] = Leave::statusList();

        $userInfo = Auth::user();
        $data['user_type'] = $user_type = $userInfo->user_type;
        $id = (($user_type == 'super_admin' || $user_type == 'hr')) ? '' : $userInfo->emp_id;

        // $filter['leave_year_id'] = getCurrentLeaveYearId();
        $data['leaveModels'] = $this->leave->findAll(20, $filter, $sort);
        $data['emp_id'] = $id;
        $data['user_id'] = $userInfo->id;

        return view('leave::leave.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $currentUserModel = $this->getCurrentUserDetail();
        if ($currentUserModel->user_type == 'supervisor' || $currentUserModel->user_type == 'employee') {
            $data['organizationId'] = optional($currentUserModel->userEmployer)->organization_id;
            $data['employeeId'] = $currentUserModel->emp_id;
            $data['status'] = '1';
        }

        $currentLeaveyear = LeaveYearSetup::currentLeaveYear();
        if (isset($currentLeaveyear) && !is_null($currentLeaveyear)) {
            $data['currentLeaveyear'] = $currentLeaveyear;
        } else {
            toastr()->error('Please set Active Leave Year first !!!');
            return redirect(route('leaveYearSetup.index'));
        }
        $data['isEdit'] = false;
        $data['currentUserModel'] = $currentUserModel;
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        $data['employeeAlternativeList'] = $this->employeeObj->getOtherEmployeeList();
        $data['leaveTypeList'] = [];
        $data['leaveKindList'] = Leave::leaveKindList();
        $data['halfTypeList'] = Leave::halfTypeList();
        $data['statusList'] = Leave::statusList();
        unset($data['statusList'][5]);
        return view('leave::leave.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(LeaveRequest $request)
    {
        $inputData = $request->all();
        $setting = $this->setting->getdata();
        $employeeModel = $this->employeeObj->find($inputData['employee_id']);
        //Nepali Date Conversion Start
        $dateConverterObject = new DateConverter();
        $start_date = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($request->start_date) : $request->start_date;
        $end_date = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($request->end_date) : $request->end_date;

        if (isset($request->substitute_date)) {
            $inputData['substitute_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($request->substitute_date) : $request->substitute_date;
        }
        // $end_date = $request->end_date;

        if ($inputData['leave_kind'] == '4') {
            $start_date = $request->leave_date;
        }
        $inputData['nepali_date'] = $dateConverterObject->eng_to_nep_convert_two_digits($start_date);
        //Nepali Date Conversion Ends

        $inputData['status'] = isset($inputData['status']) ? $inputData['status'] : 1;
        $tempDate = $start_date;
        $existingCount = 0;
        try {
            switch ($inputData['leave_kind']) {
                case '1':
                    $inputData['date'] = $tempDate;
                    $check = $this->leave->checkData($inputData);
                    if ($check) {
                        $existingCount++;
                        toastr()->warning('Some days are rejected due to duplicate entry.');
                    } else {
                        $leave_data = $this->leave->create($inputData);
                        $leave_data['enable_mail'] = $setting->enable_mail;
                        if ($leave_data) {
                            if ($request->has('attachments')) {
                                foreach ($inputData['attachments'] as $attachment) {
                                    $this->uploadAttachment($leave_data->id, $attachment);
                                }
                            }
                        }
                        $inputData['numberOfDays'] = 0.5;
                        EmployeeLeave::updateRemainingLeave($inputData, 'SUB');
                        // $this->leave->sendMailNotification($leave_data);
                        LeaveJob::dispatch($leave_data, auth()->user());
                    }
                    // $this->sendMailNotification($current_user_id, $leave_data['employee_id'], $leave_data, 'create');
                    break;
                case '2':

                    $employeeDayOffs = optional($this->employeeObj->find($inputData['employee_id']))->getEmployeeDayList();

                    $inputData['leave_type_ids'] = [$inputData['leave_type_id']];
                    foreach ($inputData['leave_type_ids'] as $key => $leave_type_id) {
                        $parentId = null;
                        $inputData['leave_type_id'] = $leave_type_id;
                        // $days = $inputData['number_of_days'][$key];
                        $days = DateTimeHelper::DateDiffInDay($start_date, $end_date);
                        $days += 1; // Adjust day for proper calculation
                        if ($days > 0) {
                            for ($i = 1; $i <= $days; $i++) {
                                $inputData['date'] = $tempDate;
                                $inputData['nepali_date'] = date_converter()->eng_to_nep_convert($tempDate);
                                // $holidayModel = HolidayDetail::where('eng_date', '=', $inputData['date'])->first();
                                $holidayModel = HolidayDetail::whereHas('holiday', function ($query) use ($employeeModel, $inputData) {
                                    $query->where('apply_for_all', 11)->orWhere('branch_id', $employeeModel->branch_id);
                                })->where('eng_date', '=', $inputData['date'])->first();
                                // dd($holidayModel);

                                $check = $this->leave->checkData($inputData);
                                if ($check) {
                                    $existingCount++;
                                } elseif (in_array(Carbon::parse($inputData['date'])->format('l'), $employeeDayOffs) || $holidayModel) {
                                    $leaveType = $this->leaveTypeObj->findOne($inputData['leave_type_id']);
                                    if ($leaveType->sandwitch_rule_status == '11') {
                                        $finalData = $inputData;
                                        $finalData["reason"] = "sandwich rule";
                                        // $finalData['leave_type_id']=null;
                                        $finalData['leave_kind'] = 2;
                                        $finalData['parent_id'] = $parentId;
                                        $leave_data = $this->leave->create($finalData);
                                        $leave_data['enable_mail'] = $setting->enable_mail;
                                        if ($parentId == null) {
                                            $parentId = $leave_data->id;
                                        }
                                        if ($leave_data) {
                                            $inputData['numberOfDays'] = 1;
                                            EmployeeLeave::updateRemainingLeave($inputData, 'SUB');
                                        }
                                    }
                                } else {
                                    $inputData['parent_id'] = $parentId;
                                    $leave_data = $this->leave->create($inputData);
                                    $leave_data['enable_mail'] = $setting->enable_mail;
                                    if ($parentId == null) {
                                        $parentId = $leave_data->id;
                                    }
                                    if ($leave_data) {
                                        $inputData['numberOfDays'] = 1;
                                        EmployeeLeave::updateRemainingLeave($inputData, 'SUB');
                                    }
                                }
                                $tempDate = date('Y-m-d', strtotime('+1 day', strtotime($tempDate)));
                                if ($i == 1) {
                                    $initialLeaveModel = $leave_data;
                                }
                            }

                            // send notification with email
                            // $this->leave->sendMailNotification($initialLeaveModel);
                            LeaveJob::dispatch($initialLeaveModel, auth()->user());


                            // save attachments
                            if ($parentId) {
                                if ($request->has('attachments')) {
                                    foreach ($inputData['attachments'] as $attachment) {
                                        $this->uploadAttachment($parentId, $attachment);
                                    }
                                }
                            }
                        }
                    }
                    break;
                case '3':
                    $dates = explode(', ', $inputData['dates']);
                    if (count($dates) > 0) {
                        foreach ($dates as $date) {
                            $inputData['date'] = $date;
                            $check = $this->leave->checkData($inputData);
                            if ($check) {
                                $existingCount++;
                                toastr()->warning('Some days are rejected due to duplicate entry.');
                            } else {
                                $leave_data = $this->leave->create($inputData);
                                $leave_data['enable_mail'] = $setting->enable_mail;
                                if ($leave_data) {
                                    if ($request->has('attachments')) {
                                        foreach ($inputData['attachments'] as $attachment) {
                                            $this->uploadAttachment($leave_data->id, $attachment);
                                        }
                                    }
                                }
                                // $this->sendMailNotification($current_user_id, $leave_data['employee_id'], $leave_data, 'create');
                            }
                        }
                        $inputData['numberOfDays'] = count($dates) - $existingCount;
                        EmployeeLeave::updateRemainingLeave($inputData, 'SUB');
                        // $this->leave->sendMailNotification($leave_data);
                        LeaveJob::dispatch($leave_data, auth()->user());
                    }
                    break;
                case '4':
                    $inputData['date'] = $tempDate;
                    $leave_data = $this->leave->create($inputData);
                    $leave_data['enable_mail'] = $setting->enable_mail;
                    $inputData['numberOfDays'] = 1;
                    EmployeeLeave::updateRemainingLeave($inputData, 'SUB');
                    // $this->leave->sendMailNotification($leave_data);
                    LeaveJob::dispatch($leave_data, auth()->user());

                    // $this->sendMailNotification($current_user_id, $leave_data['employee_id'], $leave_data, 'create');
                    break;
                default:
                    # code...
                    break;
            }
            toastr()->success('Leave Created Successfully');
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('leave.index', ['leave_year_id' => getCurrentLeaveYearId()]));
    }

    /**
     *
     */
    public function uploadAttachment($id, $file)
    {
        $fileDetail = LeaveAttachment::saveFile($file);
        $modelData['leave_id'] = $id;
        $modelData['title'] = $fileDetail['filename'];
        $modelData['extension'] = $fileDetail['extension'];
        $modelData['size'] = $fileDetail['size'];
        LeaveAttachment::create($modelData);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $user_id = auth()->user()->id;
        $leaveModel = $this->leave->findOne($id);
        $statusList = Leave::statusList();

        $emp_leave_flow = $this->leave->getEmployeeApprovalFlow($leaveModel->employee_id);
        // dd($leaveModel->status, $emp_leave_flow->first_approval_user_id, $user_id);
        if (auth()->user()->emp_id == $leaveModel->employee_id) {
            $statusList = [
                '1' => 'Pending'
            ];
        } else {
            if (!empty($emp_leave_flow)) {
                if (!empty($emp_leave_flow->first_approval_user_id) && $emp_leave_flow->first_approval_user_id > 0) {
                    if ($leaveModel->status == 1 && $emp_leave_flow->first_approval_user_id == $user_id) {
                        $statusList = [
                            '1' => 'Pending',
                            '2' => 'Recommended',
                            '4' => 'Rejected'
                        ];
                    } elseif ($leaveModel->status == 2 && $emp_leave_flow->first_approval_user_id == $user_id) {
                        $statusList = [
                            '2' => 'Recommended',
                        ];
                    } elseif ($leaveModel->status == 4 && $emp_leave_flow->first_approval_user_id == $user_id) {
                        $statusList = [
                            '4' => 'Rejected'
                        ];
                    } elseif ($leaveModel->status == 2 && $emp_leave_flow->last_approval_user_id == $user_id) {
                        $statusList = [
                            '2' => 'Recommended',
                            '3' => 'Approved',
                            '4' => 'Rejected'
                        ];
                    } elseif ($leaveModel->status == 1 && $emp_leave_flow->last_approval_user_id == $user_id) {
                        $statusList = [
                            '1' => 'Pending'
                        ];
                    } elseif ($leaveModel->status == 3 && $emp_leave_flow->first_approval_user_id == $user_id) {
                        $statusList = [];
                    } elseif ($leaveModel->status != 1 && $leaveModel->status != 2 && $emp_leave_flow->last_approval_user_id == $user_id) {
                        $statusList = [];
                    }
                } else {
                    if ($emp_leave_flow->last_approval_user_id == $user_id) {
                        $statusList = [
                            '1' => 'Pending',
                            '3' => 'Approved',
                            '4' => 'Rejected'
                        ];
                    }
                }
            }
        }
        if (auth()->user()->user_type == 'super_admin') {
            $statusList = Leave::statusList();
        }

        $data['statusList'] = $statusList;
        $data['leaveModel'] = $leaveModel;
        return view('leave::leave.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        return redirect()->back();

        // $data['isEdit'] = true;
        // $data['leaveModel'] = $this->leave->findOne($id);
        // $data['organizationList'] = $this->organization->getList();
        // $data['leaveList'] = Leave::leaveList();

        // return view('leave::leave.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $model = $this->leave->findOne($id);

        try {
            switch ($data['status']) {

                case '2':
                    $data['forward_by'] = auth()->user()->id;
                    $data['forward_message'] = $data['status_message'];
                    $data['forwarded_date'] = Carbon::now();
                    break;
                case '3':
                    $data['accept_by'] = auth()->user()->id;
                    $data['approved_date'] = Carbon::now();
                    $data['accept_message'] = $data['status_message'];
                    break;
                case '4':
                    $data['reject_by'] = auth()->user()->id;
                    $data['reject_message'] = $data['status_message'];
                    $data['rejected_date'] = Carbon::now();

                    break;
                case '5':
                    $data['cancelled_by'] = auth()->user()->id;
                    $data['cancelled_date'] = Carbon::now();

                    break;
                default:
                    // do nothing
                    break;
            }
            $result = $this->leave->update($id, $data);
            if ($result) {
                Leave::where('parent_id', $id)->update(['status' => $data['status']]);
                if ($data['status'] == '4') {
                    $inputData['employee_id'] = $model->employee_id;
                    $inputData['leave_type_id'] = $model->leave_type_id;
                    $inputData['numberOfDays'] = $model->leave_kind == '1' ? 0.5 : (count($model->childs) + 1);
                    EmployeeLeave::updateRemainingLeave($inputData, 'ADD');
                }
            }

            toastr()->success('Leave Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        if (auth()->user()->user_type == 'supervisor') {
            return redirect(route('leave.showTeamleaves'));
        } else {
            return redirect(route('leave.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->leave->delete($id);

            toastr()->success('Leave Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    /**
     *
     */
    public function getList(Request $request)
    {
        $inputData = $request->all();

        if (Auth::user()->user_type == 'employee') {
            $inputData['show_on_employee'] = "11";
        }

        if ($inputData['leave_kind'] == '1') {
            $inputData['half_leave_status'] = "11";
        }

        $data['employeeLeaveList'] = EmployeeLeave::getList($inputData);

        return view('leave::leave.partial.bulk-form', $data);
    }

    /**
     *
     */
    public function getRemainingList(Request $request)
    {
        $inputData = $request->all();

        if (in_array(Auth::user()->user_type, ['employee', 'supervisor'])) {
            $inputData['show_on_employee'] = "11";
        }
        $params = [];
        if (in_array(Auth::user()->user_type, ['employee', 'supervisor'])) {
            $params['show_on_employee'] = "11";
        }

        if ($inputData['leave_kind'] == '1') {
            $params['half_leave_status'] = "11";
        }
        $data['employeeLeaveList'] = $this->employeeObj->employeeLeaveDetails($inputData['employee_id'],null,$params);

        $leaveTypeList = [];
        foreach ($data['employeeLeaveList'] as $employeeLeave) {
            // $leaveType = $employeeLeave->leaveTypeModel;

            // if (!$leaveType) continue;

            // // Only apply filter if code is 'sublv' and employee_ids is set
            // if ($leaveType->code === 'sublv' && !empty($leaveType->employee_ids)) {
            //     $employeeIds = is_array($leaveType->employee_ids)
            //         ? $leaveType->employee_ids
            //         : json_decode($leaveType->employee_ids, true);

            //     // If employee_id not in list, skip
            //     if (!in_array($employeeLeave->employee_id, $employeeIds)) {
            //         continue;
            //     }
            // }

            $leaveTypeList[] = [
                'key' => $employeeLeave['leave_type_id'],
                'value' => $employeeLeave['leave_type'],
            ];
        }

        return [
            'leaveTypeList' => $leaveTypeList,
            'view' => view('leave::leave.partial.remaining-list', $data)->render()
        ];
    }

    public function getRemainingLeave(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $leaveTypeId = $request->input('leave_type');
        $leaveYearId = $request->input('leave_year_id');

        // Fetch the remaining leave for the given employee, leave type, and leave year
        // $remainingLeave = EmployeeLeave::where('employee_id', $employeeId)->where('leave_type_id', $leaveType)->where('leave_year_id', $leaveYearId)->first()->leave_remaining;
        $leaveDetails = $this->employeeObj->employeeLeaveDetails($employeeId,$leaveTypeId);
        if(count($leaveDetails) > 0){
            $remainingLeave = $leaveDetails[0]['leave_remaining'];
        }else{
            $remainingLeave = 0;
        }
        return $remainingLeave;
    }

    public function showTeamleaves(Request $request)
    {
        $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];

        $data['currentUserModel'] = $this->getCurrentUserDetail();
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        $data['leaveTypeList'] = $this->leaveTypeObj->getList();
        $data['leaveKindList'] = Leave::leaveKindList();
        $data['statusList'] = Leave::statusList();
        $data['teamLeaveModels'] = $this->leave->findTeamleaves(20, $filter, $sort);
        $userInfo = Auth::user();
        $data['user_type'] = $user_type = $userInfo->user_type;
        $id = (($user_type == 'super_admin' || $user_type == 'hr')) ? '' : $userInfo->emp_id;
        $data['emp_id'] = $id;
        $data['user_id'] = $userInfo->id;
        // dd($data);
        // dd($data['teamLeaveModels']->toArray());
        return view('leave::team-leave.index', $data);
    }


    /**
     *
     */
    public function updateStatus(Request $request)
    {
        $inputData = $request->all();
        try {
            switch ($inputData['status']) {
                case '2':
                    $inputData['forward_by'] = auth()->user()->id;
                    $inputData['forward_message'] = $inputData['status_message'];
                    $inputData['forwarded_date'] = Carbon::now();
                    break;
                case '3':
                    $inputData['accept_by'] = auth()->user()->id;
                    $inputData['approved_date'] = Carbon::now();

                    break;
                case '4':
                    $inputData['reject_by'] = auth()->user()->id;
                    $inputData['reject_message'] = $inputData['status_message'];
                    $inputData['rejected_date'] = Carbon::now();

                    break;
                case '5':
                    $inputData['cancelled_by'] = auth()->user()->id;
                    $inputData['cancelled_date'] = Carbon::now();

                    break;
                default:
                    // do nothing
                    break;
            }
            $result = $this->leave->update($inputData['id'], $inputData);
            $model = $this->leave->findOne($inputData['id']);
            $model['enable_mail'] = setting('enable_mail');
            if ($result) {
                Leave::where('parent_id', $inputData['id'])->update(['status' => $inputData['status']]);
                if ($inputData['status'] == '4') {
                    $inputData['employee_id'] = $model->employee_id;
                    $inputData['leave_type_id'] = $model->leave_type_id;
                    $inputData['numberOfDays'] = $model->leave_kind == '1' ? 0.5 : (count($model->childs) + 1);
                    EmployeeLeave::updateRemainingLeave($inputData, 'ADD');

                    $leaveTypeModel = $model->leaveTypeModel->where('code', 'SUBLV')->first();
                    if ($leaveTypeModel) {
                        EmployeeSubstituteLeave::where([
                            'date' => $model->substitute_date,
                            'employee_id' => $model->employee_id
                        ])->update(['is_expired' => 10]);
                    }
                }
                // $this->leave->sendMailNotification($model);
                LeaveJob::dispatch($model, auth()->user());
            }
            toastr()->success('Status Updated Successfully');

            // $model['updated_status'] = $inputData['status'];
            // $current_user_id = Auth::user()->id;
            // $this->sendMailNotification($current_user_id, $model['employee_id'], $model, 'updateLeaveStatus');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    public function updateStatusBulk(Request $request)
    {
        $inputData = $request->except('_token');
        $leave_ids = json_decode($inputData['leave_multiple_id'][0], true);
        try {
            if (!empty($leave_ids)) {
                foreach ($leave_ids as $leave_id) {
                    $leave = $this->leave->findOne($leave_id);

                    switch ($inputData['status']) {
                        case '2':
                            $updateData['forward_by'] = auth()->user()->id;
                            // $updateData['forward_message'] = $inputData['status_message'];
                            break;
                        case '3':
                            $updateData['accept_by'] = auth()->user()->id;
                            break;
                        case '4':
                            $updateData['reject_by'] = auth()->user()->id;
                            // $updateData['reject_message'] = $inputData['status_message'];
                            break;
                        default:
                            break;
                    }
                    if ($leave['status'] != $inputData['status']) {
                        $updateData['status'] = $inputData['status'];
                        $result = $this->leave->update($leave_id, $updateData);
                        $model = $this->leave->findOne($leave_id);
                        $model['enable_mail'] = setting('enable_mail');
                        if ($result) {
                            Leave::where('parent_id', $leave_id)->update(['status' => $inputData['status']]);
                            if ($inputData['status'] == '4') {
                                $params['employee_id'] = $model->employee_id;
                                $params['leave_type_id'] = $model->leave_type_id;
                                $params['numberOfDays'] = $model->leave_kind == '1' ? 0.5 : (count($model->childs) + 1);
                                EmployeeLeave::updateRemainingLeave($params, 'ADD');

                                $leaveTypeModel = $model->leaveTypeModel->where('code', 'SUBLV')->first();
                                if ($leaveTypeModel) {
                                    EmployeeSubstituteLeave::where([
                                        'date' => $model->substitute_date,
                                        'employee_id' => $model->employee_id
                                    ])->update(['is_expired' => 10]);
                                }
                            }
                        }
                        // $this->leave->sendMailNotification($model);
                        LeaveJob::dispatch($model, auth()->user());
                    }
                }
            }

            toastr()->success('Status Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    /**
     *
     */
    public function report(Request $request)
    {
        $inputData = $request->all();

        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        $data['leaveKindList'] = Leave::leaveKindList();
        $data['statusList'] = Leave::statusList();

        if (isset($inputData['organization_id'])) {
            $filter['organization_id'] = $inputData['organization_id'];
            $filter['leave_year_id'] = getCurrentLeaveYearId();
            $filter['isParent'] = false;
            $data['models'] = $this->leave->findAll(null, $inputData)->groupBy(['employee_id', 'leave_type_id']);
            $data['leaveTypeList'] = $this->leaveTypeObj->getList($filter);
        } else {
            if ($data['organizationList']->count() > 0) {
                if (setting('calendar_type') == "AD") {
                    $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
                    $endOfMonth = Carbon::now()->toDateString();
                    $dateRange = $startOfMonth . ' - ' . $endOfMonth;
                    $data['date_range'] = $filter['date_range'] = $dateRange;
                } else {
                    $nepDateArray = date_converter()->eng_to_nep(date('Y'), date('m'), date('d'));

                    $nepDate = sprintf('%04d-%02d-%02d', $nepDateArray['year'], $nepDateArray['month'], 01);

                    $data['from_nep_date'] = $filter['from_nep_date'] = $nepDate;
                    $data['to_nep_date'] = $filter['to_nep_date'] = date_converter()->eng_to_nep_convert(Carbon::now()->toDateString());
                }
                $filter['leave_year_id'] = getCurrentLeaveYearId();
                $filter['isParent'] = false;
                if (auth()->user()->user_type != 'super_admin' && auth()->user()->user_type != 'hr') {
                    $data['organization_id'] = $filter['organization_id'] = optional(auth()->user()->userEmployer)->organization_id;
                } else {
                    $data['organization_id'] = $filter['organization_id'] = $data['organizationList']->keys()->first();
                }
                $data['models'] = $this->leave->findAll(null, $filter)->groupBy(['employee_id', 'leave_type_id']);
                $data['leaveTypeList'] = $this->leaveTypeObj->getList($filter);
            }
        }
        return view('leave::leave.report', $data);
    }


    /**
     * Cron job function
     * Need to run at the start of the new month
     */
    public function checkForNewMonth()
    {
        $message = 'run successful.';

        try {
            $leaveYearModel = LeaveYearSetup::currentLeaveYear();
            $activeLeaveYearId = $leaveYearModel->id;

            $currentDate = date('Y-m-d');
            $dateObject = new DateConverter();
            $currentNepaliDate = $dateObject->eng_to_nep_convert($currentDate);
            $currentNepaliDateArray = explode('-', $currentNepaliDate);
            // if ($currentNepaliDateArray[2] == '1') {
            $employeeModels = $this->employeeObj->findAll(null, []);
            if ($employeeModels->total() > 0) {
                foreach ($employeeModels as $employeeModel) {
                    $params['leave_year_id'] = $activeLeaveYearId;
                    $params['employee_id'] = $employeeModel->id;
                    $employeeLeaveModels = EmployeeLeave::getList($params);
                    if ($employeeLeaveModels->count() > 0) {
                        foreach ($employeeLeaveModels as $employeeLeaveModel) {
                            $leaveTypeModel = $this->leaveTypeObj->findOne($employeeLeaveModel->leave_type_id);
                            if ($leaveTypeModel->prorata_status == '11') {
                                $employeeLeaveModel->leave_earned += round(($leaveTypeModel->number_of_days / 12), 2);
                                $employeeLeaveModel->leave_remaining += round(($leaveTypeModel->number_of_days / 12), 2);
                                $employeeLeaveModel->save();
                                $message = 'Successfully run on start of the month.';
                            }
                        }
                    }
                }
            }
            // } else {
            //     $message = "Oops! New month hasn't started yet.";
            // }
        } catch (\Throwable $th) {
            $message = "Oops! Something went wrong.";
        }

        return $message;
    }

    public function checkDayOff(Request $request)
    {
        $count_days = $request->sum_day;
        $start_date = Carbon::parse($request->start_date);

        $check = [];
        $exclude_days = ['Sunday', 'Saturday'];
        $j = 1;
        for ($i = 0; $i < $count_days; $i++) {
            $temp_date = $start_date->addDay($j);
            if (in_array($temp_date->format('l'), $exclude_days)) {
                $check[] = $temp_date->toDateString();
            }
        }
        return implode(", ", $check);
    }

    /**
     * Ajax function
     * Pre Process data
     */
    public function PreProcessData(Request $request)
    {
        $inputData = $request->all();
        $leaveTypeId = $inputData['params']['leaveType'];
        $startDate = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($inputData['params']['startDate']) : $inputData['params']['startDate'];
        $employeeId = $inputData['params']['employeeId'];
        $employeeModel = $this->employeeObj->find($employeeId);
        $maxDays = $inputData['params']['maxDays'];
        $leaveTypeModel = $this->leaveTypeObj->findOne($leaveTypeId);
        if ($maxDays <= 0) {
            $endDate = null;
        } else {

            if (isset($leaveTypeModel->max_per_day_leave)) {
                $newMaxDays = min($maxDays, $leaveTypeModel->max_per_day_leave);
                $adjustDays = (int)$newMaxDays - 1;
                $endDate = date('Y-m-d', strtotime('+ ' . $adjustDays . ' days', strtotime($startDate)));
            } else {
                $adjustDays = ((int)$maxDays < 1 && (int)$maxDays >= 0.5) ? 1 : (int)$maxDays - 1;
                // $adjustDays = (int)$maxDays - 1;
                $endDate = date('Y-m-d', strtotime('+ ' . $adjustDays . ' days', strtotime($startDate)));
            }

            $countHoliday = 0;
            if (($leaveTypeModel->sandwitch_rule_status == "10")) {
                // $holidayModels = HolidayDetail::where('eng_date', '>=', $startDate)->where('eng_date', '<=', $endDate)->orderBy('eng_date', 'ASC')->get();
                $holidayModels = HolidayDetail::whereHas('holiday', function ($query) use ($employeeModel, $inputData) {
                    $query->where('apply_for_all', 11)->orWhere('branch_id', $employeeModel->branch_id);
                })->where('eng_date', '>=', $startDate)->where('eng_date', '<=', $endDate)->orderBy('eng_date', 'ASC')->get();
                if (count($holidayModels) > 0) {
                    foreach ($holidayModels as $holidayModel) {
                        $countHoliday++;
                    }
                }

                $countDayOff = 0;
                $employeeDayOffModels = EmployeeDayOff::where('employee_id', $employeeId)->get();
                if (count($employeeDayOffModels) > 0) {
                    foreach ($employeeDayOffModels as $employeeDayOffModel) {
                        $excludeDays[] = $employeeDayOffModel->day_off;
                    }
                    $j = 1;
                    $tempDate = Carbon::parse($startDate);
                    $numberOfDays = 0;
                    $numberOfDays = DateTimeHelper::DateDiffInDay($startDate, $endDate);
                    $numberOfDays += 1; // adjust data from proper calculation
                    $response['numberOfDays'] = $numberOfDays;
                    for ($i = 0; $i < $numberOfDays; $i++) {
                        if (in_array($tempDate->format('l'), $excludeDays)) {
                            $countDayOff++;
                        }
                        $tempDate = $tempDate->addDay($j);
                    }
                }
                $totalSandwichDays = $countHoliday + $countDayOff;
                $endDate = date('Y-m-d', strtotime('+ ' . $totalSandwichDays . ' days', strtotime($endDate)));
            }

            if ($leaveTypeModel->code == "SUBLV") {
                $response['numberOfDays'] = 1;
                $response['endDate'] = setting('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert($startDate) : $startDate;
                return  json_encode($response);
            }
        }
        // $response['endDate'] = $endDate;
        $response['endDate'] = setting('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert($endDate) : $endDate;

        return  json_encode($response);
    }

    /**
     * Ajax function
     * Post Process data
     */
    public function PostProcessData(Request $request)
    {
        $inputData = $request->all();
        $employeeId = $inputData['params']['employeeId'];
        $employeeModel = $this->employeeObj->find($employeeId);
        $leaveTypeId = $inputData['params']['leaveType'];
        $leaveKind = $inputData['params']['leaveKind'];
        $maxDays = $inputData['params']['maxDays'];
        $endDate = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($inputData['params']['endDate']) : $inputData['params']['endDate'];
        $startDate = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($inputData['params']['startDate']) : $inputData['params']['startDate'];

        $numberOfDays = 0;

        if ($leaveKind == 1 && $maxDays >= 0.5) {
            $numberOfDays = 0.5;
        } elseif ($leaveKind == 2 && $maxDays >= 1) {
            $numberOfDays = DateTimeHelper::DateDiffInDay($startDate, $endDate);
            $numberOfDays += 1; // adjust data from proper calculation
        }
        $response['numberOfDays'] = $numberOfDays;

        $leaveTypeModel = $this->leaveTypeObj->findOne($leaveTypeId);
        $response['restrictSave'] = 'false';

        $countDayOff = 0;
        $offDates = [];
        $excludeDays = [];
        $calStartDate = Carbon::parse($startDate);
        $employeeDayOffModels = EmployeeDayOff::where('employee_id', $employeeId)->get();
        if (count($employeeDayOffModels) > 0) {
            foreach ($employeeDayOffModels as $employeeDayOffModel) {
                $excludeDays[] = $employeeDayOffModel->day_off;
            }
            $j = 1;
            $tempDate = $calStartDate;
            for ($i = 0; $i < $numberOfDays; $i++) {
                if (in_array($tempDate->format('l'), $excludeDays)) {
                    $countDayOff++;
                    $tempDateString = $tempDate->toDateString();
                    $offDates[] = setting('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert($tempDateString) : $tempDateString;
                }
                $tempDate = $calStartDate->addDay($j);
            }
            $numberOfDays -= $countDayOff;
            $data['dayOffs'] = implode(', ', $offDates);
        }

        $countHoliday = 0;
        $holidayDays = [];
        // $holidayModels = HolidayDetail::where('eng_date', '>=', $startDate)->where('eng_date', '<=', $endDate)->orderBy('eng_date', 'ASC')->get();
        $holidayModels = HolidayDetail::whereHas('holiday', function ($query) use ($employeeModel) {
            $query->where('apply_for_all', 11)->orWhere('branch_id', $employeeModel->branch_id);
        })->where('eng_date', '>=', $startDate)->where('eng_date', '<=', $endDate)->orderBy('eng_date', 'ASC')->get();
        if (count($holidayModels) > 0) {
            foreach ($holidayModels as $holidayModel) {
                $countHoliday++;
                $holidayDays[] = setting('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert($holidayModel->eng_date) : $holidayModel->eng_date;
            }
            $numberOfDays -= $countHoliday;
            $data['holidays'] = implode(', ', $holidayDays);
        }

        $leaveDays = [];

        $LeaveModels = Leave::where('employee_id', $employeeId)->where('date', '>=', $startDate)->where('date', '<=', $endDate)->whereNotIn('status', [4, 5])->orderBy('date', 'ASC')->get();
        if (count($LeaveModels) > 0) {
            foreach ($LeaveModels as $LeaveModel) {
                $leaveDays[] = setting('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert($LeaveModel->date) : $LeaveModel->date;
            }
            $data['previousLeaves'] = implode(', ', $leaveDays);
            $response['restrictSave'] = "true";
        }

        if (isset($leaveTypeModel->pre_inform_days)) {
            $today = date('Y-m-d');
            $requiredRequestDate = date('Y-m-d', strtotime('+' . $leaveTypeModel->pre_inform_days . ' Days', strtotime($today)));
            if ($startDate >= $requiredRequestDate) {
                // do nothing
            } else {
                $data['preInformMessage'] = "You have to request before " . $leaveTypeModel->pre_inform_days . " days for this leave type";
                $response['restrictSave'] = "true";
            }
        }

        if (isset($leaveTypeModel->max_per_day_leave)) {
            if ($numberOfDays > $leaveTypeModel->max_per_day_leave) {
                $data['maxLeaveMessage'] = "Maximum number of days per request for this leave type is " . $leaveTypeModel->max_per_day_leave . " Days";
                $response['restrictSave'] = "true";
            }
        }
        // if ($leaveTypeModel->sandwitch_rule_status == '11' && !isEmpty($data['dayOffs'])) {
        //     $data['sandwitchMessage'] = 'Since this leave type has a sandwich rule, your leave will also be created on ' . $data['dayOffs'];
        // }

        if ($leaveTypeModel->sandwitch_rule_status == '11') {
            $numberOfDays += ($countHoliday + $countDayOff);
            if ($data['dayOffs'] || count($holidayDays) > 0) {
                $data['sandwitchMessage'] = 'Since this leave type has a sandwich rule, your leave will also be created on ' . $data['dayOffs'] . ',' . implode(', ', $holidayDays);
            }
        }
        // elseif ($leaveTypeModel->sandwitch_rule_status != '11') {
        //     $numberOfDays -= $countHoliday;
        //     $numberOfDays -= $countDayOff;
        // }



        if ($numberOfDays > 0) {
            if ($response['restrictSave'] == 'false') {
                $data['finalMessage'] = "The total number of days you are applying is " . $numberOfDays;
            }
        } else {
            $response['restrictSave'] = "true";
        }

        $response['noticeList'] = view('leave::leave.partial.notice-list', $data)->render();

        return  json_encode($response);
    }

    public function exportLeaveReport(Request $request)
    {
        $inputData = $request->all();
        $inputData['isParent'] = false;
        $filter['organization_id'] = $inputData['organization_id'] ?? auth()->user()->organization_id;
        $filter['leave_year_id'] = getCurrentLeaveYearId();
        $filter['isParent'] = false;
        $data['models'] = $this->leave->findAll(null, $inputData)->groupBy(['employee_id', 'leave_type_id']);
        $data['leaveTypeList'] = $this->leaveTypeObj->getList($filter);

        return Excel::download(new LeaveReport($data), 'leave-report.xlsx');
        toastr('Please Filter first to download Excel Report', 'warning');
        return back();
    }


    public function teamRequestCreate()
    {
        $currentUserModel = $this->getCurrentUserDetail();
        if ($currentUserModel->user_type == 'employee') {
            $data['organizationId'] = optional($currentUserModel->userEmployer)->organization_id;
            $data['employeeId'] = $currentUserModel->emp_id;
            $data['status'] = '1';
        }

        $currentLeaveyear = LeaveYearSetup::currentLeaveYear();
        if (isset($currentLeaveyear) && !is_null($currentLeaveyear)) {
            $data['currentLeaveyear'] = $currentLeaveyear;
        } else {
            toastr()->error('Please set Active Leave Year first !!!');
            return redirect(route('leaveYearSetup.index'));
        }
        $data['isEdit'] = false;
        $data['currentUserModel'] = $currentUserModel;
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        $data['employeeAlternativeList'] = $this->employeeObj->getOtherEmployeeList();
        $data['leaveTypeList'] = [];
        $data['leaveKindList'] = Leave::leaveKindList();
        $data['halfTypeList'] = Leave::halfTypeList();
        $data['statusList'] = Leave::statusList();
        unset($data['statusList'][5]);
        return view('leave::team-leave.create', $data);
    }
    public function teamRequestStore(Request $request)
    {
        $inputData = $request->all();
        $inputData['organization_id'] = Employee::find($inputData['employee_id'])->organization_id;
        $setting = $this->setting->getdata();
        $employeeModel = $this->employeeObj->find($inputData['employee_id']);
        //Nepali Date Conversion Start
        $dateConverterObject = new DateConverter();
        $start_date = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($request->start_date) : $request->start_date;
        $end_date = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($request->end_date) : $request->end_date;

        // $end_date = $request->end_date;

        if ($inputData['leave_kind'] == '4') {
            $start_date = $request->leave_date;
        }
        $inputData['nepali_date'] = $dateConverterObject->eng_to_nep_convert_two_digits($start_date);
        //Nepali Date Conversion Ends

        $inputData['status'] = isset($inputData['status']) ? $inputData['status'] : 1;
        $tempDate = $start_date;
        $existingCount = 0;
        try {
            switch ($inputData['leave_kind']) {
                case '1':
                    $inputData['date'] = $tempDate;
                    $check = $this->leave->checkData($inputData);
                    if ($check) {
                        $existingCount++;
                        toastr()->warning('Some days are rejected due to duplicate entry.');
                    } else {
                        $leave_data = $this->leave->create($inputData);
                        $leave_data['enable_mail'] = $setting->enable_mail;
                        if ($leave_data) {
                            if ($request->has('attachments')) {
                                foreach ($inputData['attachments'] as $attachment) {
                                    $this->uploadAttachment($leave_data->id, $attachment);
                                }
                            }
                        }
                        $inputData['numberOfDays'] = 0.5;
                        EmployeeLeave::updateRemainingLeave($inputData, 'SUB');
                        // $this->leave->sendMailNotification($leave_data);
                        LeaveJob::dispatch($leave_data, auth()->user());
                    }
                    // $this->sendMailNotification($current_user_id, $leave_data['employee_id'], $leave_data, 'create');
                    break;
                case '2':

                    $employeeDayOffs = optional($this->employeeObj->find($inputData['employee_id']))->getEmployeeDayList();

                    $inputData['leave_type_ids'] = [$inputData['leave_type_id']];
                    foreach ($inputData['leave_type_ids'] as $key => $leave_type_id) {
                        $parentId = null;
                        $inputData['leave_type_id'] = $leave_type_id;
                        // $days = $inputData['number_of_days'][$key];
                        $days = DateTimeHelper::DateDiffInDay($start_date, $end_date);
                        $days += 1; // Adjust day for proper calculation
                        if ($days > 0) {
                            for ($i = 1; $i <= $days; $i++) {
                                $inputData['date'] = $tempDate;
                                $inputData['nepali_date'] = date_converter()->eng_to_nep_convert($tempDate);
                                // $holidayModel = HolidayDetail::where('eng_date', '=', $inputData['date'])->first();
                                $holidayModel = HolidayDetail::whereHas('holiday', function ($query) use ($employeeModel, $inputData) {
                                    $query->where('apply_for_all', 11)->orWhere('branch_id', $employeeModel->branch_id);
                                })->where('eng_date', '=', $inputData['date'])->first();

                                $check = $this->leave->checkData($inputData);
                                if ($check) {
                                    $existingCount++;
                                } elseif (in_array(Carbon::parse($inputData['date'])->format('l'), $employeeDayOffs) || $holidayModel) {
                                    $leaveType = $this->leaveTypeObj->findOne($inputData['leave_type_id']);
                                    if ($leaveType->sandwitch_rule_status == '11') {
                                        $finalData = $inputData;
                                        $finalData["reason"] = "sandwich rule";
                                        // $finalData['leave_type_id']=null;
                                        $finalData['leave_kind'] = 2;
                                        $finalData['parent_id'] = $parentId;
                                        $leave_data = $this->leave->create($finalData);
                                        $leave_data['enable_mail'] = $setting->enable_mail;
                                        if ($parentId == null) {
                                            $parentId = $leave_data->id;
                                        }
                                        if ($leave_data) {
                                            $inputData['numberOfDays'] = 1;
                                            EmployeeLeave::updateRemainingLeave($inputData, 'SUB');
                                        }
                                    }
                                } else {
                                    $inputData['parent_id'] = $parentId;
                                    $leave_data = $this->leave->create($inputData);
                                    $leave_data['enable_mail'] = $setting->enable_mail;
                                    if ($parentId == null) {
                                        $parentId = $leave_data->id;
                                    }
                                    if ($leave_data) {
                                        $inputData['numberOfDays'] = 1;
                                        EmployeeLeave::updateRemainingLeave($inputData, 'SUB');
                                    }
                                }
                                $tempDate = date('Y-m-d', strtotime('+1 day', strtotime($tempDate)));
                                if ($i == 1) {
                                    $initialLeaveModel = $leave_data;
                                }
                            }

                            // send notification with email
                            // $this->leave->sendMailNotification($initialLeaveModel);
                            LeaveJob::dispatch($initialLeaveModel, auth()->user());

                            // save attachments
                            if ($parentId) {
                                if ($request->has('attachments')) {
                                    foreach ($inputData['attachments'] as $attachment) {
                                        $this->uploadAttachment($parentId, $attachment);
                                    }
                                }
                            }
                        }
                    }
                    break;
                case '3':
                    $dates = explode(', ', $inputData['dates']);
                    if (count($dates) > 0) {
                        foreach ($dates as $date) {
                            $inputData['date'] = $date;
                            $check = $this->leave->checkData($inputData);
                            if ($check) {
                                $existingCount++;
                                toastr()->warning('Some days are rejected due to duplicate entry.');
                            } else {
                                $leave_data = $this->leave->create($inputData);
                                $leave_data['enable_mail'] = $setting->enable_mail;
                                if ($leave_data) {
                                    if ($request->has('attachments')) {
                                        foreach ($inputData['attachments'] as $attachment) {
                                            $this->uploadAttachment($leave_data->id, $attachment);
                                        }
                                    }
                                }
                                // $this->sendMailNotification($current_user_id, $leave_data['employee_id'], $leave_data, 'create');
                            }
                        }
                        $inputData['numberOfDays'] = count($dates) - $existingCount;
                        EmployeeLeave::updateRemainingLeave($inputData, 'SUB');
                        // $this->leave->sendMailNotification($leave_data);
                        LeaveJob::dispatch($leave_data, auth()->user());
                    }
                    break;
                case '4':
                    $inputData['date'] = $tempDate;
                    $leave_data = $this->leave->create($inputData);
                    $leave_data['enable_mail'] = $setting->enable_mail;
                    $inputData['numberOfDays'] = 1;
                    EmployeeLeave::updateRemainingLeave($inputData, 'SUB');
                    // $this->leave->sendMailNotification($leave_data);
                    LeaveJob::dispatch($leave_data, auth()->user());

                    // $this->sendMailNotification($current_user_id, $leave_data['employee_id'], $leave_data, 'create');
                    break;
                default:
                    # code...
                    break;
            }
            toastr()->success('Leave Created Successfully');
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('leave.showTeamleaves'));
    }

    public function showTemplate()
    {
        $data = [];
        $data['notified_user_fullname'] = 'Namuna';
        $data['message']  = 'Your Leave Has been accepted';
        // dd($data);

        return view('admin::mail.leave', $data);
    }

    public function viewCalendar(Request $request)
    {
        $filter = $request->all();
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        $data['leaveTypeList'] = $this->leaveTypeObj->getList();
        $data['leaveKindList'] = Leave::leaveKindList();
        $data['statusList'] = Leave::statusList();

        return view('leave::leave.calendar', $data);
    }

    public function getCalendarLeaveByAjax(Request $request)
    {

        $filter = $request->all();
        $filter['date_range'] = $filter['start'] . ' - ' . $filter['end'];
        $startDate = Carbon::parse($filter['start']);
        $endDate = Carbon::parse($filter['end']);

        // Create an array to store the dates
        $dates = [];
        $nepaliDate = [];

        // Loop through the date range
        while ($startDate <= $endDate) {
            // Add the current date to the array
            $dates[] = $startDate->format('Y-m-d');
            $nepaliDate[] = date_converter()->eng_to_nep_convert_two_digits($startDate->format('Y-m-d'));
            // Move to the next day
            $startDate->addDay();
        }
        $leaveModels = $this->leave->findAll(null, $filter);

        $data  = $leaveArray = [];
        foreach ($leaveModels as $key => $leaveModel) {
            switch ($leaveModel->status) {
                case '2':
                    $color = '#007bff';
                    break;
                case '3':
                    $color = '#25b372';
                    break;
                case '4':
                    $color = '#fc0000';
                    break;
                default:
                    $color = '#6c757d';
                    break;
            }

            $leaveArray[] = [
                'id' => $leaveModel['id'],
                'title' => optional($leaveModel->employeeModel)->getFullName(),
                'start' => $leaveModel['date'],
                // 'end' => $value['eng_date'],
                'type' => 'leave',
                'props' => ($leaveModel->getStatusWithColor()),
                'color' => $color,
                // 'custom_date' => getStandardDateFormat($leaveModel['date']),
                'leave_kind' => $leaveModel->getLeaveKind(),
                'leave_category' => optional($leaveModel->leaveTypeModel)->name,
            ];
        }
        $data = $leaveArray;
        // $data = [
        //     'leaves' => $leaveArray,
        //     'nepaliDates' => $nepaliDate,
        // ];
        return response()->json($data);
    }

    // public function getCalendarLeaveByAjax(Request $request)
    // {
    //     $filter = $request->all();
    //     $filter['date_range'] = $filter['start'] . ' - ' . $filter['end'];
    //     $startDate = Carbon::parse($filter['start']);
    //     $endDate = Carbon::parse($filter['end']);

    //     // Create an array to store the events
    //     $leaveArray = [];

    //     // Loop through the date range
    //     $i=1;
    //     while ($startDate <= $endDate) {
    //         $dateStr = $startDate->format('Y-m-d');
    //         $nepaliDate = date_converter()->eng_to_nep_convert_two_digits($dateStr);

    //         // Default entry for the date
    //         $leaveArray[] = [
    //             'id' => $i, // Use date as ID or generate a unique ID if needed
    //             'title' => '',
    //             'start' => $dateStr,
    //             'type' => 'leave',
    //             'color' => 'transparent', // Default color for no leave
    //             'props' => '',
    //             'leave_kind' => 'None',
    //             'leave_category' => 'None',
    //             'nepali_date' => $nepaliDate
    //         ];

    //         // Move to the next day
    //         $startDate->addDay();
    //         $i++;
    //     }

    //     // Fetch leave models
    //     $leaveModels = $this->leave->findAll(null, $filter);

    //     // Update array with actual leave data
    //     foreach ($leaveModels as $leaveModel) {
    //         $dateStr = $leaveModel['date'];

    //         // Find the entry for the leave date
    //         foreach ($leaveArray as &$entry) {
    //             if ($entry['start'] === $dateStr) {
    //                 switch ($leaveModel->status) {
    //                     case '2':
    //                         $color = '#007bff';
    //                         break;
    //                     case '3':
    //                         $color = '#25b372';
    //                         break;
    //                     case '4':
    //                         $color = '#fc0000';
    //                         break;
    //                     default:
    //                         $color = '#6c757d';
    //                         break;
    //                 }

    //                 $entry = [
    //                     'id' => $leaveModel['id'],
    //                     'title' => optional($leaveModel->employeeModel)->getFullName(),
    //                     'start' => $leaveModel['date'],
    //                     'type' => 'leave',
    //                     'color' => $color,
    //                     'props' => ($leaveModel->getStatusWithColor()),
    //                     'leave_kind' => $leaveModel->getLeaveKind(),
    //                     'leave_category' => optional($leaveModel->leaveTypeModel)->name,
    //                     'nepali_date' => date_converter()->eng_to_nep_convert_two_digits($leaveModel['date'])
    //                 ];
    //                 break;
    //             }
    //         }
    //     }

    //     return response()->json($leaveArray);
    // }


    public function getSubstituteDateList(Request $request)
    {
        $leaves = Leave::whereNotNull('substitute_date')->where('employee_id', $request->employee_id)->where('status', '!=', 4)->pluck('substitute_date')->toArray();
        $employeeSubstituteLeaveLists = EmployeeSubstituteLeave::whereHas('employeeSubstituteLeaveClaim', function ($query) {
            $query->where('claim_status', 3);
        })->where([
            'employee_id' => $request->employee_id,
            'is_expired' => 10,
        ])->orderBy('date', 'asc')->pluck('date', 'id');
        $finalArray = [];
        $currentDate = date('Y-m-d');
        foreach ($employeeSubstituteLeaveLists as $id => $value) {
            if (!in_array($value, $leaves)) {
                $leaveTypeId = $this->employeeSubstituteLeave->findOne($id)->leave_type_id;
                if ($leaveTypeId) {
                    $leaveType = $this->leaveTypeObj->findOne($leaveTypeId);
                    if (isset($leaveType->max_substitute_days)) {
                        $date = Carbon::parse($value);
                        $expiry_date = $date->addDays($leaveType->max_substitute_days);
                        if ($expiry_date->toDateString() >= date('Y-m-d')) {
                            $nod_days = (Carbon::parse($currentDate))->diffInDays($expiry_date);
                            $finalArray[] = [
                                'id' => setting('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert($value) : $value,
                                'date' => setting('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert($value) : $value,
                                'expiry_date' => setting('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert($expiry_date->toDateString()) : $expiry_date->toDateString(),
                                'nod' => $nod_days
                            ];
                        }
                    }
                }
            }
        }
        return json_encode($finalArray);
    }

    public function exportLeaveHistory(Request $request)
    {
        $filter = $request->all();
        $filter['is_export'] = true;
        // $filter['authUser'] = auth()->user();

        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['leaves'] = $this->leave->findAll(null, $filter, $sort);
        return Excel::download(new LeaveHistoryReport($data), 'leave-history.xlsx');
        toastr('Please Filter first to download Excel Report', 'warning');
        return back();

        // LeaveJob::dispatch($filter);
        // toastr('When your leave history is prepared, you will be notified.', 'success');
        // return back();
    }

    public function cancelLeaveRequest(Request $request)
    {
        try {
            $inputData = $request->except('_token');
            $inputData['cancelled_by'] = auth()->user()->id;
            $result = $this->leave->update($inputData['id'], $inputData);
            $model = $this->leave->findOne($inputData['id']);
            $model['enable_mail'] = setting('enable_mail');
            if ($result) {
                Leave::where('parent_id', $inputData['id'])->update(['status' => $inputData['status']]);
                if ($inputData['status'] == '5') {
                    $inputData['employee_id'] = $model->employee_id;
                    $inputData['leave_type_id'] = $model->leave_type_id;
                    $inputData['numberOfDays'] = $model->leave_kind == '1' ? 0.5 : (count($model->childs) + 1);
                    EmployeeLeave::updateRemainingLeave($inputData, 'ADD');

                    $leaveTypeModel = $model->leaveTypeModel->where('code', 'SUBLV')->first();
                    if ($leaveTypeModel) {
                        EmployeeSubstituteLeave::where([
                            'date' => $model->substitute_date,
                            'employee_id' => $model->employee_id
                        ])->update(['is_expired' => 10]);
                    }
                }
            }
            // $this->leave->sendMailNotification($model);
            LeaveJob::dispatch($model, auth()->user());


            toastr('Leave Request Status Cancelled Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Updating Leave Request Status', 'error');
        }
        return redirect()->route('leave.index');
    }

    public function downloadPDF($id)
    {
        $data['leaveModel'] = $this->leave->findOne($id);
        $pdf = PDF::loadView('exports.leave-details-report', $data)->setPaper('a4', 'landscape');
        return $pdf->download('leave-details-report.pdf');
    }

    public function encashment(Request $request)
    {
        $filter = $request->all();
        $data['organizationList'] = $this->organization->getList();
        $data['leaveEncashmentLogs'] = $this->leave->leaveEncashmentLogs(20, $filter, ['by' => 'id', 'sort' => 'DESC']);
        return view('leave::encashment.index', $data);
    }

    public function updateEncashmentStatus(Request $request)
    {
        DB::beginTransaction();
        try {
            $inputData = $request->all();
            $data['status'] = 2;
            $data['encashed_date'] = date('Y-m-d');
            $leaveEncashmentLog = LeaveEncashmentLog::where('id', $inputData['encash_log_id'])->first();

            $grossSetup = GrossSalarySetupSetting::first();
            $incomeSetup = IncomeSetup::where('id', $inputData['income_type_id'])->first();
            if (!empty($incomeSetup)) {
                $calculatedValue = 0;
                $incomeReferenceSetupDetails = IncomeReferenceSetupDetail::where('income_setup_id', $incomeSetup->id)->where('employee_id', $leaveEncashmentLog->employee_id)->first();
                if ($incomeReferenceSetupDetails) {
                    $incomeReferenceSetup = optional($incomeReferenceSetupDetails->incomeReferenceSetup);
                    if ($incomeReferenceSetup) {
                        $calculatedValue = (new EmployeeSetupRepository())->methodTypeData($incomeReferenceSetup, (new EmployeeSetupRepository())->arrangeData($grossSetup->gross_salary_type), $grossSetup->gross_salary_type, $leaveEncashmentLog->employee_id, $incomeSetup);
                    }
                    $data['encashed_amount'] = $calculatedValue;
                }
            }

            $data['leave_remaining'] = $leaveEncashmentLog->leave_remaining - $inputData['eligible_encashment'];
            $data['exceeded_balance'] = $leaveEncashmentLog->exceeded_balance - $inputData['eligible_encashment'];
            $data['total_balance'] = $leaveEncashmentLog->total_balance - $inputData['eligible_encashment'];
            $data['eligible_encashment'] = 0;
            $leaveEncashmentLog->update($data);

            //log activity create
            $activityData = [
                'leave_encashment_log_id' => $leaveEncashmentLog->id,
                'encashed_leave_balance' => $inputData['eligible_encashment']
            ];
            LeaveEncashmentLogActivity::create($activityData);
            //

            //update emp leave table
            $empLeave = EmployeeLeave::where([
                'leave_year_id' => getCurrentLeaveYearId(),
                'employee_id' => $leaveEncashmentLog->employee_id,
                'leave_type_id' => $leaveEncashmentLog->leave_type_id,
                'is_valid' => 11
            ])->first();
            if (!empty($empLeave)) {
                $empLeave->leave_remaining = $empLeave->leave_remaining - $inputData['eligible_encashment'];
                $empLeave->save();
            }

            DB::commit();
            toastr('Status Updated Successfully', 'success');
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr('Error While Updating Encashment Status', 'error');
        }
        return redirect()->route('leave.encashment');
    }

    public function encashmentActivity(Request $request)
    {
        $filter = $request->all();
        $data['organizationList'] = $this->organization->getList();
        $data['leaveEncashmentLogsActivity'] = $this->leave->leaveEncashmentLogsActivity(20, $filter, ['by' => 'id', 'sort' => 'DESC']);
        return view('leave::encashment.logs-activity', $data);
    }

    public function updateArchivedEncashmentDate(Request $request)
    {
        DB::beginTransaction();
        try {
            $encashmentActivity = LeaveEncashmentLogActivity::where('id', $request->encashment_id)->first();
            if (!$encashmentActivity) {
                throw new Exception('Something Went Wrong !!');
            }
            $encashmentActivity->leaveEncashmentLog->encashed_date = $request->archived_date;
            $encashmentActivity->leaveEncashmentLog->save();
            DB::commit();
            toastr('Updated Successfully !!', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr('Something Went Wrong !!', 'error');
            return redirect()->back();
        }
    }
}
