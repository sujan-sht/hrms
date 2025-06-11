<?php

namespace App\Modules\Leave\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\Leave\Entities\Leave;
use Illuminate\Support\Facades\Config;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Leave\Exports\LeaveMonthlyReport;
use App\Modules\Leave\Repositories\LeaveInterface;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Leave\Repositories\LeaveTypeInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\LeaveYearSetup\Repositories\LeaveYearSetupInterface;
use App\Modules\FiscalYearSetup\Repositories\FiscalYearSetupInterface;


class ReportController extends Controller
{
    private $leave;
    private $organization;
    private $leaveTypeObj;
    private $fiscalYearSetup;
    private $branchObj;
    private $employeeObj;


    private $leaveYearSetup;



    public function __construct(
        LeaveInterface $leave,
        OrganizationInterface $organization,
        LeaveTypeInterface $leaveTypeObj,
        FiscalYearSetupInterface $fiscalYearSetup,
        BranchInterface $branchObj,
        EmployeeInterface $employeeObj,
        LeaveYearSetupInterface $leaveYearSetup
    ) {
        $this->leave = $leave;
        $this->organization = $organization;
        $this->leaveYearSetup = $leaveYearSetup;
        $this->leaveTypeObj = $leaveTypeObj;
        $this->branchObj = $branchObj;
        $this->employeeObj = $employeeObj;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function previousLeaveYearReport(Request $request)
    {
        $filter = $request->all();
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branchObj->getList();
        // $data['fiscalYearList'] = $this->fiscalYearSetup->getFiscalYearList();
        $data['leaveYearList'] = $this->leaveYearSetup->getLeaveYearList();
        $data['employeeList'] = $this->employeeObj->getList();
        $dateConverter = new DateConverter();
        $data['leaveTypeList'] = $this->leaveTypeObj->getList();

        $data['monthLists'] = (setting('calendar_type') == 'BS') ? $dateConverter->getNepMonths() : $dateConverter->getEngMonths();

        $data['employeeLeaveMonths'] = [];
        if (isset($filter['leave_year_id'])) {
            $data['employeeLeaveMonths'] = self::getMonthlyReport($filter, $data, 10);
        }
        return view('leave::report.monthly-report', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function exportMonthlyLeaveReport(Request $request)
    {
        $filter = $request->all();
        $data['employeeLeaveMonths'] = [];

        $dateConverter = new DateConverter();
        $data['monthLists'] = (setting('calendar_type') == 'BS') ? $dateConverter->getNepMonths() : $dateConverter->getEngMonths();

        if (isset($filter['leave_year_id'])) {
            $data['employeeLeaveMonths'] = self::getMonthlyReport($filter, $data, Config::get('leave.export-length'));
        }
        return Excel::download(new LeaveMonthlyReport($data), 'leave-monthly-report.xlsx');
    }

    function getMonthlyReport($filter, $data, $limit)
    {
        $query = Employee::query();
        $query->where('status', '=', 1);
        if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
            $query->where('organization_id', $filter['organization_id']);
        }

        if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
            $query->where('id', $filter['employee_id']);
        }
        if (isset($filter['branch_id']) && !empty($filter['branch_id'])) {
            $query->where('branch_id', $filter['branch_id']);
        }
        if (auth()->user()->user_type == 'employee') {
            $query->where('id', auth()->user()->emp_id);
        } elseif (auth()->user()->user_type == 'supervisor') {
            $employeeIds = Employee::getSubordinates(auth()->user()->id);
            array_push($employeeIds, auth()->user()->emp_id);
            $query->whereIn('id', $employeeIds);
        }

        $employees = $query->paginate($limit);
        return $employees->setCollection($employees->getCollection()->transform(function ($emp) use ($data, $filter) {
            $date = setting('calendar_type') == 'AD' ? 'date' : 'nepali_date';
            $empLeave = Leave::select(
                DB::raw("(DATE_FORMAT($date, '%m')) as month"),
                DB::raw('ROUND(SUM(CASE WHEN leave_kind = 1 THEN 0.5 ELSE 1 END),2) AS total')
            )
                ->whereHas('leaveTypeModel', function ($query) use ($filter) {
                    $query->where([
                        'leave_year_id' => $filter['leave_year_id'],
                    ]);


                if (!empty($filter['leave_type_id'])) {
                    $query->where('leave_type_id', $filter['leave_type_id']);
                }
                })
                ->where([
                    'employee_id' => $emp->id,
                ])->where('status', '!=', 4)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');
            $leaves = [];
            foreach ($data['monthLists'] as $monthKey => $monthList) {
                $month = sprintf("%02d", $monthKey);
                $leaves[$month] = ' ';
                if (array_key_exists($month, $empLeave->toArray())) {
                    $leaves[$month] = $empLeave[$month];
                }
            }
            $emp->month_leave = $leaves;
            return $emp;
        }));
    }
}
