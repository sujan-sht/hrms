<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Attendance\Repositories\AttendanceRequestInterface;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Leave\Repositories\LeaveInterface;
use App\Modules\Leave\Repositories\LeaveTypeInterface;
use App\Modules\LeaveYearSetup\Repositories\LeaveYearSetupInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MasterReportController extends Controller
{
    private $organization;
    private $employee;
    private $branch;
    protected $leaveYearSetup;
    protected $leaveTypeObj;
    private $leave;
    protected $attendanceRequest;



    /**
     * OrganizationController constructor.
     * @param OrganizationInterface $organization
     * @param DropdownInterface $dropdown
     * @param EmploymentInterface $employment
     * @param FieldInterface $field
     */
    public function __construct(
        OrganizationInterface $organization,
        LeaveYearSetupInterface $leaveYearSetup,
        BranchInterface $branch,
        LeaveTypeInterface $leaveTypeObj,
        LeaveInterface $leave,
        AttendanceRequestInterface $attendanceRequest

    ) {
        $this->attendanceRequest = $attendanceRequest;
        $this->organization = $organization;
        $this->leave = $leave;
        $this->branch = $branch;
        $this->leaveYearSetup = $leaveYearSetup;
        $this->leaveTypeObj = $leaveTypeObj;
    }

    public function leaveReport(Request $request)
    {
        $filter = $request->all();

        $data['leaveTypeList']  = $this->leaveTypeObj->getList()->toArray();
        $filter['leave_year_id'] = isset($filter['leave_year_id']) ? $filter['leave_year_id'] : getCurrentLeaveYearId();
        $data['branchList'] = $this->branch->getList();

        $data['leaveKindList'] = Leave::leaveKindList();
        $data['statusList'] = Leave::statusList();
        $data['leaveModels'] = $this->leave->findAll('', $filter);
        $data['leaveYearList'] = $this->leaveYearSetup->getLeaveYearList();
        $data['organizationList'] = $this->organization->getList();

        $data['count_leave_types'] = $data['leaveModels']->groupBy('leave_type_id')
            ->map(function ($items) {
                return $items->count();
            })->toArray();

        // $data['count_leave_status'] = $data['leaveModels']->groupBy('status')
        //     ->map(function ($items, $category) {
        //         return $items->count();
        //     })->sortBy(function ($value, $key) {
        //         return $key;
        //     });

        $count_leave_kind = $data['leaveModels']->groupBy('leave_kind')
            ->map(function ($items, $category) {
                return $items->count();
            });

        $half_leave_kind = $data['leaveModels']->whereNotNull('half_type')->groupBy('half_type')
            ->map(function ($items, $category) {
                return $items->count();
            });

        $data['count'] = [
            'total_leave' => $count_leave_kind->sum(),
            'full_leave' => isset($count_leave_kind[2]) ? $count_leave_kind[2] : 0,
            'half_leave' => isset($count_leave_kind[1]) ? $count_leave_kind[1] : 0,
            'first_half_leave' => isset($half_leave_kind[1]) ? $half_leave_kind[1] : 0,
            'second_half_leave' => isset($half_leave_kind[2]) ? $half_leave_kind[2] : 0,

        ];
        return view('admin::report.leave', $data);
    }

    public function attendanceReport(Request $request)
    {
        abort('404');
        $filter = $request->all();

        $data['filter'] = $filter = $request->all();
        $data['statusList'] =  $status = $this->attendanceRequest->getStatus();
        // unset($data['statusList'][5]);
    // $data['allStatus'] = $status;
        $data['organizationList'] = $this->organization->getList();
        $data['type'] = $typeLists = $this->attendanceRequest->getTypes();
        $data['kind'] = $this->attendanceRequest->getKinds();
        $data['requests'] = $this->attendanceRequest->findAll(null, $filter);
        $data['typeGroupBy'] = $data['requests']->groupBy('type')
            ->map(function ($items) use ($typeLists){
                // return $items->count();
                // return $items->groupBy('status')->sortBy(function ($value, $key) {
                //     return $key;
                // });

                foreach ($items as $key => $item) {
                    return [
                        'total'=>$items->count(),
                        'type'=>$item->getType()
                    ];
                }
            })->sortBy(function ($value, $key) {
                return $key;
            });



        $attendances = Attendance::when( true, function ($query) use ($filter) {
            $query->where('checkin','>', '08:59:59');
        })->get();
        dd(date('H:i:s','08:59:59'),$attendances->toArray());




        // $data['count'] = [
        //     'total_leave' => $count_leave_kind->sum(),
        //     'full_leave' => isset($count_leave_kind[2]) ? $count_leave_kind[2] : 0,
        //     'half_leave' => isset($count_leave_kind[1]) ? $count_leave_kind[1] : 0,
        //     'first_half_leave' => isset($half_leave_kind[1]) ? $half_leave_kind[1] : 0,
        //     'second_half_leave' => isset($half_leave_kind[2]) ? $half_leave_kind[2] : 0,

        // ];

        // dd($data);

        // dd(($data['count_leave_kind']->sum()));
        return view('admin::report.attendance', $data);
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('admin::edit');
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
}
