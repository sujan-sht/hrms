<?php

namespace App\Modules\EmployeeRequest\Http\Controllers;

use App\Modules\Attendance\Repositories\AttendanceRequestInterface;
use App\Modules\Attendance\Repositories\PreOvertimeRequestInterface;
use App\Modules\EmployeeRequest\Repositories\EmployeeRequestInterface;
use App\Modules\Notification\Repositories\NotificationInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ClaimRequestController extends Controller
{
    /**
     * @var AttendanceRequestInterface
     */
    protected $attendanceRequest;
    /**
     * @var PreOvertimeRequestInterface
     */
    protected $preOvertimeRequest;
    /**
     * @var EmployeeRequestInterface
     */
    protected $employeeRequest;

    public function __construct(
        AttendanceRequestInterface $attendanceRequest,
        PreOvertimeRequestInterface $preOvertimeRequest,
        EmployeeRequestInterface $employeeRequest,
        NotificationInterface $notification
    ) {
        $this->attendanceRequest = $attendanceRequest;
        $this->preOvertimeRequest = $preOvertimeRequest;
        $this->employeeRequest = $employeeRequest;
        $this->notification = $notification;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('employeerequest::employee.claim.index');
    }

    public function teamIndex(Request $request)
    {
        $all_search = $psearch = $asearch = $dsearch = $fsearch = $search = $request->all();
        $user = auth()->user();
        $data['user_type'] = $user_type = $user->user_type;
        $data['emp_id'] = $user->emp_id;

        $all_search['first_approval_id'] = $user->id;
        $all_search['second_approval_id'] = $user->id;

        $data['requests'] = $requests = $this->employeeRequest->advanceSearch(10, $all_search);

        $psearch['status'] = 0;
        $psearch['first_approval_id'] = $user->id;
        $psearch['second_approval_id'] = $user->id;
        $data['pending_requests'] = $this->employeeRequest->advanceSearch(10, $psearch);

        $asearch['status'] = 1;
        $asearch['approved_by'] = $user->id;
        $data['approved_requests'] = $this->employeeRequest->advanceSearch(10, $asearch);

        $dsearch['status'] = 2;
        $dsearch['first_approval_id'] = $user->id;
        $dsearch['second_approval_id'] = $user->id;
        $data['rejected_requests'] = $this->employeeRequest->advanceSearch(10, $dsearch);

        //forwarded
        $fsearch['status'] = 3;
        $fsearch['first_approval_id'] = $user->id;
        $data['forwarded_requests'] = $this->employeeRequest->advanceSearch(10, $fsearch);

        $fpsearch['status'] = 3;
        $fpsearch['second_approval_id'] = $user->id;
        $data['forapproval_requests'] = $this->employeeRequest->advanceSearch(10, $fpsearch);

        return view('employeerequest::employee.request.team-index', $data);
    }

    public function preOvertimeRequest(Request $request)
    {
        $search = $request->all();
        $search['requested_by'] = auth()->user()->id;

        $data['pre_overtimes'] = $this->preOvertimeRequest->findAll(10, $search);
        return view('employeerequest::employee.claim.pre-overtime', $data);
    }

    public function attendanceRequest(Request $request)
    {
        $search = $request->all();
        //$search['request_type'] = 'ot';
        $search['requested_by'] = auth()->user()->id;

        $data['attendanceRequest'] = $this->attendanceRequest->advanceSearch(10, $search);
        return view('employeerequest::employee.claim.attendance-adjust', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('employeerequest::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
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
        return view('employeerequest::edit');
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

    public function updateRequestStatus(Request $request, $id)
    {
        $data = $request->all();
        try {
            $userInfo = auth()->user();
            $request_data = $this->employeeRequest->find($id);

            $data_array = [];
            if ($data['status'] == 3) {
                $resp = $request_data->employee->getEmpRequestFlow;
                if (!is_null($resp) && !empty($resp->request_second_approval_id)) {
                    $data['forwarded_to'] = $resp->request_second_approval_id;
                    $this->employeeRequest->update($id, $data);

                    /* ---------------------------------------------------
                    Notification Start
                    ------------------------------------------------------*/
                    $message = $userInfo->first_name . ' ' . $userInfo->last_name . " has forwarded " . optional($request_data->requestType)->title . " Request.";
                    $link = route('team-claimRequest.index');
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
                    toastr()->success('Request Updated Sucessfully!!');
                } else {
                    toastr()->error('No Second Approval found for this employee');
                }
            } elseif ($data['status'] == 1) {
                $data['approved_by'] = $userInfo->id;
                $data['approved_date'] = date('Y-m-d');
                $this->employeeRequest->update($id, $data);

                /* ---------------------------------------------------
                Notification Start
                ------------------------------------------------------*/
                $message = "Your " . optional($request_data->requestType)->title . " Request Has Been Approved.";
                $link = route('employeerequest.index');
                $notification_data = array(
                    'creator_user_id' => $userInfo->id,
                    'notified_user_id' => optional(optional($request_data->employee)->getUser)->id,
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

                $this->employeeRequest->update($id, $data);

                /* ---------------------------------------------------
                Notification Start
                ------------------------------------------------------*/
                $message = "Request Has Been " . $data['status'] == 1 ? 'Approved' : ($data['status'] == 2 ? 'Declined' : ($data['status'] == 3 ? 'Forwarded' : 'Pending')) . ". Please Check";
                $link = route('employeerequest.index');
                $notification_data = array(
                    'creator_user_id' => $userInfo->id,
                    'notified_user_id' => optional(optional($request_data->employee)->getUser)->id,
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
