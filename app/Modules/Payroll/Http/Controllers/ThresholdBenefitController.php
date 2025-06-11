<?php

namespace App\Modules\Payroll\Http\Controllers;

use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Payroll\Entities\ThresholdBenefitSetup;
use App\Modules\Payroll\Repositories\DeductionSetupInterface;
use App\Modules\Payroll\Repositories\ThresholdBenefitSetupInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ThresholdBenefitController extends Controller
{
    protected $thresholdBenefitSetup,$deduction,$organizationObj,$branch;

    public function __construct(ThresholdBenefitSetupInterface $thresholdBenefitSetup,DeductionSetupInterface $deduction, OrganizationInterface $organizationObj, BranchInterface $branch)
    {
        $this->thresholdBenefitSetup = $thresholdBenefitSetup;
        $this->deduction = $deduction;
        $this->organizationObj = $organizationObj;
        $this->branch = $branch;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        // dd($filter);
        $data['title'] = 'Assign Threshold Benefit';
        $data['deductionList']= $this->deduction->getList();
        $data['organizationList'] = $this->organizationObj->getList();
        $data['branchList'] = $this->branch->getList();
        $data['deduction']= $this->deduction->findAll($limit=null, $filter)->map(function ($deduction){
            $thresholdBenefitSetup = $deduction->thresholdBenefitSetup;
            return $deduction;
        });

        return view('payroll::threshold-benefit-setup.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        return view('payroll::threshold-benefit-setup.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        try{
            foreach ($data as $key => $value) {
                $inputArray = [
                    'deduction_setup_id' =>  $key,
                    'amount' => $value==null ? 0 : $value,
                ];
                $this->thresholdBenefitSetup->updateOrCreate($inputArray);
            }
            toastr()->success('Threshold Benefit Setup Created Successfully');

        }catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('thresholdBenefitSetup.index'));

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
        // $data['isEdit'] = true;
        // $data['thresholdBenefitSetupModel'] = $this->thresholdBenefitSetup->find($id);
        // return view('payroll::threshold-benefit-setup.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        // $data = $request->all();

        // try {
        //     $this->thresholdBenefitSetup->update($id, $data);

        //     toastr()->success('Threshold Benefit Setup Updated Successfully');
        // } catch (\Throwable $e) {
        //     toastr()->error('Something Went Wrong !!!');
        // }
        // return redirect(route('thresholdBenefitSetup.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->thresholdBenefitSetup->delete($id);
            toastr()->success('ThresholdBenefit Setup Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }
}
