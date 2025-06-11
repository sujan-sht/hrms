<?php

namespace App\Modules\Payroll\Http\Controllers;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\FiscalYearSetup\Repositories\FiscalYearSetupInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Payroll\Repositories\BonusInterface;
use App\Modules\Setting\Repositories\SettingInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BonusController extends Controller
{
    private $payrollObj;
    private $organizationObj;
    private $employeeObj;
    private $deductionSetupObj;
    private $settingObj;
    private $fiscalYearObj;
    private $dropdown;
    private $bonusObj;
    private $branchObj;
    /**
     */
    public function __construct(
        OrganizationInterface $organizationObj,
        EmployeeInterface $employeeObj,
        SettingInterface $settingObj,
        FiscalYearSetupInterface $fiscalYearObj,
        DropdownInterface $dropdown,
        BonusInterface $bonusObj,
        BranchInterface $branchObj
    ) {
        $this->organizationObj = $organizationObj;
        $this->employeeObj = $employeeObj;
        $this->settingObj = $settingObj;
        $this->fiscalYearObj = $fiscalYearObj;
        $this->dropdown = $dropdown;
        $this->bonusObj = $bonusObj;
        $this->branchObj = $branchObj;
    }

    public function index(Request $request)
    {
        $filter = $request->all();

        $data['bonusModels'] = $this->bonusObj->findAll(20, $filter);
        $data['organizationList'] = $this->organizationObj->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        $data['branchList'] = $this->branchObj->getList();

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

        return view('payroll::bonus.index', $data);
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

        $dateConverter = new DateConverter();
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

        return view('payroll::bonus.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData = $request->all();
        $inputData['calendar_type'] = $inputData['calendar_type'] ?? $inputData['eng_calendar_type'];
        $inputData['year'] = $inputData['calendar_type'] == 'nep' ? $inputData['year'] : $inputData['eng_year'];
        $inputData['month'] = $inputData['calendar_type'] == 'nep' ? $inputData['month'] : $inputData['eng_month'];
        $filter = [
            'organization' => $inputData['organization_id'],
            'year' => $inputData['year'],
            'month' => $inputData['month'],

        ];
        $bonusModel = $this->bonusObj->findAll(20,$filter);
        try {
            if (count($bonusModel) > 0) {

                toastr()->warning('Bonus Already Exists');
            } else {
                $this->bonusObj->create($inputData);

                toastr()->success('Data Created Successfully');
            }
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('bonus.index'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data['bonusModel'] = $bonusModel = $this->bonusObj->findOne($id);
        $data['incomes'] = $bonusModel->getIncomes();
        return view('payroll::bonus.show', $data);
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

    public function salaryTransfer($id)
    {
        $bonusModel = $this->bonusObj->findOne($id);
        $data['bonusModel'] = $bonusModel;
        $data['setting'] = $this->settingObj->getData();
        return view('payroll::bonus.salary-transfer-letter', $data);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->bonusObj->delete($id);

            toastr()->success('Data Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }
}
