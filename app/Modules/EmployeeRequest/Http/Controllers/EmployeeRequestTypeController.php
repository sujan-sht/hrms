<?php

namespace App\Modules\EmployeeRequest\Http\Controllers;

use App\Modules\EmployeeRequest\Http\Requests\RequestTypeRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

// Repositories
use App\Modules\EmployeeRequest\Repositories\EmployeeRequestTypeInterface;

class EmployeeRequestTypeController extends Controller
{
    private $employeeRequestType;

    public function __construct(EmployeeRequestTypeInterface $employeeRequestType)
    {
        $this->employeeRequestType = $employeeRequestType;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $requestTypes = $this->employeeRequestType->findAll($limit = 50, $filter = request('search_value'));
        return view('employeerequest::employeeRequestType.index', compact('requestTypes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('employeerequest::employeeRequestType.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(RequestTypeRequest $request)
    {
        $data = $request->all();

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        try {
            toastr()->success('Request Type Created Successfully');
            $this->employeeRequestType->save($data);
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        return redirect()->route('employeeRequestType.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('employeerequest::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $requestType = $this->employeeRequestType->find($id);
        return view('employeerequest::employeeRequestType.edit', compact('requestType'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(RequestTypeRequest $request, $id)
    {
        $data = $request->all();

        $data['updated_by'] = auth()->user()->id;

        try {
            $this->employeeRequestType->update($id, $data);
            toastr()->success('Request Type Updated Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        return redirect()->route('employeeRequestType.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
          $this->employeeRequestType->delete($id);
          toastr()->success('Request Type Deleted Successfully.');

        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        return redirect()->back();
    }
}
