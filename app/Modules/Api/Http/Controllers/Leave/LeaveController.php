<?php

namespace App\Modules\Api\Http\Controllers\Leave;

use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use Illuminate\Routing\Controller;
use App\Modules\User\Entities\User;
use App\Modules\Leave\Jobs\LeaveJob;
use Illuminate\Support\Facades\Auth;
use App\Modules\Leave\Entities\Leave;
use Doctrine\DBAL\Query\QueryException;
use App\Modules\Leave\Entities\LeaveType;
use Illuminate\Support\Facades\Validator;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Api\Service\Leave\LeaveService;
use App\Modules\Api\Transformers\LeaveResource;
use App\Modules\Holiday\Entities\HolidayDetail;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Shift\Entities\ShiftGroupMember;
use App\Modules\Api\Service\UserDeviceRepository;
use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\Leave\Repositories\LeaveRepository;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Leave\Repositories\LeaveTypeRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\Shift\Repositories\ShiftGroupRepository;
use App\Modules\Shift\Repositories\ShiftRepository;
class LeaveController extends ApiController
{
    protected $leaveObj;

    /**
     *
     */
    public function __construct()
    {
        $this->leaveObj = new LeaveRepository();
    }

    public function remainingLeave()
    {
        try {
            $inputData['leave_year_id'] = getCurrentLeaveYearId();
            $inputData['show_on_employee'] = "11";
            $inputData['employee_id'] = optional(auth()->user()->userEmployer)->id;

            $employeeLeaveList = EmployeeLeave::getList($inputData);
            $data = [];
            foreach ($employeeLeaveList as $employeeLeave) {
                $data[] = [
                    'id' => optional($employeeLeave->leaveTypeModel)->id,
                    'title' => optional($employeeLeave->leaveTypeModel)->name,
                    'remain' => $employeeLeave->leave_remaining
                ];
            }

            return  $this->respond([
                'status' => true,
                'data' => $data

            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }



    public function index(Request $request)
    {

        $filter = $request->all();
        try {
            if (isset($filter['is_self']) && !empty($filter['is_self'])) {
                $leaves = $this->getLeaveList($filter);
            } else {
                $authUser = auth()->user();

                if ($authUser->user_type == 'division_hr') {
                    $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
                    $leaves = $this->getLeaveList($filter);
                } elseif ($authUser->user_type == 'supervisor') {
                    $leaves = $this->teamRequests($filter);
                } elseif ($authUser->user_type == 'employee') {
                    $filter['employee_id'] = $authUser->emp_id;
                    $leaves = $this->getLeaveList($filter);
                } else {
                    $leaves = $this->getLeaveList($filter);
                }
            }
            $data = LeaveResource::collection($leaves);
            return  $this->respondSuccess($data);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function teamRequests($filter)
    {
        $user = auth()->user();
        $statusList = Leave::statusList();

        try {
            $firstApprovalEmps = EmployeeApprovalFlow::where('first_approval_user_id', $user->id)->pluck('last_approval_user_id', 'employee_id')->toArray();
            $firstApproval = Leave::with('employeeModel.employeeApprovalFlowRelatedDetailModel')->when(true, function ($query) use ($firstApprovalEmps, $user) {
                $query->whereIn('employee_id', array_keys($firstApprovalEmps));
                $query->where('organization_id', optional($user->userEmployer)->organization_id);
            })->get()->map(function ($approvals) use ($statusList, $user) {
                $approvalFlow = optional($approvals->employeeModel)->employeeApprovalFlowRelatedDetailModel;
                if (auth()->user()->user_type == 'supervisor') {
                    if (!$approvalFlow->last_approval_user_id || $approvals->status == 1) {
                        unset($statusList[3]);
                    }

                    if ($approvalFlow->first_approval_user_id == auth()->user()->id && $approvals->status == 2) {
                        unset($statusList[1], $statusList[3], $statusList[4]);
                    }
                    unset($statusList[5]);
                }
                $approvals->status_list = setObjectIdAndName($statusList);
                return $approvals;
            });

            $lastApprovalEmps = EmployeeApprovalFlow::where('last_approval_user_id', $user->id)->select('first_approval_user_id', 'last_approval_user_id', 'employee_id')->get()->toArray();
            $lastApproval = [];
            if (count($lastApprovalEmps) > 0) {
                $lastApproval = Leave::when(true, function ($query) use ($lastApprovalEmps, $user) {
                    $query->where('organization_id', optional($user->userEmployer)->organization_id);
                    $where = 'where';
                    foreach ($lastApprovalEmps as $value) {

                        $query->$where(function ($query) use ($value, $where) {
                            $query->where('employee_id', $value['employee_id']);
                            if (is_null($value['first_approval_user_id'])) {
                                $query->whereIn('status', [1, 2, 3, 4]);
                            } else {
                                $query->whereIn('status', [2, 3, 4]);
                            }
                        });
                        $where = 'orWhere';
                    }
                })->get()->map(function ($approvals) use ($statusList) {
                    $approvalFlow = optional($approvals->employeeModel)->employeeApprovalFlowRelatedDetailModel;
                    if (auth()->user()->user_type == 'supervisor') {
                        if ($approvals->status == 1) {
                            if (isset($approvalFlow->first_approval_user_id) && $approvalFlow->first_approval_user_id == auth()->user()->id) {
                                unset($statusList[3]);
                            } elseif (isset($approvalFlow->last_approval_user_id) && $approvalFlow->last_approval_user_id == auth()->user()->id) {
                                unset($statusList[2]);
                            }
                        } elseif ($approvals->status == 2) {
                            if (isset($approvalFlow->first_approval_user_id) && $approvalFlow->first_approval_user_id == auth()->user()->id) {
                                unset($statusList[1], $statusList[3], $statusList[4]);
                            } elseif (isset($approvalFlow->last_approval_user_id) && $approvalFlow->last_approval_user_id == auth()->user()->id) {
                                unset($statusList[1]);
                            }
                        }
                        unset($statusList[5]);
                    }
                    $approvals->status_list = setObjectIdAndName($statusList);
                    return $approvals;
                });
            }
            $mergeApproval = $firstApproval->merge($lastApproval)->sortByDesc('id');
            $myCollectionObj = collect($mergeApproval);
            $result = $myCollectionObj;

            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $result->where('organization_id', $filter['organization_id']);
            }
            if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
                $result->where('employee_id', $filter['employee_id']);
            }
            if (isset($filter['from_date']) && !empty($filter['from_date'])) {
                $result->where('date', '>=', $filter['from_date']);
            }
            if (isset($filter['to_date']) && !empty($filter['to_date'])) {
                $result->where('date', '<=', $filter['to_date']);
            }
            if (isset($filter['leave_type_id']) && !empty($filter['leave_type_id'])) {
                $result->where('leave_type_id', $filter['leave_type_id']);
            }
            if (isset($filter['leave_kind']) && !empty($filter['leave_kind'])) {
                $result->where('leave_kind', $filter['leave_kind']);
            }
            if (isset($filter['status']) && !empty($filter['status'])) {
                $result->where('status', $filter['status']);
            }

            return $result;
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function show($id)
    {
        try {
            $leaveModel = Leave::find($id);
            return  $this->respond([
                'status' => true,
                'data' =>  new LeaveResource($leaveModel)
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound($e->getMessage());
        } catch (Throwable $e) {
            return $this->respondNotTheRightParameters($e->getMessage());
        }
    }

    // public function viewleaveHistory($id)
    // {
    //     try {
    //         $user = auth()->user();
    //         $leaves = Leave::where([
    //             'id'=>$id,
    //             'employee_id' => $user->emp_id
    //         ])->firstOrFail();
    //         return LeaveResource::collection($leaves);
    //     } catch (QueryException $e) {
    //         return $this->respondInvalidQuery();
    //     }
    // }

    public function getLeaveList($filter)
    {
        return Leave::when(true, function ($query) use ($filter) {
            if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }
            if (isset($filter['from_date']) && !empty($filter['from_date'])) {
                $query->where('date', '>=', $filter['from_date']);
            }
            if (isset($filter['to_date']) && !empty($filter['to_date'])) {
                $query->where('date', '<=', $filter['to_date']);
            }
            if (isset($filter['leave_type_id']) && !empty($filter['leave_type_id'])) {
                $query->where('leave_type_id', $filter['leave_type_id']);
            }
            if (isset($filter['leave_kind']) && !empty($filter['leave_kind'])) {
                $query->where('leave_kind', $filter['leave_kind']);
            }
            if (isset($filter['status']) && !empty($filter['status'])) {
                $query->where('status', $filter['status']);
            }
            if (isset($filter['is_self']) && !empty($filter['is_self'])) {
                $query->where('employee_id', auth()->user()->emp_id);
            }

            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }

            $currentLeaveYearId = getCurrentLeaveYearId();
            if ($currentLeaveYearId != '') {
                $query->whereHas('leaveTypeModel', function ($qry) use ($currentLeaveYearId) {
                    $qry->where('leave_year_id', $currentLeaveYearId);
                });
            }
        })
            ->where('parent_id', null)
            ->orderBy('id', 'desc')->get()->map(function ($approvals) {
                $approvals->status_list = setObjectIdAndName(Leave::statusList());
                return $approvals;
            });
    }
    public function dropdown()
    {
        try {
            $leaveService = new LeaveService();
            $data['categories'] = $leaveService->leaveCategories();
            $data['sub_categories'] = $leaveService->halfLeaveTypes();
            $data['leaveTypes'] = $leaveService->leaveTypeList();
            $data['statusList'] = setObjectIdAndName(Leave::statusList());

            return  $this->respond(['data' => $data]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $inputData = $request->all();
        $user = Auth::user();

        $employeeModel = (new EmployeeRepository())->find($user->emp_id);

        $leaveData = [];

        try {
            $validate = Validator::make(
                $request->all(),
                [
                    'leave_kind' => 'required',
                    'leave_type_id' => 'required',
                    'start_date' => 'required',
                    // 'end_date' => 'required',
                    'reason' => 'required',
                ]
            );

            if ($validate->fails()) {
                return $this->respondValidatorFailed($validate);
            }

            $inputData['employee_id'] = $user->emp_id;
            $inputData['authUserType'] = $user->user_type;
            $inputData['organization_id'] = optional($user->userEmployer)->organization_id;
            $inputData['generated_by'] = 10;
            $inputData['status'] = 1;


            $currentLeaveYear = LeaveYearSetup::currentLeaveYear();

            $inputData['leave_year_id'] = $currentLeaveYear->id;
            $inputData['leaveYearStartDate'] = $currentLeaveYear->start_date_english;

            //Nepali Date Conversion Start
            $dateConverterObject = new DateConverter();
            $start_date = $request->start_date;
            $end_date = $request->end_date;

            if ($inputData['leave_kind'] == '4') {
                $start_date = $request->leave_date;
            }
            $inputData['nepali_date'] = $dateConverterObject->eng_to_nep_convert_two_digits($start_date);



            // dd($inputData);

            //Nepali Date Conversion Ends

            $inputData['status'] = isset($inputData['status']) ? $inputData['status'] : 1;
            $tempDate = $start_date;
            $existingCount = 0;

            $checkLeave = (new LeaveRepository())->checkLeave([
                'employee_id' => $user->emp_id,
                'date' => $start_date,
            ])->where('status', '!=', 4)->exists();

            if ($checkLeave) {
                return $this->respondWithError('Leave Already Exist');
            }

            if($inputData['leave_kind']==='1'){
                $finalResult=$this->restrictHalfleave($inputData);
                if($finalResult){
                    return $this->respondWithError( "You can't take half leave for this date ".$inputData['start_date']." !!");
                }
            }

            switch ($inputData['leave_kind']) {
                case '1': //half leave
                    $inputData['date'] = $tempDate;
                    $check = (new LeaveRepository())->checkData($inputData);
                    if ($check) {
                        $existingCount++;
                    } else {
                        $leave_data = (new LeaveRepository())->create($inputData);
                        if ($leave_data) {
                            if ($request->has('attachments')) {
                                foreach ($inputData['attachments'] as $attachment) {
                                    (new LeaveRepository())->uploadAttachment($leave_data->id, $attachment);
                                }
                            }
                        }
                        $inputData['numberOfDays'] = 0.5;
                        EmployeeLeave::updateRemainingLeave($inputData, 'SUB');
                        $leave_data['enable_mail'] = setting('enable_mail');
                        // (new LeaveRepository())->sendMailNotification($leave_data);
                        LeaveJob::dispatch($leave_data,$user);
                    }

                    // if (isset($leave_data)) {
                    //     (new LeaveRepository())->sendMailNotification($leave_data);

                    // }

                    return $this->respond([
                        'status' => true,
                        'message' => 'Half Leave Created Successfully',
                        // 'data' => LeaveResource::collection($leave_data)
                    ]);
                    break;
                case '2':
                    $employeeDayOffs = optional((new EmployeeRepository())->find($inputData['employee_id']))->getEmployeeDayList();
                    $inputData['leave_type_ids'] = [$inputData['leave_type_id']];
                    foreach ($inputData['leave_type_ids'] as $key => $leave_type_id) {
                        $parentId = null;
                        $inputData['leave_type_id'] = $leave_type_id;

                        // $days = $inputData['number_of_days'][$key];
                        $days = DateTimeHelper::DateDiffInDay($start_date, $end_date);
                        $days += 1; // Adjust day for proper calculation
                        if ($days > 0) {
                            $leaveArray = [];
                            for ($i = 1; $i <= $days; $i++) {
                                $inputData['date'] = $tempDate;
                                $inputData['nepali_date'] = date_converter()->eng_to_nep_convert($tempDate);
                                // $holidayModel = HolidayDetail::where('eng_date', '=', $inputData['date'])->first();

                                $holidayModel = HolidayDetail::whereHas('holiday', function ($query) use ($employeeModel) {
                                    $gender = $employeeModel->getGender;

                                    if ($gender->dropvalue == 'Male') {
                                        $gender_type = 3;
                                    } elseif ($gender->dropvalue == 'Female') {
                                        $gender_type = 2;
                                    } else {
                                        $gender_type = 1;
                                    }

                                    $query->where(function ($q) use ($employeeModel) {
                                        $q->where('apply_for_all', 11)
                                          ->orWhere(function ($q) use ($employeeModel) {
                                              $q->where('branch_id', $employeeModel->branch_id)
                                                ->where('apply_for_all', '!=', 11);
                                          });
                                    })->where('gender_type', $gender_type);
                                })
                                ->where('eng_date', '=', $inputData['date'])
                                ->first();

                                $check = (new LeaveRepository())->checkData($inputData);
                                if ($check) {
                                    $existingCount++;
                                } elseif (in_array(Carbon::parse($inputData['date'])->format('l'), $employeeDayOffs) || $holidayModel) {

                                    $leaveType = (new LeaveTypeRepository())->findOne($inputData['leave_type_id']);
                                    if ($leaveType->sandwitch_rule_status == '11') {

                                        $finalData = $inputData;
                                        $finalData["reason"] = "sandwich rule";
                                        // $finalData['leave_type_id']=null;
                                        $finalData['leave_kind'] = 2;
                                        $finalData['parent_id'] = $parentId;
                                        $leave_data = (new LeaveRepository())->create($finalData);
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
                                    $leave_data = (new LeaveRepository())->create($inputData);
                                    if ($parentId == null) {
                                        $parentId = $leave_data->id;
                                    }
                                    if ($leave_data) {
                                        $inputData['numberOfDays'] = 1;
                                        EmployeeLeave::updateRemainingLeave($inputData, 'SUB');
                                    }
                                }
                                $tempDate = date('Y-m-d', strtotime('+1 day', strtotime($tempDate)));
                                if ($i == 1 && isset($leave_data)) {
                                    $initialLeaveModel = $leave_data;
                                }
                            }

                            if (isset($initialLeaveModel)) {
                                $initialLeaveModel['enable_mail'] = setting('enable_mail');
                                // (new LeaveRepository())->sendMailNotification($initialLeaveModel);
                                LeaveJob::dispatch($initialLeaveModel,$user);

                                // (new UserDeviceRepository())->sendNotification($leave_data);
                            }

                            // save attachments
                            if ($parentId) {
                                if ($request->has('attachments')) {
                                    foreach ($inputData['attachments'] as $attachment) {
                                        (new LeaveRepository())->uploadAttachment($parentId, $attachment);
                                    }
                                }
                            }
                        }
                    }
                    return $this->respond([
                        'status' => true,
                        'message' => 'Full Leave Created Successfully',
                        // 'data' => LeaveResource::collection($leaveArray)
                    ]);
                    break;
                default:
                    # code...
                    break;
            }
            return $this->respondWithError([
                'status' => true,
                'message' => 'Something went wrong'
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function restrictHalfleave($data){
        // dd(($data));
        $engDate = date_converter()->nep_to_eng_convert($data['nepali_date']);
        $day = date('D', strtotime( $engDate));
        $empShift = optional(optional(ShiftGroupMember::where('group_member', $data['employee_id'])->orderBy('id', 'DESC')->first())->group)->shift;
        $newShiftEmp = NewShiftEmployee::getShiftEmployee($data['employee_id'], $engDate);
        if (isset($newShiftEmp)) {
            $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
            if (isset($rosterShift) && isset($rosterShift->shift_id)) {
                $empShift =(new ShiftRepository())->find($rosterShift->shift_id);
            }
        }
        $shiftStartTime = '09:00';
        $shiftEndTime = '18:00';
        if (isset($empShift)) {
            $shiftSeason = $empShift->getShiftSeasonForDate($engDate);
            $seasonalShiftId = null;
            if($shiftSeason){
                $seasonalShiftId = $shiftSeason->id;
            }
            $shiftStartTime = optional($empShift->getShiftDayWise($day, $seasonalShiftId))->start_time;
            $shiftEndTime = optional($empShift->getShiftDayWise($day, $seasonalShiftId))->end_time;
        }
        $totalDayShiftHr=$this->calculateTotalHours($shiftStartTime,$shiftEndTime);
        $restrictStatus=false;
        if((float)$totalDayShiftHr <=4){
            $restrictStatus=true;
        }
        return $restrictStatus;
    }

    public function calculateTotalHours($startTime, $endTime) {
        $start = new \DateTime('1970-01-01 ' . $startTime);
        $end = new \DateTime('1970-01-01 ' . $endTime);
        if ($end < $start) {
            $end->modify('+1 day');
        }
        $diff = $end->diff($start);
        $hours = $diff->h + ($diff->days * 24);
        $minutes = $diff->i;
        $formattedMinutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
        return $hours . '.' . $formattedMinutes;
    }


    public function preProcessData(Request $request)
    {

        $validate = Validator::make(
            $request->all(),
            [
                'leaveType' => 'required',
                'startDate' => 'required',
                'maxDays' => 'required',
            ]
        );

        if ($validate->fails()) {
            return $this->respondValidatorFailed($validate);
        }

        try {
            $data['params'] = $request->all();
            $data['params']['employeeId'] = auth()->user()->emp_id;


            $request = new Request($data);
            $data = (new LeaveRepository())->preProcessData($request);
            return  $this->respond([
                'status' => true,
                'data' => json_decode($data)
            ]);
        } catch (\Throwable $e) {
            $this->respondWithError($e->getMessage());
        }
    }

    public function postProcessData(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'leaveType' => 'required',
                'startDate' => 'required',
                'endDate' => 'required',
            ]
        );

        if ($validate->fails()) {
            return $this->respondValidatorFailed($validate);
        }

        try {
            $data['params'] = $request->all();
            $data['params']['employeeId'] = auth()->user()->emp_id;

            $request = new Request($data);
            $data = (new LeaveRepository())->postProcessData($request);
            $result = json_decode($data, true);
            unset($result['noticeList']);
            return  $this->respond([
                'status' => true,
                'data' => $result
            ]);
        } catch (\Throwable $e) {
            $this->respondWithError($e->getMessage());
        }
    }

    /**
     *
     */
    public function updateStatus($id, Request $request)
    {
        $inputData = $request->all();
        $authUser = auth()->user();

        try {
            $data = null;

            // if ($authUser->user_type != 'hr' && $authUser->user_type != 'division_hr') {
            //     return response()->json([
            //         'status' => false,
            //         'message' => "User Access Denied!"
            //     ], 401);
            // }

            switch ($inputData['status']) {
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

            $result = $this->leaveObj->update($id, $inputData);
            if ($result) {
                $leaveModel = $this->leaveObj->findOne($id);
                Leave::where('parent_id', $id)->update(['status' => $inputData['status']]);
                    $leaveModel['enable_mail'] = setting('enable_mail');
                    // $this->leaveObj->sendMailNotification($leaveModel);
                    LeaveJob::dispatch($leaveModel,$authUser);

                if ($inputData['status'] == '4') {
                    $inputData['employee_id'] = $leaveModel->employee_id;
                    $inputData['leave_type_id'] = $leaveModel->leave_type_id;
                    $inputData['numberOfDays'] = $leaveModel->leave_kind == '1' ? 0.5 : (count($leaveModel->childs) + 1);
                    EmployeeLeave::updateRemainingLeave($inputData, 'ADD');
                }
                return  $this->respondSuccess($data);
            }
        } catch (\Throwable $th) {
            //throw $th;
            $this->respondWithError($th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->leaveObj->delete($id);

            return $this->respond([
                'status' => true,
                'message' => 'Leave has been deleted Successfully',
            ]);
        } catch (\Throwable $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }
}
