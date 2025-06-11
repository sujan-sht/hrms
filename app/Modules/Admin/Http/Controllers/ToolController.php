<?php

namespace App\Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use App\Mail\BirthdayWish;
use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use Ladumor\OneSignal\OneSignal;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\User\Entities\Role;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Modules\Leave\Entities\Leave;
use App\Modules\User\Entities\UserRole;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Employee\Entities\Employee;
use App\Modules\GeoFence\Entities\GeoFence;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\User\Repositories\RoleRepository;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\User\Repositories\UserRoleRepository;
use App\Modules\Attendance\Entities\AttendanceRequest;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\Shift\Repositories\EmployeeShiftRepository;
use App\Modules\Attendance\Repositories\AttendanceRepository;
use App\Modules\Employee\Entities\EmployeeOffboardApprovalFlow;
use App\Modules\Employee\Entities\EmployeePayrollRelatedDetail;
use App\Modules\Employee\Entities\EmployeeAppraisalApprovalFlow;
use App\Modules\Employee\Entities\EmployeeAttendanceApprovalFlow;
use App\Modules\Employee\Entities\EmployeeClaimRequestApprovalFlow;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function changeNepaliDateFormat()
    {
        // $this->oneSignal();
        try {
            $leaves = Leave::all();
            foreach ($leaves as $key => $leave) {
                $date = $leave->date;
                $leave->nepali_date = date_converter()->eng_to_nep_convert($date);
                $leave->save();
            }

            $AttendanceRequest = AttendanceRequest::all();
            foreach ($AttendanceRequest as $key => $leave) {
                $date = $leave->date;
                $leave->nepali_date = date_converter()->eng_to_nep_convert($date);
                $leave->save();
            }
            echo "Success";
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }

    public function updateAtdCheck()
    {
        try {

            Attendance::chunk('200', function ($attendances) {
                foreach ($attendances as $key => $atd) {

                    if (is_null($atd->checkin_original) && $atd->checkin) {
                        $atd->checkin_original = $atd->checkin;
                    }

                    if (is_null($atd->checkout_original) && $atd->checkout) {
                        $atd->checkout_original = $atd->checkout;
                    }
                    $date = $atd->date;
                    $atd->nepali_date = date_converter()->eng_to_nep_convert($date);

                    $atd->save();
                }
            });
            echo "Success";
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }

    public function searchActiveModules(Request $request)
    {
        if ($request->ajax()) {
            $modules = DB::table('modules')
                ->where('status', 1)
                ->where('name', 'like', '%' . $request->all()['query'] . '%')
                ->whereNotIn('name', [
                    'AddArchiveTypeColumnTable',
                    'Migration',
                    'NewShift',
                    'Admin',
                    'User',
                    'Api',
                    'Notification',
                    'Dropdown'
                ])
                ->get()
                ->toArray();
            $result = array_map(function ($item) {
                return (array)$item;
            }, $modules);
            $modules = [
                'ApprovalFlow' => route('approvalFlow.index'),
                'Attendance' => route('attendanceRequest.index'),
                'Branch' => route('branch.index'),
                'BulkUpload' => route('bulkupload.familyDetail'),
                'Employee' => route('employee.index'),
                'EmployeeRequest' => route('employeerequest.index'),
                'Event' => route('event.index'),
                'FiscalYearSetup' => route('fiscalYearSetup.index'),
                'Holiday' => route('holiday.index'),
                'Leave' => route('leave.index'),
                'LeaveYearSetup' => route('leaveyearsetup.index'),
                'Loan' => route('loan.index'),
                'Notice' => route('notice.index'),
                'Organization' => route('organization.index'),
                'OrganizationalStructure' => route('organizationalStructure.index'),
                'Payroll' => route('payroll.index'),
                'Setting' => route('setting.index'),
                'Shift' => route('shift.index'),
            ];
            $new = [];
            foreach ($result as $module) {
                if (array_key_exists($module['name'], $modules)) {
                    $module['link'] = $modules[$module['name']];
                    $new[] = $module;
                }
            }
            return response()->json($new);
        }
    }

    public function checkForLinkModule($moduleName)
    {
        switch ($moduleName) {
            case 'Attendance':
                return route('attendanceRequest.index');
                break;
            default:
                return route(strtolower($moduleName) . '.index');
                break;
        }
    }

    public function sendWishMail($id)
    {
        $employee = Employee::select('first_name', 'middle_name', 'last_name', 'id', 'official_email')->findOrFail($id);

        if (!$employee->official_email) {
            toastr('Official Email not found', 'error');
            return redirect()->back();
        }

        Mail::to($employee->official_email)->send(new BirthdayWish($employee->full_name));
        toastr('Wish Mail Sent Successfully', 'success');
        return redirect()->back();
    }
    public function sendWishSMS($id, $type)
    {
        $employee = Employee::findOrFail($id);
        if (!is_null($employee->user)) {
            switch ($type) {
                case 'new employee':
                    $message = 'Welcome to the team ' . $employee->full_name;
                    break;
                case 'birthday':
                    $message = 'Happy Birthday ' . $employee->full_name;
                    break;
                case 'anniversary':
                    $message = 'Happy Anniversary ' . $employee->full_name;
                    break;
                default:
                    $message = null;
                    break;
            }

            if (!is_null($message)) {
                $employee->user->sendSMS($message);
                toastr('Wish SMS Sent Successfully', 'success');
            } else {
                toastr('Message cannot be send', 'error');
            }
        } else {
            toastr('User Not Found', 'error');
        }
        return redirect()->back();
    }

    public function storeBulkNewUser(Request $request)
    {
        ini_set('max_execution_time', '2400');
        $filter = ($request->all());

        // if ($filter['organization_id']) {
        //     echo "Organization Not Found";
        //     return false;
        // }

        try {
            $text = '';
            Employee::doesntHave('getUser')->when(true, function ($query) use ($filter) {
                if (isset($filter['organization_id'])) {
                    $query->where('organization_id', $filter['organization_id']);
                }
            })
                ->chunk('200', function ($employees) use ($text) {
                    foreach ($employees as $key => $emp) {
                        $role = Role::where('user_type', 'employee')->first();

                        $password = 'aone@2024';
                        $user_access = array(
                            'ip_address' => \Request::ip(),
                            'username' => $emp['first_name'] . '.' . $emp['last_name'],
                            'password' => bcrypt($password),
                            'email' => $emp['email'],
                            'user_type' => $role->user_type,
                            'active' => '1',
                            'first_name' => $emp['first_name'],
                            'middle_name' => $emp['middle_name'],
                            'last_name' => $emp['last_name'],
                            'phone' => $emp['phone'],
                            'emp_id' => $emp['id'],
                            'remember_token' => '$b$d' . $password . '$e$p',
                            'parent_id' => 1
                        );

                        $user = (new UserRepository())->save($user_access);

                        //Insert into User Role
                        $role_data = array(
                            'user_id' => $user->id,
                            'role_id' => $role['id']
                        );
                        (new UserRoleRepository())->save($role_data);

                        $update_emp = array(
                            'is_user_access' => '1',
                            'pass_token' => $password,
                        );

                        $emp->update($update_emp);
                        // $text .= 'SN:' . $i;
                        $text .= ' Name:' . $user->username . '   ';
                        // $text .= ' Password:' . $user->password . '\n';
                        // $i++;
                        echo $text . '</br>';
                        // Storage::disk('public')->put('/downloads/user_credentials.txt', $text);
                    }

                    // $contents = Storage::disk('public')->get('/downloads/user_credentials.txt');

                    // $tempFile = "user_credentials.txt";
                    // file_put_contents($tempFile, $contents);

                    // header("Content-type: text/html");
                    // header("Content-Length: " . filesize($tempFile));
                    // readfile($tempFile);
                });

            return 'Success';
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }

    public function setEmployeeJobTypePermanent(Request $request)
    {
        $filter = $request->all();
        try {
            Employee::when(true, function ($query) use ($filter) {
                $query->where('status', 1);
                if (isset($filter['organization_id'])) {
                    $query->where('organization_id', $filter['organization_id']);
                }
            })
                ->chunk('200', function ($employees) {
                    foreach ($employees as $key => $employee) {
                        $data['join_date'] = $employee->join_date;
                        $data['job_type'] = 10;
                        EmployeePayrollRelatedDetail::saveData($employee->id, $data);
                    }
                });
            return 'Success';
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }

    public function oneSignal()
    {


        // $apps = OneSignal::getApps();
        // dd($apps);
        // dd(OneSignal::getDevices());
        // $fields['include_player_ids'] = ['bcbd02bb-067b-4034-9f42-6f282018105f'];
        // $fields['big_picture'] = 'http://127.0.0.1:8000/admin/default.png';
        // $fields['contents'] = array(
        //     "en" => "Test Message",
        // );
        // $fields['data'] = [
        //     'url' => route('leave.index'),

        // ];
        // abort('404');

        $fields = [
            'include_player_ids' => ['bcbd02bb-067b-4034-9f42-6f282018105f'],
            'contents' => [
                "en" => "Test Message",
            ],
            'url' => '/leave/history',
            'small_icon' => 'https://bidheegroup.bidheehrms.com/uploads/setting/2023-01-31-06-14-33-1599638187738.jpeg',
            'isIos' => true,
            'isAndroid' => true,
        ];

        $notificationMsg = 'Hello!! A tiny web push notification.!';
        OneSignal::sendPush($fields, $notificationMsg);
        $oneSignals = OneSignal::getNotifications();

        dd($oneSignals);
    }

    public static function deleteOtherOrgnEmployeeOnLeaveType(Request $request)
    {
        self::setAttendanceRequest();
        set_time_limit(-1);
        $filter = $request->all();
        $leaveTypeModels = LeaveType::where('leave_year_id', getCurrentLeaveYearId())->with([
            'employeeLeave.employeeModel' => function ($query) {},
        ])->get();

        foreach ($leaveTypeModels as  $leaveTypeModel) {
            foreach ($leaveTypeModel['employeeLeave'] as  $employeeLeave) {
                $orgnId = optional($employeeLeave['employeeModel'])->organization_id;

                if ($orgnId != $leaveTypeModel->organization_id) {
                    // $test[$leaveTypeModel->name]['NotSameEmp'][] = $employeeLeave->employee_id;
                    EmployeeLeave::where([
                        'employee_id' => $employeeLeave->employee_id,
                        'leave_type_id' => $leaveTypeModel->id,
                    ])->delete();

                    EmployeeLeaveOpening::where([
                        'employee_id' => $employeeLeave->employee_id,
                        'leave_type_id' => $leaveTypeModel->id,
                    ])->delete();
                }
                //  else {
                //     $test[$leaveTypeModel->name]['sameEmp'][] = $employeeLeave->employee_id;
                // }
            }
        }
        echo "Success";
    }

    public static function setAttendanceRequest()
    {

        $atdRequests = AttendanceRequest::where('status', 3)->get();
        $checkinType = ['Missed Check In', 'Late Arrival Request'];
        $checkoutType = ['Missed Check Out', 'Early Departure Request'];
        $extraType = ['Force Attendance Request', 'Out Door Duty Request', 'Work From Home Request'];
        $dateConverter = new DateConverter();
        foreach ($atdRequests as $key => $atdRequest) {

            $attendanceExist = (new AttendanceRepository())->employeeAttendanceExists($atdRequest->employee_id, $atdRequest->date);

            //checkin and Approved
            if (in_array($atdRequest->getType(), $checkinType)) {
                if ($attendanceExist) {
                    //Update Checkin Time
                    $attendanceExist->fill(['checkin' => $atdRequest->time]);

                    if ($attendanceExist->checkout) {
                        $attendanceExist->fill(['total_working_hr' => DateTimeHelper::getTimeDiff(date('H:i', strtotime($atdRequest->time)), date('H:i', strtotime($attendanceExist->checkout)))]);
                    }
                    $attendanceExist->update();
                    $saveAtd = $attendanceExist;
                } else {
                    //Create Attendance with Checkin Type
                    $saveAtd = (new AttendanceRepository())->save([
                        'org_id' => optional($atdRequest->employee)->organization_id,
                        'emp_id' => $atdRequest->employee_id,
                        'date' => $atdRequest->date,
                        'nepali_date' => $dateConverter->eng_to_nep_convert($atdRequest->date),
                        'checkin' => $atdRequest->time,
                        'checkin_from' => 'request',
                        'total_working_hr' => null
                    ]);
                }
            }

            //checkout and Approved
            if (in_array($atdRequest->getType(), $checkoutType)) {
                if ($attendanceExist) {
                    //Update Checkout Time
                    $attendanceExist->fill(['checkout' => $atdRequest->time, 'total_working_hr' => DateTimeHelper::getTimeDiff(date('H:i', strtotime($attendanceExist->checkin)), date('H:i', strtotime($atdRequest->time)))]);

                    $attendanceExist->update();
                    $saveAtd = $attendanceExist;
                } else {
                    //Create Attendance with Checkout Type
                    $saveAtd = (new AttendanceRepository())->save([
                        'org_id' => optional($atdRequest->employee)->organization_id,
                        'emp_id' => $atdRequest->employee_id,
                        'date' => $atdRequest->date,
                        'nepali_date' => $dateConverter->eng_to_nep_convert($atdRequest->date),
                        'checkout' => $atdRequest->time,
                        'checkout_from' => 'request',
                        'total_working_hr' => null
                    ]);
                }
            }

            //extraType and Approved
            if (in_array($atdRequest->getType(), $extraType)) {
                $shiftInfo = (new EmployeeShiftRepository())->findOne(['employee_id' => $atdRequest->employee_id, 'days' => date('D', strtotime($atdRequest->date))]);
                if ($shiftInfo) {
                    $checkinTime = optional($shiftInfo->getShift)->start_time;
                    $firstHalfEnd = optional($shiftInfo->getShift)->getCheckpoint();
                    $secondHalfStart = (date('H:i', strtotime(intval('+' . 1) . 'minutes', strtotime(optional($shiftInfo->getShift)->getCheckpoint()))));
                    $checkoutTime = optional($shiftInfo->getShift)->end_time;
                } else {
                    $checkinTime = '09:00';
                    $firstHalfEnd = '14:00';
                    $secondHalfStart = '14:01';
                    $checkoutTime = '18:00';
                }

                if (isset($atdRequest['kind'])) {
                    if ($atdRequest['kind'] == 1) {
                        $checkin = $checkinTime;
                        $checkout = $firstHalfEnd;
                    } elseif ($atdRequest['kind'] == 2) {
                        $checkin = $secondHalfStart;
                        $checkout = $checkoutTime;
                    } elseif ($atdRequest['kind'] == 3) {
                        $checkin = $checkinTime;
                        $checkout = $checkoutTime;
                    }
                }
                if ($attendanceExist) {
                    //Update Checkin Time
                    $attendanceExist->fill([
                        'checkin' => $checkin,
                        'checkout' => $checkout,
                        'checkin_from' => 'request',
                        'checkout_from' => 'request',
                        'total_working_hr' => DateTimeHelper::getTimeDiff(date('H:i', strtotime($checkin)), date('H:i', strtotime($checkout)))
                    ]);

                    $attendanceExist->update();
                    // $saveAtd = $attendanceExist;
                } else {
                    $saveAtd = (new AttendanceRepository())->save([
                        'org_id' => optional($atdRequest->employee)->organization_id,
                        'emp_id' => $atdRequest->employee_id,
                        'date' => $atdRequest->date,
                        'nepali_date' => date_converter()->eng_to_nep_convert($atdRequest->date),
                        'checkin' => $checkin,
                        'checkout' => $checkout,
                        'checkin_from' => 'request',
                        'checkout_from' => 'request',
                        'total_working_hr' => DateTimeHelper::getTimeDiff(date('H:i', strtotime($checkin)), date('H:i', strtotime($checkout))),
                    ]);
                }
            }
        }
    }

    public function changeRoleToSupervisor()
    {
        $employeeIds = [
            1,
            17,
            31,
            34,
            35,
            38,
            40,
            41,
            46,
            52,
            56,
            57,
            80,
            82,
            1029,
            87,
            98,
            104,
            106,
            107,
            125,
            130,
            132,
            135,
            160,
            161,
            172,
            182,
            186,
            187,
            189,
            190,
            197,
            205,
            210,
            234,
            247,
            256,
            318,
            328,
            330,
            340,
            367,
            376,
            379,
            385,
            389,
            402,
            407,
            443,
            444,
            446,
            448,
            456,
            462,
            468,
            470,
            479,
            490,
            492,
            506,
            516,
            517,
            536,
            544,
            564,
            570,
            574,
            581,
            584,
            1015,
            598,
            612,
            614,
            622,
            623,
            1016,
            696,
            701,
            709,
            772,
            789,
            832,
            835,
            849,
            861,
            865,
            913,
            927,
            1019,
            975,
            995,
            1046
        ];

        foreach ($employeeIds as $employeeId) {
            $userModel = User::where('emp_id', $employeeId)->where('user_type', 'employee')->first();
            if (isset($userModel) && !empty($userModel)) {

                $role = Role::where('user_type', 'supervisor')->first();
                $update_role = array(
                    'role_id' => $role->id,
                    'created_at' => date('Y-m-d H:i:s')
                );
                $userRole = UserRole::where('user_id', '=', $userModel->id);
                $userRole->update($update_role);

                $userModel->user_type = 'supervisor';
                $userModel->save();
            }
        }
        toastr()->success('Employee Role changed into Supervisor');
        return redirect()->back();
    }

    public function approvalFlowView()
    {
        $data['title'] = 'Approval Flow Detail';
        return view('bulkupload::bulkUpload.approvalFlowDetail.index', $data);
    }

    public function assignApprovalFlowLeaveClaim(Request $request)
    {

        $files = $request->upload_approval_flow_leave_claim;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());


        $approvalLists = $spreadsheet->getActiveSheet()->toArray();
        array_shift($approvalLists);

        // $approvalLists = [
        //     ['employee_code'=>'003','first_approval_emp_code'=>null,'last_approval_emp_code'=>'001'],
        //     ['employee_code'=>'002','first_approval_emp_code'=>'001','last_approval_emp_code'=>'003']
        // ];

        foreach ($approvalLists as $index => $approvalList) {
            $rowNumber = $index + 2; // Excel row number (considering header)

            $employeeCode = $approvalList[0] ?? null;
            $firstApprovalCode = $approvalList[1] ?? null;
            $lastApprovalCode = $approvalList[2] ?? null;
            // dd($employeeCode, $firstApprovalCode, $lastApprovalCode);

            if (!$employeeCode) {
                return redirect()->back()->withErrors(["Row $rowNumber: Missing employee code. Upload stopped. Data uploaded till row " . ($rowNumber - 1)]);
            }

            $employee = Employee::where('employee_code', $employeeCode)->first();
            if (!$employee) {
                return redirect()->back()->withErrors(["Row $rowNumber: Employee code '$employeeCode' not found. Upload stopped. Data uploaded till row " . ($rowNumber - 1)]);
            }

            $claimReqData['employee_id'] = $leaveAttData['employee_id'] = $attendanceFlowData['employee_id'] = $employee->id;

            if ($firstApprovalCode) {
                $firstUserId = $this->getUserId($firstApprovalCode);
                if ($firstUserId == null) {
                    return redirect()->back()->withErrors(["Row $rowNumber: First approval code '$firstApprovalCode' not found. Upload stopped. Data uploaded till row " . ($rowNumber - 1)]);
                }
                $claimReqData['first_claim_approval_user_id'] = $leaveAttData['first_approval_user_id'] = $attendanceFlowData['first_approval_user_id'] = $firstUserId;
            } else {
                return redirect()->back()->withErrors(["Row $rowNumber: Missing First Approval code. Upload stopped. Data uploaded till row " . ($rowNumber - 1)]);
            }

            if ($lastApprovalCode) {
                $lastUserId = $this->getUserId($lastApprovalCode);
                if ($lastUserId == null) {
                    return redirect()->back()->withErrors(["Row $rowNumber: Last approval code '$lastApprovalCode' not found. Upload stopped. Data uploaded till row " . ($rowNumber - 1)]);
                }
                $claimReqData['last_claim_approval_user_id'] = $leaveAttData['last_approval_user_id'] = $attendanceFlowData['last_approval_user_id'] = $lastUserId;
            } else {
                return redirect()->back()->withErrors(["Row $rowNumber: Missing Last Approval code. Upload stopped. Data uploaded till row " . ($rowNumber - 1)]);
            }

            $leaveAttModel = EmployeeApprovalFlow::where('employee_id', $employee->id)->first();
            if ($leaveAttModel) {
                $leaveAttData['updated_by'] = Auth::user()->id;
            } else {
                $leaveAttData['created_by'] = Auth::user()->id;
            }
            EmployeeApprovalFlow::updateOrCreate(
                ['employee_id' => $employee->id],
                $leaveAttData
            );
            $claimRequestModel = EmployeeClaimRequestApprovalFlow::where('employee_id', $employee->id)->first();
            if ($claimRequestModel) {
                $claimReqData['updated_by'] = Auth::user()->id;
            } else {
                $claimReqData['created_by'] = Auth::user()->id;
            }
            EmployeeClaimRequestApprovalFlow::updateOrCreate(
                ['employee_id' => $employee->id],
                $claimReqData
            );
            $attendanceFlowModel = EmployeeAttendanceApprovalFlow::where('employee_id', $employee->id)->first();
            if ($attendanceFlowModel) {
                $EmployeeAttendanceApprovalFlow['updated_by'] = Auth::user()->id;
            } else {
                $EmployeeAttendanceApprovalFlow['created_by'] = Auth::user()->id;
            }
            EmployeeAttendanceApprovalFlow::updateOrCreate(
                ['employee_id' => $employee->id],
                $EmployeeAttendanceApprovalFlow
            );
        }

        toastr()->success('Bulk uploaded successfully. Supervisors assigned!');
        return redirect()->back();




        // foreach ($approvalLists as $approvalList) {
        //     $employee = Employee::where('employee_code', $approvalList[0])->first();
        //     if ($employee) {
        //         $claimReqData['employee_id'] = $leaveAttData['employee_id'] = $employee->id;
        //         if ($approvalList[1]) {
        //             $claimReqData['first_claim_approval_user_id'] = $leaveAttData['first_approval_user_id'] = $this->getUserId($approvalList[1]);
        //         } else {
        //             $claimReqData['first_claim_approval_user_id'] = $leaveAttData['first_approval_user_id'] = null;
        //         }
        //         $claimReqData['last_claim_approval_user_id'] = $leaveAttData['last_approval_user_id'] = $this->getUserId($approvalList[2]);


        //         $leaveAttModel = EmployeeApprovalFlow::where('employee_id', $employee->id)->first();
        //         $claimRequestModel = EmployeeClaimRequestApprovalFlow::where('employee_id', $employee->id)->first();

        //         if (!$leaveAttModel) {
        //             EmployeeApprovalFlow::create($leaveAttData);
        //         }

        //         if (!$claimRequestModel) {
        //             EmployeeClaimRequestApprovalFlow::create($claimReqData);
        //         }
        //     }
        // }
        // toastr()->success('Supervisors are assigned to different employees for Leave, Attendance, Claim and Request !!!');
        // return redirect()->back();
    }

    public function assignApprovalFlowOffboardAppraisal(Request $request)
    {
        $files = $request->upload_approval_appraisal_offboard;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());


        $approvalLists = $spreadsheet->getActiveSheet()->toArray();
        array_shift($approvalLists);

        foreach ($approvalLists as $approvalList) {
            $employee = Employee::where('employee_code', $approvalList[0])->first();
            if ($employee) {
                $flowData['employee_id'] = $employee->id;
                $flowData['first_approval'] = $this->getUserId($approvalList[1]);
                if ($approvalList[2]) {
                    $flowData['last_approval'] = $this->getUserId($approvalList[2]);
                } else {
                    $flowData['last_approval'] = null;
                }

                $appraisalModel = EmployeeAppraisalApprovalFlow::where('employee_id', $employee->id)->first();
                $offboardModel = EmployeeOffboardApprovalFlow::where('employee_id', $employee->id)->first();

                if (!$appraisalModel) {
                    EmployeeAppraisalApprovalFlow::create($flowData);
                }

                if (!$offboardModel) {
                    EmployeeOffboardApprovalFlow::create($flowData);
                }
            }
        }
        toastr()->success('Supervisors are assigned to different employees for Appraisal and Offboard !!!');
        return redirect()->back();
    }


    public function getUserId($employee_code)
    {
        $employee = Employee::where('employee_code', $employee_code)->first();
        if (!$employee || !$employee->getUser) {
            return null;
        }

        return $employee->getUser->id;
    }

    public function updateAttendanceDataFromRequests($type)
    {

        //for checkin type
        if ($type == 'checkin_type') {
            $checkinType = [1, 4]; //Missed Check In, Late Arrival Request
            $checkinRequests = AttendanceRequest::where('status', 3)->whereIn('type', $checkinType)->get();

            foreach ($checkinRequests as $checkinRequest) {
                $dataParams = [
                    'emp_id' => $checkinRequest->employee_id,
                    'date' => $checkinRequest->date,
                ];
                $attendanceModel = (new AttendanceRepository())->findOne($dataParams);
                if ($attendanceModel) {
                    $attendanceModel->checkin = $checkinRequest->time;
                    $attendanceModel->checkin_from = 'request';

                    if ($attendanceModel->checkout) {
                        $attendanceModel->total_working_hr = DateTimeHelper::getTimeDiff($attendanceModel->checkin, $attendanceModel->checkout);
                    }
                    $attendanceModel->save();
                }
            }
        } elseif ($type == 'checkout_type') {
            $checkoutType = [2, 3]; //Missed Check Out, Early Departure Request
            $checkoutRequests = AttendanceRequest::where('status', 3)->whereIn('type', $checkoutType)->get();

            foreach ($checkoutRequests as $checkoutRequest) {
                $dataParams = [
                    'emp_id' => $checkoutRequest->employee_id,
                    'date' => $checkoutRequest->date,
                ];
                $attendanceModel = (new AttendanceRepository())->findOne($dataParams);
                if ($attendanceModel) {
                    $attendanceModel->checkout = $checkoutRequest->time;
                    $attendanceModel->checkout_from = 'request';

                    if ($attendanceModel->checkin) {
                        $attendanceModel->total_working_hr = DateTimeHelper::getTimeDiff($attendanceModel->checkin, $attendanceModel->checkout);
                    }
                    $attendanceModel->save();
                }
            }
        } elseif ($type == 'extra_type') {
            $extraType = [6, 7]; //Out Door Duty Request, Work From Home Request
            $extraRequests = AttendanceRequest::where('status', 3)->whereIn('type', $extraType)->get();

            foreach ($extraRequests as $extraRequest) {
                $shiftInfo = (new EmployeeShiftRepository)->findOne(['employee_id' => $extraRequest->employee_id, 'days' => date('D', strtotime($extraRequest->date))]);
                if ($shiftInfo) {
                    $checkinTime = optional($shiftInfo->getShift)->start_time;
                    $firstHalfEnd = optional($shiftInfo->getShift)->getCheckpoint();
                    $secondHalfStart = (date('H:i', strtotime(intval('+' . 1) . 'minutes', strtotime(optional($shiftInfo->getShift)->getCheckpoint()))));
                    $checkoutTime = optional($shiftInfo->getShift)->end_time;
                } else {
                    $checkinTime = '09:00';
                    $firstHalfEnd = '14:00';
                    $secondHalfStart = '14:01';
                    $checkoutTime = '18:00';
                }

                if (isset($extraRequest['kind'])) {
                    if ($extraRequest['kind'] == 1) {
                        $checkin = $checkinTime;
                        $checkout = $firstHalfEnd;
                    } elseif ($extraRequest['kind'] == 2) {
                        $checkin = $secondHalfStart;
                        $checkout = $checkoutTime;
                    } elseif ($extraRequest['kind'] == 3) {
                        $checkin = $checkinTime;
                        $checkout = $checkoutTime;
                    }
                }

                $dataParams = [
                    'emp_id' => $extraRequest->employee_id,
                    'date' => $extraRequest->date,
                ];
                $attendanceModel = (new AttendanceRepository())->findOne($dataParams);
                if ($attendanceModel) {
                    $attendanceModel->checkin = $checkin;
                    $attendanceModel->checkin_from = 'request';

                    $attendanceModel->checkout = $checkout;
                    $attendanceModel->checkout_from = 'request';

                    if ($attendanceModel->checkin && $attendanceModel->checkout) {
                        $attendanceModel->total_working_hr = DateTimeHelper::getTimeDiff($attendanceModel->checkin, $attendanceModel->checkout);
                    }
                    $attendanceModel->save();
                }
            }
        }

        return "Success";
    }

    public function convertSubstitutedateInEnglish()
    {
        $leaves = Leave::select('id', 'substitute_date')->get();
        $count = 0;
        foreach ($leaves as $leave) {
            if (isset($leave['substitute_date'])) {
                $subsDate = Carbon::parse($leave['substitute_date']);
                if ($subsDate->year == 2080) {
                    $convertedSubsDate = date_converter()->nep_to_eng_convert($leave['substitute_date']);
                    $leave->fill(['substitute_date' => $convertedSubsDate]);

                    $leave->update();
                    $count = $count + 1;
                }
            }
        }
        return ('Substitute date converted successfully. ' . $count . ' rows affected.');
    }

    public function changeUserPassword()
    {
        $users = User::where('active', 1)->whereNotIn('user_type', ['super_admin', 'hr'])->get();
        if (!empty($users)) {
            foreach ($users as $user) {
                $data['password'] = bcrypt('Password@123');
                (new UserRepository)->update($user['id'], $data);
            }
        }
        return ('User password changed successfully !!!');
    }
}
