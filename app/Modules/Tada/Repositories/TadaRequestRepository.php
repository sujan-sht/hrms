<?php

namespace App\Modules\Tada\Repositories;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Employee\Entities\EmployeeClaimRequestApprovalFlow;
use App\Modules\Tada\Entities\TadaRequest;
use App\Modules\User\Entities\User;
use File;
use DB;
use Illuminate\Support\Facades\Auth;

/**
 * TadaRequestRepository
 */
class TadaRequestRepository implements TadaRequestInterface
{
    public function findAll($limit = null, $filter, $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {

        $pending_status = 1;
        $forwarded_status = 2;
        $accepted_status = 3;
        $rejected_status = 4;
        $fully_settled = 5;
        $partially_settled = 6;

        $userId = auth()->user()->id;
        $empId = optional(User::where('id', auth()->user()->id)->first()->userEmployer)->id;

        $result = TadaRequest::when(true, function ($query) use ($filter, $empId, $userId, $pending_status, $forwarded_status, $accepted_status, $rejected_status, $fully_settled, $partially_settled) {

            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->whereHas('employee', function ($qry) use ($filter) {
                    $qry->whereHas('organizationModel', function ($q) use ($filter) {
                        $q->where('id', $filter['organization_id']);
                    });
                });
            }

            if (isset($filter['branch_id']) && !empty($filter['branch_id'])) {
                $query->whereHas('employee', function ($qry) use ($filter) {
                    $qry->whereHas('branchModel', function ($q) use ($filter) {
                        $q->where('id', $filter['branch_id']);
                    });
                });
            }

            if (isset($filter['emp_id']) && !empty($filter['emp_id'])) {
                $query->where('employee_id', $filter['emp_id']);
            }
            if (setting('calendar_type') == 'BS') {
                if (isset($filter['requested_date']) && !empty($filter['requested_date'])) {
                    $query->where('nep_request_date', $filter['requested_date']);
                }
            } else {
                if (isset($filter['requested_date']) && !empty($filter['requested_date'])) {
                    $query->where('eng_request_date', $filter['requested_date']);
                }
            }
            if (isset($filter['status']) && !empty($filter['status'])) {
                $query->where('status', $filter['status']);
            }
            if (isset($filter['title']) && !empty($filter['title'])) {
                $query->where('title', 'like', '%' . $filter['title'] . '%');
            }

            if (auth()->user()->user_type == 'employee' || auth()->user()->user_type == 'supervisor') {
                $query->where('employee_id', $empId);
            } elseif (auth()->user()->user_type == 'division_hr') {
                $query->whereHas('employee', function ($q) use ($filter) {
                    $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                });
            } elseif (auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'super_admin') {
                $query->where('employee_id', '!=', null);
            }
            //ranjan
            // elseif (auth()->user()->user_type == 'supervisor') {
            //     $firstApprovalEmps = EmployeeClaimRequestApprovalFlow::where('first_claim_approval_user_id', $userId)->pluck('employee_id')->toArray();
            //     $lastApprovalEmps = EmployeeClaimRequestApprovalFlow::where('last_claim_approval_user_id', $userId)->pluck('employee_id')->toArray();
            //     $query->orWhere('employee_id', $empId);
            //     $query->orWhereIn('employee_id', $firstApprovalEmps)->whereIn('status', [$pending_status, $forwarded_status]);
            //     $query->orWhereIn('employee_id', $lastApprovalEmps)->whereNotIn('status', [$accepted_status, $rejected_status]);
            // }
            //end
            else {
                $query->where('employee_id', $empId);
            }
        })->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return TadaRequest::with('tadaDetails')->find($id);
    }

    public function update($id, $data)
    {
        return TadaRequest::find($id)
            ->update($data);
    }

    public function save($data)
    {
        return TadaRequest::create($data);
    }

    public function delete($id)
    {
        return TadaRequest::find($id)->delete();
    }

    public function getList()
    {
        return TadaRequest::pluck('title', 'id');
    }

