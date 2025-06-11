<?php

namespace App\Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Setting\Entities\LeaveDeductionSetup;
use App\Modules\Leave\Repositories\LeaveTypeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Repositories\LeaveDeductionSetupInterface;

class LeaveDeductionSetupController extends Controller
{
    protected $leaveDeductionSetupObj;
    protected $organizationObj;
    protected $leaveTypeObj;

    public function __construct(
        LeaveDeductionSetupInterface $leaveDeductionSetupObj,
        OrganizationInterface $organizationObj,
        LeaveTypeInterface $leaveTypeObj
    ) {
        $this->leaveDeductionSetupObj = $leaveDeductionSetupObj;
        $this->organizationObj = $organizationObj;
        $this->leaveTypeObj = $leaveTypeObj;
    }

    public function index(Request $request)
    {
        $filter = $request->all();
        $data['leaveDeductionSetupModels'] = $this->leaveDeductionSetupObj->findAll(20, $filter);
        return view('setting::leave-deduction-setup.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['organizationList'] = $this->organizationObj->getList();
        $data['leaveTypeList'] = $this->leaveTypeObj->getList();
        $filter = ['leave_type' => 11];
        $data['unpaidLeaveTypeList'] = $this->leaveTypeObj->getList($filter);
        $data['methodList'] = LeaveDeductionSetup::getMethods();
        $data['typeList'] = LeaveDeductionSetup::getTypes();
        return view('setting::leave-deduction-setup.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request+
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $this->leaveDeductionSetupObj->create($data);

            toastr()->success('Data Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('leaveDeductionSetup.index'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['leaveTypeList'] = $this->leaveTypeObj->getList();
        $filter = ['leave_type' => 11];
        $data['unpaidLeaveTypeList'] = $this->leaveTypeObj->getList($filter);
        $data['organizationList'] = $this->organizationObj->getList();
        $data['methodList'] = LeaveDeductionSetup::getMethods();
        $data['typeList'] = LeaveDeductionSetup::getTypes();
        $data['leaveDeductionSetupModel'] = $this->leaveDeductionSetupObj->findOne($id);

        return view('setting::leave-deduction-setup.edit', $data);
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
            $this->leaveDeductionSetupObj->update($id, $data);
            toastr()->success('Data Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('leaveDeductionSetup.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->leaveDeductionSetupObj->delete($id);

            toastr()->success('Data Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }
}
