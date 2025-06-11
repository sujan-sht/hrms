<?php

namespace App\Modules\Shift\Http\Controllers;

use App\Modules\Shift\Repositories\GroupInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

// Repositories
use App\Modules\Shift\Repositories\ShiftInterface;
use App\Modules\Shift\Repositories\EmployeeShiftInterface;
use App\Modules\Employment\Repositories\EmploymentInterface;

use App\Modules\User\Repositories\UserInterface;
use App\Modules\Notification\Repositories\NotificationInterface;

class EmployeeShiftController extends Controller
{
    private $employeeShift, $shift, $employment;
    /**
     * @var GroupInterface
     */
    private $group;

    /**
     * EmployeeShiftController constructor.
     * @param EmployeeShiftInterface $employeeShift
     * @param ShiftInterface $shift
     * @param EmploymentInterface $employment
     * @param GroupInterface $group
     * @param UserInterface $user
     * @param NotificationInterface $notification
     */
    public function __construct(EmployeeShiftInterface $employeeShift,
                                ShiftInterface $shift,
                                EmploymentInterface $employment,
                                GroupInterface $group,
                                UserInterface $user,
                                NotificationInterface $notification)
    {
        $this->shift = $shift;
        $this->employeeShift = $employeeShift;
        $this->employment = $employment;
        $this->notification = $notification;
        $this->user = $user;
        $this->group = $group;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('shift::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('shift::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        try {
            $employeeShift = $this->employeeShift->save($data);

            /* ---------------------------------------------------
                        Notification Start
            ------------------------------------------------------*/

            $shift = $this->shift->find($data['shift_id']);
            $message = "You Have Been Added To a Shift - <b>".$shift->title."</b>";
            $link = route('shift.index');
            $employee = $this->employment->find($data['employee_id']);

            if ($employee && $employee->getUser !== null) {
                $user = $employee->getUser;
                $notification_data = array(
                    'creator_user_id' => auth()->user()->id,
                    'notified_user_id' => $user->id,
                    'message' => $message,
                    'link' => $link,
                    'is_read' => '0',
                );
                $this->notification->save($notification_data);
            }

            /* ---------------------------------------------------
                        Notification End
            ------------------------------------------------------*/
            toastr()->success('Employee Shift Created Successfully.');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->route('shift.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('shift::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('shift::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function view()
    {
        $userInfo = auth()->user();
        $user_id = $userInfo->id;
        $user_type = $userInfo->user_type;
        if($user_type == 'super_admin') {
            $employees = $this->employment->findAll($limit = 20);
        } else {
            $employee = auth()->user()->userEmployer;
            $employees = [$employee];
        }
        $shifts = $this->shift->findAll();
        $days = $this->employeeShift->getDays();
        $group_data = $this->group->findAll();

        return view('shift::employeeshift.view2', compact('shifts', 'employees', 'days','group_data'));
    }

    public function remove()
    {
        try {
            $this->employeeShift->delete(request('employee_id'), request('shift_id'),request('days'),request('group_id'));

             /* ---------------------------------------------------
                        Notification Start
            ------------------------------------------------------*/

            $shift = $this->shift->find(request('shift_id'));
            $message = "You Have Been Removed From a Shift - <b>".$shift->title."</b>";
            $link = route('shift.index');
            $employee = $this->employment->find(request('employee_id'));

            if ($employee && $employee->getUser !== null) {
                $user = $employee->getUser;
                $notification_data = array(
                    'creator_user_id' => auth()->user()->id,
                    'notified_user_id' => $user->id,
                    'message' => $message,
                    'link' => $link,
                    'is_read' => '0',
                );
                $this->notification->save($notification_data);
            }

            /* ---------------------------------------------------
                        Notification End
            ------------------------------------------------------*/
            toastr()->success('Employee Shift Removed Successfully.');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    public function add()
    {
        try {
            $data = [
                'employee_id' => request('employee_id'),
                'shift_id' => request('shift_id'),
                'days' => request('days'),
                'group_id' => request('group_id'),
                'updated_by' => auth()->user()->id,
                'created_by' => auth()->user()->id
            ];

            /* ---------------------------------------------------
                        Notification Start
            ------------------------------------------------------*/

            $shift = $this->shift->find(request('shift_id'));

            $days = $this->employeeShift->getDays();
            $message = "You have been added to a <b>".$shift->title."</b> Shift on ".$days[request('days')];
            $link = route('shift.index');
            $employee = $this->employment->find(request('employee_id'));

            if ($employee && $employee->getUser !== null) {
                $user = $employee->getUser;
                $notification_data = array(
                    'creator_user_id' => auth()->user()->id,
                    'notified_user_id' => $user->id,
                    'message' => $message,
                    'link' => $link,
                    'is_read' => '0',
                );
                $this->notification->save($notification_data);
            }

            /* ---------------------------------------------------
                        Notification End
            ------------------------------------------------------*/

            $this->employeeShift->save($data);

            toastr()->success('Shift Added Successfully.');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    public function change_day_status()
    {
        $shift = $this->shift->find(request('shift_id'));

        $shiftDays = explode(',', $shift->days);

        $check = array_search(request('day'), $shiftDays);

        if ($check !== false) {
            if (count($shiftDays) == 1) {
                toastr()->error('This is only day left on this shift. Cannot remove.');
                return redirect()->back();
            }

            try {
                unset($shiftDays[$check]);

                $newDays = implode(',', $shiftDays);

                $data['days'] = $newDays;

                $this->shift->update(request('shift_id'), $data);

                toastr()->success('Shift Updated Successfully.');
            } catch (\Throwable $e) {
                toastr()->error($e->getMessage());
            }
        } else {
            try{
                array_push($shiftDays, request('day'));

                $newDays = implode(',', $shiftDays);

                $data['days'] = $newDays;

                $this->shift->update(request('shift_id'), $data);
                toastr()->success('Shift Updated Successfully.');
            } catch (\Throwable $e) {
                toastr()->error($e->getMessage());
            }

        }
        return redirect()->back();
    }
}
