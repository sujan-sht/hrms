<?php

namespace App\Modules\PMS\Http\Controllers;

use App\Modules\Appraisal\Repositories\RatingScaleInterface;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Entities\EmployeeAppraisalApprovalFlow;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;
use App\Modules\FiscalYearSetup\Repositories\FiscalYearSetupInterface;
use App\Modules\Notification\Entities\Notification;
use App\Modules\PMS\Entities\TargetAchievement;
use App\Modules\PMS\Entities\TargetAttachment;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

use App\Modules\PMS\Repositories\KRAInterface;
use App\Modules\PMS\Repositories\KPIInterface;
use App\Modules\PMS\Repositories\TargetInterface;
use App\Modules\PMS\Http\Requests\CreateTargetRequest;
use App\Modules\PMS\Http\Requests\TargetValueRequest;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Setting\Repositories\DepartmentInterface;
use App\Modules\Setting\Repositories\SettingInterface;
use App\Modules\User\Repositories\UserInterface;
use Yoeunes\Toastr\Facades\Toastr;

class TargetController extends Controller
{
    protected $kra;
    protected $kpi;
    protected $target;
    protected $dropdown;
    protected $fiscalYear;
    protected $employee;
    protected $user;
    protected $ratingScale;
    protected $setting;
    protected $department;


