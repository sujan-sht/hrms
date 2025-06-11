<?php

namespace App\Modules\PMS\Http\Controllers;

use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;
use App\Modules\FiscalYearSetup\Repositories\FiscalYearSetupInterface;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\PMS\Entities\Kra;
use App\Modules\PMS\Entities\PmsEmployee;
use App\Modules\PMS\Entities\Target;
use App\Modules\PMS\Entities\TargetAchievement;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Modules\PMS\Repositories\KRAInterface;
use App\Modules\PMS\Repositories\KPIInterface;
use App\Modules\PMS\Repositories\TargetInterface;
use App\Modules\Setting\Repositories\DepartmentInterface;
use App\Modules\Setting\Repositories\SettingInterface;
use App\Modules\User\Entities\User;
use App\Modules\User\Repositories\UserInterface;
use Illuminate\Support\Facades\Auth;

class SetFormController extends Controller
{
    protected $kra;
    protected $kpi;
    protected $target;
    protected $dropdown;
    protected $fiscalYear;
    protected $employee;
    protected $organization;
    protected $branch;
    protected $user;
    protected $setting;
    protected $department;


    public function __construct(KRAInterface $kra, KPIInterface $kpi, TargetInterface $target, DropdownInterface $dropdown, FiscalYearSetupInterface $fiscalYear, EmployeeInterface $employee, OrganizationInterface $organization, BranchInterface $branch, UserInterface $user, SettingInterface $setting, DepartmentInterface $department)
    {
        $this->kra = $kra;
        $this->kpi = $kpi;
        $this->target = $target;
        $this->dropdown = $dropdown;
        $this->fiscalYear = $fiscalYear;
        $this->employee = $employee;
        $this->organization = $organization;
        $this->branch = $branch;
        $this->user = $user;
        $this->setting = $setting;
        $this->department = $department;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['statusList'] = PmsEmployee::statusList();
        
        $subordinatesList = [];
        if(auth()->user()->user_type == 'supervisor'){
            unset($data['statusList'][1], $data['statusList'][5]);

            $subordinatesList = Employee::getSupervisorSubordinates(auth()->user()->id);
            $data['employeeList'] = $subordinatesList;
        }else{
            $data['employeeList'] = $this->employee->getList();
        }
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['PmsEmployeesModels'] = $this->target->getEmployeePMSList(10, $filter, $sort);
        return view('pms::set-form.index', $data);
    }

    public function create(Request $request)
    {
        $employees = [];
        $data['filter'] = $request->all();
        $data['kraList'] = $this->kra->getList();
        $data['kpiList'] = $this->kpi->getList();
        $data['fiscalYearList'] = $this->fiscalYear->getCurrentFiscalYear();
        $data['setting'] = $this->setting->getdata();
        $data['departmentList'] = $this->department->getList();
        $data['organizationList'] = $this->organization->getList();
        $data['fiscalYear'] = FiscalYearSetup::currentFiscalYear();

        if(auth()->user()->user_type == 'supervisor'){
            $employees = Employee::getSupervisorSubordinates(auth()->user()->id);
            $data['employeeList'] = $employees;
        }else{
            $data['employeeList'] = $this->employee->getList();
        }
        return view('pms::set-form.create', $data);
    }

    public function view(Request $request)
    {
        $filter = $request->all();
        $data['targetAchievementModel'] = [];
        $html = '';
        if (isset($filter['emp_id'])) {
            $data['targetAchievementModel'] = $this->target->findTargetDetailsByEmployee($filter['emp_id'], null);

            $selected_employee = $this->employee->find($filter['emp_id']);
            $html = " <span><b>Employee : </b> " . $selected_employee->full_name . "</span><br>";
            $html .= "<span><b>Organization : </b> " . optional($selected_employee->organizationModel)->name . "</span><br>";
            $html .= "<span><b>Department : </b> " . optional($selected_employee->department)->title . "</span><br>";
            $html .= "<span><b>Designation : </b> " . optional($selected_employee->designation)->title . "</span>";
        }
        return response()->json([
            'view' => view('pms::set-form.partial.employee-report', $data)->render(),
            'selected_employee' => $html
        ]);
    }

