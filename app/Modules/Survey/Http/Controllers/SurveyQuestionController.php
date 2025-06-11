<?php

namespace App\Modules\Survey\Http\Controllers;

use App\Modules\Poll\Entities\Poll;
use App\Modules\Survey\Entities\SurveyQuestion;
use App\Modules\Survey\Repositories\SurveyInterface;
use App\Modules\Survey\Repositories\SurveyQuestionInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SurveyQuestionController extends Controller
{
    private $surveyQuestion;
    private $survey;


    public function __construct(
        SurveyQuestionInterface $surveyQuestion,
        SurveyInterface $survey
    ) {
        $this->surveyQuestion = $surveyQuestion;
        $this->survey = $survey;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request, $survey_id)
    {
        $filter = $request->all();
        $filter['survey_id'] = $survey_id;
        $sort = [
            'by' => 'id',
            'sort' => 'desc'
        ];
        $data['survey_id'] = $survey_id;
        $data['surveyQuestionModels'] = $this->surveyQuestion->findAll(10, $filter, $sort);
        $data['questionType'] = SurveyQuestion::questionType();
        $data['multipleOptionStatus'] = Poll::multipleOptionStatus();
        return view('survey::survey-question.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($survey_id)
    {
        $data['isEdit'] = false;
        $data['surveyModel'] = $this->survey->find($survey_id);
        $data['questionType'] = SurveyQuestion::questionType();
        $data['multipleOptionStatus'] = Poll::multipleOptionStatus();
        return view('survey::survey-question.create', $data);
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
            $inputData['created_by'] = auth()->user()->id;
            if($inputData['question_type'] != 1){
                $inputData['option_1'] = $inputData['option_2'] = $inputData['option_3'] = $inputData['option_4'] = null;
            }
            $this->surveyQuestion->save($inputData);

            toastr()->success('Survey Question Added Successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong !!!');
        }
        return redirect()->back();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('survey::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($survey_id, $id)
    {
        $data['isEdit'] = true;
        $data['surveyModel'] = $this->survey->find($survey_id);
        $data['surveyQuestionModel'] = $this->surveyQuestion->find($id);
        $data['questionType'] = SurveyQuestion::questionType();
        $data['multipleOptionStatus'] = Poll::multipleOptionStatus();
        $data['surveyList'] = $this->survey->getList();
        return view('survey::survey-question.edit', $data);
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
            if($inputData['question_type'] != 1){
                $inputData['option_1'] = $inputData['option_2'] = $inputData['option_3'] = $inputData['option_4'] = null;
            }
            $this->surveyQuestion->update($id, $inputData);
            toastr()->success('Survey Question Updated Successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong !!!');
        }
        return redirect()->route('surveyQuestion.index', $inputData['survey_id']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->surveyQuestion->delete($id);
            toastr()->success('Survey Question Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }
}
