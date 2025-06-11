<?php

namespace App\Modules\Api\Http\Controllers\Leave;

use App\Helpers\DateTimeHelper;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\Api\Service\Leave\LeaveService;
use App\Modules\Api\Service\UserDeviceRepository;
use App\Modules\Api\Transformers\LeaveResource;
use App\Modules\Api\Transformers\LeaveSubstituteResource;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Employee\Entities\EmployeeSubstituteLeave;
use App\Modules\Employee\Http\Controllers\EmployeeSubstituteLeaveController;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\Employee\Repositories\EmployeeSubstituteLeaveRepository;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Leave\Repositories\LeaveRepository;
use App\Modules\Leave\Repositories\LeaveTypeRepository;
use App\Modules\User\Entities\User;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class SubstituteLeaveController extends ApiController
{
    protected $leaveObj;
    protected $employeeSubstituteLeaveObj;
    protected $leaveTypeObj;


    public function __construct()
    {
        $this->leaveObj = new LeaveRepository();
        $this->leaveTypeObj = new LeaveTypeRepository();
        $this->employeeSubstituteLeaveObj = new EmployeeSubstituteLeaveRepository();
    }


    public function index(Request $request)
    {
        $filter = $request->all();
        try {
            $employeeSubstituteLeaveModels = $this->employeeSubstituteLeaveObj->findAll(10, $filter)->map(function ($approvals) {
                $approvals->status_list = json_encode(Leave::statusList());
                return $approvals;
            });

            $data = LeaveSubstituteResource::collection($employeeSubstituteLeaveModels);
            return  $this->respondSuccess($data);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }


    public function show($id)
    {
        try {
            $leaveModel = EmployeeSubstituteLeave::find($id);
            return  $this->respond([
                'status' => true,
                'data' =>  new LeaveSubstituteResource($leaveModel)
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound($e->getMessage());
        } catch (Throwable $e) {
            return $this->respondNotTheRightParameters($e->getMessage());
        }
    }

    public function dropdown()
    {
        try {
            $data['statusList'] = setObjectIdAndName(Leave::statusList());

            return  $this->respond(['data' => $data]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function store(Request $request)
    {
        $inputData = $request->all();

        try {
            $validate = Validator::make(
                $request->all(),
                [
                    // 'employee_id' => 'required',
                    'date' => 'required',
                    // 'leave_kind' => 'required',
                    'remark' => 'required',
                    // 'status' => 'required',
                ]
            );
            if ($validate->fails()) {
                return $this->respondValidatorFailed($validate);
            }
            $exists = EmployeeSubstituteLeave::where([
                ['employee_id', '=', $inputData['employee_id']],
                ['status', '!=', 4],
                ['date', '=', $inputData['date']],

            ])->exists();

            if ($exists) {
                return $this->respondWithError('Substitute Leave for this date and employee already allocated !');
            } else {
                $employee = Employee::find($inputData['employee_id']);
                $leaveTypeModel = LeaveType::where([
                    'organization_id' => $employee->organization_id,
                    'leave_year_id' => getCurrentLeaveYearId(),
                    'code' => 'SUBLV',
                    'status' => '11',
                ])->orderBy('id', 'desc')->first();

                $inputData['leave_type_id'] = $leaveTypeModel->id;
                $inputData['status'] = 1;
                $inputData['leave_kind'] = 2;
                $inputData['nepali_date'] = date_converter()->eng_to_nep_convert($inputData['date']);
                $model = $this->employeeSubstituteLeaveObj->create($inputData);
                if ($model) {
                    $model['enable_mail'] = setting('enable_mail');
                        EmployeeSubstituteLeaveController::sendMailNotification($model);
                }
                return $this->respond([
                    'status' => true,
                    'message' => 'Substitute Leave Created Successfully',
                ]);
            }
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $inputData = $request->all();
        $model = $this->employeeSubstituteLeaveObj->findOne($id);
        try {
            if ($inputData['status'] == '2') {
                $inputData['forwarded_by'] = auth()->user()->id;
            }elseif ($inputData['status'] == '4') {
                $inputData['rejected_by'] = auth()->user()->id;
            }
            $result = $this->employeeSubstituteLeaveObj->update($model->id, $inputData);
            if ($result) {
                $updatedModel = $this->employeeSubstituteLeaveObj->findOne($id);
                if ($inputData['status'] == '3') {
                    $leaveTypeModel = $this->leaveTypeObj->findOne($model->leave_type_id);
                    if ($leaveTypeModel) {
                        $employeeLeaveModel = EmployeeLeave::where(['leave_year_id' => $leaveTypeModel->leave_year_id, 'employee_id' => $model->employee_id, 'leave_type_id' => $leaveTypeModel->id])->first();
                        $counter = $model->leave_kind == 1 ? 0.5 : 1;

                        if ($employeeLeaveModel) {
                            $employeeLeaveModel->leave_remaining += $counter;
                            $employeeLeaveModel->save();
                        }

                        $employeeLeaveOpeningModel = EmployeeLeaveOpening::where(['organization_id' => $leaveTypeModel->organization_id, 'leave_year_id' => $leaveTypeModel->leave_year_id, 'employee_id' => $model->employee_id, 'leave_type_id' => $leaveTypeModel->id])->first();
                        if ($employeeLeaveOpeningModel) {
                            $employeeLeaveOpeningModel->opening_leave += $counter;
                            $employeeLeaveOpeningModel->save();
                        }
                    }
                }

                $updatedModel['enable_mail'] = setting('enable_mail');
                    EmployeeSubstituteLeaveController::sendMailNotification($updatedModel);
            }

            return $this->respond([
                'status' => true,
                'message' => 'Status Updated Successfully',
            ]);
        } catch (\Throwable $e) {
            $this->respondWithError($e->getMessage());
        }
    }

    public function getTeamLeaves(Request $request)
    {
        $filter = $request->all();
        try {
            // $employeeSubstituteLeaveModels = $this->employeeSubstituteLeaveObj->findTeamleaves(20, $filter);

            $userId = auth()->user()->id;
            $statusList = EmployeeSubstituteLeave::statusList();
            $user = auth()->user();
            $usertype = $user->user_type;

            $firstApprovalEmps = EmployeeApprovalFlow::where('first_approval_user_id', $userId)->pluck('last_approval_user_id', 'employee_id')->toArray();
            $firstApproval = EmployeeSubstituteLeave::with('employee.employeeApprovalFlowRelatedDetailModel')->when(true, function ($query) use ($firstApprovalEmps) {
                $query->whereIn('employee_id', array_keys($firstApprovalEmps));
            })->get()->map(function ($approvals) use ($statusList, $usertype) {
                $approvalFlow = optional($approvals->employee)->employeeApprovalFlowRelatedDetailModel;
                if ($usertype == 'supervisor') {
                    if (!$approvalFlow->last_approval_user_id || $approvals->status == 1) {
                        unset($statusList[3]);
                    }

                    if ($approvalFlow->first_approval_user_id == auth()->user()->id && $approvals->status == 2) {
                        unset($statusList[1], $statusList[3], $statusList[4]);
                    }
                }
                $approvals->status_list = json_encode($statusList);
                return $approvals;
            });

            $lastApprovalEmps = EmployeeApprovalFlow::where('last_approval_user_id', $userId)->select('first_approval_user_id', 'last_approval_user_id', 'employee_id')->get()->toArray();
            $lastApproval = [];
            if (count($lastApprovalEmps) > 0) {
                $lastApproval = EmployeeSubstituteLeave::when(true, function ($query) use ($lastApprovalEmps) {
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
                })->get()->map(function ($approvals) use ($statusList, $usertype) {
                    $approvalFlow = optional($approvals->employee)->employeeApprovalFlowRelatedDetailModel;
                    if ($usertype == 'supervisor') {
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
                    }
                    $approvals->status_list = json_encode($statusList);
                    return $approvals;
                });
            }

            $mergeApproval = $firstApproval->merge($lastApproval)->sortByDesc('id');
            $myCollectionObj = collect($mergeApproval);
            $result = $myCollectionObj;

            if ($user->user_type == 'supervisor') {
                $result = $result->where('employee_id', '!=', $user->emp_id);
            }
            if (isset($filter['date_range'])) {
                $filterDates = explode(' - ', $filter['date_range']);
                $result = $result->where('date', '>=', $filterDates[0]);
                $result = $result->where('date', '<=', $filterDates[1]);
            }
            if (isset($filter['leave_type_id']) && !empty($filter['leave_type_id'])) {
                $result = $result->where('leave_type_id', $filter['leave_type_id']);
            }
            if (isset($filter['leave_kind']) && !empty($filter['leave_kind'])) {
                $result = $result->where('leave_kind', $filter['leave_kind']);
            }
            if (isset($filter['status']) && !empty($filter['status'])) {
                $result = $result->where('status', $filter['status']);
            }
            $result = $result->where('parent_id', null);
            $result = paginate($result, 20, '', ['path' => request()->url()]);

            $data = LeaveSubstituteResource::collection($result);
            return  $this->respondSuccess($data);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function destroy($id)
    {
        try {
            $this->employeeSubstituteLeaveObj->delete($id);

            return $this->respond([
                'status' => true,
                'message' => 'Substitute Leave has been deleted Successfully',
            ]);
        } catch (\Throwable $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

}
