<?php

namespace App\Modules\Setting\Http\Controllers;

use App\Modules\Dropdown\Repositories\DropdownRepository;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Leave\Repositories\LeaveRepository;
use App\Modules\LeaveYearSetup\Repositories\LeaveYearSetupRepository;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Entities\BranchSetupSetting;
use App\Modules\Setting\Entities\EmailSetup;
use App\Modules\Setting\Entities\PayrollCalenderTypeSetting;
use App\Modules\Setting\Entities\UnitSetupSetting;
use App\Modules\Setting\Repositories\DepartmentRepository;
use App\Modules\Setting\Repositories\DesignationRepository;
use App\Modules\Setting\Repositories\LevelRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Modules\Setting\Repositories\SettingInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    /**
     * @var SettingInterface
     */
    protected $setting;
    protected $organization;

    public function __construct(SettingInterface $setting, OrganizationInterface $organization)
    {
        $this->setting = $setting;
        $this->organization = $organization;
    }

    public function index()
    {
        $data['setting'] = $this->setting->find(1);
        $dropdown = new DropdownRepository();
        if ($data['setting'] == null) {
            $data['is_edit'] = false;
            $data['btnType'] = 'Save';
            $moduleList = EmailSetup::moduleList();
            $data['moduleList'] = [];
            $data['moduleList'] = collect($moduleList)->map(function ($modules) {
                return $modules;
            });
            $data['statusList'] = EmailSetup::statusList();
            $data['organizationList'] = $this->organization->getList();
            $data['categoryList'] = $dropdown->getFieldBySlug('category');
            $data['isEdit'] = false;

            $data['genderList'] = $dropdown->getFieldBySlug('gender');
            $data['maritalStatusList'] = $dropdown->getFieldBySlug('marital_status');
            $data['departmentList'] = (new DepartmentRepository())->getList();
            $data['designationList'] = (new DesignationRepository())->getList();
            $data['levelList'] = (new LevelRepository())->getList();
            $data['yesNoList'] = array('11' => 'Yes', '10' => 'No');
            $data['noYesList'] = array('10' => 'No', '11' => 'Yes');
            $data['jobTypeList'] = LeaveType::JOB_TYPE;
            $data['contractTypeList'] = LeaveType::CONTRACT;
            $data['halfLeaveList'] = Leave::halfTypeList();
            $data['statusList'] = LeaveType::statusList();
            $data['leaveTypeList'] = LeaveType::leaveTypeList();
            $data['leaveYearList'] = (new LeaveYearSetupRepository())->getLeaveYearList();
            $currentLeaveyear = (new LeaveYearSetupRepository())->getCurrentLeaveYear();
            if ($currentLeaveyear->isNotEmpty() && $currentLeaveyear->count() > 0) {
                $data['currentLeaveyear'] = $currentLeaveyear;
            } else {
                toastr()->error('Please set Active Leave Year first !!!');
                return redirect(route('leaveyearsetup.index'));
            }
            $data['currentLeaveyear'] = getCurrentLeaveYearId();
            $data['organizations'] = $this->organization->findAll()->pluck('name', 'id');


            return view('setting::setting.tab-list', $data);
        } else {
            $data['is_edit'] = true;
            $data['btnType'] = 'Update';
            $moduleList = EmailSetup::moduleList();
            $data['moduleList'] = [];
            $data['moduleList'] = collect($moduleList)->map(function ($modules) {
                return $modules;
            });

            $data['statusList'] = EmailSetup::statusList();

            $data['organizationList'] = $this->organization->getList();
            $data['categoryList'] = $dropdown->getFieldBySlug('category');
            $data['designationList'] =  (new DesignationRepository())->getList();
            $data['isEdit'] = false;


            $data['genderList'] = $dropdown->getFieldBySlug('gender');
            $data['maritalStatusList'] = $dropdown->getFieldBySlug('marital_status');
            $data['departmentList'] = (new DepartmentRepository())->getList();
            $data['designationList'] = (new DesignationRepository())->getList();
            $data['levelList'] = (new LevelRepository())->getList();
            $data['yesNoList'] = array('11' => 'Yes', '10' => 'No');
            $data['noYesList'] = array('10' => 'No', '11' => 'Yes');
            $data['jobTypeList'] = LeaveType::JOB_TYPE;
            $data['contractTypeList'] = LeaveType::CONTRACT;
            $data['halfLeaveList'] = Leave::halfTypeList();
            $data['statusList'] = LeaveType::statusList();
            $data['leaveTypeList'] = LeaveType::leaveTypeList();
            $data['leaveYearList'] = (new LeaveYearSetupRepository())->getLeaveYearList();
            $currentLeaveyear = (new LeaveYearSetupRepository())->getCurrentLeaveYear();
            if ($currentLeaveyear->isNotEmpty() && $currentLeaveyear->count() > 0) {
                $data['currentLeaveyear'] = $currentLeaveyear;
            } else {
                toastr()->error('Please set Active Leave Year first !!!');
                return redirect(route('leaveyearsetup.index'));
            }
            $data['currentLeaveyear'] = getCurrentLeaveYearId();
            $data['organizations'] = $this->organization->findAll()->pluck('name', 'id');
            return view('setting::setting.tab-list', $data);
        }
    }


    public function create()
    {
        $data['setting'] = $this->setting->find(1);
        $data['statusList'] = [10 => 'No', 11 => 'Yes'];
        if ($data['setting'] == null) {
            $data['is_edit'] = false;
            $data['btnType'] = 'Save';
            return view('setting::setting.index', $data);
        } else {
            $data['is_edit'] = true;
            $data['btnType'] = 'Update';
            return view('setting::setting.index', $data);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        try {
            if ($request->hasFile('company_logo')) {
                $data['company_logo'] = $this->setting->upload($data['company_logo']);
            }
            $this->setting->save($data);
            toastr()->success('Setting Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('setting.create'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        try {
            if ($request->hasFile('company_logo')) {
                $data['company_logo'] = $this->setting->upload($data['company_logo']);
            }
            $this->setting->update($id, $data);
            toastr()->success('Setting Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }
    public function payrollSetting()
    {
        $data['organizationList'] = $this->organization->findAll(null, [], ['by' => 'id', 'sort' => 'ASC']);
        $data['calendarTypeList'] = [
            'nep' => 'Nepali',
            'eng' => 'English'
        ];
        $data['payrollSetting'] = $this->setting->findPayrollCalenderTypeList();
        $data['branchType'] = BranchSetupSetting::first();
        $data['unitType'] = UnitSetupSetting::first();
        if (count($data['payrollSetting']) > 0) {
            $data['is_edit'] = true;
            $data['btnType'] = 'Update';
            return view('setting::payroll-setting.index', $data);
        } else {
            $data['is_edit'] = false;
            $data['btnType'] = 'Save';
            return view('setting::payroll-setting.index', $data);
        }

        return view('setting::payroll-setting.index', $data);
    }
    public function storePayrollSetting(Request $request)
    {
        $data = $request->all();

        try {
            $branchSetupSetting = BranchSetupSetting::first();
            if (!$branchSetupSetting) {
                $branchSetupSetting = new BranchSetupSetting();
            }
            $branchSetupSetting->branch_type = $request->branch_type ?? '0';
            $branchSetupSetting->save();

            $unitSetupSetting = UnitSetupSetting::first();
            if (!$unitSetupSetting) {
                $unitSetupSetting = new UnitSetupSetting();
            }
            $unitSetupSetting->unit_type = $request->unit_type ?? '0';
            $unitSetupSetting->save();
            foreach ($data['organization_id'] as $key => $value) {
                $payrollSetting['organization_id'] = $value;
                $payrollSetting['calendar_type'] = $data['calendar_type'][$key];
                $this->setting->savePayrollCalenderType($payrollSetting);
            }
            toastr()->success('Payroll Calender Type Setting Created Successfully');
        } catch (\Throwable $e) {
            // dd($e);
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('setting.payrollSetting'));
    }
    public function updatePayrollSetting(Request $request)
    {
        $data = $request->all();

        try {
            $branchSetupSetting = BranchSetupSetting::first();
            if (!$branchSetupSetting) {
                $branchSetupSetting = new BranchSetupSetting();
            }
            $branchSetupSetting->branch_type = $request->branch_type ?? '0';
            $branchSetupSetting->save();

            $unitSetupSetting = UnitSetupSetting::first();
            if (!$unitSetupSetting) {
                $unitSetupSetting = new UnitSetupSetting();
            }
            $unitSetupSetting->unit_type = $request->unit_type ?? '0';
            $unitSetupSetting->save();
            foreach ($data['organization_id'] as $key => $value) {
                $payrollSetting['organization_id'] = $value;
                $payrollSetting['calendar_type'] = $data['calendar_type'][$key];
                $payrollcalenderModel = $this->setting->findOne($payrollSetting);
                if ($payrollcalenderModel) {
                    $payrollcalenderModel->update($payrollSetting);
                } else {
                    $this->setting->savePayrollCalenderType($payrollSetting);
                }
            }
            toastr()->success('Payroll Calender Type Setting Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('setting.payrollSetting'));
    }

    public function getCalenderType(Request $request)
    {
        $data = $request->all();
        $models = PayrollCalenderTypeSetting::where('organization_id', $data['organization_id'])->first();
        return json_encode($models);
    }

    public function viewEmailSetup()
    {
        $moduleList = EmailSetup::moduleList();

        $data['moduleList'] = [];
        $data['moduleList'] = collect($moduleList)->map(function ($modules) {
            return $modules;
        });
        $data['statusList'] = EmailSetup::statusList();
        return view('setting::email-setup.index', $data);
    }

    public function storeEmailSetup(Request $request)
    {
        try {
            $data = $request->except('_token');
            foreach ($data['setups'] as $setup) {
                if (isset($setup['status'])) {
                    $model = EmailSetup::where('module_id', $setup['module_id'])->first();
                    if (isset($model)) {
                        $model->update($setup);
                    } else {
                        EmailSetup::create($setup);
                    }
                }
            }
            toastr('Email setup done successfully', 'success');
        } catch (\Throwable $th) {
            toastr('Error While doing email setup', 'error');
        }
        return redirect()->route('setting.emailSetup');
    }

    public function storeEmailSetupAjax(Request $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                $data = $request->except('_token');
                foreach ($data['setups'] as $setup) {
                    if (isset($setup['status'])) {
                        $model = EmailSetup::where('module_id', $setup['module_id'])->first();
                        if (isset($model)) {
                            $model->update($setup);
                        } else {
                            EmailSetup::create($setup);
                        }
                    }
                }
                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Email setup done successfully'
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error($th->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage() . " Line No: " . $th->getLine()
                ]);
            }
        }
    }
}
