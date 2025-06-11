<?php

namespace App\Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\HoldPaymentExport;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Payroll\Entities\HoldPayment;
use App\Modules\Payroll\Repositories\PayrollInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Payroll\Repositories\HoldPaymentInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;

class HoldPaymentController extends Controller
{
    private $holdPaymentObj;
    private $organizationObj;
    private $employeeObj;
    private $payrollObj;
    public function __construct(HoldPaymentInterface $holdPaymentObj, OrganizationInterface $organizationObj, EmployeeInterface $employeeObj, PayrollInterface $payrollObj)
    {
        $this->holdPaymentObj = $holdPaymentObj;
        $this->organizationObj = $organizationObj;
        $this->employeeObj = $employeeObj;
        $this->payrollObj = $payrollObj;
    }

    public function index(Request $request)
    {
        $filters=$request->all();
        $data['organizationList'] = $this->organizationObj->getList();
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
        $data['holdPayments'] = $this->holdPaymentObj->findAll(null,$filters);
        return view('payroll::hold-payment.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['organizationList'] = $this->organizationObj->getList();
        $data['employeeList'] = $this->employeeObj->getList();

        $yearArray = [];
        $firstYear = 2022;
        $lastYear = (int) date('Y');
        $dateConverter = new DateConverter();
        for ($i = $firstYear; $i <= $lastYear; $i++) {
            $yearArray[$i] = $i;
        }
        // $data['calendarTypeList'] = $dateConverter->getCalendarTypes();
        $data['calendarTypeList'] = [
            'eng' => 'English'
        ];
        $data['nepcalendarTypeList'] = [
            'nep' => 'Nepali'
        ];
        $data['yearList'] = $dateConverter->getEngYears();
        $data['nepaliYearList'] = $dateConverter->getNepYears();
        $data['monthList'] = $dateConverter->getEngMonths();
        $data['nepaliMonthList'] = $dateConverter->getNepMonths();
        return view('payroll::hold-payment.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData = $request->all();
        if (isset($inputData['month']) && $inputData['year']) {
            $inputData['year'] = $inputData['year'];
        } else {
            $inputData['year'] = $inputData['eng_year'];
        }
        if (isset($inputData['month']) && $inputData['month']) {
            $inputData['month'] = $inputData['month'];
        } else {
            $inputData['month'] = $inputData['eng_month'];
        }
        try {
            foreach ($inputData['employee_id'] as $emp) {
                foreach ($inputData['month'] as $month) {
                    $data['employee_id'] = $emp;
                    $data['organization_id'] = $inputData['organization_id'];
                    $data['calendar_type'] = $inputData['calendar_type'];
                    $data['year'] = $inputData['year'];
                    $data['month'] = $month;
                    $data['created_by']=auth()->user()->first_name;
                    // dd($data);
                    $this->holdPaymentObj->save($data);
                }
                // dd($data);

            }
            // $this->holdPaymentObj->save($inputData);
            toastr()->success('HoldPayment Created Successfully');
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('holdPayment.index'));
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
        $data['holdPayment'] = $this->holdPaymentObj->find($id); // Fetch the specific record by ID
        $data['organizationList'] = $this->organizationObj->getList();
        $data['employeeList'] = $this->employeeObj->getList();

        $dateConverter = new DateConverter();
        $data['calendarTypeList'] = ['eng' => 'English'];
        $data['nepcalendarTypeList'] = ['nep' => 'Nepali'];
        $data['yearList'] = $dateConverter->getEngYears();
        $data['nepaliYearList'] = $dateConverter->getNepYears();
        $data['monthList'] = $dateConverter->getEngMonths();
        $data['nepaliMonthList'] = $dateConverter->getNepMonths();

        return view('payroll::hold-payment.edit', $data);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $inputData = $request->all();

        if (isset($inputData['month']) && $inputData['year']) {
            $inputData['year'] = $inputData['year'];
        } else {
            $inputData['year'] = $inputData['eng_year'];
        }
        if (isset($inputData['month']) && $inputData['month']) {
            $inputData['month'] = $inputData['month'];
        } else {
            $inputData['month'] = $inputData['eng_month'];
        }

        try {
            foreach ($inputData['employee_id'] as $emp) {
                foreach ($inputData['month'] as $month) {
                    $data['employee_id'] = $emp;
                    $data['organization_id'] = $inputData['organization_id'];
                    $data['calendar_type'] = $inputData['calendar_type'];
                    $data['year'] = $inputData['year'];
                    $data['month'] = $month;

                    $this->holdPaymentObj->update($id, $data);
                }
            }
            toastr()->success('HoldPayment Updated Successfully');
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('holdPayment.index'));
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->holdPaymentObj->delete($id);
            toastr('Hold Payment Deleted Successfully', 'success');
            return redirect()->route('holdPayment.index');
        } catch (\Throwable $e) {
            toastr('Error While Deleting Hold Payment', 'error');
            return redirect()->route('holdPayment.index');
        }
    }
    public function cancel($id)
    {
        try {
            $holdPayment=HoldPayment::find($id);
            $holdPayment->hold_status=2;
            $holdPayment->save();
            toastr('Hold Payment Cancelled Successfully', 'success');
            return redirect()->route('holdPayment.index');
        } catch (\Throwable $e) {
            toastr('Error While Deleting Hold Payment', 'error');
            return redirect()->route('holdPayment.index');
        }
    }
    public function updateStatus(Request $request)
    {
        $data = $request->all();
        $data['payrollModel'] = $payrollModel = $this->payrollObj->findOne($data['payroll_id']);
        // dd($data['payrollModel']);
        try {
            foreach ($data['employee_id'] as $emp) {
                $inputData = [
                    'year' => $data['payrollModel']->year,
                    'month' => $data['payrollModel']->month,
                    'employee_id' => $emp,
                    'organization_id' => $data['payrollModel']->organization_id,
                    'status' => $data['status'],
                    'released_year' => $data['released_year'] ?? null,
                    'released_month' => $data['released_month'] ?? null
                ];
                $this->holdPaymentObj->updateStatus($inputData);
                // dd($inputData);
            }
            // $this->holdPaymentObj->update($data['hold_payment_id'], $data);
            // if($data['is_released'] == 1) {
            //     //update hold payroll of the selected employee;
            //     $holdPaymentInfo = $this->holdPayment->find($data['hold_payment_id']);
            //     if(!empty($holdPaymentInfo)) {
            //         $payroll_group = $this->payrollGroup->findOne(['year'=> $holdPaymentInfo->year, 'month'=>$holdPaymentInfo->month, 'calendar_type' =>1, 'org_id'=> $holdPaymentInfo->org_id]);
            //         if($payroll_group !== null) {
            //             $emp_payroll = $this->payroll->findOne(['employee_id' => $holdPaymentInfo->emp_id, 'payroll_group_id' =>$payroll_group->id]);
            //             if(!empty($emp_payroll)) {
            //                 $emp_payroll->update(['paid_status'=> 1]);
            //             }
            //         }
            //     }
            // }

            toastr()->success('Hold Payment added successfully!');
        } catch (\throwable $t) {
            toastr()->error($t->getMessage());
        }
        return redirect(route('payroll.hold.payment', $data['payrollModel']->id));
    }

    public function getDates(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $yearArray = [];
        $firstYear = 2022;
        $lastYear = (int) date('Y');
        $dateConverter = new DateConverter();
        $data['payrollModel'] = $payrollModel = $this->payrollObj->findOne($data['payroll_id']);
        // dd($data['payrollModel']);
        $data['employeeList'] = $this->holdPaymentObj->getHoldPaymentEmployeeNameList($payrollModel->year, $payrollModel->month, $payrollModel->organization_id);
        // dd($data['employeeList']);
        for ($i = $firstYear; $i <= $lastYear; $i++) {
            $yearArray[$i] = $i;
        }

        try {
            if ($data['calendar_type'] == 'eng') {
                $data['yearList'] = $dateConverter->getEngYears();
                $data['monthList'] = $dateConverter->getEngMonths();
            } else {
                $data['yearList'] = $dateConverter->getNepYears();
                $data['monthList'] = $dateConverter->getNepMonths();
            }
            // dd($data);
            return view('payroll::hold-payment.partial.ajaxdates', $data);
        } catch (\throwable $t) {
            return 0;
        }
    }

    public function filterMonth(Request $request)
    {
        try {
            $data['selectedMonth']=$request->data['selectedMonth'] ?? null;
            $data['createdPayrollMonth'] = collect($this->holdPaymentObj->getFinalizedPayrollMonth($request))
                ->mapWithKeys(function ($item) {
                    return [
                        $item => ['disabled' => 'disabled']
                    ];
                })
                ->toArray();

            $dateConverter = new DateConverter();
            if ($request->data['calender_type'] == 'nep') {
                $data['monthList'] = $dateConverter->getNepMonths();
                $data['field'] = 'month';
            } else {
                $data['monthList'] = $dateConverter->getEngMonths();
                $data['field'] = 'eng_month';
            }

            $response = [
                'error' => false,
                'data' => view('payroll::hold-payment.filterMonth', $data)->render(),
                'msg' => 'Success'
            ];
        } catch (\Throwable $th) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Something Went Wrong'
            ];
        }
        return response()->json($response, 200);
    }

    public function exportHoldPayment(Request $request)
    {
        $filter = $request->all();

        $data['arrearAdjustments'] = $this->holdPaymentObj->findAll(null,$filter);

        return Excel::download(new HoldPaymentExport($data),'hold-payment-report.xlsx');
    }
}
