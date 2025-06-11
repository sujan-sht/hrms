<?php

namespace App\Modules\Appraisal\Http\Controllers;

use App\Modules\Appraisal\Http\Requests\RatingScaleRequest;
use App\Modules\Appraisal\Repositories\RatingScaleInterface as RepositoriesRatingScaleInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RatingScaleController extends Controller
{
    protected $ratingScale;

    public function __construct(RepositoriesRatingScaleInterface $ratingScale)
    {
        $this->ratingScale = $ratingScale;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data['filter'] = $filter = $request->all();
        $data['ratingScales'] = $this->ratingScale->findAll($limit=50, $filter);

        return view('appraisal::rating-scale.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['is_edit'] = false;
        return view('appraisal::rating-scale.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(RatingScaleRequest $request)
    {
        try{
            $data = $request->all();
            $this->ratingScale->create($data);
            toastr('New Rating scale Added Successfully','success');
            return redirect()->route('ratingScale.index');
        }catch(Exception $e){
            toastr('Error While Adding Competency Library','error');
            return redirect()->route('ratingScale.index');
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
        $data['ratingScale'] = $this->ratingScale->findOne($id);
        return view('appraisal::rating-scale.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(RatingScaleRequest $request, $id)
    {
        try{
            $data = $request->all();
            $this->ratingScale->update($id,$data);
            toastr('Rating Scale Updated Successfully','success');
            return redirect()->route('ratingScale.index');
        }catch(Exception $e){
            toastr('Error While Updating Rating Scale','error');
            return redirect()->route('ratingScale.index');
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
            $this->ratingScale->delete($id);
            toastr('Rating Scale Deleted Successfully','success');
            return redirect()->route('ratingScale.index');
        }catch(Exception $e){
            toastr('Error While Deleting rating Scale','error');
            return redirect()->route('ratingScale.index');
        }
    }
}
