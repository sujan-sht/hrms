<?php

namespace App\Modules\Branch\Http\Controllers;

use App\Exports\BranchExport;
use App\Modules\Branch\Entities\BranchDayOff;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Setting\Entities\District;
use App\Modules\Setting\Entities\ProvincesDistrict;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Branch\Http\Requests\BranchRequest;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Holiday\Repositories\HolidayInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use Maatwebsite\Excel\Facades\Excel;

class BranchController extends Controller
{
    private $branch;
    private $organization;
    private $employee;
    private $holiday;

    public function __construct(
        BranchInterface $branch,
        OrganizationInterface $organization,
        EmployeeInterface $employee,
        HolidayInterface $holiday
    ) {
        $this->branch = $branch;
        $this->organization = $organization;
        $this->employee = $employee;
        $this->holiday = $holiday;
    }

    public function index(Request $request)
    {
        $filter = $request->all();

        $data['branchModels'] = $this->branch->findAll(20, $filter);
        $data['organizationList'] = $this->organization->getList();
        $data['districtList'] = District::select('id','district_name')->pluck('district_name','id');
        $data['province'] = ProvincesDistrict::select('id', 'title')->groupBy('id', 'title')->pluck('title', 'id');

        return view('branch::branch.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['districtList'] = [];
        $data['isEdit'] = false;
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employee->getList();
        $data['province'] = $province = ProvincesDistrict::select('id', 'title')->groupBy('id', 'title')->pluck('title', 'id');
        $provinceFirstData = $province->keys()->first();
        if(!is_null($provinceFirstData)){
            $districtIds = $province = ProvincesDistrict::where('id', $provinceFirstData)->first();
            $data['districtList'] = District::select('id','district_name')->whereIn('id', $districtIds['district_id'])->pluck('district_name','id');
        }
        $data['branch_day_shift'] = [];

        return view('branch::branch.create', $data);
    }

    public function getDistrictsByProvince(Request $request){
        $id = $request->get('province_ids');
        $districts = [];
        if(!is_null($id)){
            $districtIds = ProvincesDistrict::where('id', $id)->first();
            $districts = District::select('id','district_name')->whereIn('id', $districtIds['district_id'])->pluck('district_name','id');
        }

        return response()->json(['districts' => $districts]);

    }

    public function getDistrictsByProvinces(Request $request){
        $id = $request->get('province_ids');
        $districts = [];
        if(count($id)){
            $collection = ProvincesDistrict::whereIn('id', $id)->get();
            $districtIds = $collection->pluck('district_id')->map(function($item) {
                if (is_string($item)) {
                    return json_decode($item, true);
                }
                return $item;
            })->flatten()->unique()->values()->all();
            $districts = District::select('id','district_name')->whereIn('id', $districtIds )->pluck('district_name','id');
        }
        else{
            $districts = District::select('id','district_name')->pluck('district_name','id');
        }
        return response()->json(['districts' => $districts]);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(BranchRequest $request)
    {
        $data = $request->all();
        // try {
            $branch = $this->branch->create($data);
            $holidayDetails = $this->holiday->getHolidayInfoBYOrgIdProvinceIdDistrictId($data['organization_id'], $data['provinces_districts_id'], $data['district_id']);
            if(!is_null($holidayDetails)){
                $branchId = $holidayDetails['branch_id'];
                $holidayId = $holidayDetails['id'];
                $updateHoliday = $this->holiday->updateOrCreateHolidayAccordingToBranch($holidayDetails, $branch);
                if($branchId != 0){
                    $this->holiday->createHolidayDetailsAccordingToBranch($holidayId, $updateHoliday);
                }
            }
            if($branch){
                //Branch Employee Day Off
                foreach ($request->dayoff as $key => $value) {
                    $branchDayOff = [
                        'day_off' => $value,
                        'branch_id' => $branch->id
                    ];
                    BranchDayOff::create($branchDayOff);
                }
            }
           

            toastr()->success('Branch Created Successfully');
        // } catch (\Throwable $e) {
        //     toastr()->error('Something Went Wrong !!!');
        // }
        return redirect(route('branch.index'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        // return view('branch::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['branchModel'] = $this->branch->findOne($id);
        $data['district_id'] = $data['branchModel']['district_id'];
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employee->getList();
        $data['province'] = $province = ProvincesDistrict::select('id', 'title')->groupBy('id', 'title')->pluck('title', 'id');
        $provinceFirstData = $province->keys()->first();
        if(!is_null($provinceFirstData)){
            $districtIds = $province = ProvincesDistrict::where('id', $provinceFirstData)->first();
            $data['districtList'] = District::select('id','district_name')->whereIn('id', $districtIds['district_id'])->pluck('district_name','id');
        }
        $data['branch_day_shift'] = $data['branchModel']->getBranchDayList();

        return view('branch::branch.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(BranchRequest $request, $id)
    {
        $data = $request->all();

        try {
            $this->branch->update($id, $data);

            toastr()->success('Branch Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('branch.index'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function updateBackup(BranchRequest $request, $id)
    {
        $data = $request->all();
        $statusUdate = false;
        try {

            $branchInfo = $this->branch->findOne($id);
            $holidayDetails = $this->holiday->getHolidayBranch($data['organization_id'], $data['provinces_districts_id'], $data['district_id'], $id);

            $oldHOliday = $this->holiday->getHolidayBranch($branchInfo['organization_id'], $branchInfo['provinces_districts_id'], $branchInfo['district_id'], $id);


            if(!is_null($oldHOliday)){
                if($branchInfo['district_id'] != $oldHOliday['district_id']){
                    $multipleHoliday = $this->holiday->getHolidayInfoBYOrgIdProvinceIdDistrictId($branchInfo['organization_id'], $branchInfo['provinces_districts_id'], $branchInfo['district_id']);
                    // dd($multipleHoliday);
                    if(count($multipleHoliday) > 1){
                        $this->holiday->deleteHolidayDetails($oldHOliday['id']);
                        $this->holiday->delete($oldHOliday['id']);
                    }else{
                        $oldHOliday->update(['branch_id' => 0]);
                    }
                }
            }


            $branch = $this->branch->update($id, $data);
            if(is_null($holidayDetails)){
                // dd('dfsa');
                // create  here
                // if($branchInfo['district_id'] != $holidayDetails['district_id']){
                // $this->holiday->getHolidayInfoBYOrgIdProvinceIdDistrictId($branchInfo['organization_id'], $branchInfo['provinces_districts_id'], $branchInfo['district_id']);
               $d = $this->holiday->updateOrCreateHolidayAccordingToBranch($holidayDetails, $branch);
            //    dd($d);
                // }
            }


            toastr()->success('Branch Updated Successfully');
        } catch (\Throwable $e) {
            dd($e);
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('branch.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $branchInfo = $this->branch->findOne($id);
            $holidayDetails = $this->holiday->getHolidayBranch($branchInfo['organization_id'], $branchInfo['provinces_districts_id'], $branchInfo['district_id'], $id);
            $allHoliday = $this->holiday->getHolidayBranchAll($branchInfo['organization_id'], $branchInfo['provinces_districts_id'], $branchInfo['district_id']);
            if(!is_null($holidayDetails)){
                if(count($allHoliday) > 1){
                    $this->holiday->deleteHolidayDetails($holidayDetails['id']);
                    $this->holiday->delete($holidayDetails['id']);
                }else{
                    $holidayDetails->update(['branch_id' => 0]);
                }
            }
            $this->branch->delete($id);

            toastr()->success('Branch Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    public function export(Request $request)
    {
        $filter = $request->all();

        $data['branchModels'] = $this->branch->findAll(null, $filter);

        return Excel::download(new BranchExport($data),'branch-report.xlsx');
    }
}
