<?php

namespace App\Modules\Training\Http\Controllers;

use App\Modules\Admin\Entities\DateConverter;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\FiscalYearSetup\Repositories\FiscalYearSetupInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Repositories\DepartmentInterface;
use App\Modules\Template\Repositories\TemplateInterface;
use App\Modules\Template\Repositories\TemplateTypeInterface;
use App\Modules\Training\Entities\Training;
use App\Modules\Training\Repositories\TrainingInterface;
use App\Modules\Training\Http\Requests\CreateTrainingRequest;
use App\Modules\Training\Repositories\TrainingAttendanceInterface;
use App\Modules\Training\Repositories\TrainingParticipantInterface;
use App\Modules\Training\Repositories\TrainingTrainerInterface;

class TrainingController extends Controller
{
    protected $training;
    protected $trainingParticipant;
    protected $trainingAttendance;
    protected $dropdown;
    protected $fiscalYear;
    private $template;
    private $templateType;
    private $employee;
    private $organizationObj;
    private $trainingTrainer;
    private $department;

    public function __construct(
        TrainingInterface $training,
        DropdownInterface $dropdown,
        TrainingParticipantInterface $trainingParticipant,
        TrainingTrainerInterface $trainingTrainer,
        TrainingAttendanceInterface $trainingAttendance,
        FiscalYearSetupInterface $fiscalYear,
        TemplateInterface $template,
        TemplateTypeInterface $templateType,
        EmployeeInterface $employee,
        OrganizationInterface $organizationObj,
        DepartmentInterface $department
    ) {
        $this->training = $training;
        $this->trainingParticipant = $trainingParticipant;
        $this->trainingAttendance = $trainingAttendance;
        $this->dropdown = $dropdown;
        $this->fiscalYear = $fiscalYear;
        $this->template = $template;
        $this->templateType = $templateType;
        $this->employee = $employee;
        $this->organizationObj = $organizationObj;
        $this->trainingTrainer = $trainingTrainer;
        $this->department = $department;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        if(auth()->user()->user_type == 'division_hr') {
            $filter['organization'] = optional(auth()->user()->userEmployer)->organization_id;
        }
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['trainingModels'] = $this->training->findAll(20, $filter, $sort);
        // $data['divisionList'] = $this->dropdown->getFieldBySlug('division');
        $data['organizationList'] = $this->organizationObj->getList();
        $data['monthList'] = $this->dropdown->getFieldBySlug('month');
        $data['fiscalYearList'] = $this->fiscalYear->getCurrentFiscalYear();
        return view('training::training.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        // $data['divisionList'] = $this->dropdown->getFieldBySlug('division');
        $data['organizationList'] = $this->organizationObj->getList();
        $data['departmentList'] = $this->department->getList();

        // $data['monthList'] = $this->dropdown->getFieldBySlug('month');
        $data['monthList'] = (new DateConverter())->getNepMonths();

        $fiscalYearList = $this->fiscalYear->getCurrentFiscalYear();
        if ($fiscalYearList->isNotEmpty() && $fiscalYearList->count() > 0) {
            $data['fiscalYearList'] = $fiscalYearList;
        } else {
            toastr()->error('Please set Active Fiscal Year first !!!');
            return redirect(route('fiscalYearSetup.index'));
        }
        return view('training::training.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    //CreateTrainingRequest
    public function store(Request $request)
    {
        $inputData = $request->all();
        $inputData['from_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($inputData['from_date']) : $inputData['from_date'];
        $inputData['to_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($inputData['to_date']) : $inputData['to_date'];
        $inputData['date'] = date('Y-m-d');
        $inputData['created_by'] = Auth::user()->id;
        try {
            $this->training->create($inputData);
            toastr()->success('Training Created Successfully');
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('training.index'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('training::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['trainingModel'] = $this->training->findOne($id);
        $data['organizationList'] = $this->organizationObj->getList();
        $data['departmentList'] = $this->department->getList();

        // $data['divisionList'] = $this->dropdown->getFieldBySlug('division');
        // $data['monthList'] = $this->dropdown->getFieldBySlug('month');
        $data['monthList'] = (new DateConverter())->getNepMonths();

        $data['fiscalYearList'] = $this->fiscalYear->getCurrentFiscalYear();

        return view('training::training.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['from_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['from_date']) : $data['from_date'];
        $data['to_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['to_date']) : $data['to_date'];
        $data['updated_by'] = Auth::user()->id;
        try {
            $this->training->update($id, $data);

            toastr()->success('Training Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('training.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $trainingId = $this->training->delete($id);
            if ($trainingId) {
                $this->trainingParticipant->deleteParticipant($id);
                $this->trainingAttendance->deleteAttendance($id);
            }
            toastr()->success('Training Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    public function viewReport()
    {
        $data['facilitation'] = $this->training->countFacilitation();
        $data['location'] = $this->training->countLocation();
        $data['type'] = $this->training->countType();
        $data['no_of_mandays_month_division_wise'] = $this->training->no_of_mandays_month_and_division_wise();

        return view('training::training-report.view-report', $data);
    }

    public function viewTrainingMISReport()
    {
        $data['trainingModels'] = $this->training->findAll();
        return view('training::training-report.MIS-view-report', $data);
    }

    public function viewAttendeesDetailReport(Request $request)
    {
        $filter = $request->all();
        if(auth()->user()->user_type == 'division_hr') {
            $filter['organization'] = optional(auth()->user()->userEmployer)->organization_id;
        }
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['attendanceModels'] = $this->trainingAttendance->trainingAttendeesDetails(50, $filter, $sort);
        // dd($data['attendanceModels']);
        $data['trainingList'] = $this->training->getList();
        return view('training::training-report.attendees-detail-report', $data);
    }

    public function viewTrainingAttendees(Request $request, $id)
    {
        $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $attendeeDetails = $this->trainingAttendance->attendeesAllDetails($id, 20, $filter, $sort);
        $data['training_id'] = $id;
        if (isset($attendeeDetails) && !empty($attendeeDetails)) {
            $data['attendeeDetails'] = $attendeeDetails;
        } else {
            $data['attendeeDetails'] = [];
        }
        $data['employeeList'] = $this->employee->getListWithEmpCode();
        return view('training::training.partial.view-training-attendees', $data);
    }

    public function viewTrainingCertificate($training_id, $id)
    {
        $data = $this->makeTrainingCertificate($id);

        // $data['attendeeData'] = $this->trainingAttendance->findOne($id);
        // $data['training_id'] = $training_id;
        return view('training::training.partial.view-training-certificate', $data);
    }

    public function printTrainingCertificate($training_id, $id)
    {
        $data = $this->makeTrainingCertificate($id);
        return view('training::training.partial.print-training-certificate', $data);
    }

    public function makeTrainingCertificate($id)
    {
        $html = '';
        $templateType = $this->templateType->findBySlug('training_certificate');
        if ($templateType) {
            $html = $this->template->findByTemplateType($templateType->id)->text;
        }

        $vars = [];
        $trainingAttendance = $this->trainingAttendance->findOne($id);
        if ($trainingAttendance) {
            $vars = array(
                "[CURRENT_DATE]" => date('M d, Y'),
                "[EMPLOYEE_NAME]" => optional($trainingAttendance->employeeModel)->full_name,
                "[TRAINING_TITLE]" => optional($trainingAttendance->trainingInfo)->title,
                "[TRAINING_LOCATION]" => optional($trainingAttendance->trainingInfo)->location,
                "[START_DATE]" => optional($trainingAttendance->trainingInfo)->from_date,
                "[END_DATE]" => optional($trainingAttendance->trainingInfo)->to_date,
                "[TRAINING_TYPE]" => optional($trainingAttendance->trainingInfo)->type,
                "[FACILITATOR_TYPE]" => optional($trainingAttendance->trainingInfo)->facilitator,
                "[FACILITATOR]" => optional($trainingAttendance->trainingInfo)->facilitator_name,

                "[COMPANY_NAME]" => "Bidhee Pvt. Ltd.",
            );
        }
        $data['final_html'] = strtr($html, $vars);
        // dd( $data['final_html']);
        return $data;
    }

    public function storeTrainer(Request $request)
    {
        $data = $request->all();
        // dd($data);
        try {
            $this->trainingTrainer->updateOrCreate($data);
            toastr()->success('Trainer Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('training.index'));
    }
}
