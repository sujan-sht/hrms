<?php

namespace App\Modules\Tada\Http\Controllers;

// Repositories

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Notification\Repositories\NotificationInterface;
use App\Modules\Tada\Entities\TadaRequestDetail as TadaDetail;
use App\Modules\Tada\Entities\TadaPartiallySettledDetail;
use App\Modules\Tada\Repositories\BillInterface;
use App\Modules\Tada\Repositories\TadaRequestInterface;
use App\Modules\Tada\Repositories\TadaTypeInterface;
use App\Modules\Setting\Repositories\SettingInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Modules\User\Repositories\UserInterface;
use App\Modules\Employment\Entities\ClaimRequestFlow;
use App\Modules\User\Entities\User;


class EmployeeTadaRequestController extends Controller
{
    protected $employment, $tada, $tadaType, $tadaBill, $notification, $user;

    public function __construct(
        EmployeeInterface $employment,
        UserInterface $user,
        TadaRequestInterface $tada,
        TadaTypeInterface $tadaType,
        SettingInterface $setting,
        BillInterface $tadaBill,
        NotificationInterface $notification
    ) {
        $this->employment = $employment;
        $this->user = $user;
        $this->tada = $tada;
        $this->tadaType = $tadaType;
        $this->tadaBill = $tadaBill;
        $this->notification = $notification;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        $filter['employee_id'] = $this->user->getUserById(auth()->user()->id)->toArray()['emp_id'];
        $tadas = $this->tada->findAll(50, $filter);
        // dd($tadas);
        return view('tada::employee.request.index', compact('tadas'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $is_edit = false;
        $employees = $this->employment->getList();
        $tadaTypes = $this->tadaType->getList();
        return view('tada::employee.request.create', compact('employees', 'tadaTypes', 'is_edit'));
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
            $date_converter = new DateConverter();
            $from_date = explode('-', $data['nep_request_date']);

            $request_date_eng = $date_converter->nep_to_eng($from_date[0], $from_date[1], $from_date[2]);
            $data['eng_request_date'] = $request_date_eng['year'] . '-' . $request_date_eng['month'] . '-' . $request_date_eng['date'];
            $data['employee_id'] = $data['created_by'] = $this->user->getUserById(auth()->user()->id)->toArray()['emp_id'];
            $emp_req_flow = ClaimRequestFlow::where('emp_id', '=', $data['employee_id'])->first();
            $HR_id = User::where('user_type', '=', 'hr')->orWhere('user_type', '=', 'hr')->first();

            $data['approver_id'] = $emp_req_flow['request_first_approval_id'] ?? $HR_id->id;
            $tada = $this->tada->save($data);

            //save tada details
            $details = [];

            if (isset($data['amount'])) {
                for ($i = 0; $i < count($data['amount']); $i++) {

                    if (isset($data['amount'][$i]) && isset($data['type_id'][$i]) && !empty($data['amount'][$i])) {

                        $details[$i] = new TadaDetail([
                            'type_id' => $data['type_id'][$i] ?? null,
                            // 'tada_request_id' => $data['type_id'][$i] ?? null,
                            'amount' => $data['amount'][$i] ?? 0,
                            'remark' => $data['remark'][$i] ?? null,
                        ]);
                    }
                }

                if (isset($details)) {
                    $tada->tadaDetails()->saveMany($details);
                }
            }

            toastr()->success('Request Details Created Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        if (isset($tada)) {
            history()->onCreate($tada->id, 'Tada', $tada->title);
        }

        return redirect()->route('employeetadaRequest.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $tada = $this->tada->find($id);
        return view('tada::employee.request.show', compact('tada'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $is_edit = true;
        $employees = $this->employment->getList();
        $tadaTypes = $this->tadaType->getList();
        $tada = $this->tada->find($id);

        return view('tada::employee.request.edit', compact('employees', 'tadaTypes', 'tada', 'is_edit'));
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
            // dd($data);

            $date_converter = new DateConverter();
            $from_date = explode('-', $data['nep_request_date']);

            $request_date_eng = $date_converter->nep_to_eng($from_date[0], $from_date[1], $from_date[2]);
            $data['eng_request_date'] = $request_date_eng['year'] . '-' . $request_date_eng['month'] . '-' . $request_date_eng['date'];

            $data['updated_by'] = auth()->user()->id;

            $tada =  $this->tada->find($id);

            if ($request->hasFile('excel_file') && !empty($data['excel_file'])) {
                $data['excel_file'] = $this->tada->uploadExcel($data['excel_file']);
            } else {
                $data['excel_file'] = $tada->excel_file;
            }

            //Multiple Bills
            $bill_files = $request->file('bills');
            if ($request->hasFile('bills') && !empty($data['bills'])) {
                $bill_images = $this->tadaBill->uploadBills($bill_files);
            }

            $this->tada->update($id, $data);

            if (isset($bill_images)) {
                $this->tadaBill->saveBills($bill_images, $tada->id);
            }

            //save tada details

            $tada->tadaDetails()->delete();
            $details = [];

            if (isset($data['amount'])) {
                for ($i = 0; $i < count($data['amount']); $i++) {

                    if (isset($data['amount'][$i]) && isset($data['type_id'][$i]) && !empty($data['amount'][$i])) {

                        $details[$i] = new TadaDetail([
                            'type_id' => $data['type_id'][$i] ?? null,
                            'amount' => $data['amount'][$i] ?? 0,
                            'remark' => $data['remark'][$i] ?? null,
                        ]);
                    }
                }

                if (isset($details)) {
                    $tada->tadaDetails()->saveMany($details);
                }
            }

            toastr()->success('Tada Details Updated Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        history()->onUpdate($id, 'Tada', $data['title']);

        return redirect()->route('tadaRequest.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $tada = $this->tada->find($id);
            $this->tada->delete($id);
            toastr()->success('TADA Deleted Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        if ($tada->title) {
            history()->onDelete($id, 'Tada', $tada->title);
        }

        return redirect()->back();
    }

    public function deleteBillImage($image_id)
    {
        $tada_bill = $this->tadaBill->find($image_id);
        $tada_bill->delete();
        return 1;
    }

    public function updateStatusForm($id)
    {
        $data['tadaModel'] = $this->tada->find($id);

        return view('tada::partial.status-form', $data);
    }

    public function updateStatus(Request $request, $id)
    {
        $data = $request->all(); //dd($data);
        try {
            $userInfo = auth()->user();
            $tada = $this->tada->find($id);

            $emp_detail = $this->employment->find($tada->employee_id);

            $data_array = [];
            if ($data['status'] == 'forwarded') {
                $resp = optional($emp_detail->getEmpRequestFlow);
                if (!is_null($resp) && !empty($resp->request_second_approval_id)) {
                    $data['forwarded_to'] = $resp->request_second_approval_id;
                    $data['forwarded_date'] = date('Y-m-d');
                    $this->tada->update($id, $data);

                    /* ---------------------------------------------------
                    Notification Start
                    ------------------------------------------------------*/
                    $message = $userInfo->first_name . ' ' . $userInfo->last_name . " has forwarded a tada Request.";
                    $link = route('tada.index');
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
            } elseif ($data['status'] == 'fully settled') {
                $data['fully_settled_by'] = $userInfo->id;
                $data['fully_settled_date'] = date('Y-m-d');
                $this->tada->update($id, $data);

                /* ---------------------------------------------------
                Notification Start
                ------------------------------------------------------*/
                $message = "Your Tada Request Has Been Fully Settled.";
                $link = route('employee-tada.index');
                $notification_data = array(
                    'creator_user_id' => $userInfo->id,
                    'notified_user_id' => optional($emp_detail->getUser)->id,
                    'message' => $message,
                    'link' => $link,
                    'is_read' => '0',
                );
                $this->notification->save($notification_data);

                /* ---------------------------------------------------
                Notification End
                ------------------------------------------------------*/
                toastr()->success('Request Updated Sucessfully!!');
            } elseif ($data['status'] == 'partially settled') {
                $this->tada->update($id, $data);

                TadaPartiallySettledDetail::create([
                    'tada_id' => $id,
                    'settled_by' => $userInfo->id,
                    'settled_date' => date('Y-m-d'),
                    'settled_method' => $data['settled_method'],
                    'settled_amt' => $data['settled_amt'],
                    'remarks' => $data['settled_remarks']
                ]);

                /* ---------------------------------------------------
                Notification Start
                ------------------------------------------------------*/
                $message = "Your Tada Request Has Been Partially Settled.";
                $link = route('employee-tada.index');
                $notification_data = array(
                    'creator_user_id' => $userInfo->id,
                    'notified_user_id' => optional($emp_detail->getUser)->id,
                    'message' => $message,
                    'link' => $link,
                    'is_read' => '0',
                );
                $this->notification->save($notification_data);

                /* ---------------------------------------------------
                Notification End
                ------------------------------------------------------*/
                toastr()->success('Request Updated Sucessfully!!');
            } elseif ($data['status'] == 'request closed') {
                $data['accepted_by'] = $userInfo->id;
                $data['accepted_date'] = date('Y-m-d');
                $this->tada->update($id, $data);

                /* ---------------------------------------------------
                Notification Start
                ------------------------------------------------------*/
                $message = "Your Tada Request Has Been Fully Settled.";
                $link = route('employee-tada.index');
                $notification_data = array(
                    'creator_user_id' => $userInfo->id,
                    'notified_user_id' => optional($emp_detail->getUser)->id,
                    'message' => $message,
                    'link' => $link,
                    'is_read' => '0',
                );
                $this->notification->save($notification_data);

                /* ---------------------------------------------------
                Notification End
                ------------------------------------------------------*/
                toastr()->success('Request Updated Sucessfully!!');
            } elseif ($data['status'] == 'rejected') {
                $data['rejected_by'] = $userInfo->id;
                $data['rejected_date'] = date('Y-m-d');
                $this->tada->update($id, $data);

                /* ---------------------------------------------------
                Notification Start
                ------------------------------------------------------*/
                $message = "Your Tada Request Has Been Rejected. Please Check";
                $link = route('employee-tada.index');
                $notification_data = array(
                    'creator_user_id' => $userInfo->id,
                    'notified_user_id' => optional($emp_detail->getUser)->id,
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

    public function getRepeaterForm(Request $request)
    {
        $inputData = $request->all();

        $data['tadaTypes'] = $tadaTypes = $this->tadaType->getList();

        $view = view('tada::request.partial.repeater-form', $data)->render();

        return response()->json(['result' => $view]);
    }
}
