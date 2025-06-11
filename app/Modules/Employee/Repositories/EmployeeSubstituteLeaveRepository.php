<?php

namespace App\Modules\Employee\Repositories;

use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Employee\Entities\EmployeeSubstituteLeave;
use App\Modules\Employee\Entities\EmployeeSubstituteLeaveClaim;
use App\Modules\Employee\Repositories\EmployeeSubstituteLeaveInterface;
use App\Modules\Leave\Entities\LeaveType;

class EmployeeSubstituteLeaveRepository implements EmployeeSubstituteLeaveInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'desc'])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'supervisor' || $authUser->user_type == 'employee') {
            $filter['employee_id'] = $authUser->emp_id;
        }
        if ($authUser->user_type == 'division_hr') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }

        $result = EmployeeSubstituteLeave::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }

            if (isset($filter['date_range'])) {
                $filterDates = explode(' - ', $filter['date_range']);
                $query->where('date', '>=', $filterDates[0]);
                $query->where('date', '<=', $filterDates[1]);
            }

            if (isset($filter['branch_id']) && !empty($filter['branch_id'])) {
                $query->whereHas('employee', function ($qry) use ($filter) {
                    $qry->whereHas('branchModel', function ($q) use ($filter) {
                        $q->where('id', $filter['branch_id']);
                    });
                });
            }

            if (isset($filter['from_nep_date']) && !empty($filter['from_nep_date'])) {
                $query->where('nepali_date', '>=', $filter['from_nep_date']);
            }

            if (isset($filter['to_nep_date']) && !empty($filter['to_nep_date'])) {
                $query->where('nepali_date', '<=', $filter['to_nep_date']);
            }

            if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
                $query->whereHas('employee', function ($q) use ($filter) {
                    $q->where('organization_id', $filter['organization_id']);
                });
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function findOne($id)
    {
        return EmployeeSubstituteLeave::find($id);
    }

    public function create($data)
    {
        return EmployeeSubstituteLeave::create($data);
    }

    public function update($id, $data)
    {
        $model = $this->findOne($id);
        $model->update($data);

        return $model;
    }

    public function delete($id)
    {
        $model = $this->findOne($id);

        return $model->delete();
    }

    public function findTeamleaves($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $userId = auth()->user()->id;
        $statusList = EmployeeSubstituteLeave::statusList();
        $user = auth()->user();
        $usertype = $user->user_type;

        $firstApprovalEmps = EmployeeApprovalFlow::where('first_approval_user_id', $userId)->pluck('last_approval_user_id', 'employee_id')->toArray();
        // dd($firstApprovalEmps);
        $firstApproval = EmployeeSubstituteLeave::with('employee.employeeApprovalFlowRelatedDetailModel')->when(true, function ($query) use ($firstApprovalEmps) {
            $query->whereIn('employee_id', array_keys($firstApprovalEmps));
        })->get()->map(function ($approvals) use ($statusList, $usertype) {
            $approvalFlow = optional($approvals->employee)->employeeApprovalFlowRelatedDetailModel;
            if ($usertype == 'supervisor') {
                if (!$approvalFlow->last_approval_user_id || $approvals->status == 1) {
                    unset($statusList[3]);
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
                if ($usertype == 'supervisor') {
                    if ($approvals->status == 1) {
                        unset($statusList[2]);
                    } elseif ($approvals->status == 2) {
                        unset($statusList[1]);
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
        return $result;
    }

    public function findTeamClaimedleaves($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $userId = auth()->user()->id;
        $statusList = EmployeeSubstituteLeaveClaim::claimStatusList();
        $user = auth()->user();
        $usertype = $user->user_type;

        $firstApprovalEmps = EmployeeApprovalFlow::where('first_approval_user_id', $userId)->pluck('last_approval_user_id', 'employee_id')->toArray();
        // dd($firstApprovalEmps);
        $firstApproval = EmployeeSubstituteLeave::with('employee.employeeApprovalFlowRelatedDetailModel')->when(true, function ($query) use ($firstApprovalEmps) {
            $query->whereIn('employee_id', array_keys($firstApprovalEmps));
            $query->whereHas('employeeSubstituteLeaveClaim',function($q){
                $q->whereIn('claim_status',[1,2,3,4]);
            });
        })->get()->map(function ($approvals) use ($statusList, $usertype) {
            $approvalFlow = optional($approvals->employee)->employeeApprovalFlowRelatedDetailModel;
            if ($usertype == 'supervisor') {
                if (!$approvalFlow->last_approval_user_id || $approvals->status == 1) {
                    unset($statusList[3]);
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
                            $query->whereHas('employeeSubstituteLeaveClaim',function($q){
                                $q->whereIn('claim_status',[1,2,3,4]);
                            });
                        } else {
                            $query->whereHas('employeeSubstituteLeaveClaim',function($q){
                                $q->whereIn('claim_status',[2,3,4]);
                            });
                        }
                    });
                    $where = 'orWhere';
                }
            })->get()->map(function ($approvals) use ($statusList, $usertype) {
                if ($usertype == 'supervisor') {
                    if ($approvals->status == 1) {
                        unset($statusList[2]);
                    } elseif ($approvals->status == 2) {
                        unset($statusList[1]);
                    }
                }
                $approvals->status_list = json_encode($statusList);
                return $approvals;
            });
        }

        if($usertype == 'super_admin' || $usertype == 'hr'){
            $myCollectionObj = EmployeeSubstituteLeave::whereHas('employeeSubstituteLeaveClaim',function($q){
                    $q->whereIn('claim_status',[1,2,3,4]);
            })->get();
        }elseif($usertype == 'division_hr'){
            $myCollectionObj = EmployeeSubstituteLeave::whereHas('employee',function($q) use ($user){
                $q->where('organization_id',$user->userEmployer->organization_id);
            })->whereHas('employeeSubstituteLeaveClaim',function($q){
                $q->whereIn('claim_status',[1,2,3,4]);
            })->get();
        }else{
            $mergeApproval = $firstApproval->merge($lastApproval)->sortByDesc('id');
            $myCollectionObj = collect($mergeApproval);
        }

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
        return $result;
    }
    
    public function getMinDate($organizationId)
    {
        $leaveTypeModel = LeaveType::where([
            'organization_id' => $organizationId,
            'leave_year_id' => getCurrentLeaveYearId(),
            'code' => 'SUBLV',
            'status' => '11',
        ])->orderBy('id', 'desc')->first();
        $minDate = '';
        if (isset($leaveTypeModel)) {
            if (isset($leaveTypeModel->max_substitute_days) && $leaveTypeModel->max_substitute_days > 0) {
                $minDate = date('Y-m-d', strtotime("-" . $leaveTypeModel->max_substitute_days . " days", strtotime(date('Y-m-d'))));
            }
        }
        return $minDate;
    }
}
