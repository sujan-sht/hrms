<?php

namespace App\Modules\Payroll\Http\Controllers;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Payroll\Entities\GrossSalarySetup;
use App\Modules\Payroll\Repositories\EmployeeSetupInterface;
use App\Modules\Payroll\Repositories\IncomeSetupInterface;
use App\Modules\Payroll\Repositories\MassIncrementInterface;
use App\Modules\Payroll\Repositories\TaxSlabSetupInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MassIncrementController extends Controller
{
    protected $employee;
    protected $organization;
    protected $branch;
    protected $taxSlab;
    protected $massIncrement;
    protected $employeeSetup;
    protected $income;

    public function __construct(
        EmployeeInterface $employee,
        EmployeeSetupInterface $employeeSetup,
        OrganizationInterface $organization,
        BranchInterface $branch,
        TaxSlabSetupInterface $taxSlab,
        MassIncrementInterface $massIncrement,
        IncomeSetupInterface $income
    ) {
        $this->employee = $employee;
        $this->organization = $organization;
        $this->branch = $branch;
        $this->taxSlab = $taxSlab;
        $this->massIncrement = $massIncrement;
        $this->employeeSetup = $employeeSetup;
        $this->income = $income;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $filter = ($request->all());
        $data['title'] = 'Assign Employeee Income';
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['employeePluck'] = $this->employee->getList();
        $data['employeeList'] = [];
        $data['selected_effective_date'] = date('Y-m-d');
        if (isset($filter['organization_id'])) {
            $data['employeeList'] = $this->employee->getEmployeeByOrganization($filter['organization_id'], $filter);
        }
        return view('payroll::mass-increment.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('payroll::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        try {
            $data = $request->all();
            $total_emp = count($data['emp_id']);
            // dd($total_emp);
            if ($total_emp > 0) {
                for ($i = 0; $i < $total_emp; $i++) {
                    if (!empty($data['new_income'][$i])) {
                        if (isset($data['nep_effective_date'][$i]) && !empty($data['nep_effective_date'][$i])) {
                            $nep_date = $data['nep_effective_date'][$i];
                        } else {
                            $cal = new DateConverter();
                            $eff_date_year = date('Y', strtotime($data['effective_date'][$i]));
                            $eff_date_month = date('m', strtotime($data['effective_date'][$i]));
                            $eff_date_day = date('d', strtotime($data['effective_date'][$i]));
                            $nep_date_resp = $cal->eng_to_nep($eff_date_year, $eff_date_month, $eff_date_day);
                            $nep_date = $nep_date_resp['year'] . '-' . $nep_date_resp['month'] . '-' . $nep_date_resp['date'];
                        }

                        $check_condition = ['emp_id' => $data['emp_id'][$i], 'effective_date' => $data['effective_date'][$i]];
                        $mass_inc_info  = $this->massIncrement->findOne($check_condition);
                        // dd($mass_inc_info);
                        if (empty($mass_inc_info)) {
                            $mass_inc_array = [
                                'emp_id' => $data['emp_id'][$i],
                                'name' => $data['name'][$i],
                                'organization_id' => $data['organization_id'][$i],
                                'branch_id' => $data['branch_id'][$i],
                                'designation_id' => $data['designation_id'][$i],
                                'emp_status' => $data['emp_status'][$i],
                                'existing_income' => $data['existing_income'][$i],
                                'increased_by' => !empty($data['increased_by'][$i]) ? $data['increased_by'][$i] : 0,
                                'new_income' => !empty($data['new_income'][$i]) ? $data['new_income'][$i] : 0,
                                // 'arrear_amt' => $data['arrear_amt'][$i],
                                'effective_date' => $data['effective_date'][$i],
                                'nep_effective_date' => $nep_date
                            ];
                            $mass_increment = $this->massIncrement->save($mass_inc_array);
                        } else {
                            $mass_inc_array = [
                                'emp_id' => $data['emp_id'][$i],
                                'name' => $data['name'][$i],
                                'organization_id' => $data['organization_id'][$i],
                                'branch_id' => $data['branch_id'][$i],
                                'designation_id' => $data['designation_id'][$i],
                                'emp_status' => $data['emp_status'][$i],
                                'existing_income' => $data['existing_income'][$i],
                                'increased_by' => !empty($data['increased_by'][$i]) ? $data['increased_by'][$i] : 0,
                                'new_income' => !empty($data['new_income'][$i]) ? $data['new_income'][$i] : 0,
                                // 'arrear_amt' => $data['arrear_amt'][$i],
                                'effective_date' => $data['effective_date'][$i],
                                'nep_effective_date' => $nep_date
                            ];
                            $this->massIncrement->update($mass_inc_info->id, $mass_inc_array);
                        }

                        if ($data['effective_date'][$i] <= date('Y-m-d')) {
                            $this->employeeSetup->updateGrosssalary($data['emp_id'][$i], ['gross_salary' => $data['new_income'][$i]]);
                            $employeeModel = $this->employee->find($data['emp_id'][$i]);
                            $grossSalary = $employeeModel->employeeGrossSalarySetup;
                            $grossSalary = $grossSalary->gross_salary;
                            $basics = 0;
                            $data['income'] = $income = $this->income->findAll(null, ['organizationId' => $data['organization_id'][$i]]);
                            foreach ($income as $key => $value) {
                                $filter = [
                                    'reference' => 'income',
                                    'reference_id' => $value->id,
                                    'employee_id' => $data['emp_id'][$i]
                                ];
                                $employeeIncome = $this->employeeSetup->findOne($filter);
                                if ($employeeIncome) {
                                    if ($value->method == 2) {
                                        if ($value->salary_type == 2) {

                                            if ($value->short_name == 'BS') {
                                                $per = $value->percentage;
                                                $basic = ($per / 100) * $grossSalary;
                                                $basics = $basic;
                                                $amount = ($per / 100) * $grossSalary;
                                            } else {
                                                $per = $value->percentage;
                                                $amount = ($per / 100) * $grossSalary;
                                            }
                                        } else {
                                            $per = $value->percentage;
                                            $amount = ($per / 100) * $basics;
                                        }
                                    } else {
                                        $amount = $employeeIncome->amount;
                                    }
                                    $inputArray = [
                                        'employee_id' =>  $data['emp_id'][$i],
                                        'organization_id' => $data['organization_id'][$i],
                                        'reference' => 'income',
                                        'reference_id' => $value->id,
                                        'amount' => $amount,
                                        'status' => 11
                                    ];
                                }

                                $employeeIncome->update($inputArray);
                            }
                        }
                    }
                }
            }
            toastr()->success('Mass Increment Created Successfully');
        } catch (\Throwable $e) {
            // dd($e);
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('massIncrement.index'));
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
        return view('payroll::edit');
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

    public function updateGrossEmployeeSetup(Request $request)
    {
        try {
            $massIncrementModel = $this->massIncrement->getTodayMassIncrement();
            // dd($massIncrementModel);
            foreach ($massIncrementModel as $key => $massIncrement) {
                $employee_id = $massIncrement->emp_id;
                $this->employeeSetup->updateGrosssalary($employee_id, ['gross_salary' => $massIncrement->new_income]);
                $employeeModel = $this->employee->find($employee_id);
                $grossSalary = $employeeModel->employeeGrossSalarySetup;
                $grossSalary = $grossSalary->gross_salary;
                $basics = 0;
                $data['income'] = $income = $this->income->findAll(null, ['organizationId' => $massIncrement->organization_id]);
                foreach ($income as $key => $value) {
                    $filter = [
                        'reference' => 'income',
                        'reference_id' => $value->id,
                        'employee_id' => $employee_id
                    ];
                    $employeeIncome = $this->employeeSetup->findOne($filter);
                    if ($employeeIncome) {
                        if ($value->method == 2) {
                            if ($value->salary_type == 2) {

                                if ($value->short_name == 'BS') {
                                    $per = $value->percentage;
                                    $basic = ($per / 100) * $grossSalary;
                                    $basics = $basic;
                                    $amount = ($per / 100) * $grossSalary;
                                } else {
                                    $per = $value->percentage;
                                    $amount = ($per / 100) * $grossSalary;
                                }
                            } else {
                                $per = $value->percentage;
                                $amount = ($per / 100) * $basics;
                            }
                        } else {
                            $amount = $employeeIncome->amount;
                        }
                        $inputArray = [
                            'employee_id' =>  $employee_id,
                            'organization_id' => $massIncrement->organization_id,
                            'reference' => 'income',
                            'reference_id' => $value->id,
                            'amount' => $amount,
                            'status' => 11
                        ];
                    }

                    $employeeIncome->update($inputArray);
                }
            }
            toastr()->success('Gross Salary and Employee Setup Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('massIncrement.index'));
    }
}
