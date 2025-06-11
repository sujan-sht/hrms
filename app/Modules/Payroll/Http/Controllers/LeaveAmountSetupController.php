<?php

namespace App\Modules\Payroll\Http\Controllers;

use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Payroll\Repositories\LeaveAmountSetupInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LeaveAmountSetupController extends Controller
{
    protected $organization;
    protected $leaveAmount;
    protected $branch;

    public function __construct(
        LeaveAmountSetupInterface $leaveAmount, 
        OrganizationInterface $organization,
        BranchInterface $branch
        )
    {
        $this->organization = $organization;
        $this->leaveAmount = $leaveAmount;
        $this->branch = $branch;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $inputData = $request->all();

        $filter = [];
        
        // if(isset($inputData['organization_id'])) {
        //     $filter['organizationId'] = $inputData['organization_id'];
        // }
        
        if(isset($inputData['organization_id']) && isset($inputData['branch_id'])) {
            $filter['organizationId'] = $inputData['organization_id'];
            $filter['branchId'] = $inputData['branch_id'];
        }

        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['leaveAmountSetupModels'] = $this->leaveAmount->findAll(20, $filter);
        return view('payroll::leave-amount-setup.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['organizationList'] = $this->organization->getList();
        return view('payroll::leave-amount-setup.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData = $request->all();
        unset($inputData['income_id']);
        try {
            $leaveSetupModel = $this->leaveAmount->save($inputData);
            foreach($request->income_id as $key=>$value){
                $detail['leave_amount_setup_id'] = $leaveSetupModel->id;
                $detail['income_setup_id'] = $value;
                $this->leaveAmount->saveDetail($detail);
            }
            toastr()->success('Leave Amount Setup Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('leaveAmountSetup.index'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('payroll::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['organizationList'] = $this->organization->getList();
        $data['leaveAmountSetupModel'] = $this->leaveAmount->find($id);

        return view('payroll::leave-amount-setup.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $inputData = $request->all();
        unset($inputData['income_id']);


        try {
            $this->leaveAmount->update($id, $inputData);
            $this->leaveAmount->deleteChild($id);
            foreach($request->income_id as $key=>$value){
                $detail['leave_amount_setup_id'] = $id;
                $detail['income_setup_id'] = $value;
                $this->leaveAmount->saveDetail($detail);
            }

            toastr()->success('Leave Amount Setup Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('leaveAmountSetup.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->leaveAmount->delete($id);
            $this->leaveAmount->deleteChild($id);
            toastr()->success('Leave Amount Setup Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }
}
