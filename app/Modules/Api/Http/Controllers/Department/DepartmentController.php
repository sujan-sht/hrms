<?php

namespace App\Modules\Api\Http\Controllers\Department;

use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\Api\Transformers\DepartmentResource;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class DepartmentController extends ApiController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {
            $userModel = auth()->user();

            $employees = Employee::when(true, function ($query) use ($userModel) {
                $departmentId = optional($userModel->userEmployer)->department_id;
                $empModel = $userModel->userEmployer;
                $query->where('department_id', $departmentId);
                $query->where('organization_id', $empModel->organization_id);
            })
            ->where('id', '!=', $userModel->emp_id)
            ->where('status', 1)
            ->get();

            $departmentMembers = DepartmentResource::collection($employees);

            return $this->respond([
                'status' => true,
                'data' => $departmentMembers
            ]);

        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('api::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('api::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('api::edit');
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
