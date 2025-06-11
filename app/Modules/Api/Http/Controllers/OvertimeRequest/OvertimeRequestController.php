<?php

namespace App\Modules\Api\Http\Controllers\OvertimeRequest;

use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\OvertimeRequest\Entities\OvertimeRequest;
use App\Modules\OvertimeRequest\Repositories\OvertimeRequestInterface;
use App\Modules\Employee\Entities\EmployeeBusinessTripApprovalFlow;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Entities\OtRateSetup;
use App\Modules\User\Entities\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OvertimeRequestController extends ApiController
{
    private $overtimeRequest;
    private $employee;
    private $organization;

    public function __construct(
        OvertimeRequestInterface $overtimeRequest,
        EmployeeInterface $employee,
        OrganizationInterface $organization
    ) {
        $this->overtimeRequest = $overtimeRequest;
        $this->employee = $employee;
        $this->organization = $organization;
    }

    public function getDropdown()
    {
        try {
            $statusList = OvertimeRequest::STATUS;
            $claimStatusList = OvertimeRequest::CLAIM_STATUS;
            $data['statusList'] = setObjectIdAndName($statusList);
            $data['claimStatusList'] = setObjectIdAndName($claimStatusList);
            $data['organizationList'] = setObjectIdAndName($this->organization->getList());

            return  $this->respondSuccess($data);
        } catch (\Throwable $th) {
            return $this->respondInvalidQuery($th->getMessage());
        }
    }

    public function index(Request $request)
    {
        $filter=$request->all();
        $overtimeRequestModel  = OvertimeRequest::query();
        $overtimeRequestModel->when(true, function ($query) use ($filter) {
            if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
                $query->whereHas('employee', function ($q) use ($filter) {
                    $q->where('organization_id', $filter['organization_id']);
                });
            }

            if (isset($filter['employee_id']) && $filter['employee_id'] != '') {
                $query = $query->where('employee_id', $filter['employee_id']);
            }

            if (isset($filter['date_range'])) {
                $filterDates = explode(' - ', $filter['date_range']);
                $query->where('date', '>=', $filterDates[0]);
                $query->where('date', '<=', $filterDates[1]);
            }

            if (isset($filter['from_date_nep']) && !empty($filter['from_date_nep'])) {
                $query->where('nepali_date', '>=', $filter['from_date_nep']);
            }

            if (isset($filter['to_date_nep']) && !empty($filter['to_date_nep'])) {
                $query->where('nepali_date', '<=', $filter['to_date_nep']);
            }

            if (isset($filter['status']) && $filter['status'] != '') {
                $query = $query->where('status', $filter['status']);
            }

            if (auth()->user()->user_type == 'employee' || auth()->user()->user_type == 'supervisor') { //supervisor logic changes
                $empId = optional(User::where('id', auth()->user()->id)->first()->userEmployer)->id;

                $query->where('employee_id', $empId);
            } elseif (auth()->user()->user_type == 'division_hr') {
                $query->whereHas('employee', function ($q) use ($filter) {
                    $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                });
            } elseif (auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'super_admin') {
                $query->where('employee_id', '!=', null);
            }
        })->with(['employee' => function ($q) {
            $q->select('id', 'first_name','middle_name','last_name');
        }]);
        $result = $overtimeRequestModel->orderBy('id', 'DESC')->get();
        return $this->respond([
            'status' => true,
            'data' => $result
        ]);
    }

    public function store(Request $request)
    {
        $inputData = $request->all();

        try {
            $validate = Validator::make(
                $inputData,
                [
                    'employee_id' => 'required',
                    'date' => 'required',
                    'start_time' => 'required',
                    'end_time' => 'required',
                    'ot_time' => 'required',
                    // 'to_date' => 'required|date|after_or_equal:from_date',
                    'remarks' => 'required'
                ]
            );
            if($validate->fails()){
                return $this->respondValidatorFailed($validate);
            }

            //check min ot time in minutes
            $employee = $this->employee->find($inputData['employee_id']);
            $otRateSetup = OtRateSetup::where('organization_id', $employee->organization_id)->first();

            if(isset($otRateSetup) && !empty($otRateSetup)){
                if(isset($otRateSetup->is_min_ot_requirement) && $otRateSetup->is_min_ot_requirement == 11){
                    if($inputData['ot_time'] < $otRateSetup->min_ot_time){
                        return $this->respondWithError('Your minimum time to request OT is '. $otRateSetup->min_ot_time . ' minutes. So, you cannot request this Overtime.');
                    }
                }
            }
            //

            $inputData['nepali_date'] = date_converter()->eng_to_nep_convert($inputData['date']);
            $inputData['status'] = 1;
            $inputData['created_by'] = auth()->user()->id;
            $otRequest = $this->overtimeRequest->save($inputData);
            if (isset($otRequest)) {
                $otRequest['enable_mail'] = setting('enable_mail');
                $this->overtimeRequest->sendMailNotification($otRequest);
            }
            return $this->respond([
                'status' => true,
                'message' => 'Overtime Request Created Successfully',
            ]);
        } catch (\Throwable $th) {
            return $this->respondInvalidQuery($th->getMessage());
        }
    }

    public function view($id)
    {
        try{
            $overtimeRequest = OvertimeRequest::find($id);
            return $this->respond([
                'status' => true,
                'data' => $overtimeRequest
            ]);

        }catch (\Throwable $th) {
            return $this->respondInvalidQuery($th->getMessage());
        }
    } 

    public function update(Request $request, $id)
    {
        try {
            $inputData = $request->all();
            $validate = Validator::make(
                $inputData,
                [
                    'employee_id' => 'required',
                    'date' => 'required',
                    'start_time' => 'required',
                    'end_time' => 'required',
                    'ot_time' => 'required',
                    // 'to_date' => 'required|date|after_or_equal:from_date',
                    'remarks' => 'required'
                ]
            );
            if($validate->fails()){
                return $this->respondValidatorFailed($validate);
            }

            //check min ot time in minutes
            $employee = $this->employee->find($inputData['employee_id']);
            $otRateSetup = OtRateSetup::where('organization_id', $employee->organization_id)->first();

            if(isset($otRateSetup) && !empty($otRateSetup)){
                if(isset($otRateSetup->is_min_ot_requirement) && $otRateSetup->is_min_ot_requirement == 11){
                    if($inputData['ot_time'] < $otRateSetup->min_ot_time){
                        return $this->respondWithError('Your minimum time to request OT is '. $otRateSetup->min_ot_time . ' minutes. So, you cannot request this Overtime.');
                    }
                }
            }
            //

            $inputData['nepali_date'] = date_converter()->eng_to_nep_convert($inputData['date']);
            $inputData['status'] = 1;
            $inputData['updated_by'] = auth()->user()->id;
            $otRequest = $this->overtimeRequest->update($id, $inputData);
            if (isset($otRequest)) {
                $otRequest['enable_mail'] = setting('enable_mail');
                $this->overtimeRequest->sendMailNotification($otRequest);
            }
            return $this->respond([
                'status' => true,
                'message' => 'Overtime Request Updated Successfully',
            ]);
        } catch (\Throwable $th) {
            return $this->respondInvalidQuery($th->getMessage());
        }
    }
   
    public function updateStatus(Request $request,$id)
    {
        try {
            $data = $request->all();
            $this->overtimeRequest->update($id, $data);

            $overtimeRequest = $this->overtimeRequest->find($id);
            $overtimeRequest['enable_mail'] = setting('enable_mail');
            $this->overtimeRequest->sendMailNotification($overtimeRequest);
            return $this->respond([
                'status' => true,
                'message' => 'Overtime Request Status Updated Successfully',
            ]);
        } catch (\Throwable $th) {
            return $this->respondInvalidQuery($th->getMessage());
        }
    }

    public function updateClaimStatus(Request $request,$id)
    {
        try {
            $data = $request->all();
            $this->overtimeRequest->update($id, $data);
            return $this->respond([
                'status' => true,
                'message' => 'Overtime Request Claim Status Updated Successfully',
            ]);

        } catch (\Throwable $th) {
            return $this->respondInvalidQuery($th->getMessage());
        }
    }

    public function teamRequests(Request $request)
    {
        $filter = $request->all();
        $user = auth()->user();
        $userId = $user->id;
        $usertype = $user->user_type;

        // Initialize the query for OvertimeRequest
        $overtimeRequestQuery = OvertimeRequest::query();

        // Build query for first approval
        $firstApprovalEmps = EmployeeApprovalFlow::where('first_approval_user_id', $userId)
            ->pluck('last_approval_user_id', 'employee_id')
            ->toArray();

        $overtimeRequestQuery->when(count($firstApprovalEmps) > 0, function ($query) use ($firstApprovalEmps, $usertype, $userId) {
            $query->whereIn('employee_id', array_keys($firstApprovalEmps))
                ->where(function ($query) use ($usertype, $userId, $firstApprovalEmps) {
                    $query->when($usertype == 'supervisor', function ($query) use ($userId, $firstApprovalEmps) {
                        $query->where(function ($query) use ($userId, $firstApprovalEmps) {
                            foreach ($firstApprovalEmps as $empId => $lastApproval) {
                                $query->orWhere(function ($query) use ($empId, $userId, $lastApproval) {
                                    $query->where('employee_id', $empId)
                                          ->where(function ($query) use ($userId, $lastApproval) {
                                              if (!$lastApproval) {
                                                  $query->whereIn('status', [1, 2, 3, 4]);
                                              } else {
                                                  $query->whereIn('status', [2, 3, 4]);
                                              }
                                          });
                                });
                            }
                        });
                    });
                });
        });

        // Build query for last approval
        $lastApprovalEmps = EmployeeApprovalFlow::where('last_approval_user_id', $userId)
            ->select('first_approval_user_id', 'last_approval_user_id', 'employee_id')
            ->get();

        $overtimeRequestQuery->when(count($lastApprovalEmps) > 0, function ($query) use ($lastApprovalEmps, $usertype) {
            $query->where(function ($query) use ($lastApprovalEmps, $usertype) {
                foreach ($lastApprovalEmps as $emp) {
                    $query->orWhere(function ($query) use ($emp, $usertype) {
                        $query->where('employee_id', $emp->employee_id)
                              ->where(function ($query) use ($emp, $usertype) {
                                  if (is_null($emp->first_approval_user_id)) {
                                      $query->whereIn('status', [1, 2, 3, 4]);
                                  } else {
                                      $query->whereIn('status', [2, 3, 4]);
                                  }
                              });
                    });
                }
            });
        });

        // Apply filters
        $overtimeRequestQuery->when(isset($filter['employee_id']) && $filter['employee_id'] != '', function ($query) use ($filter) {
            $query->where('employee_id', $filter['employee_id']);
        });

        $overtimeRequestQuery->when(isset($filter['date_range']), function ($query) use ($filter) {
            $filterDates = explode(' - ', $filter['date_range']);
            $query->where('from_date', '>=', $filterDates[0])
                  ->where('to_date', '<=', $filterDates[1]);
        });

        $overtimeRequestQuery->when(isset($filter['from_date_nep']) && !empty($filter['from_date_nep']), function ($query) use ($filter) {
            $query->where('from_date_nep', '>=', $filter['from_date_nep']);
        });

        $overtimeRequestQuery->when(isset($filter['to_date_nep']) && !empty($filter['to_date_nep']), function ($query) use ($filter) {
            $query->where('to_date_nep', '<=', $filter['to_date_nep']);
        });

        $overtimeRequestQuery->when(isset($filter['status']) && !empty($filter['status']), function ($query) use ($filter) {
            $query->where('status', $filter['status']);
        });

        // Fetch the results and apply sorting
        $result = $overtimeRequestQuery->with(['employee' => function ($q) {
            $q->select('id', 'first_name','middle_name','last_name');
        }])->orderBy('id', 'DESC')->get()->map(function ($approvals) use ($usertype) {
            $statusList = OvertimeRequest::STATUS;
            $approvalFlow = optional($approvals->employee)->employeeApprovalFlowRelatedDetailModel;

            if ($usertype == 'supervisor') {
                if (!$approvalFlow->last_approval_user_id || $approvals->status == 1) {
                    unset($statusList[3]);
                }

                if ($approvalFlow->first_approval_user_id == auth()->user()->id && $approvals->status == 2) {
                    unset($statusList[1], $statusList[3], $statusList[4]);
                }
                unset($statusList[5]);
            }

            $approvals->status_list = json_encode($statusList);
            return $approvals;
        });

        return $this->respond([
            'status' => true,
            'data' => $result
        ]);
    }

}
