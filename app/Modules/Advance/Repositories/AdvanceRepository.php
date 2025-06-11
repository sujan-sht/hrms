<?php

namespace App\Modules\Advance\Repositories;

use App\Modules\Admin\Entities\MailSender;
use App\Modules\Advance\Entities\Advance;
use App\Modules\Advance\Entities\AdvancePaymentLedger;
use App\Modules\Advance\Entities\AdvanceSettlement;
use App\Modules\Advance\Entities\AdvanceSettlementPayment;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Setting\Entities\Setting;
use App\Modules\User\Entities\User;

class AdvanceRepository implements AdvanceInterface
{
    protected $advancePaymentLedgerObj;

    /**
     * Constructor
     */
    public function __construct(
        AdvancePaymentLedgerInterface $advancePaymentLedgerObj
    ) {
        $this->advancePaymentLedgerObj = $advancePaymentLedgerObj;
    }


    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $authUser = auth()->user();
        if($authUser->user_type == 'division_hr') {
            $filter['organization'] = optional($authUser->userEmployer)->organization_id;
        }
        $result = Advance::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['employee']) && !empty($filter['employee'])) {
                $query->where('employee_id', $filter['employee']);
            }
            if (isset($filter['organization'])) {
                $query->where('organization_id', $filter['organization']);
            }
        })
        ->orderBy($sort['by'], $sort['sort'])
        ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }


    public function findOne($id)
    {
        return Advance::find($id);
    }


    public function create($data)
    {
        $data['status'] = 1;
        $result = Advance::create($data);
        if($result) {
            
            $advancePaymentLedgerData['advance_id'] = $result->id;
            $advancePaymentLedgerData['date'] = $result->from_date;
            $advancePaymentLedgerData['description'] = 'Advance issued to '. optional($result->employeeModel)->full_name;
            $advancePaymentLedgerData['debit'] = $result->advance_amount;
            $advancePaymentLedgerData['balance'] = $result->advance_amount;
            $this->advancePaymentLedgerObj->create($advancePaymentLedgerData);

            switch ($data['settlement_type']) {
                case '1':
                    $newData['advance_id'] = $result->id;
                    $newData['due_date'] = $data['due_date'];
                    $newData['amount'] = $data['total_amount'];
                    AdvanceSettlement::create($newData);
                break;
                case '2':
                    foreach ($data['partial_date'] as $key => $partialDate) {
                        // if($partialDate != null) {
                            $newData['advance_id'] = $result->id;
                            $newData['due_date'] = $partialDate ?? date_converter()->nep_to_eng_convert($data['partial_date_nep'][$key]);
                            $newData['nepali_due_date'] = $data['partial_date_nep'][$key] ?? date_converter()->eng_to_nep_convert($partialDate);
                            $newData['amount'] = $data['partial_amount'][$key];
                            AdvanceSettlement::create($newData);

                            $settlementPaymentData['advance_id'] = $result->id;
                            $settlementPaymentData['date'] = $partialDate ?? date_converter()->nep_to_eng_convert($data['partial_date_nep'][$key]) ;
                            $settlementPaymentData['nepali_date'] =$data['partial_date_nep'][$key] ?? date_converter()->eng_to_nep_convert($partialDate);
                            $settlementPaymentData['amount'] = $data['partial_amount'][$key];
                            $settlementPaymentData['remark'] = 'Partially Pay';
                            AdvanceSettlementPayment::create($settlementPaymentData);
                        // }
                    }
                break;
                case '3':
                    $newData['advance_id'] = $result->id;
                    $newData['amount'] = $data['monthly_amount'];
                    $newData['starting_month'] = $data['eng_starting_month'] ?? $data['nep_starting_month'];
                    $newData['number_of_month'] = $data['number_of_month'];
                    AdvanceSettlement::create($newData);

                    $year = date('Y', strtotime($result->from_date));
                    $nepYear = date('Y', strtotime(date_converter()->eng_to_nep_convert($result->from_date)));
                    // dd($year);
                    // $nepYear 
                    if($data['eng_starting_month']){

                        $engDate = $year . '-' . sprintf("%02d", $data['eng_starting_month']) . '-01';
                    }
                    else{
                        $nepDate = $nepYear . '-' . sprintf("%02d", $data['nep_starting_month']) . '-01';
                    }
                    // dd($nepDate);
                    
                    for ($i = 0; $i < $data['number_of_month']; $i++) { 
                        $settlementPaymentData['advance_id'] = $result->id;
                        $settlementPaymentData['date'] = isset($engDate) ? $engDate : date_converter()->nep_to_eng_convert($nepDate);
                        $settlementPaymentData['nepali_date'] = isset($nepDate) ? $nepDate : date_converter()->eng_to_nep_convert($engDate);
                        $settlementPaymentData['amount'] = $data['monthly_amount'];
                        $settlementPaymentData['remark'] = 'EMI Pay';
                        AdvanceSettlementPayment::create($settlementPaymentData);
                        if(isset($engDate)){
                            $engDate = date('Y-m-d', strtotime('+1 month', strtotime($engDate)));
                        }
                        else{
                            $nepDate = date('Y-m-d', strtotime('+1 month', strtotime($nepDate)));
                        }
                        
                       
                    }
                break;
                default:
                    # code...
                break;
            }
        }

        return $result;
    }

    public function update($id, $data)
    {
        $model = $this->findOne($id);
        $advanceSettlementPaymentStatus = AdvanceSettlementPayment::where('advance_id',$id)->pluck('status');

        $result = $model->update($data);

        if($result) {
            $this->deleteChildTables($id);
            switch ($data['settlement_type']) {
                case '1':
                    $newData['advance_id'] = $id;
                    $newData['due_date'] = $data['due_date'];
                    $newData['amount'] = $data['total_amount'];
                    AdvanceSettlement::create($newData);
                break;
                case '2':
                    foreach ($data['partial_date'] as $key => $partialDate) {
                        // if($partialDate != null) {
                            $newData['advance_id'] = $id;
                            $newData['due_date'] = $partialDate ?? date_converter()->nep_to_eng_convert($data['partial_date_nep'][$key]);
                            $newData['nepali_due_date'] = $data['partial_date_nep'][$key] ?? date_converter()->eng_to_nep_convert($partialDate);
                            $newData['amount'] = $data['partial_amount'][$key];
                            AdvanceSettlement::create($newData);

                            $settlementPaymentData['advance_id'] = $id;
                            $settlementPaymentData['date'] = $partialDate;
                            $settlementPaymentData['nepali_date'] = date_converter()->eng_to_nep_convert($partialDate);
                            $settlementPaymentData['amount'] = $data['partial_amount'][$key];
                            $settlementPaymentData['remark'] = 'Partially Pay';
                            $settlementPaymentData['status'] = $advanceSettlementPaymentStatus[$key];
                            AdvanceSettlementPayment::create($settlementPaymentData);
                        // }
                    }
                break;
                case '3':
                    $newData['advance_id'] = $id;
                    $newData['amount'] = $data['monthly_amount'];
                    $newData['starting_month'] = $data['starting_month'];
                    $newData['number_of_month'] = $data['number_of_month'];
                    AdvanceSettlement::create($newData);

                    $year = date('Y', strtotime($result->from_date));
                    $engDate = $year . '-' . sprintf("%02d", $data['starting_month']) . '-01';
                    for ($i = 0; $i < $data['number_of_month']; $i++) { 
                        $settlementPaymentData['advance_id'] = $id;
                        $settlementPaymentData['date'] = $engDate;
                        $settlementPaymentData['nepali_date'] = date_converter()->eng_to_nep_convert($engDate);
                        $settlementPaymentData['amount'] = $data['monthly_amount'];
                        $settlementPaymentData['remark'] = 'EMI Pay';
                        AdvanceSettlementPayment::create($settlementPaymentData);

                        $engDate = date('Y-m-d', strtotime('+1 month', strtotime($engDate)));
                    }
                break;
                default:
                    # code...
                break;
            }
        }

        return $result;
    }
    public function updateStatus($id, $data)
    {
        $model = $this->findOne($id);
        $result = $model->update($data);
        return $result;
    }
    public function updateAdvanceStatus($id, $data)
    {
        $model = $this->findOne($id);
        $result = $model->update($data);
        return $result;
    }

    public function delete($id)
    {
        $result = Advance::destroy($id);
        if($result) {
            $this->deleteChildTables($id);
        }
        
        return $result;
    }

    public function deleteChildTables($id) 
    {
        $result = AdvanceSettlement::where('advance_id', $id)->delete();
        $result = AdvanceSettlementPayment::where('advance_id', $id)->delete();
        $result = AdvancePaymentLedger::where('advance_id', $id)->delete();
        return $result;
    }
    public function sendMailNotification($model)
    {
        $authUser = auth()->user();
        // $leaveTypeModel = LeaveType::find($model['leave_type_id']);
        $employeeModel = Employee::find($model->employee_id);
        // dd($employeeModel);
        $userModel = optional($employeeModel->getUser);
        //check if there is first approval or not
        if (isset(optional($employeeModel->employeeAdvanceApprovalDetailModel)->first_approval) && !empty(optional($employeeModel->employeeAdvanceApprovalDetailModel)->first_approval)) {
            $singleApproval = false;
        } else {
            $singleApproval = true;
        }

        if ($authUser->user_type == 'super_admin') {
            $authorName = $authUser->first_name;
        } else {
            $authorName = optional($authUser->userEmployer)->full_name;
        }
        // dd($authUser->id,optional($employeeModel->getUser)->id);

        if ($model->approval_status == '1') {
            $statusTitle = 'Created';
        } else {
            $statusTitle = $model->getApprovalStatus();
        }

        $mailArray = [];
        if (optional($employeeModel->getUser)->id) {

            if ($authUser->id != optional($employeeModel->getUser)->id) {
                // create notification for employee user
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $userModel->id;
                $notificationData['message'] = "Your " . "Advance" . " has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('advance.index');
                $notificationData['type'] = 'Advance';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);
            }
        }

        // check for first approval
        // dd(optional($employeeModel->employeeAdvanceApprovalDetailModel)->first_approval,$model->approval_status);
        if (optional($employeeModel->employeeAdvanceApprovalDetailModel)->first_approval && $model->approval_status == '1') {


            // send email to supervisor
            // $notified_user_email = User::getUserEmail(optional($employeeModel->employeeAdvanceApprovalDetailModel)->first_approval);
            // if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                // create notification for first approval
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = optional($employeeModel->employeeAdvanceApprovalDetailModel)->first_approval;
                $notificationData['message'] = $employeeModel->full_name . "'s " . "Advance" . " has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('advance.index');
                $notificationData['type'] = 'Advance';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

            //     $notified_user_fullname = Employee::getName(optional($employeeModel->employeeAdvanceApprovalDetailModel)->first_approval);
            //     $details = array(
            //         'email' => $notified_user_email,
            //         'message' => $employeeModel->full_name . "'s " . "Advance" . " has been " . $statusTitle . " by " . $authorName,
            //         'notified_user_fullname' => $notified_user_fullname,
            //         'setting' => Setting::first()
            //     );
            //     $mailArray[] = $details;
            // }
        }

        // check for last approval
        if (optional($employeeModel->employeeAdvanceApprovalDetailModel)->last_approval && ($model->approval_status == '2' || ($singleApproval == true && $model->approval_status == '1'))) {
            // send email to last approval
            // $notified_user_email = User::getUserEmail(optional($employeeModel->employeeAdvanceApprovalDetailModel)->last_approval);
            // if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                // create notification for last approval
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = optional($employeeModel->employeeAdvanceApprovalDetailModel)->last_approval;
                $notificationData['message'] = $employeeModel->full_name . "'s " . "Advance" . " has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('advance.index');
                $notificationData['type'] = 'Leave';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

            //     $notified_user_fullname = Employee::getName(optional($employeeModel->employeeAdvanceApprovalDetailModel)->last_approval);
            //     $details = array(
            //         'email' => $notified_user_email,
            //         'message' => $employeeModel->full_name . "'s " . "Advance" . " has been " . $statusTitle . " by " . $authorName,
            //         'notified_user_fullname' => $notified_user_fullname,
            //         'setting' => Setting::first()
            //     );
            //     $mailArray[] = $details;
            // }
        }

        // check for all hr roles
        $hrs = User::where('user_type', 'hr')->pluck('id');
        if (isset($hrs) && !empty($hrs)) {
            foreach ($hrs as $hr) {
                // create notification for hr
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $hr;
                $notificationData['message'] = $employeeModel->full_name . "'s " . "Advance" . " has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('advance.index');
                $notificationData['type'] = 'Advance';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

                // send email to all hr
                // $notified_user_email = User::getUserEmail($hr);
                // if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                //     $notified_user_fullname = Employee::getName($hr);
                //     $details = array(
                //         'email' => $notified_user_email,
                //         'message' => $employeeModel->full_name . "'s " . "Advance" . " has been " . $statusTitle . " by " . $authorName,
                //         'notified_user_fullname' => $notified_user_fullname,
                //         'setting' => Setting::first()
                //     );
                //     $mailArray[] = $details;
                // }
            }
        }

        // Send all email at once
        if (count($mailArray) > 0) {
            foreach ($mailArray as $mailDetail) {
                $mail = new MailSender();
                $mail->sendMail('admin::mail.leave', $mailDetail);
            }
        }

        return true;
    }


}
