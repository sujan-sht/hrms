<?php

namespace App\Modules\Tada\Http\Controllers;

// Repositories

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Notification\Repositories\NotificationInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Repositories\SettingInterface;
use App\Modules\Tada\Entities\TadaRequestDetail as TadaDetail;
use App\Modules\Tada\Entities\TadaRequestPartiallySettledDetail;
use App\Modules\Tada\Http\Requests\EmpRequestRequest;
use App\Modules\Tada\Repositories\BillInterface;
use App\Modules\Tada\Repositories\TadaTypeInterface;
use App\Modules\Tada\Repositories\TadaInterface;
use App\Modules\Tada\Repositories\TadaRequestInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use PDF;

class TadaRequestController extends Controller
{
    private $employment,  $tadaType, $tada, $tadaRequest, $tadaBill, $notification, $organization, $branch, $setting;

    public function __construct(
        EmployeeInterface $employment,
        TadaTypeInterface $tadaType,
        TadaInterface $tada,
        TadaRequestInterface $tadaRequest,
        BillInterface $tadaBill,
        NotificationInterface $notification,
        OrganizationInterface $organization,
        BranchInterface $branch,
        SettingInterface $setting

    ) {
        $this->employment = $employment;
        $this->tadaType = $tadaType;
        $this->tada = $tada;
        $this->tadaRequest = $tadaRequest;
        $this->tadaBill = $tadaBill;
        $this->notification = $notification;
        $this->organization = $organization;
        $this->branch = $branch;
        $this->setting = $setting;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        $data['tadas'] = $this->tadaRequest->findAll(50, $filter);
        // $data['employees'] = $this->employment->getList();
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['employeeList'] = $this->employment->getList();
        $data['user'] = $user = Auth::user();
        $data['user_id'] = $user->id;
        $data['user_type'] = $user_type = $user->user_type;
        $data['statusList'] = $this->tadaRequest->getStatusList();

        // $data['emp_id'] = (($user_type == 'super_admin' || $user_type == 'hr')) ? '' : $user->emp_id;
        $data['org_list'] = $this->organization->getList();

        return view('tada::request.index', $data);
    }

    public function showTeamRequest(Request $request)
    {
        $filter = $request->all();
        $data['tadas'] = $this->tadaRequest->findTeamRequest(50, $filter);
        $data['employees'] = $this->employment->getList();

        $data['user'] = $user = Auth::user();
        $data['user_id'] = $user->id;
        $data['user_type'] = $user_type = $user->user_type;
        $data['statusList'] = $this->tadaRequest->getStatusList();
        $data['org_list'] = $this->organization->getList();

        return view('tada::request.team-index', $data);
    }



    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['is_edit'] = false;
        $data['employees'] = $this->employment->getList();
        $data['tadaTypes'] = $this->tadaType->getList('request');
        $data['tadaSubTypes'] = $this->tadaType->subTypeLists();
        $data['statusList'] = $this->tadaRequest->getStatusList();

