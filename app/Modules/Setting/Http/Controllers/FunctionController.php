<?php

namespace App\Modules\Setting\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Setting\Entities\Functional;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Setting\Entities\FunctionOrganization;
use App\Modules\Setting\Repositories\FunctionInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;

class FunctionController extends Controller
{


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];

        $departmentModel  = Functional::query();
        $departmentModel->when(true, function ($query) use ($filter) {});
        $data['functions'] = $departmentModel->orderBy($sort['by'], $sort['sort'])->paginate(25);
        return view('setting::function.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        return view('setting::function.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $data = $request->except('_token');
            Functional::create($data);
            toastr('Function added Successfully', 'success');
        } catch (\Throwable $th) {
            toastr('Error While Adding Function', 'error');
        }
        return redirect()->route('function.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */

    // public function viewDetail($id)
    // {
    //     $data['department'] = $this->function->find($id);
    //     return view('setting::function.show', $data);
    // }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['id'] = $id;
        $data['function'] = Functional::find($id);
        $data['isEdit'] = true;
        return view('setting::function.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            $function = Functional::find($id);
            $function->update($data);
            toastr('Function Updated Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Updating Function', 'error');
        }
        return redirect()->route('function.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $function = FUnctional::find($id);
            $function->delete();
            toastr('Function Deleted Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Deleting Function', 'error');
        }
        return redirect()->route('function.index');
    }
}
