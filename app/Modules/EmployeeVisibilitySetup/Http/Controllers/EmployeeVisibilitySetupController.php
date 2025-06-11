<?php

namespace App\Modules\EmployeeVisibilitySetup\Http\Controllers;

use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\EmployeeVisibilitySetup\Repositories\EmployeeVisibilitySetupInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\User\Repositories\RoleInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmployeeVisibilitySetupController extends Controller
{

    public $organization;
    public $employee;
    public $visibility;
    public $role;

    public function __construct(RoleInterface $role, EmployeeVisibilitySetupInterface $visibility, OrganizationInterface $organization, EmployeeInterface $employee)
    {
        $this->organization = $organization;
        $this->employee = $employee;
        $this->visibility = $visibility;
        $this->role = $role;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function ListAll(Request $request)
    {
        // $request = $request->all();
        $data['organizations'] = $this->organization->getList();
        $firstKey = $data['organizations']->keys()->first();
        $data['roles'] = $this->role->findAll(100);
        $roleName = $request->role_id;
        $organization_id = $request->organization_id != null ? $request->organization_id : $firstKey;
        //it works in search
        $employees = $this->employee->getEmployeeByOrganization($organization_id)->when($request->employee_id, function ($q) use ($request) {
            return $q->where('id', $request->employee_id);
        });


        if ($roleName) {
            $data['employees'] = collect($employees)->filter(function ($q) use ($roleName) {
                if ($q->getUser) {
                    return $q->getUser->user_type == $roleName;
                }
                return false;
            })->values();
        } else {
            $data['employees'] = $employees;
        }

        return view('employeevisibilitysetup::employeevisibilitysetup.index', $data, compact('firstKey'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('employeevisibilitysetup::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $this->visibility->store($request);

        if ($data == true) {
            toastr()->success('Employee visibility setup stored successfully.');
            return redirect()->back();
        } else {
            toastr()->error('Somethings went wrong !!!');
            return redirect()->back();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('employeevisibilitysetup::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('employeevisibilitysetup::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
