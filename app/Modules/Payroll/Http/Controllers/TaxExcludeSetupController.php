<?php

namespace App\Modules\Payroll\Http\Controllers;

use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Payroll\Entities\TaxExcludeSetup;
use App\Modules\Payroll\Repositories\TaxExcludeSetupInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TaxExcludeSetupController extends Controller
{
    protected $organizationObj;
    protected $taxExcludeSetupObj;
    protected $branch;

    public function __construct(
        OrganizationInterface $organizationObj,
        TaxExcludeSetupInterface $taxExcludeSetupObj,
        BranchInterface $branch
    ) 
    {
        $this->organizationObj = $organizationObj;
        $this->taxExcludeSetupObj = $taxExcludeSetupObj;
        $this->branch = $branch;
    }

    public function index(Request $request)
    {
        $inputData = $request->all();

        $filter = [];
        $sort = ['by' => 'order', 'sort' => 'ASC'];
        
        // if(isset($inputData['organization_id'])) {
        //     $filter['organizationId'] = $inputData['organization_id'];
        // }

        if(isset($inputData['organization_id']) && isset($inputData['branch_id'])) {
            $filter['organizationId'] = $inputData['organization_id'];
            $filter['branchId'] = $inputData['branch_id'];
        }

        $data['organizationList'] = $this->organizationObj->getList();
        $data['branchList'] = $this->branch->getList();
        $data['taxExcludeSetupModels'] = $this->taxExcludeSetupObj->findAll(20, $filter, $sort);
        return view('payroll::tax-exclude-setup.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['statusList'] = TaxExcludeSetup::statusList();
        $data['typeList'] = TaxExcludeSetup::typeList();
        $data['organizationList'] = $this->organizationObj->getList();
        return view('payroll::tax-exclude-setup.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData = $request->all();
        try {
            $incomeModel = $this->taxExcludeSetupObj->save($inputData);
            toastr()->success('Tax Exclude Setup Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('taxExcludeSetup.index'));
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
        $data['typeList'] = TaxExcludeSetup::typeList();
        $data['statusList'] = TaxExcludeSetup::statusList();
        $data['organizationList'] = $this->organizationObj->getList();
        $data['taxExcludeSetupModel'] = $this->taxExcludeSetupObj->find($id);

        return view('payroll::tax-exclude-setup.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        try {
            $this->taxExcludeSetupObj->update($id, $data);

            toastr()->success('Tax Exclude Setup Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('taxExcludeSetup.index'));
    }

    public function checkTaxExcludeOrder(Request $request)
    {
        $data = $request->all();
        $validator = \Validator::make($request->all(), [
            'order' => 'required|unique:tax_exclude_setups,order,' . $data['id'],
        ]);
        if ($validator->fails()) {
            return  json_encode(false);
            // dd($validator->errors()->all());
        }
        return  json_encode(true);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->taxExcludeSetupObj->delete($id);
            toastr()->success('Tax Exclude Setup Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }
}
