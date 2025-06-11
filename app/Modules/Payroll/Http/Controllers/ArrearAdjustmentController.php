<?php

namespace App\Modules\Payroll\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrearAdjustmentExport;
use App\Modules\Payroll\Entities\Payroll;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Payroll\Entities\GrossSalarySetup;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;
use App\Modules\Payroll\Repositories\IncomeSetupInterface;
use App\Modules\Payroll\Repositories\EmployeeSetupInterface;
use App\Modules\Payroll\Repositories\ArrearAdjustmentInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;

class ArrearAdjustmentController extends Controller
{
    protected $employee;
    protected $organization;
    protected $branch;
    protected $taxSlab;
    protected $arrearAdjustment;
    protected $employeeSetup;
    protected $income;
    private $employeeObj;
    public function __construct(
        EmployeeInterface $employee,
        EmployeeSetupInterface $employeeSetup,
        OrganizationInterface $organization,
        BranchInterface $branch,
        ArrearAdjustmentInterface $arrearAdjustment,
        IncomeSetupInterface $income,
        EmployeeInterface $employeeObj
    ) {
        $this->employee = $employee;
        $this->organization = $organization;
        $this->branch = $branch;
        $this->arrearAdjustment = $arrearAdjustment;
        $this->employeeSetup = $employeeSetup;
        $this->income = $income;
        $this->employeeObj = $employeeObj;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $filter = ($request->all());
        // dd($filter);
        $data['arrearAdjustments'] = $this->arrearAdjustment->findAll(null,$filter);
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        $yearArray = [];
        $firstYear = 2022;
        $lastYear = (int) date('Y');
        $dateConverter = new DateConverter();
        for ($i = $firstYear; $i <= $lastYear; $i++) {
            $yearArray[$i] = $i;
        }
        $data['yearList'] = $yearArray;
        $data['nepaliYearList'] = $dateConverter->getNepYears();
        $data['monthList'] = $dateConverter->getEngMonths();
        $data['nepaliMonthList'] = $dateConverter->getNepMonths();
        // $data['title'] = 'Assign Employeee Income';
        // $data['organizationList'] = $this->organization->getList();
        // $data['branchList'] = $this->branch->getList();
        // $data['employeePluck'] = $this->employee->getList();
        // $data['employeeList'] = [];
        // $data['selected_effective_date'] = date('Y-m-d');
        // if (isset($filter['organization_id'])) {
        //     $data['employeeList'] = $this->employee->getEmployeeByOrganization($filter['organization_id'], $filter);
        // }
        return view('payroll::arrear-adjustment.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['organizationList'] = $this->organization->getList();

        $payrollGeneratedMonth = Payroll::all()->map(function($item){
            if($item->checkCompleted()){
                return $item;
            }
        })->whereNotNull()->where('calendar_type','nep')->pluck('month');
        $dateConverter = new DateConverter();
        $data['nepaliYearList'] = $dateConverter->getNepYears();
        $data['nepaliMonthList'] = collect(date_converter()->getNepMonths())->except($payrollGeneratedMonth);
        return view('payroll::arrear-adjustment.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    // public function store(Request $request)
    // {
    //     try {
    //         $data = $request->all();
    //         $total_emp = count($data['emp_id']);
    //         if ($total_emp > 0) {
    //             for ($i = 0; $i < $total_emp; $i++) {
    //                 if(!empty($data['arrear_amt'][$i])) {
    //                     if (isset($data['nep_effective_date'][$i]) && !empty($data['nep_effective_date'][$i])) {
    //                         $nep_date = $data['nep_effective_date'][$i];
    //                     } else {
    //                         $cal = new DateConverter();
    //                         $eff_date_year = date('Y', strtotime($data['effective_date'][$i]));
    //                         $eff_date_month = date('m', strtotime($data['effective_date'][$i]));
    //                         $eff_date_day = date('d', strtotime($data['effective_date'][$i]));
    //                         $nep_date_resp = $cal->eng_to_nep($eff_date_year, $eff_date_month, $eff_date_day);
    //                         $nep_date = $nep_date_resp['year'] . '-' . $nep_date_resp['month'] . '-' . $nep_date_resp['date'];
    //                     }

    //                     $check_condition = ['emp_id' => $data['emp_id'][$i], 'effective_date' => $data['effective_date'][$i]];
    //                     $mass_inc_info  = $this->arrearAdjustment->findOne($check_condition);
    //                     if (empty($mass_inc_info)) {
    //                         $mass_inc_array = [
    //                             'emp_id' => $data['emp_id'][$i],
    //                             'name' => $data['name'][$i],
    //                             'organization_id' => $data['organization_id'][$i],
    //                             'branch_id' => $data['branch_id'][$i],
    //                             'designation_id' => $data['designation_id'][$i],
    //                             'emp_status' => $data['emp_status'][$i],
    //                             'arrear_amt' => $data['arrear_amt'][$i],
    //                             'effective_date' => $data['effective_date'][$i],
    //                             'nep_effective_date' => $nep_date
    //                         ];
    //                         $arrear_adjustment = $this->arrearAdjustment->save($mass_inc_array);
    //                     } else {
    //                         $mass_inc_array = [
    //                             'emp_id' => $data['emp_id'][$i],
    //                             'name' => $data['name'][$i],
    //                             'organization_id' => $data['organization_id'][$i],
    //                             'branch_id' => $data['branch_id'][$i],
    //                             'designation_id' => $data['designation_id'][$i],
    //                             'emp_status' => $data['emp_status'][$i],
    //                             'arrear_amt' => $data['arrear_amt'][$i],
    //                             'effective_date' => $data['effective_date'][$i],
    //                             'nep_effective_date' => $nep_date
    //                         ];
    //                         $this->arrearAdjustment->update($mass_inc_info->id, $mass_inc_array);
    //                     }


    //                 }
    //             }
    //         }
    //         toastr()->success('Arrear Adjustment Created Successfully');
    //     } catch (\Throwable $e) {
    //         dd($e);
    //         toastr()->error('Something Went Wrong !!!');
    //     }
    //     return redirect(route('arrearAdjustment.index'));
    // }
    public function store(Request $request)
    {
        $authUser = auth()->user();
        $inputData = $request->all();
        $fiscalYear = FiscalYearSetup::currentFiscalYear()->fiscal_year;
        $year = $fiscalYear ? explode('/', $fiscalYear)[0] : null;
        if($request->year){
            $year = $request->year;
        }
        DB::beginTransaction();
        try {
            $arrearData = [
                'organization_id' => $inputData['organization_id'],
                'emp_id' => $inputData['employee_id'],
                // 'year' => $inputData['month'] > 3 ? $year : $year + 1 ,
                'year' => $year ,
                'month' => $inputData['month'],
                'created_by' => $authUser->id
            ];
            $arrearObj = $this->arrearAdjustment->save($arrearData);
            foreach($inputData['income_setup_id'] as $key => $value){
                $detailData['arrear_adjustment_id'] = $arrearObj->id;
                $detailData['income_setup_id'] = $value;
                $detailData['arrear_amount'] = $inputData['arrear_amount'][$key];
                $detailData['income_type'] = $inputData['income_type'][$key];
                $this->arrearAdjustment->saveDetail($detailData);

            }
            DB::commit();
            toastr()->success('Data Created Successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('arrearAdjustment.index'));
    }

    public function addIncome(Request $request)
    {
        $data = $request->all();
        $numberIncr = $data['numberIncr'];
        $selectedIncomes = $data['selectedIncomes'] ?? [];

        if ($request->ajax()) {
            $organizationId = $data['organization_id'];

            if (!$organizationId) {
                return response()->json(['error' => 'Invalid organization ID'], 400);
            }

            // Fetch incomes specific to the organization and exclude selected incomes
            $incomes = $this->income->getList(['organizationId' => $organizationId])
                ->reject(function ($value, $key) use ($selectedIncomes) {
                    return in_array($key, $selectedIncomes);
                });

            // Render the partial view
            $data = view('payroll::arrear-adjustment.partial.add-income', compact('incomes','numberIncr'))->render();
            return response()->json(['options' => $data]);
        }
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
        // return view('payroll::edit');
        $data['isEdit'] = true;

        $data['organizationList'] = $this->organization->getList();
        $data['incomeSetups'] = $this->arrearAdjustment->getDetailByArrearId($id);
        $data['arrearAdjustmentModel'] =  $this->arrearAdjustment->find($id);
        if(!$data['arrearAdjustmentModel']->checkEditStatus()){
            toastr()->error('Payroll Has Locked !!!');
            return redirect(route('arrearAdjustment.index'));
        }

        $payrollGeneratedMonth = Payroll::all()->map(function($item){
            if($item->checkCompleted()){
                return $item;
            }
        })->whereNotNull()->where('calendar_type','nep')->pluck('month');
        $dateConverter = new DateConverter();
        $data['nepaliYearList'] = $dateConverter->getNepYears();
        $data['nepaliMonthList'] = collect(date_converter()->getNepMonths())->except($payrollGeneratedMonth);

        return view('payroll::arrear-adjustment.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $authUser = auth()->user();
        $inputData = $request->all();
        $fiscalYear = FiscalYearSetup::currentFiscalYear()->fiscal_year;
        $year = $fiscalYear ? explode('/', $fiscalYear)[0] : null;
        if($request->year){
            $year = $request->year;
        }
        DB::beginTransaction();
        try {
            $arrearData = [
                'organization_id' => $inputData['organization_id'],
                'emp_id' => $inputData['employee_id'],
                // 'year' => $inputData['month'] > 3 ? $year : $year + 1,
                'year' => $year,
                'month' => $inputData['month'],
                'updated_by' => $authUser->id
            ];
            $this->arrearAdjustment->update($id, $arrearData);

            $this->arrearAdjustment->find($id)->arrearAdjustmentDetail()->delete();
            foreach ($inputData['income_setup_id'] as $key => $value) {
                $detailData['arrear_adjustment_id'] = $id;
                $detailData['income_setup_id'] = $value;
                $detailData['arrear_amount'] = $inputData['arrear_amount'][$key];
                $detailData['income_type'] = $inputData['income_type'][$key];
                $this->arrearAdjustment->saveDetail($detailData);
            }

            DB::commit();
            toastr()->success('Data Updated Successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('arrearAdjustment.index'));
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->arrearAdjustment->delete($id);
            toastr('Arrear Adjustment Deleted Successfully', 'success');
            return redirect()->route('arrearAdjustment.index');
        } catch (Exception $e) {
            toastr('Error While Deleting Arrear Adhustment');
            return redirect()->route('arrearAdjustment.index');
        }
    }

    public function exportArrearAdjustment(Request $request)
    {
        $filter = $request->all();

        $data['arrearAdjustments'] = $this->arrearAdjustment->findAll(null,$filter);

        return Excel::download(new ArrearAdjustmentExport($data),'arrear-adjustment-report.xlsx');
    }
}
