<?php

namespace App\Modules\Leave\Repositories;

use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Leave\Entities\LeaveTypeLevel;
use App\Modules\Leave\Entities\LeaveTypeDepartment;
use App\Modules\Leave\Entities\LeaveTypeJobType;

class LeaveTypeRepository implements LeaveTypeInterface
{
    public function getList($filter = [])
    {
        $models = LeaveType::where('status', 11);
        if (auth()->user()->user_type != 'super_admin' && auth()->user()->user_type != 'hr') {
            $models->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
        }
        if (isset($filter['organization_id'])) {
            $models->where('organization_id', $filter['organization_id']);
        }

        if (isset($filter['leave_year_id'])) {
            $models->where('leave_year_id', $filter['leave_year_id']);
        }

        if (isset($filter['leave_type'])) {
            $models->where('leave_type', $filter['leave_type']);
        }

        return $models->pluck('name', 'id');
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }

        $result = LeaveType::with('employeeLeave.employeeModel')->when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['leave_year_id']) && !empty($filter['leave_year_id'])) {
                $query->where('leave_year_id', $filter['leave_year_id']);
            }
            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }
            if (isset($filter['encashable_status']) && !empty($filter['encashable_status'])) {
                $query->where('encashable_status', $filter['encashable_status']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));
        // dd($result->toArray());
        return $result;
    }

    public function findOne($id)
    {
        return LeaveType::find($id);
    }
    public function findFromCode($code, $org)
    {
        return LeaveType::where('code', $code)->where('organization_id', $org)->where('leave_year_id', getCurrentLeaveYearId())->first();
    }

    public function create($data)
    {
        $model = LeaveType::create($data);
        if ($model) {

            $this->saveLeaveTypeDepartments($model->id, $data);
            $this->saveLeaveTypeLevels($model->id, $data);
            $this->saveLeaveTypeJobTypes($model->id, $data);
        }

        return $model;
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);
        $flag = $result->update($data);

        if ($flag) {
            $this->deleteLeaveTypeDepartments($id);
            $this->saveLeaveTypeDepartments($id, $data);
            $this->deleteLeaveTypeLevels($id);
            $this->saveLeaveTypeLevels($id, $data);

            $this->deleteLeaveTypeJobTypes($id);
            $this->saveLeaveTypeJobTypes($id, $data);
        }

        return $flag;
    }

    public function delete($id)
    {
        $flag = LeaveType::destroy($id);

        if ($flag) {
            $this->deleteLeaveTypeDepartments($id);
            $this->deleteLeaveTypeLevels($id);
            $this->deleteLeaveTypeJobTypes($id);
            $this->deleteEmployeeLeave($id);
            $this->deleteEmployeeLeaveOpening($id);
            $this->deleteLeave($id);
        }

        return $flag;
    }

    public function upload($file)
    {
        // $imageName = $file->getClientOriginalName();
        // $fileName = time() . '-' . preg_replace('[ ]', '-', $imageName);
        // $file->move(public_path() . '/' . LeaveType::IMAGE_PATH, $fileName);

        // return $fileName;
    }

    public function getLeaveTypeName($leave_type_id)
    {
        return LeaveType::where('id', $leave_type_id)->pluck('name')->first();
    }

    public function getLeaveTypes($organization_id, $leave_year_id, $gender, $marital_status, $params = null)
    {
        $filter = $params;
        $filter['organization_id'] = $organization_id;
        $filter['leave_year_id'] = $leave_year_id;

        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }

        return LeaveType::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization_id']) && !is_null($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }
            if (isset($filter['leave_year_id']) && !is_null($filter['leave_year_id'])) {
                $query->where('leave_year_id', $filter['leave_year_id']);
            }
            if (isset($filter['department_id']) && !is_null($filter['department_id'])) {
                $query->whereHas('departments', function ($qry) use ($filter) {
                    $qry->where('department_id', $filter['department_id']);
                });
            }
            if (isset($filter['level_id']) && !is_null($filter['level_id'])) {
                $query->whereHas('levels', function ($qry) use ($filter) {
                    $qry->where('level_id', $filter['level_id']);
                });
            }
        })
            ->where('status', 11)
            ->where('gender', null)
            ->where('marital_status', null)
            ->orWhere('gender', $gender)
            ->where('marital_status', $marital_status)
            ->get();
    }

    public function getLeaveTypesFromOrganization($organization_id, $leave_year_id, $params = null)
    {
        $filter = $params;
        $filter['organization_id'] = $organization_id;
        $filter['leave_year_id'] = $leave_year_id;
        // dd($leave_year_id,$filter);

        return LeaveType::with('departments', 'levels')->where('status', 11)
            ->where('organization_id', $filter['organization_id'])
            ->where('leave_year_id', $filter['leave_year_id'])
            ->when(array_keys($filter, true), function ($query) use ($filter) {

                if (isset($filter['gender']) && !is_null($filter['gender'])) {
                    $query->where(function ($query) use ($filter) {
                        $query->where('gender', $filter['gender']);
                        $query->orWhere('gender', null);
                    });
                }
                // dd($filter);

                if (isset($filter['marital_status']) && !is_null($filter['marital_status'])) {
                    $query->where(function ($query) use ($filter) {
                        $query->where('marital_status', $filter['marital_status']);
                        $query->orWhere('marital_status', null);
                    });
                }

                // if (isset($filter['job_type']) && !is_null($filter['job_type'])) {
                //     $query->where(function ($query) use ($filter) {
                //         $query->where('job_type', $filter['job_type']);
                //         $query->orWhere('job_type', 100);
                //     });
                // }

                if (isset($filter['job_type']) && !is_null($filter['job_type'])) {
                    $query->where(function ($query) use ($filter) {
                        $query->whereHas('jobTypes', function ($qry) use ($filter) {
                            $qry->where('job_type_id', $filter['job_type']);
                            // $qry->orWhereNull('job_type_id');
                        });
                    });
                }

                if (isset($filter['level_id']) && !is_null($filter['level_id'])) {
                    $query->where(function ($query) use ($filter) {
                        $query->whereHas('levels', function ($qry) use ($filter) {
                            $qry->where('level_id', $filter['level_id']);
                            $qry->orWhereNull('level_id');
                        });
                    });
                }

                if (isset($filter['department_id']) && !is_null($filter['department_id'])) {
                    $query->where(function ($query) use ($filter) {
                        $query->whereHas('departments', function ($qry) use ($filter) {
                            $qry->where('department_id', $filter['department_id']);
                        });
                    });
                }
            })
            ->get();
    }

    public function getAllLeaveTypes($organization_id, $leave_year_id)
    {
        return LeaveType::with('departments')->where('status', 11)->where('organization_id', $organization_id)->where('leave_year_id', $leave_year_id)->get();
    }

    /**
     *
     */
    public function saveLeaveTypeDepartments($leaveTypeId, $data)
    {
        if (isset($data['departmentArray'])) {
            foreach ($data['departmentArray'] as $departmentId) {
                $model = LeaveTypeDepartment::where('leave_type_id', $leaveTypeId)->where('department_id', $departmentId)->first();
                if (!$model) {
                    $model = new LeaveTypeDepartment();
                }

                $model->leave_type_id = $leaveTypeId;
                $model->department_id = $departmentId;
                $model->save();
            }
        }

        return true;
    }

    public function deleteLeaveTypeDepartments($leaveTypeId)
    {
        return LeaveTypeDepartment::where('leave_type_id', $leaveTypeId)->delete();
    }

    public function deleteEmployeeLeave($leaveTypeId)
    {
        return EmployeeLeave::where('leave_type_id', $leaveTypeId)->delete();
    }

    public function deleteEmployeeLeaveOpening($leaveTypeId)
    {
        return EmployeeLeaveOpening::where('leave_type_id', $leaveTypeId)->delete();
    }

    public function deleteLeave($leaveTypeId)
    {
        return Leave::where('leave_type_id', $leaveTypeId)->delete();
    }

    /**
     *
     */
    public function saveLeaveTypeLevels($leaveTypeId, $data)
    {
        if (isset($data['levelArray'])) {
            foreach ($data['levelArray'] as $levelId) {
                $model = LeaveTypeLevel::where('leave_type_id', $leaveTypeId)->where('level_id', $levelId)->first();
                if (!$model) {
                    $model = new LeaveTypeLevel();
                }

                $model->leave_type_id = $leaveTypeId;
                $model->level_id = $levelId;
                $model->save();
            }
        }

        return true;
    }

    public function saveLeaveTypeJobTypes($leaveTypeId, $data)
    {
        if (isset($data['jobTypeArray'])) {
            foreach ($data['jobTypeArray'] as $jobTypeId) {
                $model = LeaveTypeJobType::where('leave_type_id', $leaveTypeId)->where('job_type_id', $jobTypeId)->first();
                if (!$model) {
                    $model = new LeaveTypeJobType();
                }

                $model->leave_type_id = $leaveTypeId;
                $model->job_type_id = $jobTypeId;
                $model->save();
            }
        }

        return true;
    }

    public function deleteLeaveTypeLevels($leaveTypeId)
    {
        return LeaveTypeLevel::where('leave_type_id', $leaveTypeId)->delete();
    }

    public function deleteLeaveTypeJobTypes($leaveTypeId)
    {
        return LeaveTypeJobType::where('leave_type_id', $leaveTypeId)->delete();
    }

    public function getEmpListFromLeaveType($id, $filter = [])
    {
        $leaveTypeModel = LeaveType::with([
            'employeeLeave.employeeModel' => function ($query) {
            },
            'employeeLeave' => function ($query) use ($filter) {
                if (isset($filter['valid']) && !empty($filter['valid'])) {
                    $query->where('is_valid', $filter['valid']);
                }
            }
        ])->findOrfail($id);
        $empArray = [];

        foreach ($leaveTypeModel['employeeLeave'] as $key => $empLeave) {
            $empArray[] = [
                'emp_id' =>  $empLeave['employeeModel']->id,
                'name' =>  $empLeave['employeeModel']->full_name,
                'organization' => optional($empLeave['employeeModel']->organizationModel)->name,
                'gender' => $empLeave['employeeModel']->gender,
                'marital_status' => $empLeave['employeeModel']->marital_status,
                'contract_type' => optional($empLeave['employeeModel']->payrollRelatedDetailModel)->contract_type ? LeaveType::CONTRACT[optional($empLeave['employeeModel']->payrollRelatedDetailModel)->contract_type] : '',
                'job_type' => optional($empLeave['employeeModel']->payrollRelatedDetailModel)->job_type ?  LeaveType::JOB_TYPE[optional($empLeave['employeeModel']->payrollRelatedDetailModel)->job_type] : '',
                'leave_remaining' => $empLeave->leave_remaining,
                'is_valid' => $empLeave->is_valid == 11 ? 'Yes' : 'No'
            ];
        }

        return $empArray;
    }
}
