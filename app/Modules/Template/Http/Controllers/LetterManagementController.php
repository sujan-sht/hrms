<?php

namespace App\Modules\Template\Http\Controllers;

use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Template\Entities\LetterManagement;
use App\Modules\Template\Repositories\TemplateInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LetterManagementController extends Controller
{
    protected $template;
    protected $employee;

    public function __construct(TemplateInterface $template, EmployeeInterface $employee)
    {
        $this->template = $template;
        $this->employee = $employee;

    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data['filter'] = $filter = $request->all();
        $data['letters'] = LetterManagement::latest()->paginate(20);
        $data['typeList'] = LetterManagement::typeList();
        return view('template::letter-management.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['employeeList'] = $this->employee->getListWithEmpCode();
        $data['typeList'] = LetterManagement::typeList();
        return view('template::letter-management.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try{
            $data = $request->all();
            LetterManagement::create($data);
            toastr('Letter Added Successfully','success');
            return redirect()->route('letterManagement.index');
        }catch(Exception $e){
            toastr('Error While Adding Letter','danger');
            return redirect()->route('letterManagement.index');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data['letter'] = LetterManagement::find($id);
        return view('template::letter-management.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['employeeList'] = $this->employee->getListWithEmpCode();
        $data['typeList'] = LetterManagement::typeList();
        $data['letter'] = LetterManagement::find($id);
        return view('template::letter-management.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        try{
            $data = $request->all();
            $letter = LetterManagement::find($id);
            $letter->update($data);
            toastr('Letter Updated Successfully','success');
        }catch(Exception $e){
            toastr('Error While Updating Letter','danger');
        }
        return redirect()->route('letterManagement.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try{
            $letter = LetterManagement::find($id);
            $letter->delete();
            toastr('Letter deleted Successfully','success');
        }catch(Exception $e){
            toastr('Error While Deleting Letter','danger');
        }
        return redirect()->back();

    }
}