    public function getStatusList()
    {
        return TadaRequest::statusList();
    }

    public function getListById($id)
    {
        return TadaRequest::Where('employee_id', '=', $id)->pluck('title', 'id');
    }

    public function getEmployeeTadaRequest($limit = null)
    {
        $activeUserModel = Auth::user();
        $query = TadaRequest::query();
        $query->select('id', 'employee_id', 'title', 'eng_request_date', 'status', "eng_request_date as date", 'created_at')
            ->addSelect(DB::raw("'request' as type"))
            ->where('status', 1);

        if ($activeUserModel->user_type == 'employee') {
            $query->where('employee_id', $activeUserModel->emp_id);
        }

        if ($activeUserModel->user_type == 'supervisor') {
            $authEmpId = array(intval($activeUserModel->emp_id));
            $subordinateEmpIds = Employee::getSubordinates($activeUserModel->id);
            $empIds = array_merge($authEmpId, $subordinateEmpIds);
            $query->whereIn('employee_id', $empIds);
        }

        if ($activeUserModel->user_type == 'division_hr') {
            $query->whereHas('employee', function ($q) {
                $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            });
        }

        $result = $query->orderBy('created_at', 'DESC')->take($limit ? $limit : env('DEF_PAGE_LIMIT', 9999))->get();
        return $result;
    }


    public function uploadExcel($file)
    {
        $path = public_path() . '/uploads/tada/excels';
        if (!file_exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $imageName = $file->getClientOriginalName();
        $fileName = time() . '_' . preg_replace('[ ]', '-', $imageName);
        $file->move($path, $fileName);

        return $fileName;
    }

    public function findTeamRequest($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $userId = auth()->user()->id;

        $statusList = $this->getStatusList();
        $user = auth()->user();
        $usertype = $user->user_type;

        $firstApprovalEmps = EmployeeClaimRequestApprovalFlow::where('first_claim_approval_user_id', $userId)->pluck('last_claim_approval_user_id', 'employee_id')->toArray();
        $firstApproval = TadaRequest::with('employee')->when(true, function ($query) use ($firstApprovalEmps, $user) {
            // $query->where('organization_id', optional($user->userEmployer)->organization_id);
            $query->whereHas('employee', function ($q) use ($user) {
                $q->where('organization_id', optional($user->userEmployer)->organization_id);
            });
            $query->whereIn('employee_id', array_keys($firstApprovalEmps));
            $query->where('status', 1);
        })->get()->map(function ($approvals) use ($statusList, $usertype) {
            $approvalFlow = optional($approvals->employee)->employeeClaimRequestApprovalDetailModel;
            if ($usertype == 'supervisor') {
                if (!$approvalFlow->last_claim_approval_user_id || $approvals->status == 1) {
                    unset($statusList[3]);
                }
                // if ($approvals->status == 1) {
                //     unset($statusList[3]);
                // }
            }
            $approvals->status_list = json_encode($statusList);
            return $approvals;
        });

        $lastApprovalEmps = EmployeeClaimRequestApprovalFlow::where('last_claim_approval_user_id', $userId)->select('first_claim_approval_user_id', 'last_claim_approval_user_id', 'employee_id')->get()->toArray();

        $lastApproval = TadaRequest::when(true, function ($query) use ($lastApprovalEmps, $user) {
            $query->whereHas('employee', function ($q) use ($user) {
                $q->where('organization_id', optional($user->userEmployer)->organization_id);
            });
            $where = 'where';
            foreach ($lastApprovalEmps as $value) {

                $query->$where(function ($query) use ($value, $where) {
                    $query->where('employee_id', $value['employee_id']);
                    if (is_null($value['first_claim_approval_user_id'])) {
                        // $query->whereIn('status', [1, 2]);
                        $query->whereIn('status', [1, 2, 3, 4]);
                    } else {
                        // $query->where('status', 2);
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

        $result = $firstApproval->merge($lastApproval);
        return $result;
    }
}
