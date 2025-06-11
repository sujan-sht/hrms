<?php

namespace App\Modules\Labour\Http\Controllers;

use App\Modules\Labour\Entities\SkillSetup;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SkillSetupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data['skills']=SkillSetup::latest()->get();
        return view('labour::index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('labour::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData=$request->validate([
            'category'=>'required|string|max:200',
            'daily_wage'=>'required|numeric'
        ]);
        try{
            SkillSetup::create($inputData);
        }catch(\Throwable $e){
            toastr()->error($e->getMessage());
        }
        toastr()->success('Skill Setup Created Successfully.');
        return redirect()->route('skillSetup.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('labour::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['skill']=SkillSetup::find($id);
        return view('labour::edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $inputData=$request->validate([
            'category'=>'required|string|max:200',
            'daily_wage'=>'required|numeric'
        ]);
        try{
            $data['skill']=SkillSetup::find($id);
            $data['skill']->update($inputData);
        }catch(\Throwable $e){
            toastr()->error($e->getMessage());
        }
        toastr()->success('Skill Setup edited Successfully.');
        return redirect()->route('skillSetup.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try{
            $skill=SkillSetup::find($id);
            $skill->delete();
        }catch(\Throwable $e){
            toastr()->error($e->getMessage());
        }
        toastr()->success('Skill Setup deleted Successfully.');
        return redirect()->route('skillSetup.index');
    }
}