        return view('tada::request.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(EmpRequestRequest $request)
    {
        $data = $request->all();
        $setting = $this->setting->getdata();

        try {

            // $date_converter = new DateConverter();
            // $from_date = explode('-', $data['nep_request_date']);

            // $request_date_eng = $date_converter->nep_to_eng($from_date[0], $from_date[1], $from_date[2]);
            // $data['eng_request_date'] = $request_date_eng['year'] . '-' . $request_date_eng['month'] . '-' . $request_date_eng['date'];

            $data['eng_request_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['nep_request_date']) : $data['eng_request_date'];
            $data['nep_request_date'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['eng_request_date']) : $data['nep_request_date'];

            $data['created_by'] = auth()->user()->id;

            if (isset($data['is_agree']) && $data['is_agree'] == 1) {
                $data['is_agree'] = 1;
            } else {
                $data['is_agree'] = 0;
            }

            $tada = $this->tadaRequest->save($data);
            $tada['enable_mail'] = $setting->enable_mail;

            // send notification
            $this->tada->sendMailNotification($tada, 'TadaRequest');
            //save tadaRequest details
            $details = [];

            if (isset($data['amount'])) {
                for ($i = 0; $i < count($data['amount']); $i++) {

                    if (isset($data['amount'][$i]) && isset($data['type_id'][$i]) && !empty($data['amount'][$i])) {

                        $details[$i] = new TadaDetail([
                            'type_id' => $data['type_id'][$i] ?? null,
                            'sub_type_id' => $data['sub_type_id'][$i] ?? null,
                            'amount' => $data['amount'][$i] ?? 0,
                            'remark' => $data['remark'][$i] ?? null,
                        ]);
                    }
                }

                if (isset($details)) {
                    $tada->tadaDetails()->saveMany($details);
                }
            }

            toastr()->success('Tada Request Details Created Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        // if (isset($tada)) {
        //     history()->onCreate($tada->id, 'Tada', $tada->title);
        // }

        return redirect()->route('tadaRequest.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $tada = $this->tadaRequest->find($id);
        return view('tada::request.show', compact('tada'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data['is_edit'] = true;
        $data['employees'] = $this->employment->getList();
        $data['tadaTypes'] = $this->tadaType->getList('request');
        $data['tada'] = $this->tadaRequest->find($id);
        $data['statusList'] = $this->tadaRequest->getStatusList();
        $data['tadaSubTypes'] = $this->tadaType->subTypeLists();
        return view('tada::request.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(EmpRequestRequest $request, $id)
    {
        $data = $request->all();
        // dd($data);
        try {
            // $date_converter = new DateConverter();
            // $from_date = explode('-', $data['nep_request_date']);

            // $request_date_eng = $date_converter->nep_to_eng($from_date[0], $from_date[1], $from_date[2]);
            // $data['eng_request_date'] = $request_date_eng['year'] . '-' . $request_date_eng['month'] . '-' . $request_date_eng['date'];

            $data['eng_request_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['nep_request_date']) : $data['eng_request_date'];
            $data['nep_request_date'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['eng_request_date']) : $data['nep_request_date'];
            $data['updated_by'] = auth()->user()->id;

            if (isset($data['is_agree']) && $data['is_agree'] == 1) {
                $data['is_agree'] = 1;
            } else {
                $data['is_agree'] = 0;
            }

            $tada =  $this->tadaRequest->find($id);

            if ($request->hasFile('excel_file') && !empty($data['excel_file'])) {
                $data['excel_file'] = $this->tadaRequest->uploadExcel($data['excel_file']);
            } else {
                $data['excel_file'] = $tada->excel_file;
            }

            //Multiple Bills
            $bill_files = $request->file('bills');
            if ($request->hasFile('bills') && !empty($data['bills'])) {
                $bill_images = $this->tadaBill->uploadBills($bill_files);
            }

            $this->tadaRequest->update($id, $data);

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
                            'sub_type_id' => $data['sub_type_id'][$i] ?? null,
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

        // history()->onUpdate($id, 'Tada', $data['title']);

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
            $tada = $this->tadaRequest->find($id);
            $this->tadaRequest->delete($id);
            toastr()->success('TADA Deleted Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        // if ($tada->title) {
        //     history()->onDelete($id, 'Tada', $tada->title);
        // }

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
        $data['tadaModel'] = $this->tadaRequest->find($id);

        return view('tada::request.partial.status-form', $data);
    }

    public function updateStatus(Request $request, $id)
    {
        $data = $request->except('_token');
        $setting = $this->setting->getdata();

        try {
            $userInfo = auth()->user();
            $tadaRequest = $this->tadaRequest->find($id);

            $emp_detail = $this->employment->find($tadaRequest->employee_id);
            //Pending
            if ($data['status'] == '2') {
                $resp = optional($emp_detail->employeeClaimRequestApprovalDetailModel);
                if (!is_null($resp) && !empty($resp->last_claim_approval_user_id)) {
                    $data['forwarded_to'] = $resp->last_claim_approval_user_id;
                    $data['forwarded_date'] = date('Y-m-d');
                    $this->tadaRequest->update($id, $data);

                    $updatedTadaDetail = $this->tadaRequest->find($id);
                    $updatedTadaDetail['enable_mail'] = $setting->enable_mail;
                    $this->tada->sendMailNotification($updatedTadaDetail, 'TadaRequest');
                    toastr()->success('Request Status Updated Sucessfully!!');
                } else {
                    toastr()->error('No Last Approval found for this employee');
                }
            } elseif ($data['status'] == '3') {
                $data['accepted_by'] = $userInfo->id;
                $data['accepted_date'] = date('Y-m-d');
                $this->tadaRequest->update($id, $data);

                $updatedTadaDetail = $this->tadaRequest->find($id);
                $updatedTadaDetail['enable_mail'] = $setting->enable_mail;
                $this->tada->sendMailNotification($updatedTadaDetail, 'TadaRequest');
                toastr()->success('Request Status Updated Sucessfully!!');
                // } elseif ($data['status'] == '5') {
                //     $data['fully_settled_by'] = $userInfo->id;
                //     $data['fully_settled_date'] = date('Y-m-d');
                //     $this->tadaRequest->update($id, $data);

                //     $updatedTadaDetail = $this->tadaRequest->find($id);
                //     $this->tada->sendMailNotification($updatedTadaDetail, 'TadaRequest');
                //     toastr()->success('Request Status Updated Sucessfully!!');
                // } elseif ($data['status'] == '6') {

                //     $this->tadaRequest->update($id, $data);
                //     TadaRequestPartiallySettledDetail::create([
                //         'tada_id' => $id,
                //         'settled_by' => $userInfo->id,
                //         'settled_date' => date('Y-m-d'),
                //         'settled_method' => $data['settled_method'],
                //         'settled_amt' => $data['settled_amt'],
                //         'remarks' => $data['settled_remarks']
                //     ]);

                //     $updatedTadaDetail = $this->tadaRequest->find($id);
                //     $this->tada->sendMailNotification($updatedTadaDetail, 'TadaRequest');
                //     toastr()->success('Request Status Updated Sucessfully!!');
            } elseif ($data['status'] == '4') {
                $data['rejected_by'] = $userInfo->id;
                $data['rejected_date'] = date('Y-m-d');
                $data['rejected_remarks'] = $data['rejected_remarks'];
                $this->tadaRequest->update($id, $data);

                $updatedTadaDetail = $this->tadaRequest->find($id);
                $updatedTadaDetail['enable_mail'] = $setting->enable_mail;
                $this->tada->sendMailNotification($updatedTadaDetail, 'TadaRequest');
                toastr()->success('Request Status Updated Sucessfully!!');
            }
        } catch (\Throwable $t) {
            toastr()->error($t->getMessage());
        }
        return redirect()->back();
    }

    public function getRepeaterForm(Request $request)
    {
        $inputData = $request->all();

        $data['tadaTypes'] = $tadaTypes = $this->tadaType->getList('request');
        $data['tadaSubTypes'] = $this->tadaType->subTypeLists();

        $view = view('tada::request.partial.repeater-form', $data)->render();

        return response()->json(['result' => $view]);
    }

    public function downloadPdfRequest($id)
    {
        $data['tada'] = $this->tadaRequest->find($id);

        $pdf = PDF::loadView('exports.request-report-pdf', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('request-report.pdf');
    }
}
