<?php

namespace App\Modules\OrganizationalStructure\Repositories;

use App\Modules\OrganizationalStructure\Entities\OrganizationalStructure;
use App\Modules\OrganizationalStructure\Entities\OrganizationalStructureDetail;

class OrganizationalStructureRepository implements OrganizationalStructureInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = OrganizationalStructure::when(true, function ($query) use ($filter) {

            // if (in_array(auth()->user()->user_type, ['employee', 'supervisor', 'division_hr'])) {

            //     $activeEmployeeModel = optional(auth()->user());

            //     $empFlag = false;
            //     if ($activeEmployeeModel->user_type == 'employee') {
            //         $query->where('status', '=', 11);
            //         $empFlag = true;
            //     }
            //     $query->GetEmployeeWiseHoliday($activeEmployeeModel->userEmployer, $empFlag, $empFlag);
            // }

            // if (isset($filter['start']) && !empty(['start'])) {
            //     $query->whereHas('holidayDetail', function ($query)  use ($filter) {
            //         $query->whereDate('eng_date', '>=', $filter['start']);
            //     });
            // }

            // if (isset($filter['end']) && !empty(['end'])) {
            //     $query->whereHas('holidayDetail', function ($query)  use ($filter) {
            //         $query->whereDate('eng_date', '<=', $filter['end']);
            //     });
            // }

            // if (isset($filter['leave_year_id']) && !empty($filter['leave_year_id'])) {
            //     $query->where('leave_year_id', $filter['leave_year_id']);
            // }

        })->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return OrganizationalStructure::find($id);
    }

    // public function getList()
    // {
    //     $result = Holiday::pluck('title', 'id');
    //     return $result;
    // }

    public function save($data)
    {
        return OrganizationalStructure::create($data);
    }

    public function update($id, $data)
    {
        $result = OrganizationalStructure::find($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return OrganizationalStructure::destroy($id);
    }

    public function getOrgStructureDetails($orgStructureId)
    {
        return  OrganizationalStructureDetail::where('org_structure_id', $orgStructureId)->get();
    }

    public function deleteOrgStructureDetails($orgStructureId)
    {
        return  OrganizationalStructureDetail::where('org_structure_id', $orgStructureId)->delete();
    }

    // public function getHolidayList($filter = [])
    // {
    //     $now = Carbon::now()->toDateString();
    //     // $compile_now_date = date('m-d', strtotime($now));
    //     $beforeDate =  date('Y-m-d', strtotime(Carbon::now() . '+ 7 days'));
    //     $holidays = Holiday::when(true, function ($query) use ($now, $beforeDate, $filter) {
    //         $query->where('status', '=', 11);

    //         if (auth()->user()->user_type == 'employee') {
    //             $activeEmployeeModel = optional(auth()->user())->userEmployer;
    //             $query->GetEmployeeWiseHoliday($activeEmployeeModel, true, true);
    //         }

    //         if (auth()->user()->user_type == 'supervisor' || auth()->user()->user_type == 'division_hr') {
    //             $activeEmployeeModel = optional(auth()->user())->userEmployer;
    //             $query->GetEmployeeWiseHoliday($activeEmployeeModel);
    //         }

    //         $query->whereHas('holidayDetail', function ($query)  use ($now, $beforeDate) {
    //             $query->whereDate('eng_date', '>=', $now);
    //             $query->whereDate('eng_date', '<=', $beforeDate);
    //         });
    //     })->get();

    //     $returnArray = [];
    //     foreach ($holidays as $key => $holiday) {
    //         foreach ($holiday->holidayDetail as $key => $value) {
    //             // if ($value['eng_date'] < $now) continue;
    //             $returnArray[] = [
    //                 'id' => $value['id'],
    //                 'title' => $value['sub_title'],
    //                 'date' => $value['eng_date'],
    //                 'type' => 'holiday'

    //             ];
    //         }
    //     }
    //     return $returnArray;
    // }

}
