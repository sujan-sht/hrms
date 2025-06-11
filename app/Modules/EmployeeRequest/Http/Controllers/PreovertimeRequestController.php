<?php

namespace App\Modules\EmployeeRequest\Http\Controllers;

use App\Modules\Attendance\Repositories\DailyAttendanceInterface;
use App\Modules\Attendance\Repositories\PreOvertimeRequestInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Notification\Repositories\NotificationInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class PreovertimeRequestController extends Controller
{
    /**
     * @var PreOvertimeRequestInterface
     */
    protected $preOvertimeRequest;
    /**
     * @var NotificationInterface
     */
    protected $notification;
    /**
     * @var DailyAttendanceInterface
     */
    protected $dailyAttendance;
    /**
     * @var EmployeeInterface
     */
    protected $employment;

    public function __construct(
        PreOvertimeRequestInterface $preOvertimeRequest,
        NotificationInterface $notification,
        DailyAttendanceInterface $dailyAttendance,
        EmployeeInterface $employment) {
        $this->preOvertimeRequest = $preOvertimeRequest;
        $this->notification = $notification;
        $this->dailyAttendance = $dailyAttendance;
        $this->employment = $employment;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $search = $request->all();
        $search['requested_by'] = auth()->user()->id;

        $data['pre_overtimes'] = $this->preOvertimeRequest->findAll(10, $search);
        return view('employeerequest::employee.claim.pre-overtime', $data);
    }

    public function teamIndex(Request $request)
    {
        $all_search = $psearch = $asearch = $dsearch = $fsearch = $search = $request->all();
        $user = auth()->user();
        $data['user_type'] = $user_type = $user->user_type;
        $data['emp_id'] = $user->emp_id;

        $all_search['first_approval_id'] = $user->id;
        $all_search['second_approval_id'] = $user->id;

        $data['requests'] = $requests = $this->preOvertimeRequest->findAll(10, $all_search);

        $psearch['status'] = 'Pending';
        $psearch['first_approval_id'] = $user->id;
        $psearch['second_approval_id'] = $user->id;
        $data['pending_requests'] = $this->preOvertimeRequest->findAll(10, $psearch);

        $asearch['status'] = 'Approved';
        $asearch['approved_by'] = $user->id;
        $data['approved_requests'] = $this->preOvertimeRequest->findAll(10, $asearch);

        $dsearch['status'] = 'Rejected';
        $dsearch['first_approval_id'] = $user->id;
        $dsearch['second_approval_id'] = $user->id;
        $data['rejected_requests'] = $this->preOvertimeRequest->findAll(10, $dsearch);

        //forwarded
        $fsearch['status'] = 'Forwarded';
        $fsearch['first_approval_id'] = $user->id;
        $data['forwarded_requests'] = $this->preOvertimeRequest->findAll(10, $fsearch);

        $fpsearch['status'] = 'Forwarded';
        $fpsearch['second_approval_id'] = $user->id;
        $data['forapproval_requests'] = $this->preOvertimeRequest->findAll(10, $fpsearch);

        return view('employeerequest::employee.request.preovertime.team-index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('employeerequest::employee.request.preovertime.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $user = auth()->user();
            $data['status'] = 'Pending';
            $data['requested_by'] = $user->id;
            $message = auth()->user()->first_name . ' ' . auth()->user()->middle_name . ' ' . auth()->user()->last_name . ' ' . "has created pre-overtime request";
            $link = route('claimRequest.preovertime');
            $notified_user_id = 1;

            $daily_att = $this->dailyAttendance->getEmpAttendanceByDate($data['ot_date'], $user->emp_id);
            if (!empty($daily_att)) {
                $data['daily_attendance_id'] = $daily_att->id;
            }

            $emp_detail = $this->employment->find($user->emp_id);
            if (!empty(optional($emp_detail->getEmpRequestFlow)->request_first_approval_id)) {
                $data['first_approval_id'] = $notified_user_id = $emp_detail->getEmpRequestFlow->request_first_approval_id;

                $this->preOvertimeRequest->save($data);

                /* ---------------------------------------------------
                Notification Start
                ------------------------------------------------------*/

                $notification_data = array(
                    'creator_user_id' => $user->id,
                    'notified_user_id' => $notified_user_id,
                    'message' => $message,
                    'link' => $link,
                    'is_read' => '0',
                );

                $this->notification->save($notification_data);
                /* ---------------------------------------------------
                Notification End
                ------------------------------------------------------*/

                toastr()->success('Pre-overtime Request Created Successfully!');
            } else {
                toastr()->error('Please set first approval for the employee');
            }

        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        if ($data['btn_name'] == 'submit_new') {
            return redirect()->route('preOvertimeRequest.create');
        } else {
            return redirect()->route('claimRequest.preovertime');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('employeerequest::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data['pre_overtime'] = $this->preOvertimeRequest->find($id);
        return view('employeerequest::employee.request.preovertime.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        try {
            $user = auth()->user();
            $message = auth()->user()->first_name . ' ' . auth()->user()->middle_name . ' ' . auth()->user()->last_name . ' ' . "has updated pre-overtime request";
            $link = route('claimRequest.preovertime');
            $notified_user_id = 1;

            $daily_att = $this->dailyAttendance->getEmpAttendanceByDate($data['ot_date'], $user->emp_id);
            if (!empty($daily_att)) {
                $data['daily_attendance_id'] = $daily_att->id;
            }
            $this->preOvertimeRequest->update($id, $data);

            /* ---------------------------------------------------
            Notification Start
            ------------------------------------------------------*/

            //foreach ($this->user->getAdminuser() as $key => $user) {
            $notification_data = array(
                'creator_user_id' => $user->id,
                'notified_user_id' => $notified_user_id,
                'message' => $message,
                'link' => $link,
                'is_read' => '0',
            );
            //}

            $this->notification->save($notification_data);
            /* ---------------------------------------------------
            Notification End
            ------------------------------------------------------*/

            toastr()->success('Pre-overtime Request Created Successfully!');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        if ($data['btn_name'] == 'submit_new') {
            return redirect()->route('preOvertimeRequest.create');
        } else {
            return redirect()->route('claimRequest.preovertime');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->preOvertimeRequest->delete($id);
            toastr()->success('Pre-overtime Request Deleted Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }
        return redirect()->back();
    }

    public function updateRequestStatus(Request $request, $id)
    {
        $data = $request->all();
        try {
            $userInfo = auth()->user();
            $request_data = $this->preOvertimeRequest->find($id);

            $data_array = [];
            if ($data['status'] == 'Forwarded') {
                $resp = optional(optional($request_data->getRequestedBy)->userEmployer)->getEmpRequestFlow;
                if (!is_null($resp) && !empty($resp->request_second_approval_id)) {
                    $data['forwarded_to'] = $resp->request_second_approval_id;
                    $this->preOvertimeRequest->update($id, $data);

                    /* ---------------------------------------------------
                    Notification Start
                    ------------------------------------------------------*/
                    $message = $userInfo->first_name . ' ' . $userInfo->last_name . " has forwarded an Pre-overtime Request.";
                    $link = route('team-preOvertimeRequest.index');
                    $notification_data = array(
                        'creator_user_id' => $userInfo->id,
                        'notified_user_id' => $data['forwarded_to'],
                        'message' => $message,
                        'link' => $link,
                        'is_read' => '0',
                    );
                    $this->notification->save($notification_data);

                    /* ---------------------------------------------------
                    Notification End
                    ------------------------------------------------------*/
                    toastr('Request Updated Sucessfully!!')->success();
                } else {
                    toastr()->error('No Second Approval found for this employee');
                }

            } elseif ($data['status'] == 'Approved') {
                $data['approved_by'] = $userInfo->id;
                $data['approved_date'] = date('Y-m-d');
                $this->preOvertimeRequest->update($id, $data);

                /* ---------------------------------------------------
                Notification Start
                ------------------------------------------------------*/
                $message = "Your Pre-overtime Request Has Been Approved.";
                $link = route('claimRequest.preovertime');
                $notification_data = array(
                    'creator_user_id' => $userInfo->id,
                    'notified_user_id' => $request_data->requested_by,
                    'message' => $message,
                    'link' => $link,
                    'is_read' => '0',
                );
                $this->notification->save($notification_data);

                /* ---------------------------------------------------
                Notification End
                ------------------------------------------------------*/
                toastr()->success('Request Updated Sucessfully!!');
            } else {

                $this->preOvertimeRequest->update($id, $data);

                /* ---------------------------------------------------
                Notification Start
                ------------------------------------------------------*/
                $message = "Pre-overtime Request Has Been " . $data['status']. ". Please Check";
                $link = route('claimRequest.preovertime');
                $notification_data = array(
                    'creator_user_id' => $userInfo->id,
                    'notified_user_id' => $request_data->requested_by,
                    'message' => $message,
                    'link' => $link,
                    'is_read' => '0',
                );
                $this->notification->save($notification_data);

                /* ---------------------------------------------------
                Notification End
                ------------------------------------------------------*/
                toastr()->success('Request Updated Sucessfully!!');
            }

        } catch (\Throwable $t) {

            toastr()->error($t->getMessage());
        }
        return redirect()->back();
    }
}
