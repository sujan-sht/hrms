<?php

namespace App\Modules\Survey\Http\Controllers;

use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Repositories\DepartmentInterface;
use App\Modules\Setting\Repositories\LevelInterface;
use App\Modules\Survey\Http\Requests\SurveyAllocateRequest;
use App\Modules\Survey\Repositories\SurveyInterface;
use App\Modules\Survey\Repositories\SurveyQuestionInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SurveyController extends Controller
{
    private $survey;
    private $surveyQuestion;
    private $organization;
    private $dropdown;
    private $employee;
    private $department;
    private $level;


    public function __construct(
        SurveyInterface $survey,
        SurveyQuestionInterface $surveyQuestion,
        OrganizationInterface $organization,
        DropdownInterface $dropdown,
        EmployeeInterface $employee,
        DepartmentInterface $department,
        LevelInterface $level
    ) {
        $this->survey = $survey;
        $this->surveyQuestion = $surveyQuestion;
        $this->organization = $organization;
        $this->dropdown = $dropdown;
        $this->employee = $employee;
        $this->department = $department;
        $this->level = $level;
}

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        // if(auth()->user()->user_type == 'division_hr') {
        //     $filter['created_by'] = auth()->user()->id;
        // }
        $sort = [
            'by' => 'id',
            'sort' => 'desc'
        ];
        $data['surveyModels'] = $this->survey->findAll(10, $filter, $sort);
        return view('survey::survey.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        return view('survey::survey.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData = $request->except('_token');
        try {
            $inputData['title'] = trim($inputData['title']);
            $inputData['created_by'] = auth()->user()->id;
            $this->survey->save($inputData);
            toastr()->success('Survey Added Successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong !!!');
        }
        return redirect()->route('survey.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('survey::survey-question.create');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['surveyModel'] = $this->survey->find($id);
        $data['isEdit'] = true;
        return view('survey::survey.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $inputData = $request->all();
        try {
            $inputData['updated_by'] = auth()->user()->id;
            $this->survey->update($id, $inputData);
            toastr()->success('Survey Updated Successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong !!!');
        }
        return redirect()->route('survey.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $resp = $this->survey->delete($id);
            if ($resp) {
                $this->survey->deleteSurveyQuestion($id);
                $this->survey->deleteSurveyParticipants($id);
                $this->survey->deleteSurveyAnswers($id);
            }
            toastr()->success('Survey Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    public function allocateForm($id)
    {
        $data['survey_id'] = $id;
        $data['organizationList'] = $this->organization->getList();
        $data['departmentList'] = $this->department->getList();
        $data['levelList'] = $this->level->getList();
        return view('survey::survey.allocation.create', $data);
    }

    public function allocate(SurveyAllocateRequest $request)
    {
        $inputData = $request->except('_token');
        try {
            $this->survey->updateOrCreateSurveyParticipant($inputData);

            toastr()->success('Survey Allocated Successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong !!!');
        }
        return redirect()->route('survey.index');
    }

    public function allocationList(Request $request)
    {
        $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'desc'
        ];
        $data['surveyModels'] = $this->survey->findAll(5, $filter, $sort);
        return view('survey::survey.allocation.index', $data);
    }

    public function viewSurveyByEmpoyee($id)
    {
        $data['surveyModel'] = $this->survey->find($id);
        return view('survey::survey.employee-view', $data);
    }

    public function storeResponse(Request $request)
    {
        $inputData = $request->except('_token');
       try {
            $data['survey_id'] = $inputData['survey_id'];
            $data['employee_id'] = $inputData['employee_id'];

            if (isset($inputData['survey_questions']) && !empty($inputData['survey_questions'])) {
                foreach ($inputData['survey_questions'] as $survey_qn_id => $answer) {
                    $data['survey_question_id'] = $survey_qn_id;
                    $data['answer'] = json_encode($answer);
                    $this->survey->saveSurveyAnswer($data);
                }
            }
            toastr()->success('Survey Answers Submitted Successfully !!!');
            return redirect('/admin/dashboard');
       } catch (\Throwable $th) {
            toastr()->error('Something went wrong !!!');
            return redirect('/admin/dashboard');
       }
    }

    public function viewSurveyReportByEmpoyee($survey_id)
    {
        $inputData = [
            'survey_id' => $survey_id,
            'employee_id' => optional(auth()->user())->userEmployer->id,
        ];
        $data['surveyTitle'] = $this->survey->find($survey_id)->title;
        $data['surveyResponses'] = $this->survey->getEmployeeSurveyResponse($inputData);
        return view('survey::survey.employee-response-report', $data);
    }

    public function viewReport($id) {
        $data['surveyQuestions'] = $surveyQuestions = $this->surveyQuestion->questionLists($id);
        $employeeLists = $this->survey->responseGivenEmployeeList($id);
        $report = [];
        foreach ($employeeLists as $employeeId ) {
            $employee = $this->employee->find($employeeId);
            $report[$employeeId]['fullName'] = $employee->full_name;
            $report[$employeeId]['image'] = $employee->getImage();
            $report[$employeeId]['code'] = $employee->employee_code;

            foreach ($surveyQuestions as $surveyQuestionId=>$question) {
                $input = [
                    'employeeId' => $employeeId,
                    'surveyId' => $id,
                    'surveyQnId' => $surveyQuestionId
                ];
                $response = $this->survey->checkResponseSubmitted($input);
                if($response){
                    $report[$employeeId]['responses'][$surveyQuestionId] = $response->answer ? implode(", ", json_decode($response->answer, true)) : '';
                }
            }
        }
        $data['surveyResponses'] = $report;
        return view('survey::survey.report.final-report', $data);
    }
}
