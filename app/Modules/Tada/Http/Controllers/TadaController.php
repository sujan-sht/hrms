<?php

namespace App\Modules\Tada\Http\Controllers;

// Repositories

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Notification\Repositories\NotificationInterface;
use App\Modules\Tada\Entities\TadaDetail;
use App\Modules\Tada\Entities\TadaPartiallySettledDetail;
use App\Modules\Tada\Repositories\BillInterface;
use App\Modules\Tada\Repositories\TadaInterface;
use App\Modules\Tada\Repositories\TadaTypeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Repositories\SettingInterface;
use App\Modules\Tada\Http\Requests\ClaimRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Traits\LogTrait;
use Illuminate\Support\Facades\Cache;

class TadaController extends Controller
{

    use LogTrait;
    private $employment, $tada, $tadaType, $tadaBill, $notification, $organization, $branch, $setting;

    public function __construct(
        EmployeeInterface $employment,
        TadaInterface $tada,
        TadaTypeInterface $tadaType,
        BillInterface $tadaBill,
        OrganizationInterface $organization,
        NotificationInterface $notification,
        BranchInterface $branch,
        SettingInterface $setting
    ) {
        $this->employment = $employment;
        $this->tada = $tada;
        $this->tadaType = $tadaType;
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
        $data['tadas'] = $this->tada->findAll(50, $filter);
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['employeeList'] = $this->employment->getList();
        $data['user'] = $user = Auth::user();
        $data['user_type'] = $user_type = $user->user_type;
        $data['statusList'] = $this->tada->getStatusList();
        if (in_array(auth()->user()->user_type, ['super_admin', 'hr', 'division_hr'])) {
            unset($data['statusList'][2]);
        }

        return view('tada::index', $data);
    }

    public function showTeamClaim(Request $request)
    {
        $filter = $request->all();
        $data['tadas'] = $this->tada->findTeamClaim(50, $filter);
        $data['employees'] = $this->employment->getList();

        $data['user'] = $user = Auth::user();
        $data['user_type'] = $user_type = $user->user_type;
        $data['statusList'] = $this->tada->getStatusList();
        $data['org_list'] = $this->organization->getList();
        return view('tada::team-index', $data);
    }

    // public function filter(Request $request)
    // {
    //     $filter = $request->all();
    //     if(!empty($filter)){
    //         dd($filter);
    //         $tadas = $this->tada->findAll(50, $filter);
    //         $employees = $this->employment->getList();
    //         $data['user'] = $user = Auth::user();
    //         $data['user_type'] = $user_type = $user->user_type;
    //         $data['org_list'] = $this->organization->getList();
    //         $data['organization_types'] = $this->organization->getOrganizationTypes();
    //         return view('tada::index', compact('tadas','employees'),$data);
    //     }
    // }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['is_edit'] = false;
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['employees'] = $this->employment->getList();
        $data['tadaTypes'] = $this->tadaType->getList('claim');
        $data['statusList'] = $this->tada->getStatusList();