    public function filterKraList(Request $request)
    {
        $query = Kra::query();
        if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
            $query->where('division_id', optional(auth()->user()->userEmployer)->organization_id);
        }
        $all_kras = $query->where('title', 'like', '%' . $request->search . '%')->select(
                'id',
                'title'
            )->orderBy('id', 'desc')
                ->paginate(10, ['*'], 'page', $request->page);;

        $kras = [];
        foreach ($all_kras as $key =>  $value) {
            $kras[] = array(
                "id" => $value->id,
                "text" => $value->title,
            );
        }

        return response()->json([
            'items' => $kras,
            'total_count' => count($kras)
        ]);
    }

    public function fetchTargetDetails(Request $request)
    {
        if (isset($request->kpi_id)) {
            $targetDetail = $this->target->targetDetailKpiwise($request->kpi_id);
            return json_encode($targetDetail);
        }
    }

    public function store(Request $request)
    {
        $inputData = $request->except('kra_title');
        try {
            if ($inputData['employee_id']) {
                PmsEmployee::checkAndStore($inputData['employee_id']);
            }

            for ($i = 0; $i < count($inputData['kpi_id']); $i++) {
                $model = Target::where([
                    'kra_id' => $inputData['kra_id'],
                    'kpi_id' => $inputData['kpi_id'][$i]
                ])->first();

                if ($model) {
                    $model->fiscal_year_id = $inputData['fiscal_year_id'][$i];
                    $model->title = $inputData['title'][$i];
                    $model->frequency = $inputData['frequency'][$i];
                    $model->category = $inputData['category'][$i];
                    $model->weightage = $inputData['weightage'][$i];
                    $model->eligibility = $inputData['eligibility'][$i];
                    $model->no_of_quarter = 4;
                    $model->updated_by = Auth::user()->id;
                    $model->save();

                    $this->saveTargetValues($inputData, $model, $i);
                } else {
                    $targetData = [
                        'kra_id' => $inputData['kra_id'],
                        'kpi_id' => $inputData['kpi_id'][$i],
                        'fiscal_year_id' => $inputData['fiscal_year_id'][$i],
                        'title' => $inputData['title'][$i],
                        'frequency' => $inputData['frequency'][$i],
                        'category' => $inputData['category'][$i],
                        'weightage' => $inputData['weightage'][$i],
                        'no_of_quarter' => 4,
                        'created_by' => Auth::user()->id
                    ];
                    $targetModel = $this->target->create($targetData);

                    $this->saveTargetValues($inputData, $targetModel, $i);
                }
            }
            toastr()->success('Target Values Set Successfully');
        } catch (\Throwable $th) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('set-form.create', ['employee_id' => $inputData['employee_id']]));
    }

    public function saveTargetValues($inputData, $targetModel, $i)
    {
        if ($targetModel) {
            foreach ($inputData['target_values'] as $quarter => $target_value) {
                $targetAchievementData = [
                    'employee_id' => $inputData['employee_id'],
                    'target_id' => $targetModel['id'],
                    'quarter' => $quarter,
                    'target_value' => $target_value[$i],
                ];
                TargetAchievement::checkAndSetTargetValue($targetAchievementData, $targetModel);
            }
        }
    }

    public function storeSingleKra(Request $request)
    {
        if (isset($request->title)) {
            $inputData = $request->except('_token');
            $inputData['created_by'] = Auth::user()->id;
            $kraModel = $this->kra->create($inputData);
            return json_encode($kraModel);
        }
    }

    public function storeSingleKpi(Request $request)
    {
        if (isset($request->title)) {
            $inputData = $request->except('_token');
            $inputData['created_by'] = Auth::user()->id;
            $kpiModel = $this->kpi->create($inputData);
            return json_encode($kpiModel);
        }
    }

    public function updateTargetValues(Request $request)
    {
        //check for if achieved value already store then don't store
        if ($request->ajax()) {
            $inputData = $request->except('_token');
            foreach ($inputData['target_val'] as $targetAchievement) {
                $data['target_value'] = $targetAchievement['value'];
                $this->target->updateTargetValues($targetAchievement['name'], $data);
            }
            return response()->json([
                'status' => true,
                'message' => 'Target Values Set Successfully !!!'
            ]);
        }
    }

    public function destroyKpi(Request $request, $kpi_id)
    {
        if ($request->employee_id) {
            $this->target->deleteTargetAchievement($kpi_id, $request->employee_id);
            return response()->json([
                'status' => true,
                'message' => 'Target Acheivement Details Deleted Successfully !!!'
            ]);
        }
    }

    public function pmsEmployeeupdateStatus(Request $request)
    {
        $inputData = $request->except('_token');
        $employeeModel = Employee::find($inputData['employee_id']);
        $userModel = optional($employeeModel->getUser);
        try {
            $this->target->updateStatusPMSEmployee($inputData['id'], $inputData);
            if($inputData['status'] == '3'){
                // create notification for hr
                $hrs = User::where('user_type', 'hr')->pluck('id');
                if (isset($hrs) && !empty($hrs)) {
                    foreach ($hrs as $hr) {
                        $notificationData['creator_user_id'] = auth()->user()->id;
                        $notificationData['notified_user_id'] = $hr;
                        $notificationData['message'] = $employeeModel->full_name . "'s target achievement values filled form has been forwarded by " . optional(auth()->user()->userEmployer)->full_name;
                        $notificationData['link'] = route('set-form.index');
                        $notificationData['type'] = 'PMS';
                        $notificationData['type_id_value'] = $inputData['id'];
                        Notification::create($notificationData);
                    }
                }

                // create notification for division hr
                $divisionHrs = User::when(true, function ($query) use ($employeeModel){
                    $query->whereHas('userEmployer', function($q) use ($employeeModel){
                        $q->where('organization_id', $employeeModel->organization_id)->where('status', 1);
                    });
                })->where('user_type', 'division_hr')->pluck('id');

                if (isset($divisionHrs) && !empty($divisionHrs)) {
                    foreach ($divisionHrs as $divisionHr) {
                        $notificationData['creator_user_id'] = auth()->user()->id;
                        $notificationData['notified_user_id'] = $divisionHr;
                        $notificationData['message'] = $employeeModel->full_name . "'s target achievement values filled form has been forwarded by " . optional(auth()->user()->userEmployer)->full_name;
                        $notificationData['link'] = route('set-form.index');
                        $notificationData['type'] = 'PMS';
                        $notificationData['type_id_value'] = $inputData['id'];
                        Notification::create($notificationData);
                    }
                }
            }elseif($inputData['status'] == '4'){
                $notificationData['creator_user_id'] = auth()->user()->id;
                $notificationData['notified_user_id'] = $userModel->id;
                $notificationData['message'] = "Your target achievement values filled form has been rejected by " . optional(auth()->user()->userEmployer)->full_name;
                $notificationData['link'] = route('target.viewTargetAchievement', $inputData['employee_id']);
                $notificationData['type'] = 'PMS';
                $notificationData['type_id_value'] = $inputData['id'];
                Notification::create($notificationData);
            }
            toastr()->success('Status Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    public function setRollout(Request $request)
    {
        $inputData = $request->except('_token');
        if ($inputData['type'] == 1) {
            $data['rollout_date'] = date('Y-m-d');
            $data['status'] = 2;
            $data['type'] = 1;

            // create notification for employee
            $notified_user_id = $this->user->getUserId($inputData['employee_id'])['id'];
            $notificationData['creator_user_id'] = auth()->user()->id;
            $notificationData['notified_user_id'] = $notified_user_id;
            $notificationData['message'] = "Please set achieved values for different quarters";
            $notificationData['link'] = route('target.viewTargetAchievement', $inputData['employee_id']);
            $notificationData['type'] = 'target_achievements';
            $notificationData['type_id_value'] = null;
            Notification::create($notificationData);
            //
        } else {
            $data['rollout_date'] = $inputData['rollout_date'];
            $data['type'] = 2;
        }
        try {
            $this->target->updateStatusPMSEmployee($inputData['id'], $data);
            toastr()->success('Status Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }
    
}
