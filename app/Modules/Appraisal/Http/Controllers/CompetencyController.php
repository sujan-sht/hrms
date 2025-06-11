<?php

namespace App\Modules\Appraisal\Http\Controllers;

use App\Modules\Appraisal\Http\Requests\CompetencyRequest;
use App\Modules\Appraisal\Repositories\CompetencyInterface;
use App\Modules\Appraisal\Repositories\CompetencyQuestionInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CompetencyController extends Controller
{
    protected $competency;
    protected $competencyQuestion;

    public function __construct(CompetencyInterface $competency,CompetencyQuestionInterface $competencyQuestion)
    {
        $this->competency = $competency;
        $this->competencyQuestion = $competencyQuestion;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data['filter'] = $filter = $request->all();
        $data['competencies'] = $this->competency->findAll($limit=50, $filter);

        return view('appraisal::competency.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['is_edit'] = false;
        return view('appraisal::competency.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CompetencyRequest $request)
    {
        try{
            $data = $request->all();
            $newCompetency = $this->competency->save($data);

            foreach($data['questions'] as $question)
            {
                if($question != null)
                {
                    $questionData['competency_id'] = $newCompetency->id;
                    $questionData['question'] = $question;
                    $this->competencyQuestion->save($questionData);
                }
            }

            toastr('New Competency Added Successfully','success');
            return redirect()->route('competence.index');
        }catch(Exception $e){
            toastr('Error While Adding Competency','error');
            return redirect()->route('competence.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['is_edit'] = true;
        $data['competency'] = $this->competency->findOne($id);
        return view('appraisal::competency.edit',$data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        $data['competency'] = $this->competency->findOne($request->id);
        return view('appraisal::competency.partial.view-detail',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CompetencyRequest $request, $id)
    {
        try{
            $data = $request->all();
            $competencyInfo = $this->competency->update($id,$data);
            $this->competency->findOne($id)->questions()->delete();

            foreach($data['questions'] as $question)
            {
                if($question != null)
                {
                    $questionData['competency_id'] = $competencyInfo->id;
                    $questionData['question'] = $question;
                    $this->competencyQuestion->save($questionData);
                }
            }

            $this->competency->update($id,$data);
            toastr('Competency Updated Successfully','success');
            return redirect()->route('competence.index');
        }catch(Exception $e){
            toastr('Error While Updating Competency','error');
            return redirect()->route('competence.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try{
            $this->competency->delete($id);
            toastr('Competency Deleted Successfully','success');
            return redirect()->route('competence.index');
        }catch(Exception $e){
            toastr('Error While Deleting Competency','error');
            return redirect()->route('competence.index');
        }
    }

    public function addMoreQuestion()
    {
        $view = view('appraisal::competency.partial.add-more-question')->render();
        return response()->json(['result' => $view]);
    }
}
