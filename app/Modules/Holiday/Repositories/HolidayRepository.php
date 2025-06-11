<?php

namespace App\Modules\Holiday\Repositories;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Holiday\Entities\Holiday;
use App\Modules\Holiday\Entities\EventParticipant;
use App\Modules\Holiday\Entities\HolidayDetail;
use App\Modules\Notification\Entities\Notification;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Log;

class HolidayRepository implements HolidayInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = Holiday::when(true, function ($query) use ($filter) {

            if (in_array(auth()->user()->user_type, ['employee', 'supervisor', 'division_hr'])) {

                $activeEmployeeModel = optional(auth()->user());

                $empFlag = false;
                if ($activeEmployeeModel->user_type == 'employee') {
                    $query->where('status', '=', 11);
                    $empFlag = true;
                }
                $query->GetEmployeeWiseHoliday($activeEmployeeModel->userEmployer, $empFlag, $empFlag);
            }

            if (isset($filter['start']) && !empty(['start'])) {
                $query->whereHas('holidayDetail', function ($query)  use ($filter) {
                    $query->whereDate('eng_date', '>=', $filter['start']);
                });
            }

            if (isset($filter['end']) && !empty(['end'])) {
                $query->whereHas('holidayDetail', function ($query)  use ($filter) {
                    $query->whereDate('eng_date', '<=', $filter['end']);
                });
            }

            if (isset($filter['fiscal_year_id']) && !empty($filter['fiscal_year_id'])) {
                $query->where('fiscal_year_id', $filter['fiscal_year_id']);
            }

        })->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return Holiday::find($id);
    }

    public function getList()
    {
        $result = Holiday::pluck('title', 'id');
        return $result;
    }

    public function save($data)
    {
        return Holiday::create($data);
    }

    public function update($id, $data)
    {
        $result = Holiday::find($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return Holiday::destroy($id);
    }

    public function getHolidayDetails($holidayId)
    {
        return  HolidayDetail::where('holiday_id', $holidayId)->get();
    }

    public function deleteHolidayDetails($holidayId)
    {
        return  HolidayDetail::where('holiday_id', $holidayId)->delete();
    }

    public function getGenderType()
    {
        return Holiday::GENDER;
    }

    public function getReligionType()
    {
        return Holiday::RELIGION;
    }
    public function getHolidayList($filter = [])
    {
        $now = Carbon::now()->toDateString();
        // $compile_now_date = date('m-d', strtotime($now));
        $beforeDate =  date('Y-m-d', strtotime(Carbon::now() . '+ 7 days'));
        $holidays = Holiday::when(true, function ($query) use ($now, $beforeDate, $filter) {
            $query->where('status', '=', 11);

            if (auth()->user()->user_type == 'employee') {
                $activeEmployeeModel = optional(auth()->user())->userEmployer;
                $query->GetEmployeeWiseHoliday($activeEmployeeModel, true, true);
            }

            if (auth()->user()->user_type == 'supervisor' || auth()->user()->user_type == 'division_hr') {
                $activeEmployeeModel = optional(auth()->user())->userEmployer;
                $query->GetEmployeeWiseHoliday($activeEmployeeModel);
            }

            $query->whereHas('holidayDetail', function ($query)  use ($now, $beforeDate) {
                $query->whereDate('eng_date', '>=', $now);
                $query->whereDate('eng_date', '<=', $beforeDate);
            });
        })->get();

        $returnArray = [];
        foreach ($holidays as $key => $holiday) {
            foreach ($holiday->holidayDetail as $key => $value) {
                // if ($value['eng_date'] < $now) continue;
                $returnArray[] = [
                    'id' => $value['id'],
                    'title' => $value['sub_title'],
                    'date' => $value['eng_date'],
                    'type' => 'holiday'

                ];
            }
        }
        return $returnArray;
    }

    public function getLatestId()
    {
        $latestRecord = Holiday::orderBy('id', 'desc')->first();
        return $latestRecord['id'] ?? 0;
    }

    public function findAllGroupData($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $subQuery = FacadesDB::table('holidays')
            ->select(FacadesDB::raw('MAX(id) as id'))
            ->whereNotNull('group_id')
            ->groupBy('group_id');

        $result = Holiday::joinSub($subQuery, 'sub', function ($join) {
                $join->on('holidays.id', '=', 'sub.id');
            })
            ->when(true, function ($query) use ($filter) {

                if (in_array(auth()->user()->user_type, ['employee', 'supervisor', 'division_hr'])) {

                    $activeEmployeeModel = optional(auth()->user());

                    $empFlag = false;
                    if ($activeEmployeeModel->user_type == 'employee') {
                        $query->where('status', '=', 11);
                        $empFlag = true;
                    }
                    $query->GetEmployeeWiseHoliday($activeEmployeeModel->userEmployer, $empFlag, $empFlag);
                }

                if (isset($filter['start']) && !empty($filter['start'])) {
                    $query->whereHas('holidayDetail', function ($query)  use ($filter) {
                        $query->whereDate('eng_date', '>=', $filter['start']);
                    });
                }

                if (isset($filter['end']) && !empty($filter['end'])) {
                    $query->whereHas('holidayDetail', function ($query)  use ($filter) {
                        $query->whereDate('eng_date', '<=', $filter['end']);
                    });
                }

                if (isset($filter['fiscal_year_id']) && !empty($filter['fiscal_year_id'])) {
                    $query->where('fiscal_year_id', $filter['fiscal_year_id']);
                }

            })
            ->orderBy('holidays.' . $sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function getIdByGroupId($groupId)
    {
        return Holiday::select('id')->where('group_id', $groupId)->get()->toArray();
    }

    public function getHolidayBranch($organisationId, $provinceId, $districtId, $branchId)
    {
        return Holiday::where('organization_id', $organisationId)->where('province_id', $provinceId)->where('district_id', $districtId)->where('branch_id', $branchId)->orderBy('fiscal_year_id','desc')->first();
    }

    public function getHolidayInfoBYOrgIdProvinceIdDistrictId($organisationId, $provinceId, $districtId)
    {
        return Holiday::where('organization_id', $organisationId)->where('province_id', $provinceId)->where('district_id', $districtId)->orderBy('fiscal_year_id','desc')->first();
    }

    public function getHolidayBranchAll($organisationId, $provinceId, $districtId)
    {
        return Holiday::where('organization_id', $organisationId)->where('province_id', $provinceId)->where('district_id', $districtId)->orderBy('fiscal_year_id','desc')->get();
    }

    public function updateOrCreateHolidayAccordingToBranch($holidayDetails, $branch)
    {
        if($holidayDetails['branch_id'] == 0){
            return $holidayDetails->update(['branch_id' => $branch['id']]);
        }else{
            $data = [
                'fiscal_year_id' => $holidayDetails['fiscal_year_id'],
                'organization_id' => $holidayDetails['organization_id'],
                'branch_id' => $branch['id'],
                'province_id' => $holidayDetails['province_id'],
                'district_id' => $holidayDetails['district_id'],
                'group_id' => $holidayDetails['group_id'],
                'calendar_type' => $holidayDetails['calendar_type'],
                'gender_type' => $holidayDetails['gender_type'],
                'religion_type' => $holidayDetails['religion_type'],
                'status' => $holidayDetails['status'],
                'is_festival' => $holidayDetails['is_festival'],
                'apply_for_all' => $holidayDetails['apply_for_all'],
                'created_by' => $holidayDetails['created_by'],
            ];

            return Holiday::create($data);
        }

    }

    public function createHolidayDetailsAccordingToBranch($holidayId, $updateHoliday)
    {
        $data =  HolidayDetail::where('holiday_id', $holidayId)->get();

        $calendarType = $updateHoliday['calendar_type'];
        $holidayDetailArray = [];
        $dateConverter = new DateConverter();
        foreach ($data as $day) {
            $holidayDetailArray = [
                'holiday_id' => $updateHoliday['id'],
                'sub_title' => $day['sub_title'],
                'nep_date' => $calendarType == 2 ? $dateConverter->eng_to_nep_convert($day['eng_date']) : $day['nep_date'],
                'eng_date' => $calendarType == 1 ? $dateConverter->nep_to_eng_convert($day['nep_date']) : $day['eng_date'],
            ];
         HolidayDetail::create($holidayDetailArray);
        }
        return true;
    }
}
