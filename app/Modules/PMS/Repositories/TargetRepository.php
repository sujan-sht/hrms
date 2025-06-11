<?php

namespace App\Modules\PMS\Repositories;

use App\Modules\Employee\Entities\EmployeeAppraisalApprovalFlow;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\PMS\Entities\Kpi;
use App\Modules\PMS\Entities\Kra;
use App\Modules\PMS\Entities\PmsEmployee;
use App\Modules\PMS\Entities\Target;
use App\Modules\PMS\Entities\TargetAchievement;

class TargetRepository implements TargetInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = Target::when(true, function ($query) use ($filter) {
            if (isset($filter['fiscal_year_id']) && !empty($filter['fiscal_year_id'])) {
                $query->where('fiscal_year_id', $filter['fiscal_year_id']);
            }
            if (isset($filter['kra_id']) && !empty($filter['kra_id'])) {
                $query->where('kra_id', $filter['kra_id']);
            }
            if (isset($filter['kpi_id']) && !empty($filter['kpi_id'])) {
                $query->where('kpi_id', $filter['kpi_id']);
            }

            if (isset($filter['frequency']) && !empty($filter['frequency'])) {
                $query->where('frequency', 'like', '%' . $filter['frequency'] . '%');
            }

            if (isset($filter['title']) && !empty($filter['title'])) {
                $query->where('title', 'like', '%' . $filter['title'] . '%');
            }
            if (auth()->user()->user_type == 'division_hr') {
                $query->whereHas('kraInfo', function ($qry) {
                    $qry->where('division_id', optional(auth()->user()->userEmployer)->organization_id);
                });
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return Target::find($id);
    }

    public function create($data)
    {
        return Target::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return Target::destroy($id);
    }

    public function KpiData($kpi_id)
    {
        return Kpi::where('id', $kpi_id)->pluck('title', 'id');
    }

    public function getTargetDetails($id)
    {
        return Target::where('id', $id)->get();
    }

    public function findTargetDetails($target_id, $quarter)
    {
        return TargetAchievement::where('target_id', $target_id)->where('quarter', $quarter)->first();
    }
    public function findTargetDetailsByEmployee($employee_id, $quarter)
    {
        // return TargetAchievement::where('employee_id', $employee_id)->get();

        $query = TargetAchievement::query();
        $query->where('employee_id', $employee_id);

        if (isset($quarter) && !empty($quarter)) {
            $query->where('quarter', $quarter);
        }
        $result = $query->get()->groupBy('target_id');
        return $result;
    }

    //set target value
    public function storeAchievedValue($data)
    {
        return TargetAchievement::create($data);
    }
    //

    //set achieved value
    public function updateAchievedValue($id, $data)
    {
        $result = TargetAchievement::find($id);
        return $result->update($data);
    }
    //

    //report
    public function findReport($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = Kra::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['department_id']) && !empty($filter['department_id'])) {
                $query->where('department_id', $filter['department_id']);
            }
            if (isset($filter['division_id']) && !empty($filter['division_id'])) {
                $query->where('division_id', $filter['division_id']);
            }
            // if (isset($filter['fiscal_year_id']) && !empty($filter['fiscal_year_id'])) {
            //     $query->whereHas('targetModel', function ($qry) use ($filter) {
            //         $qry->where('fiscal_year_id', $filter['fiscal_year_id']);
            //     });
            //     // $query->whereIn('kra_id', $filter['kra_id']);
            // }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));
        return $result;
    }

    public function targetDetailKpiwise($kpi_id)
    {
        return Target::where('kpi_id', $kpi_id)->first();
    }

    public function findTargetAchievement($id)
    {
        return TargetAchievement::find($id);
    }


    public function updateTargetValues($id, $data)
    {
        $result = $this->findTargetAchievement($id);
        return $result->update($data);
    }

    // public function deleteTarget($kpi_id)
    // {
    //     return Target::where('kpi_id', $kpi_id)->delete();
    // }

    public function deleteTargetAchievement($kpi_id, $employee_id)
    {
        return TargetAchievement::where('kpi_id', $kpi_id)->where('employee_id', $employee_id)->delete();
    }

    public function getEmployeePMSList($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        if (auth()->user()->user_type == 'supervisor') {
            $filter['emp_id'] = EmployeeAppraisalApprovalFlow::where('first_approval', auth()->user()->id)->pluck('employee_id');
            $filter['status'] = [1, 2, 3, 4];
        } elseif (auth()->user()->user_type == 'division_hr') {
            $filter['org_id'] = optional(auth()->user()->userEmployer)->organization_id;
        }
        $result = PmsEmployee::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['org_id']) && !empty($filter['org_id'])) {
                $query->whereHas('employeeModel', function ($qry) use ($filter) {
                    $qry->where('organization_id', $filter['org_id']);
                });
            }

            if (isset($filter['branch_id']) && !empty($filter['branch_id'])) {
                $query->whereHas('employeeModel', function ($qry) use ($filter) {
                    $qry->where('branch_id', $filter['branch_id']);
                });
            }
            if (isset($filter['emp_id']) && !empty($filter['emp_id'])) {
                $query->whereIn('employee_id', $filter['emp_id']);
            }
            if (isset($filter['status']) && !empty($filter['status'])) {
                $query->whereIn('status', $filter['status']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));
        return $result;
    }

    public function findPMSEmployee($id)
    {
        return PmsEmployee::find($id);
    }

    public function updateStatusPMSEmployee($id, $data)
    {
        $result = $this->findPMSEmployee($id);
        return $result->update($data);
    }

    public function employeeTargetReportQuarterwise($employee_id)
    {
        $data = TargetAchievement::where('employee_id', $employee_id)->get();
        $results = $data->groupBy('quarter')->map(function ($items) {
            $totalTargetValue = $items->sum('target_value');
            $totalAchievedValue = $items->sum('achieved_value');
            $totalAchievedPercent = $items->sum('achieved_percent');
            $totalScore = $items->sum('score');
            $totalResponse = $items->count();

            return [
                'totalTargetValue' => $totalTargetValue,
                'totalAchievedValue' => $totalAchievedValue,
                'totalAvgAchievementPerc' => $totalAchievedPercent / $totalResponse,
                'totalAvgScore' => $totalScore / $totalResponse
            ];
        });
        return $results;
    }
}