        return view('tada::create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ClaimRequest $request)
    {
        $data = $request->all();
        $setting = $this->setting->getdata();
        try {

            // $date_converter = new DateConverter();
            // $from_date = explode('-', $data['nep_from_date']);
            // $to_date = explode('-', $data['nep_to_date']);

            // $start_date_eng = $date_converter->nep_to_eng($from_date[0], $from_date[1], $from_date[2]);
            // $data['eng_from_date'] = $start_date_eng['year'] . '-' . $start_date_eng['month'] . '-' . $start_date_eng['date'];

            // $end_date_eng = $date_converter->nep_to_eng($to_date[0], $to_date[1], $to_date[2]);
            // $data['eng_to_date'] = $end_date_eng['year'] . '-' . $end_date_eng['month'] . '-' . $end_date_eng['date'];

            $data['eng_from_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['nep_from_date']) : $data['eng_from_date'];
            $data['nep_from_date'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['eng_from_date']) : $data['nep_from_date'];

            $data['eng_to_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['nep_to_date']) : $data['eng_to_date'];
            $data['nep_to_date'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['eng_to_date']) : $data['nep_to_date'];

            $data['created_by'] = auth()->user()->id;

            if ($request->hasFile('excel_file')) {
                $data['excel_file'] = $this->tada->uploadExcel($data['excel_file']);
            }

            //Multiple Bills
            $bill_files = $request->file('bills');
            if ($request->hasFile('bills')) {
                $bill_images = $this->tadaBill->uploadBills($bill_files);
            }

            if (isset($data['is_agree']) && $data['is_agree'] == 1) {
                $data['is_agree'] = 1;
            } else {
                $data['is_agree'] = 0;
            }

            $tada = $this->tada->save($data);
            $tada['enable_mail'] = $setting->enable_mail;

            // send notification
            $this->tada->sendMailNotification($tada, 'Tada');
            if (isset($bill_images)) {
                $this->tadaBill->saveBills($bill_images, $tada->id);
            }

            //save tada details
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
            $logData = [
                'title' => 'New claim created',
                'action_id' => $tada->id,
                'action_model' => get_class($tada),
                'route' => route('tada.show', $tada->id)
            ];
            $this->setActivityLog($logData);
            toastr()->success('Tada Details Created Succesfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        // if (isset($tada)) {
        //     history()->onCreate($tada->id, 'Tada', $tada->title);
        // }

        return redirect()->route('tada.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $tada = $this->tada->find($id);
        $logData = [
            'title' => 'Claim viewed',
            'action_id' => $tada->id,
            'action_model' => get_class($tada),
            'route' => route('tada.show', $tada->id)
        ];
        $this->setActivityLog($logData);
        return view('tada::show', compact('tada'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data['is_edit'] = true;
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['employees'] = $this->employment->getList();
        $data['tadaTypes'] = $this->tadaType->getList('claim');
        $data['tada'] = $this->tada->find($id);
        $data['statusList'] = $this->tada->getStatusList();

        return view('tada::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ClaimRequest $request, $id)
    {
        $data = $request->except(['_method', '_token', 'type_id', 'amount', 'remark', 'bills']);
        $detailData = $request->only(['type_id', 'remark', 'amount']);
        try {

            // $date_converter = new DateConverter();
            // $from_date = explode('-', $data['nep_from_date']);
            // $to_date = explode('-', $data['nep_to_date']);

            // $start_date_eng = $date_converter->nep_to_eng($from_date[0], $from_date[1], $from_date[2]);
            // $data['eng_from_date'] = $start_date_eng['year'] . '-' . $start_date_eng['month'] . '-' . $start_date_eng['date'];

            // $end_date_eng = $date_converter->nep_to_eng($to_date[0], $to_date[1], $to_date[2]);
            // $data['eng_to_date'] = $end_date_eng['year'] . '-' . $end_date_eng['month'] . '-' . $end_date_eng['date'];

            $data['eng_from_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['nep_from_date']) : $data['eng_from_date'];
            $data['nep_from_date'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['eng_from_date']) : $data['nep_from_date'];

            $data['eng_to_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['nep_to_date']) : $data['eng_to_date'];
            $data['nep_to_date'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['eng_to_date']) : $data['nep_to_date'];

            $data['updated_by'] = auth()->user()->id;

            if (isset($data['is_agree']) && $data['is_agree'] == 1) {
                $data['is_agree'] = 1;
            } else {
                $data['is_agree'] = 0;
            }

            $tada =  $this->tada->find($id);

            if ($request->hasFile('excel_file') && !empty($data['excel_file'])) {
                $data['excel_file'] = $this->tada->uploadExcel($data['excel_file']);
            } else {
                $data['excel_file'] = $tada->excel_file;
            }

            //Multiple Bills
            $bill_files = $request->file('bills');
            // if ($request->hasFile('bills') && !empty($data['bills'])) {
            if ($request->hasFile('bills')) {
                $bill_images = $this->tadaBill->uploadBills($bill_files);
            }

            $this->tada->update($id, $data);

            if (isset($bill_images)) {
                $this->tadaBill->saveBills($bill_images, $tada->id);
            }

            //save tada details

            $tada->tadaDetails()->delete();
            $details = [];

            if (isset($detailData['amount'])) {
                for ($i = 0; $i < count($detailData['amount']); $i++) {

                    if (isset($detailData['amount'][$i]) && isset($detailData['type_id'][$i]) && !empty($detailData['amount'][$i])) {

                        $details[$i] = new TadaDetail([
                            'type_id' => $detailData['type_id'][$i] ?? null,
                            'amount' => $detailData['amount'][$i] ?? 0,
                            'remark' => $detailData['remark'][$i] ?? null,
                        ]);
                    }
                }

                if (isset($details)) {
                    $tada->tadaDetails()->saveMany($details);
                }
            }
            $logData = [
                'title' => 'Claim updated',
                'action_id' => $tada->id,
                'action_model' => get_class($tada),
                'route' => route('tada.show', $tada->id)
            ];
            $this->setActivityLog($logData);
            toastr()->success('Tada Details Updated Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        // history()->onUpdate($id, 'Tada', $data['title']);

        return redirect()->route('tada.index');
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
            $logData = [
                'title' => 'Claim deleted',
                'action_id' => null,
                'action_model' => null,
                'route' => route('tada.index')
            ];
            $this->setActivityLog($logData);
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
        $data['tadaModel'] = $this->tada->find($id);
        return view('tada::partial.status-form', $data);
    }

    public function updateStatus(Request $request, $id)
    {
        $data = $request->except('_token');
        $setting = $this->setting->getdata();
        try {
            // $this->tada->update($id, $data);
            // $tada = $this->tada->find($id);

            // // send notification
            // $this->tada->sendMailNotification($tada, 'Claim');
            // toastr()->success('Claim Updated Sucessfully!!');

            $userInfo = auth()->user();
            $tada = $this->tada->find($id);

            $emp_detail = $this->employment->find($tada->employee_id);
            //Pending
            if ($data['status'] == '2') {
                $resp = optional($emp_detail->employeeClaimRequestApprovalDetailModel);
                if (!is_null($resp) && !empty($resp->last_claim_approval_user_id)) {
                    $data['forwarded_to'] = $resp->last_claim_approval_user_id;
                    $data['forwarded_date'] = date('Y-m-d');
                    $this->tada->update($id, $data);

                    $updatedTadaDetail = $this->tada->find($id);
                    $updatedTadaDetail['enable_mail'] = $setting->enable_mail;
                    $this->tada->sendMailNotification($updatedTadaDetail, 'Tada');
                    toastr()->success('Claim Status Updated Sucessfully!!');
                } else {
                    toastr()->error('No Last Approval found for this employee');
                }
            } elseif ($data['status'] == '3') {
                $data['request_closed_by'] = $userInfo->id;
                $data['request_closed_date'] = date('Y-m-d');
                $this->tada->update($id, $data);

                $updatedTadaDetail = $this->tada->find($id);
                $updatedTadaDetail['enable_mail'] = $setting->enable_mail;
                $this->tada->sendMailNotification($updatedTadaDetail, 'Tada');
                toastr()->success('Claim Status Updated Sucessfully!!');
            } elseif ($data['status'] == '5') {
                $data['fully_settled_by'] = $userInfo->id;
                $data['fully_settled_date'] = date('Y-m-d');
                $this->tada->update($id, $data);

                $updatedTadaDetail = $this->tada->find($id);
                $updatedTadaDetail['enable_mail'] = $setting->enable_mail;
                $this->tada->sendMailNotification($updatedTadaDetail, 'Tada');
                toastr()->success('Claim Status Updated Sucessfully!!');
            } elseif ($data['status'] == '6') {

                $this->tada->update($id, $data);
                TadaPartiallySettledDetail::create([
                    'tada_id' => $id,
                    'settled_by' => $userInfo->id,
                    'settled_date' => date('Y-m-d'),
                    'settled_method' => $data['settled_method'],
                    'settled_amt' => $data['settled_amt'],
                    'remarks' => $data['settled_remarks']
                ]);

                $updatedTadaDetail = $this->tada->find($id);
                $updatedTadaDetail['enable_mail'] = $setting->enable_mail;
                $this->tada->sendMailNotification($updatedTadaDetail, 'Tada');
                toastr()->success('Claim Status Updated Sucessfully!!');
            } elseif ($data['status'] == '4') {
                $data['rejected_by'] = $userInfo->id;
                $data['rejected_date'] = date('Y-m-d');
                $data['rejected_remarks'] = $data['rejected_remarks'];
                $this->tada->update($id, $data);

                $updatedTadaDetail = $this->tada->find($id);
                $updatedTadaDetail['enable_mail'] = $setting->enable_mail;
                $this->tada->sendMailNotification($updatedTadaDetail, 'Tada');
                toastr()->success('Claim Status Updated Sucessfully!!');
            }
            $logData = [
                'title' => 'Claim status updated',
                'action_id' => $tada->id,
                'action_model' => get_class($tada),
                'route' => route('tada.show', $tada->id)
            ];
            // $cacheKey = 'pending_approvals_' . auth()->user()->emp_id;
            // Cache::forget($cacheKey);
            $this->setActivityLog($logData);
        } catch (\Throwable $t) {
            toastr()->error($t->getMessage());
        }
        return redirect()->back();
    }

    public function getRepeaterForm(Request $request)
    {
        $inputData = $request->all();
        $data['tadaTypes'] = $tadaTypes = $this->tadaType->getList('claim');
        $view = view('tada::partial.repeater-form', $data)->render();
        return response()->json(['result' => $view]);
    }

    public function downloadPdfClaim($id)
    {

        $data['tada'] = $this->tada->find($id);
        $logData = [
            'title' => 'Claim download',
            'action_id' => $data['tada']->id,
            'action_model' => get_class($data['tada']),
            'route' => route('tada.show', $data['tada']->id)
        ];
        $this->setActivityLog($logData);
        $pdf = PDF::loadView('exports.claim-report-pdf', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('claim-report.pdf');
    }
}
