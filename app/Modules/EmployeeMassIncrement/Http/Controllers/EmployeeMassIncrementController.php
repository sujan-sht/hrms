<?php

namespace App\Modules\EmployeeMassIncrement\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\Payroll\Entities\Payroll;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Payroll\Entities\IncomeSetup;
use App\Modules\Payroll\Entities\GrossSalarySetup;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Payroll\Repositories\IncomeSetupInterface;
use App\Modules\Payroll\Repositories\TaxSlabSetupInterface;
use App\Modules\Payroll\Repositories\EmployeeSetupInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\EmployeeMassIncrement\Repositories\EmployeeMassIncrementInterface;

class EmployeeMassIncrementController extends Controller
{
    protected $massIncrementObj;
    protected $employee;
    protected $organization;
    protected $branch;
    protected $taxSlab;
    protected $employeeSetup;
    protected $income;

    public function __construct(
        EmployeeMassIncrementInterface $massIncrementObj,
        EmployeeInterface $employee,
        EmployeeSetupInterface $employeeSetup,
        OrganizationInterface $organization,
        BranchInterface $branch,
        TaxSlabSetupInterface $taxSlab,
        IncomeSetupInterface $income
    ) {
        $this->massIncrementObj = $massIncrementObj;
        $this->employee = $employee;
        $this->organization = $organization;
        $this->branch = $branch;
        $this->taxSlab = $taxSlab;
        $this->employeeSetup = $employeeSetup;
        $this->income = $income;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data['employeeMassIncrements']=$this->massIncrementObj->getList();
        return view('employeemassincrement::mass-increment.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['organizationList'] = $this->organization->getList();
        $data['currentDateInNep']=date_converter()->eng_to_nep_convert(Carbon::now()->format('Y-m-d'));
        $data['createdEmployee']= $this->massIncrementObj->all();
        return view('employeemassincrement::mass-increment.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $inputData=$request->all();
            $this->massIncrementObj->save($inputData);
            DB::commit();
            toastr()->success('Employee Mass Increment Added Successfully');
            return redirect()->route('employeeMassIncrement.index');
        }catch(\Throwable $th){
            DB::rollBack();
            toastr()->error('Something Went Wrong !!');
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
        return view('employeemassincrement::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try{
            $employeeMassIncrement=$this->massIncrementObj->find($id);
            if(!$employeeMassIncrement){
                throw new Exception();
            }
            $data['employeeMassIncrement']=$employeeMassIncrement;
            $data['organizationList'] = $this->organization->getList();
            $models=Employee::where('id',$employeeMassIncrement->employee_id)->get();
            if ($models) {
                foreach ($models as $model) {
                    $data['employees'][$model->id] = $model->full_name. ' :: ' . $model->employee_code;
                }
            }
            $finalArray=[
                // 0=>'Gross'
            ];
            $incomeArray=IncomeSetup::where('organization_id',$employeeMassIncrement->organization_id)->where('method','=',1)->get()->pluck('title','id')->toArray();
            $incomeList=$finalArray+$incomeArray;

            $selectedIncome=[];
            $data['employeeMassIncrement']->details->map(function($item) use (&$selectedIncome,$incomeList){
                $item->detailIncome=collect($incomeList)->except($selectedIncome);
                $selectedIncome[]=$item->income_setup_id;
            });
            $data['currentDateInNep']=date_converter()->eng_to_nep_convert(Carbon::now()->format('Y-m-d'));
            return view('employeemassincrement::mass-increment.edit', $data);
        }catch(\Throwable $th){
            toastr()->error('Something Went Wrong !!');
            return redirect()->back();
        }
        return view('employeemassincrement::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $employeeMassIncrement=$this->massIncrementObj->find($id);
            if(!$employeeMassIncrement){
                throw new Exception();
            }
            $inputData=$request->all();
            $this->massIncrementObj->update($employeeMassIncrement,$inputData);
            DB::commit();
            toastr()->success('Employee Mass Increment Updated Successfully');
            return redirect()->route('employeeMassIncrement.index');
        }catch(\Throwable $th){
            DB::rollBack();
            toastr()->error('Something Went Wrong !!');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $employeeMassIncrement=$this->massIncrementObj->find($id);
            if(!$employeeMassIncrement){
                throw new Exception();
            }
            $employeeMassIncrement->details()->delete();
            $employeeMassIncrement->delete();
            DB::commit();
            toastr()->success('Employee Mass Increment Deleted Successfully');
            return redirect()->route('employeeMassIncrement.index');
        }catch(\Throwable $th){
            DB::rollBack();
            toastr()->error('Something Went Wrong !!');
            return redirect()->back();
        }
    }

    public function addIncome(Request $request)
    {
        $data = $request->all();
        $numberIncr = $data['numberIncr'];
        $selectedIncomes = $data['selectedIncomes'] ?? [];
        $finalArray=[

        ];
        if ($request->ajax()) {
            $organizationId = $data['organization_id'];
            if (!$organizationId) {
                return response()->json(['error' => 'Invalid organization ID'], 400);
            }

            $finalArray=$finalArray+$this->income->getFixedList(['organizationId' => $organizationId])->toArray();
            $incomes = collect($finalArray)
                ->reject(function ($value, $key) use ($selectedIncomes) {
                    return in_array($key, $selectedIncomes);
                });
                $currentDateInNep=date_converter()->eng_to_nep_convert(Carbon::now()->format('Y-m-d'));
            $data = view('employeemassincrement::mass-increment.partial.add-income', compact('incomes', 'numberIncr','currentDateInNep'))->render();
            return response()->json(['options' => $data]);
        }
    }

    public function fetchincomeEmployee(Request $request)
    {
        try {
            $employee = Employee::where('id', $request->employeeId)->first();
            $incomeAmount=0;
            if (!$employee) {
                throw new Exception();
            }

            if($request->selectedIncome==0){
                $grossSalary=GrossSalarySetup::where([
                    'organization_id' => $request->organizationId,
                    'employee_id'=> $employee->employee_id
                ])->first();
                if($grossSalary){
                    $incomeAmount=$grossSalary->gross_salary;
                }
            }else{
                $incomeSetup = IncomeSetup::where('id', $request->selectedIncome)->first();
                if (!$incomeSetup) {
                    throw new Exception();
                }
                $organization = Organization::where('id', $request->organizationId)->first();
                if (!$organization) {
                    throw new Exception();
                }
                $filter = [
                    "organization_id" => $organization->id,
                    "employee_id" => $employee->employee_id
                ];


                if (isset($filter['organization_id'])) {
                    $incomeList = $this->income->getList(['organizationId' => $filter['organization_id']])->toArray();
                    $income = $this->income->findAll(null, ['organizationId' => $filter['organization_id']]);
                }
                $data['employeeList'] = [];
                if (isset($filter['organization_id'])) {
                    $data['employeeList'] = $this->employee->getEmployeeByOrganization($filter['organization_id'], $filter)->map(function ($employeeList) use ($incomeList, $income) {
                        $employeeGrossSalarySetup = $employeeList->employeeGrossSalarySetup;
                        $gross_salary = optional($employeeGrossSalarySetup)->gross_salary ?? 0;
                        $grade = optional($employeeGrossSalarySetup)->grade ?? 0;
                        $basics = 0;
                        foreach ($income as $key => $value) {
                            $filter = [
                                'reference' => 'income',
                                'reference_id' => $value->id,
                                'employee_id' => $employeeList->id
                            ];
                            $employeeIncome = $this->employeeSetup->findOne($filter);
                            if (!$employeeIncome) {
                                if ($value->method == 2) {
                                    if ($value->short_name == 'BS') {
                                        $amount = 0;
                                        foreach ($value->incomeDetail as $incomeDetail) {
                                            $per = $incomeDetail->percentage;
                                            $basic = ($per / 100) * $gross_salary;
                                            $basics += $basic;
                                            $amount += ($per / 100) * $gross_salary;
                                        }
                                    } else {
                                        $amount = 0;
                                        foreach ($value->incomeDetail as $incomeDetail) {
                                            if ($incomeDetail->salary_type == 2) {
                                                $per = $incomeDetail->percentage;
                                                $amount += ($per / 100) * $gross_salary;
                                            } elseif ($incomeDetail->salary_type == 3) {
                                                $per = $incomeDetail->percentage;
                                                $amount += ($per / 100) * $grade;
                                            } else {
                                                $per = $incomeDetail->percentage;
                                                $amount += ($per / 100) * $basics;
                                            }
                                        }
                                    }
                                } else {
                                    $amount = $value->amount;
                                }
                                // if (!in_array($value->id, $pluckIncome) || in_array($value->id, $pluckIncome ) ){
                                $expectIncome[] = [
                                    'id' => '',
                                    'amount' => round($amount, 2),
                                    'reference_id' => $value->id
                                ];
                                // }
                            } else {
                                $expectIncome[] = [
                                    'id' => '',
                                    'amount' => round($employeeIncome->amount, 2),
                                    'reference_id' => $value->id
                                ];
                            }
                        }
                        if (isset($expectIncome)) {
                            $employeeList->employeeIncomeSetup = $expectIncome;
                        }

                        return $employeeList;
                    });
                }
                if(!$data['employeeList'] && count($data['employeeList']) <= 0){
                    throw new Exception();
                }
                $filterData=collect($data['employeeList'][0]['employeeIncomeSetup'])->where('reference_id',$incomeSetup->id)->first();

                if($filterData){
                    $incomeAmount=$filterData['amount'];
                }
            }

            $response = [
                'error' => false,
                'data' => $incomeAmount,
                'msg' => 'Success !!'
            ];
        } catch (\Throwable $th) {
            $response = [
                'error' => true,
                'msg' => 'Something Went Wrong !!'
            ];
        }
        return response()->json($response, 200);
    }
}
