<?php

namespace App\Modules\Onboarding\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Onboarding\Entities\Evaluation;
use App\Modules\Onboarding\Repositories\MrfInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Onboarding\Entities\InterviewLevelQuestion;
use App\Modules\Onboarding\Repositories\ApplicantInterface;
use App\Modules\Onboarding\Repositories\InterviewInterface;
use App\Modules\Onboarding\Repositories\EvaluationInterface;
use App\Modules\Onboarding\Repositories\InterviewLevelInterface;

class EvaluationController extends Controller
{
    private $evaluationObj;
    private $interviewObj;
    private $applicantObj;
    private $interviewLevelObj;
    private $mrfObj;
    private $employeeObj;

    /**
     * Constructor
     */
    public function __construct(
        EvaluationInterface $evaluationObj,
        InterviewInterface $interviewObj,
        ApplicantInterface $applicantObj,
        InterviewLevelInterface $interviewLevelObj,
        MrfInterface $mrfObj,
        EmployeeInterface $employeeObj
    ) {
        $this->evaluationObj = $evaluationObj;
        $this->interviewObj = $interviewObj;
        $this->applicantObj = $applicantObj;
        $this->interviewLevelObj = $interviewLevelObj;
        $this->mrfObj = $mrfObj;
        $this->employeeObj = $employeeObj;
    }

    /**
     *
     */
    public function getCurrentUserDetail()
    {
        return User::where('id', Auth::user()->id)->first();
    }

    /**
     *
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        if(auth()->user()->user_type == 'division_hr') {
            $filter['organization'] = optional(auth()->user()->userEmployer)->organization_id;
        }

        $filter['parent'] = true;

        $data['evaluationModels'] = $this->evaluationObj->findAll(20, $filter);

        $data['mrfList'] = $this->mrfObj->getListWithTitle();

        return view('onboarding::evaluation.index', $data);
    }

    /**
     *
     */
    public function subIndex(Request $request)
    {
        $filter = $request->all();

        $data['evaluationModels'] = $this->evaluationObj->findAll(20, $filter);
        $data['mrfList'] = $this->mrfObj->getListWithTitle();

        return view('onboarding::evaluation.sub-index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $inputData = $request->all();

        $data['isEdit'] = false;
        $data['questionsList'] = [];
        $data['scoreList'] = Evaluation::GetScoreList();
        $data['interviewerList'] = $this->employeeObj->getList();
        if (isset($inputData['interview'])) {
            $interviewModel = $this->interviewObj->findOne($inputData['interview']);
            $data['interviewModel'] = $interviewModel;
            $data['questionsList'] = optional($interviewModel->interviewLevelModel)->getQuestionModels;
        }

        return view('onboarding::evaluation.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $inputData = $request->all();
        try {
            $this->evaluationObj->create($inputData);

            toastr()->success('Data Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('evaluation.index'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $data['evaluationModel'] = $this->evaluationObj->findOne($id);

        return view('onboarding::evaluation.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $evaluationModel = $this->evaluationObj->findOne($id);
        $data['evaluationModel'] = $evaluationModel;
        $interviewModel = $this->interviewObj->findOne($evaluationModel->interview_id);
        $data['interviewModel'] = $interviewModel;
        $data['questionsList'] = optional($interviewModel->interviewLevelModel)->getQuestionModels;

        $data['scoreList'] = Evaluation::GetScoreList();
        $data['applicantList'] = $this->applicantObj->getList();
        $data['interviewLevelList'] = $this->interviewLevelObj->getList();
        $data['interviewerList'] = $this->employeeObj->getList();

        return view('onboarding::evaluation.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $inputData = $request->all();

        try {
            $this->evaluationObj->update($id, $inputData);

            toastr()->success('Data Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('interview.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->evaluationObj->delete($id);

            toastr()->success('Data Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    /**
     *
     */
    public function updateStatus(Request $request)
    {
        $inputData = $request->all();

        try {
            $this->evaluationObj->update($inputData['id'], $inputData);

            toastr()->success('Status Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    /**
     *
     */
    public function report($id)
    {
        $filter['parent_id'] = $id;

        $data['parentModel'] = $model = $this->evaluationObj->findOne($id);
        $data['evaluationModels'] = $this->evaluationObj->findAll(100, $filter);
        $data['interviewQuestionModels'] = InterviewLevelQuestion::where('interview_level_id', $model->interview_level_id)->get();

        return view('onboarding::evaluation.report', $data);
    }
}
