<?php

namespace App\Modules\Template\Http\Controllers;

use App\Modules\Template\Http\Requests\TemplateTypeRequest;
use App\Modules\Template\Repositories\TemplateTypeInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TemplateTypeController extends Controller
{

    protected $templateType;

    public function __construct(TemplateTypeInterface $templateType)
    {
        $this->templateType = $templateType;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data['templateTypes'] = $this->templateType->findAll();
        return view('template::template-type.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('template::template-type.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(TemplateTypeRequest $request)
    {
        try{
            $data = $request->all();
            $this->templateType->create($data);
            toastr('Template Type Added Successfully','success');
            return redirect()->route('templateType.index');
        }catch(Exception $e){
            toastr('Error While Adding Template Type','success');
            return redirect()->route('templateType.index');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('template::template-type.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['templateType'] = $this->templateType->findOne($id);
        return view('template::template-type.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(TemplateTypeRequest $request, $id)
    {
        try{
            $data = $request->all();
            $this->templateType->update($id,$data);
            toastr('Template Type Updated Successfully','success');
            return redirect()->route('templateType.index');
        }catch(Exception $e){
            toastr('Error While Updating Template Type','success');
            return redirect()->route('templateType.index');
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
            $this->templateType->delete($id);
            toastr('Template Type Deleted Successfully','success');
            return redirect()->route('cheatSheet.index');
        }catch(Exception $e){
            toastr('Error While deleting Template Type','success');
            return redirect()->route('cheatSheet.index');
        }   
    }
}
