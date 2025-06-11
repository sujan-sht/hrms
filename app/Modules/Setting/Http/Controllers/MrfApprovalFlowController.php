<?php

namespace App\Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Setting\Repositories\MrfApprovalFlowInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;

class MrfApprovalFlowController extends Controller
{
    protected $mrfApprovalFlowObj;
    protected $employeeObj;
    protected $organization;

    public function __construct(
        MrfApprovalFlowInterface $mrfApprovalFlowObj,
        EmployeeInterface $employeeObj,
        OrganizationInterface $organization
    ) {
        $this->mrfApprovalFlowObj = $mrfApprovalFlowObj;
        $this->employeeObj = $employeeObj;
        $this->organization = $organization;
    }

    public function index(Request $request)
    {
        $filter = $request->all();

        $data['mrfApprovalFlowModels'] = $this->mrfApprovalFlowObj->findAll(20, $filter);

        return view('setting::mrf-approval-flow.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['organizationList'] = $this->organization->findAll()->pluck('name','id');
        $data['employeeList'] = $this->employeeObj->getList();

        return view('setting::mrf-approval-flow.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        try {
            $this->mrfApprovalFlowObj->create($data);

            toastr()->success('Data Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('mrfApprovalFlow.index'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['mrfApprovalFlowModel'] = $this->mrfApprovalFlowObj->findOne($id);
        $data['organizationList'] = $this->organization->findAll()->pluck('name','id');
        $data['employeeList'] = $this->employeeObj->getList();

        return view('setting::mrf-approval-flow.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        try {
            $this->mrfApprovalFlowObj->update($id, $data);

            toastr()->success('Data Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('mrfApprovalFlow.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->mrfApprovalFlowObj->delete($id);

            toastr()->success('Data Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

}
