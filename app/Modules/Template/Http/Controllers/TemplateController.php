<?php

namespace App\Modules\Template\Http\Controllers;

use App\Modules\Template\Http\Requests\TemplateRequest;
use App\Modules\Template\Repositories\CheatSheetInterface;
use App\Modules\Template\Repositories\TemplateInterface;
use App\Modules\Template\Repositories\TemplateTypeInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TemplateController extends Controller
{
    protected $templateType;
    protected $template;
    protected $cheatSheet;

    public function __construct(TemplateTypeInterface $templateType, TemplateInterface $template, CheatSheetInterface $cheatSheet)
    {
        $this->templateType = $templateType;
        $this->template = $template;
        $this->cheatSheet = $cheatSheet;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data['filter'] = $filter = $request->all();
        $data['templateTypes'] = $this->templateType->findAll($limit=50, $filter);
        $data['templateTypesList'] = $this->templateType->findAll()->pluck('title','id');
        $data['existingTemplateTypes'] = $this->template->findAll()->pluck('template_type_id')->toArray();
        return view('template::template.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        $data['cheatSheets'] = $this->cheatSheet->findAll();
        $data['templateType'] = $this->templateType->findOne($id);
        $data['template_type_id'] = $id;
        return view('template::template.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(TemplateRequest $request)
    {
        try{
            $data = $request->all();
            $this->template->create($data);
            toastr('Template Added Successfully','success');
            return redirect()->route('template.index');
        }catch(Exception $e){
            toastr('Error While Adding Template','success');
            return redirect()->route('template.index');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data['templateType'] = $templateType = $this->templateType->findOne($id);
        $data['template'] = $this->template->findByTemplateType($templateType->id);
        return view('template::template.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['cheatSheets'] = $this->cheatSheet->findAll();
        $data['templateType'] = $templateType = $this->templateType->findOne($id);
        $data['template'] = $this->template->findByTemplateType($templateType->id);
        $data['template_type_id'] = $id;
        return view('template::template.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(TemplateRequest $request, $id)
    {
        try{
            $data = $request->all();
            // dd($data);
            $this->template->update($id,$data);
            toastr('Template Updated Successfully','success');
            return redirect()->route('template.index');
        }catch(Exception $e){
            toastr('Error While Updating Template','success');
            return redirect()->route('template.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {

    }
}
