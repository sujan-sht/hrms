<?php

namespace App\Modules\Setting\Http\Controllers;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Entities\DepartmentOrganization;
use App\Modules\Setting\Entities\Functional;
use App\Modules\Setting\Repositories\DepartmentInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DepartmentController extends Controller
{
    private $organization;
    private $department;
    private $dropdown;


    public function __construct(
        OrganizationInterface $organization,
        DepartmentInterface $department,
        DropdownInterface $dropdown
    ) {
        $this->organization = $organization;
        $this->department = $department;
        $this->dropdown = $dropdown;
    }

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

        $data['organizationList'] = $this->organization->getList();
        $data['departments'] = $this->department->findAll(25, $filter, $sort);
        return view('setting::department.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['organizationList'] = $this->organization->getList();
        $data['categoryList'] = $this->dropdown->getFieldBySlug('category');
        $data['isEdit'] = false;
        return view('setting::department.create', $data);
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
            if (isset($data['display_short_code'])) {
                $data['display_short_code'] = 1;
            }
            $this->department->save($data);
            toastr('Department added Successfully', 'success');
        } catch (\Throwable $th) {
            toastr('Error While Adding Department', 'error');
        }
        return redirect()->route('department.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */

    public function viewDetail($id)
    {
        $data['department'] = $this->department->find($id);
        return view('setting::department.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['organizationList'] = $this->organization->getList();
        $data['department'] = $this->department->find($id);
        $data['categoryList'] = $this->dropdown->getFieldBySlug('category');
        $data['functionlist'] = Functional::pluck('title', 'id');
        $data['id'] = $id;
        $data['isEdit'] = true;
        return view('setting::department.edit', $data);
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
            if (isset($data['display_short_code'])) {
                $data['display_short_code'] = 1;
            } else {
                $data['display_short_code'] = 0;
            }
            $this->department->update($id, $data);
            toastr('Department Updated Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Updating Department', 'error');
        }
        return redirect()->route('department.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->department->delete($id);
            toastr('Department Deleted Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Deleting Department', 'error');
        }
        return redirect()->route('department.index');
    }
}
