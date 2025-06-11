<?php

namespace App\Modules\Holiday\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Branch\Entities\Branch;
use App\Modules\Holiday\Entities\HolidayDetail;
use App\Modules\User\Repositories\UserInterface;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Holiday\Http\Requests\HolidayRequest;
use App\Modules\Holiday\Repositories\HolidayInterface;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\FiscalYearSetup\Repositories\FiscalYearSetupInterface;
use App\Modules\Holiday\Entities\Holiday;
use App\Modules\Setting\Entities\District;
use App\Modules\Setting\Entities\ProvincesDistrict;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class HolidayController extends Controller
{
    protected $holiday;
    /**
     * @var UserInterface
     */
    protected $user;
    protected $fiscalYearSetup;
    protected $dropdownObj;
    protected $organizationObj;
    protected $branchObj;

    public function __construct(
        HolidayInterface $holiday,
        UserInterface $user,
        FiscalYearSetupInterface $fiscalYearSetup,
        DropdownInterface $dropdownObj,
        OrganizationInterface $organizationObj,
        BranchInterface $branchObj
    ) {
        $this->holiday = $holiday;
        $this->user = $user;
        $this->fiscalYearSetup = $fiscalYearSetup;
        $this->dropdownObj = $dropdownObj;
        $this->organizationObj = $organizationObj;
        $this->branchObj = $branchObj;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $search = $request->all();
        $search['creator'] = 'admin';
        $data['holiday'] = $holiday = $this->holiday->findAllGroupData(20, $search);
        $groupId = $holiday->pluck('group_id');
        $data['groupData'] = $this->getManageDataByGroupId($groupId);
        $data['fiscalYearList'] = $this->fiscalYearSetup->getFiscalYearList();
        return view('holiday::holiday.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['is_edit'] = false;
        $data['users'] = [];
        $data['managedGroupIds'] = [];
        $data['districtList'] = [];
        $data['fiscalYearList'] = $this->fiscalYearSetup->getFiscalYearList();
        $data['currentFiscalyear'] = $this->fiscalYearSetup->getCurrentFiscalYear();
        // $data['gender'] = $this->dropdownObj->getFieldBySlug('gender');
        $data['organizationList'] = $this->organizationObj->getList();
        $data['branchList'] = $this->branchObj->getList();
        $data['gender_type'] = $this->holiday->getGenderType();
        $data['religion_type'] = $this->holiday->getReligionType();
        $data['calendar_type'] = [1 => 'BS', 2 => 'AD'];
        $data['holidayDetail'] = [];
        $data['province'] = $province = ProvincesDistrict::select('id', 'title')->groupBy('id', 'title')->pluck('title', 'id');
        $data['districtList'] = District::select('id', 'district_name')->pluck('district_name', 'id');
        return view('holiday::holiday.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */

    public function store(Request $request)
    {
        $inputData = $request->all();
        try {
            $id = $this->holiday->getLatestId() + 1;
            $inputData['group_id'] = $id;
            if ($inputData['apply_for_all'] == 10) {

                $getbranchId = $this->getbranchId($inputData);
                if (!count($getbranchId)) {
                    toastr()->error('No Branch Found in the Selected District');
                    return redirect(route('holiday.index'));
                }
                $inputData['branchId'] = $getbranchId['branch_id'];
                foreach ($inputData['branchId'] as $key => $branchId) {
                    $inputData['branch_id'] = $branchId;
                    $inputData['province_id'] = $getbranchId['province_id'][$key];
                    $inputData['district_id'] = $getbranchId['district_id'][$key];
                    $holiday = $this->holiday->save($inputData);

                    $calendarType = $inputData['calendar_type'];
                    $holidayDetailArray = [];
                    $dateConverter = new DateConverter();
                    foreach ($inputData['holiday_days'] as $day) {
                        $holidayDetailArray = [
                            'holiday_id' => $holiday->id,
                            'sub_title' => $day['day'],
                            'nep_date' => $calendarType == 2 ? $dateConverter->eng_to_nep_convert($day['eng_date']) : $day['nep_date'],
                            'eng_date' => $calendarType == 1 ? $dateConverter->nep_to_eng_convert($day['nep_date']) : $day['eng_date'],
                        ];

                        HolidayDetail::create($holidayDetailArray);
                    }
                }
            } else {
                $inputData['province_id'] = null;
                $inputData['district_id'] = null;
                $holiday = $this->holiday->save($inputData);
                $calendarType = $inputData['calendar_type'];
                $holidayDetailArray = [];
                $dateConverter = new DateConverter();
                foreach ($inputData['holiday_days'] as $day) {
                    $holidayDetailArray = [
                        'holiday_id' => $holiday->id,
                        'sub_title' => $day['day'],
                        'nep_date' => $calendarType == 2 ? $dateConverter->eng_to_nep_convert($day['eng_date']) : $day['nep_date'],
                        'eng_date' => $calendarType == 1 ? $dateConverter->nep_to_eng_convert($day['nep_date']) : $day['eng_date'],
                    ];

                    HolidayDetail::create($holidayDetailArray);
                }
            }


            toastr()->success('Holiday Created Successfully');
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something went wrong');
        }
        return redirect(route('holiday.index'));
    }

    // public function storeBackup(Request $request)
    // {
    //     $inputData = $request->all();
    //     DB::beginTransaction();
    //     try {

    //         $organizationIds = $request->organization_id;

    //         foreach ($organizationIds as $key=>$organization) {

    //             if(isset($request->branchId)){
    //             $branchIds = $request->branchId[$key];

    //                 foreach($branchIds as $branchId){

    //                     $inputData['branch_id'] = $branchId;
    //                     $inputData['organization_id'] = $request->organization_id[$key];
    //                     $holiday = $this->holiday->save($inputData);
    //                     $calendarType = $inputData['calendar_type'];
    //                     $holidayDetailArray = [];
    //                     $dateConverter = new DateConverter();
    //                     foreach ($inputData['holiday_days'] as $day) {
    //                         $holidayDetailArray = [
    //                             'holiday_id' => $holiday->id,
    //                             'sub_title' => $day['day'],
    //                             'nep_date' => $calendarType == 2 ? $dateConverter->eng_to_nep_convert($day['eng_date']) : $day['nep_date'],
    //                             'eng_date' => $calendarType == 1 ? $dateConverter->nep_to_eng_convert($day['nep_date']) : $day['eng_date'],
    //                         ];

    //                         HolidayDetail::create($holidayDetailArray);
    //                         // $holiday->holidayDetail()->save($holidayDetailArray);
    //                     }
    //                 }
    //             }else{
    //                 $inputData['organization_id'] = $request->organization_id[$key];
    //                 $inputData['province_id'] = null;
    //                 $inputData['district_id'] = null;
    //                 $holiday = $this->holiday->save($inputData);

    //                 $calendarType = $inputData['calendar_type'];
    //                 $holidayDetailArray = [];
    //                 $dateConverter = new DateConverter();
    //                 foreach ($inputData['holiday_days'] as $day) {
    //                     $holidayDetailArray = [
    //                         'holiday_id' => $holiday->id,
    //                         'sub_title' => $day['day'],
    //                         'nep_date' => $calendarType == 2 ? $dateConverter->eng_to_nep_convert($day['eng_date']) : $day['nep_date'],
    //                         'eng_date' => $calendarType == 1 ? $dateConverter->nep_to_eng_convert($day['nep_date']) : $day['eng_date'],
    //                     ];

    //                     HolidayDetail::create($holidayDetailArray);
    //                     // $holiday->holidayDetail()->save($holidayDetailArray);
    //                 }
    //             }


    //         }
    //         DB::commit();

    //         toastr()->success('Holiday Created Successfully');
    //     } catch (\Throwable $e) {
    //         throw $e;
    //         toastr()->error('Something went wrong');
    //     }
    //     return redirect(route('holiday.index'));
    // }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        abort('404');
        $data['holiday'] = $this->holiday->find($id);
        $data['holidayDetails'] = $this->holiday->getHolidayDetails($id);
        return view('holiday::holiday.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['is_edit'] = true;
        $data['districtList'] = [];
        $data['holiday'] = $holiday = $this->holiday->find($id);
        $data['holidayDetails'] = $this->holiday->getHolidayDetails($id);
        $data['gender_type'] = $this->holiday->getGenderType();
        $data['religion_type'] = $this->holiday->getReligionType();
        $data['fiscalYearList'] = $this->fiscalYearSetup->getFiscalYearList();
        $data['currentFiscalyear'] = $data['holiday']->fiscal_year_id;
        $data['calendar_type'] = [1 => 'BS', 2 => 'AD'];
        $data['organizationList'] = $this->organizationObj->getList();
        // $data['branchs'] = $this->branchObj->branchListOrganizationwise($data['holiday']->organization_id);
        $data['province'] = $province = ProvincesDistrict::select('id', 'title')->groupBy('id', 'title')->pluck('title', 'id');
        //        $data['districtList'] = District::select('id','district_name')->pluck('district_name','id');
        $data['managedGroupIds'] = $this->getManageDataId($holiday);
        return view('holiday::holiday.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $updateData = $request->all();
        try {
            if ($updateData['apply_for_all'] == 10) {
                $ids = $this->holiday->getLatestId() + 1;
                $updateData['group_id'] = $ids;
                $getbranchId = $this->getbranchId($updateData);
                if (!count($getbranchId)) {
                    toastr()->error('No Branch Found in the Selected District');
                    return redirect(route('holiday.index'));
                }
                $this->deleteHolidayData($id);
                $updateData['branchId'] = $getbranchId['branch_id'];
                foreach ($updateData['branchId'] as $key => $branchId) {
                    $updateData['branch_id'] = $branchId;
                    $updateData['province_id'] = $getbranchId['province_id'][$key];
                    $updateData['district_id'] = $getbranchId['district_id'][$key];
                    $holiday = $this->holiday->save($updateData);

                    $calendarType = $updateData['calendar_type'];
                    $holidayDetailArray = [];
                    $dateConverter = new DateConverter();
                    foreach ($updateData['holiday_days'] as $day) {
                        $holidayDetailArray = [
                            'holiday_id' => $holiday->id,
                            'sub_title' => $day['day'],
                            'nep_date' => $calendarType == 2 ? $dateConverter->eng_to_nep_convert($day['eng_date']) : $day['nep_date'],
                            'eng_date' => $calendarType == 1 ? $dateConverter->nep_to_eng_convert($day['nep_date']) : $day['eng_date'],
                        ];

                        HolidayDetail::create($holidayDetailArray);
                    }
                }
            } else {
                $updateData['province_id'] = null;
                $updateData['district_id'] = null;
                $updateData['group_id'] = $id;
                $holiday = $this->holiday->update($id, $updateData);
                if (!empty($updateData['holiday_days'])) {
                    $holiday  = $this->holiday->find($id);
                    $calendarType = $updateData['calendar_type'];
                    $dateConverter = new DateConverter();

                    $holiday->holidayDetail()->delete();
                    foreach ($updateData['holiday_days'] as $day) {
                        $holidayDetailArray = [
                            'sub_title' => $day['day'],
                            'nep_date' => $calendarType == 2 ? $dateConverter->eng_to_nep_convert($day['eng_date']) : $day['nep_date'],
                            'eng_date' => $calendarType == 1 ? $dateConverter->nep_to_eng_convert($day['nep_date']) : $day['eng_date'],
                        ];
                        $holiday->holidayDetail()->saveMany([new HolidayDetail($holidayDetailArray)]);
                    }
                }
            }

            toastr()->success('Event Updated Successfully');
        } catch (\Throwable $e) {
            Log::info('error', ['Error' => $e]);
            toastr()->error('Something went wrong');
        }
        return redirect(route('holiday.index'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function updateBackup(Request $request, $id)
    {
        $updateData = $request->all();
        try {
            $updateData['organization_id'] = $request->organization_id[0];
            $holiday = $this->holiday->update($id, $updateData);
            if (!empty($updateData['holiday_days'])) {
                $holiday  = $this->holiday->find($id);
                $calendarType = $updateData['calendar_type'];
                $dateConverter = new DateConverter();

                $holiday->holidayDetail()->delete();
                foreach ($updateData['holiday_days'] as $day) {
                    $holidayDetailArray = [
                        'sub_title' => $day['day'],
                        'nep_date' => $calendarType == 2 ? $dateConverter->eng_to_nep_convert($day['eng_date']) : $day['nep_date'],
                        'eng_date' => $calendarType == 1 ? $dateConverter->nep_to_eng_convert($day['nep_date']) : $day['eng_date'],
                    ];
                    $holiday->holidayDetail()->saveMany([new HolidayDetail($holidayDetailArray)]);
                }
            }
            toastr()->success('Event Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something went wrong');
        }
        return redirect(route('holiday.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->deleteHolidayData($id);
            toastr()->success('Holiday Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }
        return redirect(route('holiday.index'));
    }

    public function destroyBackup($id)
    {
        try {
            $this->deleteHolidayData($id);
            $this->holiday->deleteHolidayDetails($id);
            $this->holiday->delete($id);
            toastr()->success('Holiday Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }
        return redirect(route('holiday.index'));
    }

    public function cloneDay(Request $request)
    {
        $data = $request->all();
        $count = $data['count'] + 1;
        $calendar_type = $data['calendar_type'];
        return response()->json([
            'data' => view('holiday::holiday.partial.clone', compact('count', 'calendar_type'))->render(),
        ]);
    }

    public function getOrganizationBranch(Request $request)
    {
        $filter = $request->all();
        if (isset($filter['organization_id'])) {
            $branchs = $this->branchObj->branchListOrganizationwise($filter['organization_id']);
        }
        return response()->json($branchs);
    }

    public function cloneProvinceDistrictFields(Request $request)
    {
        $provinceList = ProvincesDistrict::select('id', 'title')->pluck('title', 'id');
        $districtList = District::select('id', 'district_name')->pluck('district_name', 'id');
        $html = view('holiday::holiday.partial.clone-province-district', compact('provinceList', 'districtList'))->render();
        return response()->json(['html' => $html]);
    }

    public function getbranchIdBackup($data)
    {
        $branchId = [];
        $provinces_id = $data['province_id'];
        $district_ids = $data['district_id'];

        if (count($provinces_id)) {
            foreach ($provinces_id as $key => $id) {
                $provincesDistricts = ProvincesDistrict::where('id', $id)
                    ->where(function ($query) use ($district_ids) {
                        foreach ($district_ids as $district_id) {
                            $query->orWhereJsonContains('district_id', $district_id);
                        }
                    })
                    ->get();

                foreach ($provincesDistricts as $provinceDistrict) {
                    $branchId['province_district_id'][] = $provinceDistrict->id;
                    $branchId['district_id'][] = $district_ids;
                    $branchId['province_id'][] = $id;
                }
            }
        }
        return array_filter($branchId);
    }


    public function getbranchId($data)
    {
        $branchId = [];
        $provinces_id = $data['province_id'];
        $district_id = $data['district_id'];
        if (count($provinces_id)) {
            foreach ($provinces_id as $key => $id) {
                $branches = Branch::where('provinces_districts_id', $id)->whereIn('district_id', $district_id)->get();
                if (count($branches)) {
                    foreach ($branches as $branch) {
                        $branchId['branch_id'][] = $branch->id;
                        $branchId['district_id'][] = $branch->district_id;
                        $branchId['province_id'][] = $id;
                    }
                } else {
                    foreach ($district_id as $d_id) {
                        $branchId['branch_id'][] = 0;
                        $branchId['district_id'][] = $d_id;
                        $branchId['province_id'][] = $id;
                    }
                }
            }
        }
        return array_filter($branchId);
    }

    public function getManageDataByGroupId($groupId)
    {
        $groupIdsArray = $groupId->toArray();

        $holidays = Holiday::whereIn('group_id', $groupIdsArray)
            ->select('group_id', 'branch_id', 'province_id', 'district_id')
            ->get();

        $branches = Branch::whereIn('id', $holidays->pluck('branch_id')->unique())
            ->pluck('name', 'id');

        $provinces = ProvincesDistrict::whereIn('id', $holidays->pluck('province_id')->unique())
            ->pluck('title', 'id');

        $districts = District::whereIn('id', $holidays->pluck('district_id')->unique())
            ->pluck('district_name', 'id');
        $result = $holidays->map(function ($holiday) use ($branches, $provinces, $districts) {
            $branchName = $branches->get($holiday->branch_id, 'Unknown');
            if ($branchName === 'Unknown') {
                return null;
            }
            return [
                'group_id' => $holiday->group_id,
                'branch_name' => $branchName,
                'province_name' => $provinces->get($holiday->province_id, 'Unknown'),
                'district_name' => $districts->get($holiday->district_id, 'Unknown'),
            ];
        })->filter();
        return $this->getGroupedData($result);
    }

    public function getManageDataId($groupId)
    {
        $groupIdsArray = $groupId->toArray();
        $holiday = Holiday::whereIn('group_id', $groupIdsArray)
            ->select('group_id', 'branch_id', 'province_id', 'district_id')
            ->get()->toArray();
        return array_values(array_reduce($holiday, function ($carry, $item) {
            if ($item['branch_id'] !== null) {
                $key = $item['province_id'] . '-' . $item['district_id'];
                if (!isset($carry[$key])) {
                    $carry[$key] = $item;
                }
            }
            return $carry;
        }, []));
    }

    public function getGroupedData(Collection $data)
    {
        $groupedData = [];
        foreach ($data as $item) {
            $groupId = $item['group_id'];
            $branchName = $item['branch_name'];
            $provinceName = $item['province_name'];
            $districtName = $item['district_name'];
            if (!isset($groupedData[$groupId])) {
                $groupedData[$groupId] = [
                    'branch' => [],
                    'province' => [],
                    'district' => [],
                ];
            }

            $groupedData[$groupId]['branch'][] = $branchName;
            $groupedData[$groupId]['province'][] = $provinceName;
            $groupedData[$groupId]['district'][] = $districtName;

            $groupedData[$groupId]['branch'] = array_unique($groupedData[$groupId]['branch']);
            $groupedData[$groupId]['province'] = array_unique($groupedData[$groupId]['province']);
            $groupedData[$groupId]['district'] = array_unique($groupedData[$groupId]['district']);
        }
        return collect($groupedData);
    }

    public function deleteHolidayData($id)
    {
        $holiday = $this->holiday->find($id);
        $holidayArray =  $this->holiday->getIdByGroupId($holiday['group_id']);
        if (count($holidayArray)) {
            foreach ($holidayArray as $key => $holidayId) {
                $this->holiday->delete($holidayId);
                $this->holiday->deleteHolidayDetails($holidayId);
            }
        }
    }
}
