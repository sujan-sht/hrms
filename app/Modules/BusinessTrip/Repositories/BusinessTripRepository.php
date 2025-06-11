<?php

namespace App\Modules\BusinessTrip\Repositories;

use App\Modules\Admin\Entities\MailSender;
use App\Modules\BusinessTrip\Entities\BusinessTrip;
use App\Modules\BusinessTrip\Entities\SettigWiseAllowanceSetup;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeBusinessTripApprovalFlow;
use App\Modules\Employee\Entities\EmployeeClaimRequestApprovalFlow;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Setting\Entities\Designation;
use App\Modules\Setting\Entities\Level;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Setting\Entities\TravelAllowanceSetup;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ladumor\OneSignal\OneSignal;

class BusinessTripRepository implements BusinessTripInterface
{

    protected $allowancePerDay = 0;
    protected $amount = 0;
    public function getAllowanceData($limit, $filter)
    {
        $bussinessTripQuery = BusinessTrip::query();
        $bussinessTripQuery->when(true, function ($query) use ($filter) {
            if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
                $query->whereHas('employee', function ($q) use ($filter) {
                    $q->where('organization_id', $filter['organization_id']);
                });
            }

            if (isset($filter['employee_id']) && $filter['employee_id'] != '') {
                $query = $query->where('employee_id', $filter['employee_id']);
            }

            if (isset($filter['date_range'])) {
                $filterDates = explode(' - ', $filter['date_range']);
                $query->where('from_date', '>=', $filterDates[0]);
                $query->where('to_date', '<=', $filterDates[1]);
            }

            if (isset($filter['from_date_nep']) && !empty($filter['from_date_nep'])) {
                $query->where('from_date_nep', '>=', $filter['from_date_nep']);
            }

            if (isset($filter['to_date_nep']) && !empty($filter['to_date_nep'])) {
                $query->where('to_date_nep', '<=', $filter['to_date_nep']);
            }

            if (isset($filter['status']) && $filter['status'] != '') {
                $query = $query->where('status', $filter['status']);
            }

            if (auth()->user()->user_type == 'employee' || auth()->user()->user_type == 'supervisor') { //supervisor logic changes
                $empId = optional(User::where('id', auth()->user()->id)->first()->userEmployer)->id;

                $query->where('employee_id', $empId);
            } elseif (auth()->user()->user_type == 'division_hr') {
                $query->whereHas('employee', function ($q) use ($filter) {
                    $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                });
            } elseif (auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'super_admin') {
                $query->where('employee_id', '!=', null);
            }
        });
        $bussinessTripRequest = $bussinessTripQuery->get()->map(function ($item) {
            $level = optional($item->employee->level);
            $designation = optional($item->employee->designation);
            $employee = optional($item->employee);
            $this->getAllowanceTypeData($item, $level, $designation, $employee);
            return [
                'id' => $item->id,
                'empName' => @$employee->getFullName(),
                'level' => @$level->title  ?? '',
                'designation' => @$designation->title ?? '',
                'branch' => optional($item->employee->branchModel)->name ?? '',
                'type' => optional($item->type)->title,
                'purpose' => @$item->remarks ?? '',
                'num_of_days' => $item->request_days,
                'allowance_per_day' => $this->allowancePerDay,
                'amount' => $this->amount
            ];
        });
        // dd($filter);
        return $bussinessTripRequest;
    }

    public function getAllowanceTypeData($item, $level = null, $designation = null, $employee = null)
    {
        $travelAllowanceSetup = TravelAllowanceSetup::first();
        if (!$travelAllowanceSetup) {
            $this->allowancePerDay = 0;
            $this->amount = 0;
            return true;
        }
        switch ($travelAllowanceSetup->allowance_type) {
            case '1':
                $field = 'employee_id';
                $searchModel = $employee;
                break;
            case '2':
                $field = 'level_id';
                $searchModel = $level;
                break;
            case '3':
                $field = 'designation_id';
                $searchModel = $designation;
                break;
            default:
                $this->allowancePerDay = 0;
                $this->amount = 0;
                break;
        }
        $data = SettigWiseAllowanceSetup::where([
            [$field, $searchModel->id],
            ['travel_setup_type', $travelAllowanceSetup->allowance_type],
            ['type_id', $item->type_id]
        ])->first();
        if (!$data) {
            $this->allowancePerDay = 0;
            $this->amount = 0;
        }
        $this->allowancePerDay = $data->per_day_allowance ?? 0;
        $this->amount = $this->allowancePerDay * $item->request_days ?? 0;
    }
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $businessTripModel  = BusinessTrip::query();
        $businessTripModel->when(true, function ($query) use ($filter) {
            if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
                $query->whereHas('employee', function ($q) use ($filter) {
                    $q->where('organization_id', $filter['organization_id']);
                });
            }

            if (isset($filter['employee_id']) && $filter['employee_id'] != '') {
                $query = $query->where('employee_id', $filter['employee_id']);
            }

            if (isset($filter['date_range'])) {
                $filterDates = explode(' - ', $filter['date_range']);
                $query->where('from_date', '>=', $filterDates[0]);
                $query->where('to_date', '<=', $filterDates[1]);
            }

            if (isset($filter['from_date_nep']) && !empty($filter['from_date_nep'])) {
                $query->where('from_date_nep', '>=', $filter['from_date_nep']);
            }

            if (isset($filter['to_date_nep']) && !empty($filter['to_date_nep'])) {
                $query->where('to_date_nep', '<=', $filter['to_date_nep']);
            }

            if (isset($filter['status']) && $filter['status'] != '') {
                $query = $query->where('status', $filter['status']);
            }

            if (auth()->user()->user_type == 'employee' || auth()->user()->user_type == 'supervisor') { //supervisor logic changes
                $empId = optional(User::where('id', auth()->user()->id)->first()->userEmployer)->id;

                $query->where('employee_id', $empId);
            } elseif (auth()->user()->user_type == 'division_hr') {
                $query->whereHas('employee', function ($q) use ($filter) {
                    $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                });
            } elseif (auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'super_admin') {
                $query->where('employee_id', '!=', null);
            }
        });

        // if (isset($filter['is_export']) && $filter['is_export'] != '') { //for export
        //     $result = $businessTripModel->get();
        // } else {
        $result = $businessTripModel->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : config('attendance.export-length'));
        // }
        return $result;
    }

    public function find($id)
    {
        return BusinessTrip::find($id);
    }

    public function save($data)
    {
        $model = BusinessTrip::create($data);
        return $model;
    }

    public function update($id, $data)
    {
        return BusinessTrip::find($id)->update($data);
    }

    public function delete($id)
    {
        return BusinessTrip::find($id)->delete();
    }

    public function findTeamBusinessTripRequests($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $statusList = $this->getStatus();
        $user = auth()->user();
        $userId = $user->id;
        $usertype = $user->user_type;

        $firstApprovalEmps = EmployeeBusinessTripApprovalFlow::where('first_approval', $userId)->pluck('last_approval', 'employee_id')->toArray();
        $firstApproval = BusinessTrip::with('employee.employeeBusinessTripApprovalDetailModel')->when(true, function ($query) use ($firstApprovalEmps, $user) {
            // $query->whereHas('employee', function ($q) use ($user) {
            //     $q->where('organization_id', optional($user->userEmployer)->organization_id);
            // });
            $query->whereIn('employee_id', array_keys($firstApprovalEmps));
        })->get()->map(function ($approvals) use ($statusList, $usertype, $userId) {
            $approvalFlow = optional($approvals->employee)->employeeBusinessTripApprovalDetailModel;
            if ($usertype == 'supervisor') {
                if (!$approvalFlow->last_approval || $approvals->status == 1) {
                    unset($statusList[3]);
                }

                if ($approvalFlow->first_approval == $userId && $approvals->status == 2) {
                    unset($statusList[1], $statusList[3], $statusList[4]);
                }
                unset($statusList[5]);
            }
            $approvals->status_list = json_encode($statusList);
            return $approvals;
        });
        $lastApprovalEmps = EmployeeBusinessTripApprovalFlow::where('last_approval', $userId)->select('first_approval', 'last_approval', 'employee_id')->get()->toArray();
        $lastApproval = [];
        if (count($lastApprovalEmps) > 0) {
            $lastApproval = BusinessTrip::when(true, function ($query) use ($lastApprovalEmps, $user) {
                // $query->whereHas('employee', function ($q) use ($user) {
                //     $q->where('organization_id', optional($user->userEmployer)->organization_id);
                // });
                $where = 'where';
                foreach ($lastApprovalEmps as $value) {

                    $query->$where(function ($query) use ($value, $where) {
                        $query->where('employee_id', $value['employee_id']);
                        if (is_null($value['first_approval'])) {
                            $query->whereIn('status', [1, 2, 3, 4]);
                            // $query->whereIn('status', [1, 2, 3, 4, 5]);
                        } else {
                            $query->whereIn('status', [2, 3, 4]);
                            // $query->whereIn('status', [2, 3, 4, 5]);
                        }
                    });
                    $where = 'orWhere';
                }
            })->get()->map(function ($approvals) use ($statusList, $usertype, $userId) {
                $approvalFlow = optional($approvals->employee)->employeeBusinessTripApprovalDetailModel;
                if ($usertype == 'supervisor') {
                    if ($approvals->status == 1) {
                        if (isset($approvalFlow->first_approval) && $approvalFlow->first_approval == $userId) {
                            unset($statusList[3]);
                        } elseif (isset($approvalFlow->last_approval) && $approvalFlow->last_approval == $userId) {
                            unset($statusList[2]);
                        }
                    } elseif ($approvals->status == 2) {
                        if (isset($approvalFlow->first_approval) && $approvalFlow->first_approval == $userId) {
                            unset($statusList[1], $statusList[3], $statusList[4]);
                        } elseif (isset($approvalFlow->last_approval) && $approvalFlow->last_approval == $userId) {
                            unset($statusList[1]);
                        }
                    }
                    unset($statusList[5]);
                }
                $approvals->status_list = json_encode($statusList);
                return $approvals;
            });
        }
        $mergeApproval = $firstApproval->merge($lastApproval)->sortByDesc('id');
        $myCollectionObj = collect($mergeApproval);
        $result = $myCollectionObj;

        if (isset($filter['employee_id']) && $filter['employee_id'] != '') {
            $result = $result->where('employee_id', $filter['employee_id']);
        }

        if (isset($filter['date_range'])) {
            $filterDates = explode(' - ', $filter['date_range']);
            $result = $result->where('from_date', '>=', $filterDates[0]);
            $result = $result->where('to_date', '<=', $filterDates[1]);
        }

        if (isset($filter['from_date_nep']) && !empty($filter['from_date_nep'])) {
            $result = $result->where('from_date_nep', '>=', $filter['from_date_nep']);
        }

        if (isset($filter['to_date_nep']) && !empty($filter['to_date_nep'])) {
            $result = $result->where('to_date_nep', '<=', $filter['to_date_nep']);
        }

        if (isset($filter['status']) && !empty($filter['status'])) {
            $result = $result->where('status', $filter['status']);
        }

        $result = paginate($result, 20, '', ['path' => request()->url()]);
        return $result;
    }

    public function getStatus()
    {
        return BusinessTrip::STATUS;
    }

    public function sendMailNotification($model)
    {
        $authUser = auth()->user();
        $employeeModel = Employee::find($model->employee_id);
        $userModel = optional($employeeModel->getUser);

        //check if there is first approval or not
        if (isset(optional($employeeModel->employeeBusinessTripApprovalDetailModel)->first_approval) && !empty(optional($employeeModel->employeeBusinessTripApprovalDetailModel)->first_approval)) {
            $singleApproval = false;
        } else {
            $singleApproval = true;
        }
        //

        if ($authUser->user_type == 'super_admin') {
            $authorName = $authUser->first_name;
        } else {
            $authorName = optional($authUser->userEmployer)->full_name;
        }

        if ($model->status == '1') {
            $statusTitle = 'Created';
        } else {
            $statusTitle = $model->getStatus();
        }

        $mailArray = [];
        if (optional($employeeModel->getUser)->id) {
            if ($authUser->id != optional($employeeModel->getUser)->id && ($model->status == '1' || $model->status == '3' || $model->status == '4')) {
                // create notification for employee user
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = optional($employeeModel->getUser)->id;
                $notificationData['message'] = "Your travel request has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('businessTrip.index');
                $notificationData['type'] = 'Travel';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

                // send email to employee
                if (emailSetting(7) == 11 && $model->enable_mail == 11) {
                    // $fields['include_player_ids'] = [optional($userModel->device)->os_player_id];
                    // $message = $notificationData['message'];
                    // $oneSignal =  OneSignal::sendPush($fields, $message);

                    $notified_user_email = User::getUserEmail(optional($employeeModel->getUser)->id);
                    if (isset($notified_user_email) && !empty($notified_user_email)) {
                        $notified_user_fullname = Employee::getName(optional($employeeModel->getUser)->id);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => "Your travel request has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'subject' => 'Travel Request Notification'
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        // check for first approval
        if (optional($employeeModel->employeeBusinessTripApprovalDetailModel)->first_approval && $model->status == '1') {
            // create notification for first approval
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeBusinessTripApprovalDetailModel)->first_approval;
            $notificationData['message'] = $employeeModel->full_name . "'s travel request has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = route('businessTrip.teamRequests');
            $notificationData['type'] = 'Travel';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            // send email to supervisor
            if (emailSetting(7) == 11 && $model->enable_mail == 11) {

                $notified_user_email = User::getUserEmail(optional($employeeModel->employeeBusinessTripApprovalDetailModel)->first_approval);
                if (isset($notified_user_email) && !empty($notified_user_email)) {
                    $notified_user_fullname = Employee::getName(optional($employeeModel->employeeBusinessTripApprovalDetailModel)->first_approval);
                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employeeModel->full_name . "'s travel request has been " . $statusTitle . " by " . $authorName,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'subject' => 'Travel Request Notification'
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // check for last approval
        if (optional($employeeModel->employeeBusinessTripApprovalDetailModel)->last_approval && ($model->status == '2' || ($singleApproval == true && $model->status == '1'))) {
            // create notification for last approval
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeBusinessTripApprovalDetailModel)->last_approval;
            $notificationData['message'] = $employeeModel->full_name . "'s travel request has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = route('businessTrip.teamRequests');
            $notificationData['type'] = 'Travel';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            // send email to last approval
            if (emailSetting(7) == 11 && $model->enable_mail == 11) {

                $notified_user_email = User::getUserEmail(optional($employeeModel->employeeBusinessTripApprovalDetailModel)->last_approval);
                if (isset($notified_user_email) && !empty($notified_user_email)) {
                    $notified_user_fullname = Employee::getName(optional($employeeModel->employeeBusinessTripApprovalDetailModel)->last_approval);
                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employeeModel->full_name . "'s travel request has been " . $statusTitle . " by " . $authorName,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'subject' => 'Travel Request Notification'
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // check for all hr roles
        $hrs = User::where('user_type', 'hr')->pluck('id');
        if (isset($hrs) && !empty($hrs)) {
            foreach ($hrs as $hr) {
                // create notification for hr
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $hr;
                $notificationData['message'] = $employeeModel->full_name . "'s travel request has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('businessTrip.index');
                $notificationData['type'] = 'Travel';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

                // send email to all hr
                if (emailSetting(7) == 11 && $model->enable_mail == 11) {

                    $notified_user_email = User::getUserEmail($hr);
                    if (isset($notified_user_email) && !empty($notified_user_email)) {
                        $notified_user_fullname = Employee::getName($hr);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $employeeModel->full_name . "'s travel request has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'subject' => 'Travel Request Notification'
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        // check for all division hr roles
        $divisionHrs = User::when(true, function ($query) use ($employeeModel) {
            $query->whereHas('userEmployer', function ($q) use ($employeeModel) {
                $q->where('organization_id', $employeeModel->organization_id)->where('status', 1);
            });
        })->where('user_type', 'division_hr')->pluck('id');

        if (isset($divisionHrs) && !empty($divisionHrs)) {
            foreach ($divisionHrs as $divisionHr) {
                // create notification for division hr
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $divisionHr;
                $notificationData['message'] = $employeeModel->full_name . "'s travel request has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('businessTrip.index');
                $notificationData['type'] = 'Travel';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

                // send email to all division hr
                if (emailSetting(7) == 11 && $model->enable_mail == 11) {

                    $notified_user_email = User::getUserEmail($divisionHr);
                    if (isset($notified_user_email) && !empty($notified_user_email)) {
                        $notified_user_fullname = Employee::getName($divisionHr);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $employeeModel->full_name . "'s travel request has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'subject' => 'Travel Request Notification'
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        //  Send all email at once
        if (count($mailArray) > 0) {
            foreach ($mailArray as $mailDetail) {
                $mail = new MailSender();
                $mail->sendMail('admin::mail.business_trip', $mailDetail);
            }
        }
        return true;
    }


    public function getEmployeeBusinessTrips($employeeId = null, $limit = null)
    {
        $activeUserModel = Auth::user();
        $query = BusinessTrip::query();
        $query->select('id', 'employee_id', 'title', 'from_date', 'status', "from_date as date", 'created_at')->where('status', 1)->addSelect(DB::raw("'businessTrip' as type"));



        if ($activeUserModel->user_type == 'employee') {
            $query->where('employee_id', $activeUserModel->emp_id);
        }

        if ($activeUserModel->user_type == 'supervisor') {
            $authEmpId = array(intval($activeUserModel->emp_id));
            $subordinateEmpIds = Employee::getSubordinates($activeUserModel->id);
            $empIds = array_merge($authEmpId, $subordinateEmpIds);
            $query->whereIn('employee_id', $empIds);
        }

        if (auth()->user()->user_type == 'division_hr') {
            $organizationId = optional($activeUserModel->userEmployer)->organization_id;

            if ($organizationId) {
                $employeeIds = Employee::where('organization_id', $organizationId)->pluck('id');
                $query->whereIn('employee_id', $employeeIds);
            } else {
                return collect(); // Returning an empty collection
            }
        }



        $result = $query->orderBy('created_at', 'DESC')->take($limit ? $limit : env('DEF_PAGE_LIMIT', 9999))->get();
        return $result;
    }

    public function empAllowanceSetup($data, $filter, $limit = null)
    {
        $select = ['id', 'employee_code', 'first_name', 'middle_name', 'last_name'];
        $query = Employee::query();
        $query->select($select);
        $query->where('status', 1);

        if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
            $query = $query->where('organization_id', $filter['organization_id']);
        }

        if (isset($filter['employee_id']) && $filter['employee_id'] != '') {

            $query = $query->where('id', $filter['employee_id']);
        }
        $employees = $query->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $employees->setCollection($employees->getCollection()->transform(function ($emp) use ($data) {
            if (!empty($data['typeList'])) {
                foreach ($data['typeList'] as $type_id => $title) {
                    $typeData = (new TravelRequestTypeRepository)->find($type_id);
                    $types[$type_id] = optional($emp->employeeAllowanceSetup($type_id))->per_day_allowance ? optional($emp->employeeAllowanceSetup($type_id))->per_day_allowance : $typeData->amount;
                }
                $emp->types = $types;
                return $emp;
            }
        }));
    }
    public function getSetWiseAllowaceSetup($data, $setUpData, $filter, $limit)
    {
        $travelSetupType = $setUpData->allowance_type;
        switch ($travelSetupType) {
            case "1": //Employee
                $select = ['id', 'employee_code', 'first_name', 'middle_name', 'last_name'];
                $query = Employee::query();
                $query->select($select);
                $query->where('status', 1);
                if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
                    $query = $query->where('organization_id', $filter['organization_id']);
                }
                if (isset($filter['Employee']) && $filter['Employee'] != '') {

                    $query = $query->where('id', $filter['Employee']);
                }
                break;
            case "2": //Level
                $select = ['id', 'title', 'short_code'];
                $query = Level::query();
                $query->select($select);
                if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
                    $query->whereHas('organizations', function ($q) use ($filter) {
                        $q->where('organization_id', $filter['organization_id']);
                    });
                }
                if (isset($filter['Level']) && $filter['Level'] != '') {

                    $query = $query->where('id', $filter['Level']);
                }
                break;
            case "3": //Designation
                $select = ['id', 'title', 'short_code'];
                $query = Designation::query();
                $query->select($select);
                if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
                    $query->whereHas('organizations', function ($q) use ($filter) {
                        $q->where('organization_id', $filter['organization_id']);
                    });
                }
                if (isset($filter['Designation']) && $filter['Designation'] != '') {

                    $query = $query->where('id', $filter['Designation']);
                }
                break;
            default:
                break;
        }
        $travelWiseVariable = $this->arrangeData($setUpData->allowance_type);
        $retriveData = $query->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $retriveData->setCollection($retriveData->getCollection()->transform(function ($item) use ($data, $travelSetupType, $travelWiseVariable) {
            if (!empty($data['typeList'])) {
                foreach ($data['typeList'] as $type_id => $title) {
                    $typeData = (new TravelRequestTypeRepository)->find($type_id);
                    $saveValue = SettigWiseAllowanceSetup::where([
                        ['travel_setup_type', $travelSetupType],
                        [$travelWiseVariable, $item->id],
                        ['type_id', $type_id]
                    ])->first();
                    $types[$type_id] = optional($saveValue)->per_day_allowance ? optional($saveValue)->per_day_allowance : $typeData->amount;
                }
                $item->types = $types;
                switch ($travelSetupType) {
                    case "1": //Employee
                        return [
                            'id' => $item->id,
                            'columns' => [
                                'first_name' => $this->generateEmployeeDetails($item),
                            ],
                            'types' => $item->types
                        ];
                        break;
                    case "2": //Level
                    case "3": //Designation
                        return [
                            'id' => $item->id,
                            'columns' => [
                                'title' => $item->title,
                                'short_code' => $item->short_code,
                            ],
                            'types' => $item->types
                        ];
                        break;
                    default:
                        break;
                }
                return $item;
            }
        }));
    }

    public function arrangeData($allowance_type)
    {
        switch ($allowance_type) {
            case "1": //Employee
                return 'employee_id';
                break;
            case "2": //Level
                return 'level_id';
                break;
            case "3": //Designation
                return 'designation_id';
                break;
            default:
                break;
        }
    }

    public function generateEmployeeDetails($emp)
    {
        $html = '<div class="media">
                    <div class="mr-3">
                        <a href="#">
                            <img src="' . @$emp->getImage() . '"
                                class="rounded-circle" width="40" height="40" alt="">
                        </a>
                    </div>
                    <div class="media-body">
                        <div class="media-title font-weight-semibold">
                             ' . @$emp->getFullName() . '</div>
                        <span
                            class="text-muted">' . @$emp->official_email . '</span>
                    </div>
                </div>';
        return $html;
    }
}
