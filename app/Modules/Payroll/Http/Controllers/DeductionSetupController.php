<?php

namespace App\Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Payroll\Entities\Payroll;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Payroll\Entities\IncomeSetup;
use Illuminate\Contracts\Validation\Validator;
use App\Modules\Payroll\Entities\EmployeeSetup;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Payroll\Entities\ThresholdBenefitSetup;
use App\Modules\Payroll\Repositories\IncomeSetupInterface;
use App\Modules\Payroll\Repositories\DeductionSetupInterface;
use App\Modules\Employee\Entities\EmployeePayrollRelatedDetail;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Employee\Entities\EmployeeThresholdRelatedDetail;

class DeductionSetupController extends Controller
{
    protected $incomeSetup;
    protected $deductionSetup;
    protected $organization;
    protected $branch;

    public function __construct(
        IncomeSetupInterface $incomeSetup,
        DeductionSetupInterface $deductionSetup,
        OrganizationInterface $organization,
        BranchInterface $branch
    ) {
        $this->incomeSetup = $incomeSetup;
        $this->deductionSetup = $deductionSetup;
        $this->organization = $organization;
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
        $sort = ['by' => 'order', 'sort' => 'ASC'];

        // if(isset($inputData['organization_id'])) {
        //     $filter['organizationId'] = $inputData['organization_id'];
        // }

        if(isset($inputData['organization_id']) && isset($inputData['branch_id'])) {
            $filter['organizationId'] = $inputData['organization_id'];
            $filter['branchId'] = $inputData['branch_id'];
        }

        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['deductionSetupModels'] = $this->deductionSetup->findAll(20, $filter, $sort);

        return view('payroll::deduction-setup.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['organizationList'] = $this->organization->getList();
        $data['incomeList'] = $this->incomeSetup->getList();
        return view('payroll::deduction-setup.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData = $request->all();
        $deductionData = $request->except('percentage','income_id');
        // dd($deductionData);
        try {
            $deductionModel = $this->deductionSetup->save($deductionData);
            foreach($inputData['percentage'] as $key=>$value){
                if($value){
                    $deductionDataDetail['deduction_setup_id'] = $deductionModel->id;
                    $deductionDataDetail['percentage'] = $value;
                    $deductionDataDetail['income_id'] = $inputData['income_id'][$key];
                    $this->deductionSetup->saveDetail($deductionDataDetail);
                }

                // dd($deductionDataDetail);
            }
            toastr()->success('Deduction Setup Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('deductionSetup.index'));
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
        $data['deductionSetupModel'] = $this->deductionSetup->find($id);
        $data['incomeList'] = $this->incomeSetup->getList();

        return view('payroll::deduction-setup.edit', $data);
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
        $deductionData = $request->except('percentage','income_id');

        try {
            $this->deductionSetup->update($id, $deductionData);
            $this->deductionSetup->deleteChild($id);
            foreach($data['percentage'] as $key=>$value){
                if($value){
                    $deductionDataDetail['deduction_setup_id'] = $id;
                    $deductionDataDetail['percentage'] = $value;
                    $deductionDataDetail['income_id'] = $data['income_id'][$key];
                    $this->deductionSetup->saveDetail($deductionDataDetail);
                }
            }

            toastr()->success('Deduction Setup Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('deductionSetup.index'));
    }

    public function checkDeductionOrder(Request $request)
    {
        $data = $request->all();
        $validator = \Validator::make($request->all(), [
            'order' => 'required|unique:deduction_setups,order,' . $data['id'],
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
            $this->deductionSetup->delete($id);
            EmployeeSetup::where('reference','deduction')->where('reference_id',$id)->delete();
            ThresholdBenefitSetup::where('deduction_setup_id',$id)->delete();
            EmployeeThresholdRelatedDetail::where('deduction_setup_id',$id)->delete();
            toastr()->success('Deduction Setup Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }
    public function getIncomeTypes(Request $request){
        $inputData = $request->all();
        $models= IncomeSetup::where('organization_id',$inputData['organization_id'])->pluck('title','id')->toArray();
        return json_encode($models);
    }

    public function updateMonth(Request $request){
        $payrollGeneratedMonth = Payroll::where([
            'organization_id'=>$request->organization_id,
            'calendar_type'=>'nep',
            'year'=>$request->year
        ])->get()->map(function($item){
            if($item->checkCompleted()){
                return $item;
            }
        })->whereNotNull()->where('calendar_type','nep')->pluck('month');
        return json_encode(collect(date_converter()->getNepMonths())->except($payrollGeneratedMonth));
    }

    public function getIncomeTypesWithGross(Request $request){
        $inputData = $request->all();
        $finalArray=[];
        $models= IncomeSetup::where('organization_id',$inputData['organization_id'])->where('method','=',1)->pluck('title','id')->toArray();
        $finalArray=[
            // 0=>'Gross'
        ];
        $finalArray = $finalArray + $models;
        return json_encode($finalArray);
    }
}
