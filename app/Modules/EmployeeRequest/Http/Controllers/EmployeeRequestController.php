<?php

namespace App\Modules\EmployeeRequest\Http\Controllers;

use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\EmployeeRequest\Repositories\EmployeeRequestInterface;
use App\Modules\EmployeeRequest\Repositories\EmployeeRequestTypeInterface;
use App\Modules\Notification\Repositories\NotificationInterface;

// Repositories
use App\Modules\User\Repositories\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class EmployeeRequestController extends Controller
{
    private $employeeRequest, $requestType, $user, $notification, $employment;

    public function __construct(EmployeeInterface $employment, EmployeeRequestInterface $employeeRequest, EmployeeRequestTypeInterface $requestType, UserInterface $user, NotificationInterface $notification)
    {
        $this->employeeRequest = $employeeRequest;
        $this->requestType = $requestType;
        $this->user = $user;
        $this->notification = $notification;
        $this->employment = $employment;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $search = $request->all();

        $userInfo = auth()->user();
        $user_id = $userInfo->id;
        $user_type = $userInfo->user_type;
        $user_emp_id = $userInfo->emp_id;

        $search_request_value = '';
        if (isset($search['search_value']) && !empty($search['search_value'])) {
            $search_request_value = $search['search_value'];
        }

        if ($user_type == 'super_admin' || strtolower($user_type) == 'admin' || strtolower($user_type) == 'hr') {
            $employeeRequests = $this->employeeRequest->findall($limit = 50, $filter = request('search_value'));
            return view('employeerequest::index', compact('employeeRequests', 'search_request_value'));
        } else {
            $employeeRequests = $this->employeeRequest->findUserRequests($limit = 50, $user_emp_id);
            return view('employeerequest::employee.request.index', compact('employeeRequests', 'search_request_value'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $requestTypes = $this->requestType->getList();
        $users = $this->user->getEmployeeList();
        $dropdown = $this->employeeRequest->benefit();

        $userInfo = auth()->user();
        $user_type = $userInfo->user_type;
        $is_edit = false;
        if ($user_type == 'super_admin' || strtolower($user_type) == 'admin' || strtolower($user_type) == 'hr') {
            return view('employeerequest::create', compact('requestTypes', 'users', 'dropdown', 'is_edit'));
        } else {
            // return view('employeerequest::create', compact('requestTypes', 'users', 'dropdown'));
            return view('employeerequest::employee.request.create', compact('requestTypes', 'users', 'dropdown', 'is_edit'));
        }
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
            $data['created_by'] = $user->id;

            if (strtolower($user->user_type) == 'admin' || $user->user_type == 'super_admin' || $user->user_type == 'hr') {

                $emp_detail = $this->employment->find($data['employee_id']);
                $message = auth()->user()->first_name . ' ' . auth()->user()->middle_name . ' ' . auth()->user()->last_name . ' ' . "has created request on behalf of " . $emp_detail->first_name . ' ' . $emp_detail->middle_name . ' ' . $emp_detail->last_name;
                $link = route('employeerequest.index');
                $emp_user = $this->user->getUserId($data['employee_id']);
                $notified_user_id = $emp_user->id;
            } else {
                $data['status'] = 0;
                $data['employee_id'] = $user->emp_id;
                $emp_detail = $this->employment->find($user->emp_id);
                $message = auth()->user()->first_name . ' ' . auth()->user()->middle_name . ' ' . auth()->user()->last_name . ' ' . "has created request";
                $link = route('employeerequest.index');
                $notified_user_id = 1;
            }

            if ($request->hasFile('bill')) {
                $data['bill'] = $this->employeeRequest->upload($data['bill']);
            }

            // if (!empty(optional($emp_detail->getEmpRequestFlow)->request_first_approval_id)) {

            //     if($data['status'] == 1) {
            //         $data['approved_date'] = date('Y-m-d');
            //         $data['approved_by'] = $user->id;
            //     } else {
            //         $data['first_approval_id'] = $notified_user_id = $emp_detail->getEmpRequestFlow->request_first_approval_id;
            //     }

            //     $resp = $this->employeeRequest->save($data);

            //     /* ---------------------------------------------------
            //     Notification Start
            //     ------------------------------------------------------*/

            //     $notification_data = array(
            //         'creator_user_id' => $user->id,
            //         'notified_user_id' => $notified_user_id,
            //         'message' => $message,
            //         'link' => $link,
            //         'is_read' => '0',
            //     );

            //     $this->notification->save($notification_data);

            //     toastr()->success('Request Sent Successful!');
            //     /* ---------------------------------------------------
            //     Notification End
            //     ------------------------------------------------------*/
            // } else {
            //     toastr()->error('Please set first approval for the employee');
            // }

            $resp = $this->employeeRequest->save($data);
            toastr()->success('Request Saved Successful!');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        if (isset($data['btn_name']) && $data['btn_name'] == 'submit_new') {
            return redirect()->route('employeeRequest.create');
        } else {
            return redirect()->route('employeerequest.index');
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
        $employeeRequest = $this->employeeRequest->find($id);
        $requestTypes = $this->requestType->getList();
        $users = $this->user->getEmployeeList();
        $dropdown = $this->employeeRequest->benefit();

        $userInfo = auth()->user();
        $user_type = $userInfo->user_type;
        $is_edit = true;
        if ($user_type == 'super_admin' || strtolower($user_type) == 'admin' || strtolower($user_type) == 'hr') {
            return view('employeerequest::edit', compact('employeeRequest', 'requestTypes', 'users', 'dropdown', 'is_edit'));
        } else {
            return view('employeerequest::employee.request.edit', compact('employeeRequest', 'requestTypes', 'users', 'dropdown', 'is_edit'));
        }
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
            if ($request->hasFile('bill')) {
                $data['bill'] = $this->employeeRequest->upload($data['bill']);
            }

            if ($data['status'] == 1) {
                $data['approved_date'] = date('Y-m-d');
                $data['approved_by'] = auth()->user()->id;
            }

            $this->employeeRequest->update($id, $data);

            /* ---------------------------------------------------
            Notification Start
            ------------------------------------------------------*/
            $employeeRequest = $this->employeeRequest->find($id);
            if ($employeeRequest->status == '1') {
                $message = 'Your request for “' . optional($employeeRequest->requestType)->title . '” has been approved by the Administration.';
            } else if ($employeeRequest->status == '2') {
                $message = 'Your request for “' . optional($employeeRequest->requestType)->title . '” has been rejected by the Administration.';
            } else {
                $message = auth()->user()->first_name . ' ' . auth()->user()->middle_name . ' ' . auth()->user()->last_name . ' ' . "has updated request";
            }

            if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'Admin') {

                $emp_detail = $this->employment->find($data['employee_id']);
                // $message = auth()->user()->first_name . ' ' . auth()->user()->middle_name . ' ' . auth()->user()->last_name . ' ' . "has Updated request on behalf of " . $emp_detail->first_name . ' ' . $emp_detail->middle_name . ' ' . $emp_detail->last_name;
                $link = route('employeerequest.index');
                $emp_user = $this->user->getUserId($data['employee_id']);
                $notified_user_id = $emp_user->id;
            } else {
                // $message = auth()->user()->first_name . ' ' . auth()->user()->middle_name . ' ' . auth()->user()->last_name . ' ' . "has updated request";
                $link = route('employeerequest.index');
                $notified_user_id = 1;
            }

            $emprequest = $this->employeeRequest->find($id);
            if ($emprequest && $emprequest->employee_id) {
                $notification_data = array(
                    'creator_user_id' => auth()->user()->id,
                    'notified_user_id' => $notified_user_id,
                    'message' => $message,
                    'link' => $link,
                    'is_read' => '0',
                );

                $this->notification->save($notification_data);
            }
            /* ---------------------------------------------------
            Notification End
            ------------------------------------------------------*/

            toastr()->success('Request Updated Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        if (isset($data['btn_name']) && $data['btn_name'] == 'submit_new') {
            return redirect()->route('employeeRequest.create');
        } else {
            return redirect()->route('employeerequest.index');
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
            $this->employeeRequest->delete($id);
            toastr()->success('Request Deleted Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }
        return redirect()->back();
    }

    public function view()
    {
        $employeeRequest = $this->employeeRequest->find(request('id'));
        $html = "";
        $html .= "<ul class='list-group'>";
        $html .= "<li class='list-group-item'><b>Title:&nbsp</b>" . $employeeRequest->title . "</li>";
        $html .= "<li class='list-group-item'><b>Request Type:&nbsp</b>" . $employeeRequest->requestType->title . "</li>";
        $html .= "<li class='list-group-item'><b>Description:&nbsp</b>" . $employeeRequest->description . "</li>";
        $html .= "<li class='list-group-item'><b>Employee:&nbsp</b>";

        $html .= $employeeRequest->employee ? $employeeRequest->employee->first_name . ' ' . $employeeRequest->employee->middle_name . ' ' . $employeeRequest->employee->last_name : null;

        $html .= "</li>";
        if ($employeeRequest->requestType->title == 'Benefit Type') {
            $html .= "<li class='list-group-item'><b>Benefit Type:&nbsp</b>" . optional($employeeRequest->dropdown)->dropvalue . "</li>";
            if ($employeeRequest->pay_type == '1') {
                $html .= "<li class='list-group-item'><b>Pay Type:&nbsp</b>Cash</li>";
            } elseif ($employeeRequest->pay_type == '2') {
                $html .= "<li class='list-group-item'><b>Pay Type:&nbsp</b>Credit</li>";
                $html .= "<li class='list-group-item'><b>Bank Name:&nbsp</b>" . $employeeRequest->bank_name . "</li>";
                $html .= "<li class='list-group-item'><b>Account Number:&nbsp</b>" . $employeeRequest->account_number . "</li>";
            }
        }

        if ($employeeRequest->type_id == '2') {
            $html .= "<li class='list-group-item'><b>Travel Date:&nbsp</b>" . $employeeRequest->travel_date . "</li>";
            $html .= "<li class='list-group-item'><b>Market Visit Location:&nbsp</b>" . $employeeRequest->market_visit_location . "</li>";
            $html .= "<li class='list-group-item'><b>Night Halt:&nbsp</b>" . $employeeRequest->night_halt . "</li>";
            $html .= "<li class='list-group-item'><b>Transport Cost:&nbsp</b>Rs." . ($employeeRequest->transport_cost ?? 0) . "</li>";
            $html .= "<li class='list-group-item'><b>Lodging:&nbsp</b>Rs." . ($employeeRequest->lodging ?? 0) . "</li>";
            $html .= "<li class='list-group-item'><b>Fooding:&nbsp</b>Rs." . ($employeeRequest->fooding ?? 0) . "</li>";
            $html .= "<li class='list-group-item'><b>Local DA:&nbsp</b>Rs." . ($employeeRequest->local_DA ?? 0) . "</li>";
            $html .= "<li class='list-group-item'><b>DA:&nbsp</b>Rs." . ($employeeRequest->DA ?? 0) . "</li>";
            $html .= "<li class='list-group-item'><b>Vehicle Expenses:&nbsp</b>Rs." . ($employeeRequest->motor_cycle_expenses ?? 0) . "</li>";
        }

        $html .= "<li class='list-group-item'><b>Request Date:&nbsp</b>" . date('jS M, Y', strtotime($employeeRequest->created_at)) . "</li>";
        $html .= "</ul>";

        return $html;
    }

    public function statistics()
    {
        $userInfo = Auth::user();
        $data['department_id'] = $userInfo->department;
        $data['userType'] = $userInfo->user_type;

        $data['total'] = $this->employeeRequest->getTotalRequest();
        $data['approved'] = $this->employeeRequest->getTotal(1);
        $data['pending'] = $this->employeeRequest->getTotal(0);
        $data['canceled'] = $this->employeeRequest->getTotal(2);

        return view('employeerequest::statistics', compact('data'));
    }

    public function findrequestbytype($type)
    {
        $data['request_data'] = $this->employeeRequest->findRequestByType(auth()->user()->emp_id, $type);
        return view('admin::employeedashboard.partial.requesttype', $data)->render();
    }
}
