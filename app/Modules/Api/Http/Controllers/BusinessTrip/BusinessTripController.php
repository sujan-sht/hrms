<?php

namespace App\Modules\Api\Http\Controllers\BusinessTrip;

use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\BusinessTrip\Entities\BusinessTrip;
use App\Modules\BusinessTrip\Entities\BusinessTripAllowanceSetup;
use App\Modules\BusinessTrip\Repositories\BusinessTripInterface;
use App\Modules\Employee\Entities\EmployeeBusinessTripApprovalFlow;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\User\Entities\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessTripController extends ApiController
{
    private $businessTrip;
    private $organization;
    public function __construct(
        BusinessTripInterface $businessTrip,
        OrganizationInterface $organization
    ) {
        $this->businessTrip = $businessTrip;
        $this->organization = $organization;

    }

    public function getDropdown()
    {
        try {
            $statusList = BusinessTrip::STATUS;
            $claimStatusList = BusinessTrip::CLAIM_STATUS;
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
        $businessTripModel  = BusinessTrip::query();
        $businessTripModel->when(true, function ($query) use ($filter) {
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
                $query->where('from_date', '>=', $filterDates[0]);
                $query->where('to_date', '<=', $filterDates[1]);
            }

            if (isset($filter['from_date_nep']) && !empty($filter['from_date_nep'])) {
                $query->where('from_date_nep', '>=', $filter['from_date_nep']);
            }

            if (isset($filter['to_date_nep']) && !empty($filter['to_date_nep'])) {
                $query->where('to_date_nep', '<=', $filter['to_date_nep']);
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
        $result = $businessTripModel->orderBy('id', 'DESC')->get();
        return $this->respond([
            'status' => true,
            'data' => $result
        ]);
    }

    public function teamRequests(Request $request)
    {
        $filter = $request->all();
        $user = auth()->user();
        $userId = $user->id;
        $usertype = $user->user_type;

        // Initialize the query for BusinessTrip
        $businessTripQuery = BusinessTrip::query();

        // Build query for first approval
        $firstApprovalEmps = EmployeeBusinessTripApprovalFlow::where('first_approval', $userId)
            ->pluck('last_approval', 'employee_id')
            ->toArray();

        $businessTripQuery->when(count($firstApprovalEmps) > 0, function ($query) use ($firstApprovalEmps, $usertype, $userId) {
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
        $lastApprovalEmps = EmployeeBusinessTripApprovalFlow::where('last_approval', $userId)
            ->select('first_approval', 'last_approval', 'employee_id')
            ->get();

        $businessTripQuery->when(count($lastApprovalEmps) > 0, function ($query) use ($lastApprovalEmps, $usertype) {
            $query->where(function ($query) use ($lastApprovalEmps, $usertype) {
                foreach ($lastApprovalEmps as $emp) {
                    $query->orWhere(function ($query) use ($emp, $usertype) {
                        $query->where('employee_id', $emp->employee_id)
                              ->where(function ($query) use ($emp, $usertype) {
                                  if (is_null($emp->first_approval)) {
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
        $businessTripQuery->when(isset($filter['employee_id']) && $filter['employee_id'] != '', function ($query) use ($filter) {
            $query->where('employee_id', $filter['employee_id']);
        });

        $businessTripQuery->when(isset($filter['date_range']), function ($query) use ($filter) {
            $filterDates = explode(' - ', $filter['date_range']);
            $query->where('from_date', '>=', $filterDates[0])
                  ->where('to_date', '<=', $filterDates[1]);
        });

        $businessTripQuery->when(isset($filter['from_date_nep']) && !empty($filter['from_date_nep']), function ($query) use ($filter) {
            $query->where('from_date_nep', '>=', $filter['from_date_nep']);
        });

        $businessTripQuery->when(isset($filter['to_date_nep']) && !empty($filter['to_date_nep']), function ($query) use ($filter) {
            $query->where('to_date_nep', '<=', $filter['to_date_nep']);
        });

        $businessTripQuery->when(isset($filter['status']) && !empty($filter['status']), function ($query) use ($filter) {
            $query->where('status', $filter['status']);
        });

        // Fetch the results and apply sorting
        $result = $businessTripQuery->with(['employee' => function ($q) {
            $q->select('id', 'first_name','middle_name','last_name');
        }])->orderBy('id', 'DESC')->get()->map(function ($approvals) use ($usertype) {
            $statusList = BusinessTrip::STATUS;
            $approvalFlow = optional($approvals->employee)->employeeBusinessTripApprovalDetailModel;

            if ($usertype == 'supervisor') {
                if (!$approvalFlow->last_approval || $approvals->status == 1) {
                    unset($statusList[3]);
                }

                if ($approvalFlow->first_approval == auth()->user()->id && $approvals->status == 2) {
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

    public function store(Request $request)
    {
        $inputData = $request->all();

        try {
            $validate = Validator::make(
                $inputData,
                [
                    'title' => 'required',
                    'from_date' => 'required',
                    'to_date' => 'required|date|after_or_equal:from_date',
                ]
            );
            if($validate->fails()){
                return $this->respondValidatorFailed($validate);
            }
            $inputData['employee_id'] = auth()->user()->emp_id;
            $inputData['from_date_nep'] = date_converter()->eng_to_nep_convert($inputData['from_date']);
            $inputData['to_date_nep'] = date_converter()->eng_to_nep_convert($inputData['to_date']);

            // Check for existing date range conflict
            $conflict = BusinessTrip::where('employee_id', $inputData['employee_id'])
            ->where('status', '!=', 4)
                ->where(function($query) use ($inputData) {
                    $query->whereBetween('from_date', [$inputData['from_date'], $inputData['to_date']])
                        ->orWhereBetween('to_date', [$inputData['from_date'], $inputData['to_date']])
                        ->orWhere(function($query) use ($inputData) {
                            $query->where('from_date', '<=', $inputData['from_date'])
                                    ->where('to_date', '>=', $inputData['to_date']);
                        });
                })
                ->exists();

            if ($conflict) {
                return $this->respondWithError('The employee already has a business trip scheduled within this date range.');
            }


            $startDate = Carbon::parse($inputData['from_date']);
            $endDate = Carbon::parse($inputData['to_date']);
            $inputData['status'] = 1;
            $inputData['request_days'] = $startDate->diffInDays($endDate) + 1;
            $inputData['eligible_amount'] = 0;
            $model = BusinessTripAllowanceSetup::where('employee_id', $inputData['employee_id'])->first();
            if (isset($model) && isset($model->per_day_allowance) && $model->per_day_allowance > 0) {
                $inputData['eligible_amount'] = $inputData['request_days'] * $model->per_day_allowance;
            }
            $inputData['created_by'] = auth()->user()->id;
            $trip = $this->businessTrip->save($inputData);
            if (isset($trip)) {
                $trip['enable_mail'] = setting('enable_mail');
                $this->businessTrip->sendMailNotification($trip);
            }
            return $this->respond([
                'status' => true,
                'message' => 'Business Trip Request Created Successfully',
            ]);
        } catch (\Throwable $th) {
            return $this->respondInvalidQuery($th->getMessage());
        }
    }

    public function view($id)
    {
        try{
            $trip = BusinessTrip::find($id);

            return $this->respond([
                'status' => true,
                'data' => $trip
            ]);

        }catch (\Throwable $th) {
            return $this->respondInvalidQuery($th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            $validate = Validator::make(
                $data,
                [
                    'title' => 'required',
                    'from_date' => 'required',
                    'to_date' => 'required'
                ]
            );
            if($validate->fails()){
                return $this->respondValidatorFailed($validate);
            }
            $data['employee_id'] = auth()->user()->emp_id;
            $data['from_date_nep'] = date_converter()->eng_to_nep_convert($data['from_date']);
            $data['to_date_nep'] =date_converter()->eng_to_nep_convert($data['to_date']);

            $conflict = BusinessTrip::where('employee_id', $data['employee_id'])
            ->where('status', '!=', 4)
            ->where('id', '!=', $id)
            ->where(function($query) use ($data) {
                $query->whereBetween('from_date', [$data['from_date'], $data['to_date']])
                    ->orWhereBetween('to_date', [$data['from_date'], $data['to_date']])
                    ->orWhere(function($query) use ($data) {
                        $query->where('from_date', '<=', $data['from_date'])
                              ->where('to_date', '>=', $data['to_date']);
                    });
            })
            ->exists();

            if ($conflict) {
                return $this->respondWithError('The employee already has a business trip scheduled within this date range.');
            }


            $startDate = Carbon::parse($data['from_date']);
            $endDate = Carbon::parse($data['to_date']);
            $data['request_days'] = $startDate->diffInDays($endDate) + 1;
            $data['eligible_amount'] = 0;
            $model = BusinessTripAllowanceSetup::where('employee_id', $data['employee_id'])->first();
            if (isset($model) && isset($model->per_day_allowance) && $model->per_day_allowance > 0) {
                $data['eligible_amount'] = $data['request_days'] * $model->per_day_allowance;
            }
            $this->businessTrip->update($id, $data);
            return $this->respond([
                'status' => true,
                'message' => 'Business Trip Request Updated Successfully',
            ]);
        } catch (\Throwable $th) {
            return $this->respondInvalidQuery($th->getMessage());
        }
    }

    public function updateStatus(Request $request,$id)
    {
        try {
            $data = $request->all();
            $this->businessTrip->update($id, $data);

            $businessTrip = $this->businessTrip->find($id);
            $businessTrip['enable_mail'] = setting('enable_mail');
            $this->businessTrip->sendMailNotification($businessTrip);
            return $this->respond([
                'status' => true,
                'message' => 'Business Trip Status Updated Successfully',
            ]);
        } catch (\Throwable $th) {
            return $this->respondInvalidQuery($th->getMessage());
        }
    }

    public function updateClaimStatus(Request $request,$id)
    {
        try {
            $data = $request->all();
            $this->businessTrip->update($id, $data);
            return $this->respond([
                'status' => true,
                'message' => 'Business Trip Request Claim Status Updated Successfully',
            ]);

        } catch (\Throwable $th) {
            return $this->respondInvalidQuery($th->getMessage());
        }
    }

}
