<?php

namespace App\Modules\Appraisal\Http\Controllers;

use App\Modules\Appraisal\Http\Requests\CompetencyLibraryRequest;
use App\Modules\Appraisal\Repositories\CompetencyLibraryInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CompetencyLibraryController extends Controller
{
    protected $competencyLibrary;

    public function __construct(CompetencyLibraryInterface $competencyLibrary)
    {
        $this->competencyLibrary = $competencyLibrary;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data['filter'] = $filter = $request->all();
        $data['competencyLibraries'] = $this->competencyLibrary->findAll($limit=50, $filter);

        return view('appraisal::competency-library.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['is_edit'] = false;
        return view('appraisal::competency-library.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CompetencyLibraryRequest $request)
    {
        try{
            $data = $request->all();
            $this->competencyLibrary->save($data);
            toastr('New Competency Library Added Successfully','success');
            return redirect()->route('competenceLibrary.index');
        }catch(Exception $e){
            toastr('Error While Adding Competency Library','error');
            return redirect()->route('competenceLibrary.index');
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
        $data['competencyLibrary'] = $this->competencyLibrary->findOne($id);
        return view('appraisal::competency-library.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CompetencyLibraryRequest $request, $id)
    {
        try{
            $data = $request->all();
            $this->competencyLibrary->update($id,$data);
            toastr('Competency Library Updated Successfully','success');
            return redirect()->route('competenceLibrary.index');
        }catch(Exception $e){
            toastr('Error While Updating Competency Library','error');
            return redirect()->route('competenceLibrary.index');
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
            $this->competencyLibrary->delete($id);
            toastr('Competency Library Deleted Successfully','success');
            return redirect()->route('competenceLibrary.index');
        }catch(Exception $e){
            toastr('Error While Deleting Competency Library','error');
            return redirect()->route('competenceLibrary.index');
        }
    }
}