    public function __construct(KRAInterface $kra, KPIInterface $kpi, TargetInterface $target, DropdownInterface $dropdown, FiscalYearSetupInterface $fiscalYear, EmployeeInterface $employee, UserInterface $user, RatingScaleInterface $ratingScale, SettingInterface $setting, DepartmentInterface $department)
    {
        $this->kra = $kra;
        $this->kpi = $kpi;
        $this->target = $target;
        $this->dropdown = $dropdown;
        $this->fiscalYear = $fiscalYear;
        $this->employee = $employee;
        $this->user = $user;
        $this->ratingScale = $ratingScale;
        $this->setting = $setting;
        $this->department = $department;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data['kraList'] = $this->kra->getList();
        $data['kpiList'] = $this->kpi->getList();
        $data['fiscalYearList'] = $this->fiscalYear->getCurrentFiscalYear();
        $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['targetModels'] = $this->target->findAll(20, $filter, $sort);
        return view('pms::target.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['kraList'] = $this->kra->getList();
        $data['kpiList'] = $this->kpi->getList();
        $fiscalYearList = $this->fiscalYear->getCurrentFiscalYear();
        if ($fiscalYearList->isNotEmpty() && $fiscalYearList->count() > 0) {
            $data['fiscalYearList'] = $fiscalYearList;
        } else {
            toastr()->error('Please set Active Fiscal Year first !!!');
            return redirect(route('fiscalYearSetup.index'));
        }
        return view('pms::target.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateTargetRequest $request)
    {
        $inputData = $request->all();
        $inputData['date'] = date('Y-m-d');
        $inputData['created_by'] = Auth::user()->id;
        try {
            $this->target->create($inputData);
            toastr()->success('Target Created Successfully');
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('target.index'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('pms::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['kraList'] = $this->kra->getList();
        $data['kpiList'] = $this->kpi->getList();
        $data['targetModel'] = $this->target->findOne($id);
        $data['fiscalYearList'] = $this->fiscalYear->getCurrentFiscalYear();
        $data['editKpiData'] = $this->target->KpiData($data['targetModel']['kpi_id']);

        return view('pms::target.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CreateTargetRequest $request, $id)
    {
        $data = $request->all();
        $data['updated_by'] = Auth::user()->id;

        try {
            $this->target->update($id, $data);

            toastr()->success('Target Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('target.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->target->delete($id);

            toastr()->success('Target Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    //Fetch KPIs based on KRA
    public function fetchKPIs(Request $request)
    {
        if ($request->ajax()) {
            $kra_id = $request->kra_id;
            if (isset($kra_id)) {
                $kpi_info = $this->kpi->getKPIs($kra_id);
            }
            return json_encode($kpi_info);
        }
    }

    //view target quarter page
    public function setTargetQuarter($id)
    {
        $targetDetails = $this->target->getTargetDetails($id);
        if (isset($targetDetails) && !empty($targetDetails)) {
            $data['targetDetails'] = $targetDetails;
        }
        return view('pms::target.setTargetQuarter.set-target-quarter', $data);
    }
    //

    //set target value and achieved value
    public function setValue(Request $request)
    {
        $data = $request->all();
        try {
            $get_target_values = $this->target->findTargetDetails($data['target_id'], $data['quarter']);
            if (isset($get_target_values) && !empty($get_target_values)) {
                $achieved_percent = ($data['achieved_value'] / $get_target_values['target_value']) * 100;
                if ($achieved_percent >= 100) {
                    $score = $data['weightage'];
                } else {
                    $score = ($data['weightage'] / $achieved_percent) * 100;
                }

                $update_data = [
                    'kra_id' => $data['kra_id'],
                    'kpi_id' => $data['kpi_id'],
                    'target_id' => $data['target_id'],
                    'quarter' => $data['quarter'],
                    'target_value' => $get_target_values['target_value'],
                    'achieved_value' => $data['achieved_value'],
                    'achieved_percent' => $achieved_percent,
                    'score' => $score,
                ];
                $this->target->updateAchievedValue($get_target_values['id'], $update_data);
                toastr()->success('Achieved Value Set Successfully');
            } else {
                $store_data = [
                    'kra_id' => $data['kra_id'],
                    'kpi_id' => $data['kpi_id'],
                    'target_id' => $data['target_id'],
                    'quarter' => $data['quarter'],
                    'target_value' => $data['target_value'],
                ];
                $this->target->storeAchievedValue($store_data);
                toastr()->success('Target Value Set Successfully');
            }
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }
    //

    public function viewReport(Request $request)
    {
        $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['kraData'] = $this->target->findReport(20, $filter, $sort);

        $data['departmentList'] = $this->department->getList();
        $data['divisionList'] = $this->dropdown->getFieldBySlug('division');
        $data['fiscalYearList'] = $this->fiscalYear->getCurrentFiscalYear();
        return view('pms::target.partial.view-report', $data);
    }

    //PMS Final Report
    public function viewFinalReport(Request $request)
    {
        $filter = $request->all();

        if(auth()->user()->user_type == 'employee'){
            $data['employee'][auth()->user()->emp_id] = optional(auth()->user()->userEmployer)->full_name;
        }else{
            $data['employee'] = $this->employee->getList();
        }
        
        $data['statusList'] = TargetAchievement::statusList();
        $data['setting'] = $this->setting->getdata();
        $data['fiscalYear'] = FiscalYearSetup::currentFiscalYear();

        $data['targetReportQuarterwise'] = [];
        $data['targetAchievementModel'] = [];
        if (isset($filter['emp_id'])) {
            $data['selected_employee'] = $this->employee->find($filter['emp_id']);
            $data['targetReportQuarterwise'] = $this->target->employeeTargetReportQuarterwise($filter['emp_id']);
            $data['targetAchievementModel'] = $this->target->findTargetDetailsByEmployee($filter['emp_id'], null);
            $data['ratingScaleModels'] = $this->ratingScale->findAll();
        }
        return view('pms::report.view-final-report', $data);
    }
    //


    //view target quarter page
    public function setTarget($id)
    {
        $data['employees'] = $this->employee->findAll()->pluck('full_name', 'id');
        $data['target'] = $this->target->findOne($id);
        return view('pms::target.set-target.index', $data);
    }
    //

    //set target value
    public function setTargetValue(TargetValueRequest $request)
    {
        $data = $request->all();
        try {
            $targetModel = $this->target->findOne($data['target_id']);

            if (isset($data['employee_ids']) && !empty($data['employee_ids'])) {
                foreach ($data['employee_ids'] as $employee_id) {
                    foreach ($data['target_values'] as $quarter => $target_value) {
                        $updateData = [
                            'employee_id' => $employee_id,
                            'target_id' => $data['target_id'],
                            'quarter' => $quarter,
                            'target_value' => $target_value,
                        ];
                        TargetAchievement::checkAndSetTargetValue($updateData, $targetModel);
                    }

                    // create notification for employee
                    $notified_user_id = $this->user->getUserId($employee_id)['id'];

                    $notificationData['creator_user_id'] = auth()->user()->id;
                    $notificationData['notified_user_id'] = $notified_user_id;
                    $notificationData['message'] = "Please set achieved values for different quarters for " . '<strong>' . $targetModel->title . '</strong>' . ' target';
                    $notificationData['link'] = route('target.viewTargetAchievement', $employee_id);
                    $notificationData['type'] = 'target_achievements';
                    $notificationData['type_id_value'] = $data['target_id'];
                    Notification::create($notificationData);
                    //
                }
            }
            toastr()->success('Target Value Set Successfully');
            return redirect(route('target.index'));
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }
    //

    public function employeeTargetView(Request $request)
    {
        $filter = $request->all();

        $data['employee'] = $this->employee->findAll()->pluck('full_name', 'id');
        $data['setting'] = $this->setting->getdata();
        $data['fiscalYear'] = FiscalYearSetup::currentFiscalYear();
        $data['targetAchievementModel'] = [];
        if (isset($filter['emp_id'])) {
            $data['targetAchievementModel'] = $this->target->findTargetDetailsByEmployee($filter['emp_id'], null);
            $data['employeeModel'] = $this->employee->find($filter['emp_id']);
        }
        return view('pms::target.employee-target-view.index', $data);
    }


    public function viewTargetAchievement($id)
    {
        $data['employee_id'] = $employee_id = $id;
        $data['targetAchievementModel'] = $this->target->findTargetDetailsByEmployee($employee_id, null);
        return view('pms::report.view-by-employee', $data);
    }

    public function updateAchievement(Request $request)
    {
        try {
            $data = $request->all();
            foreach ($data['achieved_value'] as $key => $achievedValue) {
                foreach ($achievedValue as $quarter => $value) {
                    $achieved_value = $data['achieved_value'][$key][$quarter];
                    $target_value = $data['target_value'][$key][$quarter];
                    $weightage = $data['weightage'][$key][$quarter];
                    $eligibility = $data['eligibility'][$key][$quarter];

                    if (!$achieved_value) {
                        $achieved_percent = ($achieved_value / $target_value) * 100;
                    } else {
                        $achieved_percent = 0;
                    }

                    $achieved_percent = ($achieved_value / $target_value) * 100;
                    if ($achieved_percent >= 100) {
                        $score = $weightage;
                    } elseif ($achieved_percent < $eligibility) {
                        $score = 0;
                    } else {
                        if ($achieved_percent > 0) {
                            $score = ($weightage * $achieved_percent) / 100;
                        } else {
                            $score = 0;
                        }
                    }
                    $remarks = $data['remarks'][$key];
                    $target = TargetAchievement::where('employee_id', $data['employee_id'])->where('target_id', $key)->where('quarter', $quarter + 1)->first();
                    $target->update([
                        'achieved_value' => $achieved_value,
                        'achieved_percent' => $achieved_percent,
                        'score' => $score,
                        'remarks' =>$remarks
                    ]);
                }
            }

            //save remarks
            // foreach ($data['remarks'] as $target_id => $target_remarks) {
            //     // if (isset($target_remarks)) {
            //     $targetData['remarks'] = $target_remarks;
            //     $this->target->update($target_id, $targetData);
            //     // }
            // }

            //save attachments
            if (isset($data['attachments']) && !empty($data['attachments'])) {
                foreach ($data['attachments'] as $target_id => $attachments) {
                    foreach ($attachments as $attachment) {
                        if (isset($attachment)) {
                            $this->uploadAttachment($target_id, $attachment);
                        }
                    }
                }
            }

            // create notification for first approval 
            $employeeApraisalApprovalData = EmployeeAppraisalApprovalFlow::where('employee_id', $data['employee_id'])->first();
            $notificationData['creator_user_id'] = auth()->user()->id;
            $notificationData['notified_user_id'] = $employeeApraisalApprovalData->first_approval;
            $notificationData['message'] = "Please review the achievement values set by ". optional(auth()->user()->userEmployer)->full_name;
            $notificationData['link'] = route('set-form.index');
            $notificationData['type'] = 'PMS';
            $notificationData['type_id_value'] = null;
            Notification::create($notificationData);
            //

            // if($employeeApprovalData->first_approval_user_id && $employeeApprovalData->is_first_approve && $employeeApprovalData->is_first_approve == 1){
            //     $notificationData['creator_user_id'] = auth()->user()->id;
            //     $notificationData['notified_user_id'] = $employeeApprovalData->first_approval_user_id;
            //     $notificationData['message'] = "Please review the achievement values set by employee.";
            //     $notificationData['link'] = route('set-form.index');
            //     $notificationData['type'] = 'PMS';
            //     $notificationData['type_id_value'] = null;
            //     Notification::create($notificationData);
            // }

            toastr()->success('Achieved Value Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    /**
     *
     */
    public function uploadAttachment($id, $file)
    {
        $fileDetail = TargetAttachment::saveFile($file);
        $modelData['target_id'] = $id;
        $modelData['title'] = $fileDetail['filename'];
        $modelData['extension'] = $fileDetail['extension'];
        $modelData['size'] = $fileDetail['size'];

        TargetAttachment::create($modelData);
    }

    public function viewDetailQuarterwise(Request $request){
       $filter = $request->all();

       $data['targetAchievement'] = $this->target->findTargetDetailsByEmployee($filter['employee_id'], $filter['quarter']);
       $data['quarter'] = $filter['quarter'];
       $view = view('pms::report.view-detail-quarterwise', $data)->render();
       return response()->json([
        'result' => $view,
       ]);
    }

    public function viewStaticReport() {
        return view('pms::target.partial.static-report');
    }
}
