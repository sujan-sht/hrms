<?php

namespace App\Modules\Appraisal\Http\Controllers;

use App\Modules\Appraisal\Http\Requests\QuestionnaireRequest;
use App\Modules\Appraisal\Repositories\AppraisalInterface;
use App\Modules\Appraisal\Repositories\CompetencyInterface;
use App\Modules\Appraisal\Repositories\CompetencyLibraryInterface;
use App\Modules\Appraisal\Repositories\CompetencyQuestionInterface;
use App\Modules\Appraisal\Repositories\QuestionnaireInterface;
use App\Modules\Appraisal\Repositories\ScoreInterface;
use App\Modules\FiscalYearSetup\Repositories\FiscalYearSetupInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class QuestionnaireController extends Controller
{
    protected $questionnaire;
    protected $competencies;
    protected $competency_libraries;
    protected $appraisal;
    protected $competency_questions;
    protected $score;
    protected $fiscalYear;

    public function __construct(QuestionnaireInterface $questionnaire, CompetencyInterface $competencies, CompetencyLibraryInterface $competency_libraries, AppraisalInterface $appraisal, CompetencyQuestionInterface $competency_questions,ScoreInterface $score,FiscalYearSetupInterface $fiscalYear)
    {
        $this->questionnaire = $questionnaire;
        $this->competencies = $competencies;
        $this->competency_libraries = $competency_libraries;;
        $this->appraisal = $appraisal;
        $this->competency_questions = $competency_questions;
        $this->score = $score;
        $this->fiscalYear = $fiscalYear;
    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data['filter'] = $filter = $request->all();
        $data['questionnaires'] = $this->questionnaire->findAll($limit = 50, $filter);

        return view('appraisal::questionnaires.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['is_edit'] = false;
        $data['competency_libraries'] = $this->competency_libraries->findAll()->pluck('title', 'id');
        $data['competencies'] = $this->competencies->findAll()->pluck('name', 'id');
        return view('appraisal::questionnaires.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(QuestionnaireRequest $request)
    {
        try {
            $data = $request->all();
            $data['competency_ids'] = json_encode($request->competency_ids);
            if (setting('calendar_type') == 'BS'){
                if(!is_null($data['roll_out_date'])){
                    $data['roll_out_date'] = date_converter()->nep_to_eng_convert($data['roll_out_date']);
                }
            }
            $this->questionnaire->save($data);

            toastr('New Questionnaire Added Successfully', 'success');
            return redirect()->route('questionnaire.index');
        } catch (Exception $e) {
            dd($e->getMessage());
            toastr('Error While Adding Questionnaire', 'error');
            return redirect()->route('questionnaire.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        $data['questionnaire'] = $questionnaire = $this->questionnaire->findOne($request->id);

        $data['competencies'] = $this->competencies->findAll()->whereIn('id',json_decode($questionnaire->competency_ids));

        return view('appraisal::questionnaires.partial.view-detail', $data);
    }


    public function showForm(Request $request){
        $data['fiscalYear'] = $this->fiscalYear->getFiscalYear();
        $data['fields'] = ['Frequency', 'Ability', 'Effectiveness'];
        $data['frequencies'] = $this->score->findAll()->pluck('frequency');
        $data['abilities'] = $this->score->findAll()->pluck('ability');
        $data['effectiveness'] = $this->score->findAll()->pluck('effectiveness');
        $data['questionnaire'] = $questionnaire = $this->questionnaire->findOne($request->id);
        $data['competencies'] = $this->competencies->findAll()->whereIn('id',json_decode($questionnaire->competency_ids));
        // dd($data['competencies']);
        $data['competencyQuestions'] = $this->competency_questions->findAll()->whereIn('competency_id',json_decode($questionnaire->competency_ids));
        return view('appraisal::questionnaires.show-form',$data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['is_edit'] = true;
        $data['competency_libraries'] = $this->competency_libraries->findAll()->pluck('title', 'id');
        $data['questionnaire'] = $this->questionnaire->findOne($id);
        $data['competencies'] = $this->competencies->findAll()->pluck('name', 'id');
        if (setting('calendar_type') == 'BS'){
            if(!is_null($data['questionnaire']['roll_out_date'])){
                $data['questionnaire']['roll_out_date'] = date_converter()->eng_to_nep_convert($data['questionnaire']['roll_out_date']);
            }
        }
        return view('appraisal::questionnaires.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(QuestionnaireRequest $request, $id)
    {
        try {
            $data = $request->all();
            if (setting('calendar_type') == 'BS'){
                if(!is_null($data['roll_out_date'])){
                    $data['roll_out_date'] = date_converter()->nep_to_eng_convert($data['roll_out_date']);
                }
            }
            $this->questionnaire->update($id, $data);
            toastr('Questionnaire Updated Successfully', 'success');
            return redirect()->route('questionnaire.index');
        } catch (Exception $e) {
            toastr('Error While Updating Questionnaire', 'error');
            return redirect()->route('questionnaire.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->questionnaire->delete($id);
            toastr('Questionnaire Deleted Successfully', 'success');
            return redirect()->route('questionnaire.index');
        } catch (Exception $e) {
            toastr('Error While Deleting Questionnaire', 'error');
            return redirect()->route('questionnaire.index');
        }
    }
}
