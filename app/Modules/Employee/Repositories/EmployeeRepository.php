<?php

namespace App\Modules\Employee\Repositories;

use Carbon\Carbon;
use App\Traits\Paginate;
use Illuminate\Support\Str;
use App\Traits\PaginationTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use App\Modules\Leave\Entities\Leave;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\Leave\Entities\LeaveType;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Employee\Entities\Country;
use App\Modules\Shift\Entities\ShiftGroup;
use App\Modules\Employee\Entities\District;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\Province;
use App\Modules\Employee\Scopes\ActiveScope;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Shift\Entities\EmployeeShift;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Employee\Entities\ArchivedDetail;
use App\Modules\Leave\Entities\LeaveEncashmentLog;
use App\Modules\Employee\Entities\EmployeeTimeline;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Leave\Repositories\LeaveTypeRepository;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\Employee\Entities\EmployeeCarrierMobility;
use App\Modules\Employee\Entities\VisaAndImmigrationDetail;
use App\Modules\Employee\Entities\EmployeePayrollRelatedDetail;
use App\Modules\Employee\Entities\EmployeeAppraisalApprovalFlow;
use App\Modules\Employee\Entities\EmployeeCareerMobilityTransfer;
use App\Modules\Employee\Entities\EmployeeThresholdRelatedDetail;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityDemotion;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityPromotion;
use App\Modules\Employee\Entities\EmployeeClaimRequestApprovalFlow;
use App\Modules\Employee\Entities\EmployeeCareerMobilityAppointment;
use App\Modules\Employee\Entities\NewEmployeeCareerMobilityTimeline;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityAppointment;
use App\Modules\LeaveYearSetup\Repositories\LeaveYearSetupRepository;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityConfirmation;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityTemporaryTransfer;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityProbationaryPeriod;

class EmployeeRepository implements EmployeeInterface
{

    use PaginationTrait;

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'first_name', 'sort' => 'asc'], $status = [0, 1])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr' || $authUser->user_type == 'supervisor') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }

        if ($authUser->user_type == 'employee') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }
        $result = Employee::filterBy($filter)
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ?? 25);

        // dd($result);
        return $result;
    }

    public function findAllForRoster($limit = null, $filter = [], $sort = ['by' => 'first_name', 'sort' => 'asc'], $status = [0, 1])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr' || $authUser->user_type == 'supervisor') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }

        if ($authUser->user_type == 'employee') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }


        $result = Employee::where('status', 1)->when(array_keys($filter, true), function ($query) use ($filter) {

            if (isset($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }

            if (isset($filter['role_name'])) {
                $query->whereHas('user', function ($q) use ($filter) {
                    $q->where('user_type', $filter['role_name']);
                });
            }

            if (isset($filter['level_id'])) {
                $query->whereIn('level_id', $filter['level_id']);
            }

            if (isset($filter['name'])) {
                $name = trim($filter['name']);
                $query->where(function ($q) use ($name) {
                    $check = Str::contains($name, ' ');
                    if ($check) {
                        $fullname = explode(' ', $name);
                        if (count($fullname) == 2) {
                            $q->whereRaw("concat(first_name, ' ', last_name) like '%" . $name . "' ");
                        } else {
                            $q->whereRaw("concat(first_name, ' ', middle_name, ' ', last_name) like '%" . $name . "' ");
                        }
                    } else {
                        $q->where('first_name', 'like', '%' . $name . '%')->orWhere('middle_name', 'like', '%' . $name . '%')->orWhere('last_name', 'like', '%' . $name . '%');
                    }
                });
            }

            if (isset($filter['email'])) {
                $query->where('personal_email', $filter['email'])->orWhere('official_email', $filter['email']);
            }

            if (isset($filter['phone'])) {
                $query->where('mobile', $filter['phone']);
            }

            if (isset($filter['employee_code'])) {
                $query->where('employee_code', $filter['employee_code']);
            }


            if (isset($filter['function_id'])) {
                if (is_array($filter['function_id'])) {
                    $query->whereIn('function_id', $filter['function_id']);
                } else {
                    $query->where('function_id', $filter['function_id']);
                }
            }

            if (isset($filter['department_id'])) {
                if (is_array($filter['department_id'])) {
                    $query->whereIn('department_id', $filter['department_id']);
                } else {
                    $query->where('department_id', $filter['department_id']);
                }
            }


            if (isset($filter['branch_id'])) {
                $query->where('branch_id', $filter['branch_id']);
            }

            if (isset($filter['employee_id'])) {
                $query->where('id', $filter['employee_id']);
            }

            if (isset($filter['emp_ids'])) {
                $query->whereIn('id', $filter['emp_ids']);
            }

            if (isset($filter['job_status'])) {
                $query->where('job_status', $filter['job_status']);
            }

            if (isset($filter['gpa_enable']) || isset($filter['gmi_enable'])) {
                $query->whereHas('insuranceDetail', function ($q1) use ($filter) {
                    if (isset($filter['gpa_enable'])) {
                        $q1->where('gpa_enable', $filter['gpa_enable']);
                    }

                    if (isset($filter['gmi_enable'])) {
                        $q1->where('gmi_enable', $filter['gmi_enable']);
                    }
                });
            }

            if (isset($filter['permanentprovince'])) {
                $query->where('permanentprovince', $filter['permanentprovince']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])->get();
        return $result;
    }

    public function fetchTableViewEmployees($filter = [])
    {
        $authUser = auth()->user();
        if (in_array($authUser->user_type, ['division_hr', 'supervisor', 'employee'])) {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }
        $query = Employee::with([
            'user',
            'branchModel',
            'department',
            'level',
            'getBloodGroup',
            'designation',
            'getUser',
            'organizationModel',
            'getGender',
            'getMaritalStatus',
            'permanentProvinceModel',
            'permanentDistrictModel',
            'payrollRelatedDetailModel',
            'insuranceDetail',
            'manager'
        ])
            ->filterBy($filter)->where('status', 1)
            ->latest();
        return DataTables::of($query)->make(true);
    }

    public function findAllArchived($limit = null, $filter = [], $sort = ['by' => 'first_name', 'sort' => 'asc'], $status = [0, 1])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr' || $authUser->user_type == 'supervisor') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }

        if ($authUser->user_type == 'employee') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
            // $filter['department_id'] = collect(optional($authUser->userEmployer)->department_id)->toArray();
            // dd($filter);
        }


        $result = Employee::where('status', 0)->when(array_keys($filter, true), function ($query) use ($filter) {

            if (isset($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }

            if (isset($filter['role_name'])) {
                $query->whereHas('user', function ($q) use ($filter) {
                    $q->where('user_type', $filter['role_name']);
                });
            }

            if (isset($filter['level_id'])) {
                $query->where('level_id', $filter['level_id']);
            }

            if (isset($filter['name'])) {
                $name = trim($filter['name']);
                $query->where(function ($q) use ($name) {
                    $check = Str::contains($name, ' ');
                    if ($check) {
                        $fullname = explode(' ', $name);
                        if (count($fullname) == 2) {
                            $q->whereRaw("concat(first_name, ' ', last_name) like '%" . $name . "' ");
                        } else {
                            $q->whereRaw("concat(first_name, ' ', middle_name, ' ', last_name) like '%" . $name . "' ");
                        }
                    } else {
                        $q->where('first_name', 'like', '%' . $name . '%')->orWhere('middle_name', 'like', '%' . $name . '%')->orWhere('last_name', 'like', '%' . $name . '%');
                    }

                    // $q->orWhere('personal_email', 'like', '%' . $name . '%')->orWhere('official_email', 'like', '%' . $name . '%');
                    // $q->orWhere('mobile', 'like', '%' . $name . '%');
                });

                // $check = Str::contains($name, ' ');
                // if ($check) {
                //     $fullname = explode(' ', $name);
                //     if (count($fullname) == 2) {
                //         $query->whereRaw("concat(first_name, ' ', last_name) like '%" . $name . "' ");
                //     } else {
                //         $query->whereRaw("concat(first_name, ' ', middle_name, ' ', last_name) like '%" . $name . "' ");
                //     }
                // } else {
                //     $query->where('first_name', 'like', '%' . $name . '%')->orWhere('middle_name', 'like', '%' . $name . '%')->orWhere('last_name', 'like', '%' . $name . '%');
                // }

            }

            if (isset($filter['email'])) {
                $query->where('personal_email', $filter['email'])->orWhere('official_email', $filter['email']);
            }

            if (isset($filter['phone'])) {
                $query->where('mobile', $filter['phone']);
            }

            if (isset($filter['employee_code'])) {
                $query->where('employee_code', $filter['employee_code']);
            }

            if (isset($filter['designation_id'])) {
                $query->where('designation_id', $filter['designation_id']);
            }

            if (isset($filter['department_id'])) {
                $query->where('department_id', $filter['department_id']);
            }

            if (isset($filter['branch_id'])) {
                $query->where('branch_id', $filter['branch_id']);
            }

            if (isset($filter['employee_id'])) {
                $query->where('id', $filter['employee_id']);
            }

            if (isset($filter['emp_ids'])) {
                $query->whereIn('id', $filter['emp_ids']);
            }

            if (isset($filter['job_status'])) {
                $query->where('job_status', $filter['job_status']);
            }

            if (isset($filter['gpa_enable']) || isset($filter['gmi_enable'])) {
                $query->whereHas('insuranceDetail', function ($q1) use ($filter) {
                    if (isset($filter['gpa_enable'])) {
                        $q1->where('gpa_enable', $filter['gpa_enable']);
                    }

                    if (isset($filter['gmi_enable'])) {
                        $q1->where('gmi_enable', $filter['gmi_enable']);
                    }
                });
            }

            if (isset($filter['from_date']) && isset($filter['to_date'])) {
                $query->whereBetween('nep_archived_date', [$filter['from_date'], $filter['to_date']]);
            }

            if (isset($filter['permanentprovince'])) {
                $query->where('permanentprovince', $filter['permanentprovince']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function findArchive($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }
        // withoutGlobalScope(new ActiveScope)->
        $result = Employee::where('status', '=', 0)->when(array_keys($filter, true), function ($query) use ($filter) {

            if (isset($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }

            if (isset($filter['department_id'])) {
                $query->where('department_id', '=', $filter['department_id']);
            }

            if (isset($filter['designation_id'])) {
                $query->where('designation_id', '=', $filter['designation_id']);
            }

            if (isset($filter['employee_id'])) {
                $query->where('id', '=', $filter['employee_id']);
            }
        })->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }
    public function getList($withOrganization = null)
    {
        $data = [];
        $filter = [];
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }


        $employeeModels = Employee::where('status', 1)->when(true, function ($query) use ($filter) {
            if (auth()->user()->user_type == 'supervisor') {
                $filter['ids'] = Employee::getSubordinates(auth()->user()->id);
                array_push($filter['ids'], (int)auth()->user()->emp_id);
                $query->whereIn('id', $filter['ids']);
            }
            if (isset($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }
        })
            ->latest()
            ->get();

        if ($employeeModels->count() > 0) {
            foreach ($employeeModels as $employeeModel) {
                if ($withOrganization) {
                    $data[$employeeModel->id] = $employeeModel->full_name . ' :: ' . optional($employeeModel->organizationModel)->name;
                } else {
                    $data[$employeeModel->id] = $employeeModel->full_name . ' :: ' . $employeeModel->employee_code;
                }
            }
        }
        return $data;
    }

    public function filterList($withOrganization = null)
    {
        $data = [];
        $filter = $withOrganization;
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }


        $employeeModels = Employee::where('status', 1)->when(true, function ($query) use ($filter) {
            if (auth()->user()->user_type == 'supervisor') {
                $filter['ids'] = Employee::getSubordinates(auth()->user()->id);
                array_push($filter['ids'], (int)auth()->user()->emp_id);
                $query->whereIn('id', $filter['ids']);
            }
            if (isset($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }
        })
            ->get();

        if ($employeeModels->count() > 0) {
            foreach ($employeeModels as $employeeModel) {
                $data[$employeeModel->id] = $employeeModel->full_name;
            }
        }
        return $data;
    }

    public function getListExceptSelectedEmployee($reqData, $withOrganization = null)
    {
        $data = [];
        $filter = [];
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }


        $employeeModels = Employee::where('status', 1)->when(true, function ($query) use ($filter, $reqData) {
            if ($reqData['root_emp_id']) {
                $query->where('id', '!=', $reqData['root_emp_id']);
            }
            if (auth()->user()->user_type == 'supervisor') {
                $filter['ids'] = Employee::getSubordinates(auth()->user()->id);
                array_push($filter['ids'], (int)auth()->user()->emp_id);
                $query->whereIn('id', $filter['ids']);
            }
            if (isset($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }
        })
            ->get();

        if ($employeeModels->count() > 0) {
            foreach ($employeeModels as $employeeModel) {
                if ($withOrganization) {
                    $data[$employeeModel->id] = $employeeModel->full_name . ' :: ' . optional($employeeModel->organizationModel)->name;
                } else {
                    $data[$employeeModel->id] = $employeeModel->full_name;
                }
            }
        }
        return $data;
    }

    public function employeeListWithFilter($filter)
    {
        $employeeModels = Employee::where('status', 1)->when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }
        })
            ->get();

        if ($employeeModels->count() > 0) {
            foreach ($employeeModels as $employeeModel) {
                $data[$employeeModel->id] = $employeeModel->full_name;
            }
        }

        return $data;
    }

    public function getListWithEmpCode()
    {
        $data = [];
        $filter = [];

        if (auth()->user()->user_type == 'supervisor') {
            $filter['ids'] = Employee::getSubordinates(auth()->user()->id);
        }
        if (auth()->user()->user_type == 'division_hr') {
            $filter['organization'] = optional(auth()->user()->userEmployer)->organization_id;
        }

        $employeeModels = Employee::where('status', 1)->when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['ids'])) {
                $query->whereIn('id', $filter['ids']);
            }
            if (isset($filter['organization'])) {
                $query->where('organization_id', $filter['organization']);
            }
        })
            ->get();

        if ($employeeModels->count() > 0) {
            foreach ($employeeModels as $employeeModel) {
                $data[$employeeModel->id] = $employeeModel->full_name . ' - ' . $employeeModel->employee_code;
            }
        }

        return $data;
    }

    public function getOtherEmployeeList()
    {
        $emp_id = Auth::user()->userEmployer;
        $emp_id = optional(Auth::user()->userEmployer)->id;

        $employer_user = Employee::select('id', 'first_name', 'middle_name', 'last_name')->where('status', 1)->where('id', '!=', $emp_id)->get();
        $employee_data = array();
        foreach ($employer_user as $key => $user) {
            $full_name = !empty($user->middle_name) ? $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name : $user->first_name . ' ' . $user->last_name;
            $employee_data += array(
                $user->id => $full_name,
            );
        }
        return $employee_data;
    }

    public function getArchiveList()
    {
        $employer_user = Employee::select('id', 'first_name', 'middle_name', 'last_name')->where('status', 0)->get();
        $employee_data = [];
        foreach ($employer_user as $key => $user) {
            $full_name = !empty($user->middle_name) ? $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name : $user->first_name . ' ' . $user->last_name;
            $employee_data += array(
                $user->id => $full_name,
            );
        }
        return $employee_data;
    }

    public function uploadProfilePic($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = date('Y-m-d-h-i-s') . '-' . preg_replace('[ ]', '-', $imageName);

        $file->move(public_path() . Employee::PROFILE_PATH, $fileName);

        return $fileName;
    }

    public function uploadCitizen($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = date('Y-m-d-h-i-s') . '-' . preg_replace('[ ]', '-', $imageName);

        $file->move(public_path() . Employee::CITIZEN_PATH, $fileName);

        return $fileName;
    }

    public function uploadNationalId($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = date('Y-m-d-h-i-s') . '-' . preg_replace('[ ]', '-', $imageName);

        $file->move(public_path() . Employee::NATIONALID_PATH, $fileName);

        return $fileName;
    }

    public function uploadMaritalImg($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = date('Y-m-d-h-i-s') . '-' . preg_replace('[ ]', '-', $imageName);

        $file->move(public_path() . Employee::MARITAL_PATH, $fileName);

        return $fileName;
    }


    public function uploadPassportImg($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = date('Y-m-d-h-i-s') . '-' . preg_replace('[ ]', '-', $imageName);

        $file->move(public_path() . Employee::PASSPORT_PATH, $fileName);

        return $fileName;
    }

    public function uploadDocument($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = date('Y-m-d-h-i-s') . '-' . preg_replace('[ ]', '-', $imageName);

        $file->move(public_path() . Employee::DOC_PATH, $fileName);

        return $fileName;
    }

    public function uploadSignature($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = date('Y-m-d-h-i-s') . '-' . preg_replace('[ ]', '-', $imageName);

        $file->move(public_path() . Employee::SIGNATURE_PATH, $fileName);
        return $fileName;
    }


    public function uploadResume($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = date('Y-m-d-h-i-s') . '-' . preg_replace('[ ]', '-', $imageName);

        $file->move(public_path() . Employee::RESUME_PATH, $fileName);
        return $fileName;
    }

    public function save($data)
    {
        $model = Employee::create($data);
        if ($model) {
            $this->saveEmployeeJoinDetails($model);
            $this->saveEmployeeCreatedDetails($model);
        }
        return $model;
    }

    public function update($id, $data)
    {

        $result = Employee::find($id);
        return $result->update($data);
    }

    public function setArchivedDetail($id, $data)
    {
        $status = ArchivedDetail::create($data);
        $this->setLogDetails($data);
        return $status;
    }

    public function setLogDetails($data)
    {
        $detail = [
            'employee_id' => $data['employee_id'],
            'date' => $data['archived_date'],
            'title' => 'Archived',
            'description' => $data['archive_reason'],
            'icon' => 'icon-user',
            'color' => 'primary',
            'reference' => 'employee',
            'reference_id' => $data['employee_id']
        ];
        EmployeeTimeline::create($detail);
    }

    public function updateStatus($id)
    {
        $employee = $this->find($id);

        if ($employee->status == 1) {
            $data['status'] = 0;
            $user_status['active'] = 0;
            if ($employee->getUser != null) {
                $employee->getUser->update($user_status);
            }
            return $employee->update($data);

            //Update eligible encashment
            if ($employee->status == 0) {
                $encashmentLogs = LeaveEncashmentLog::where('employee_id', $employee->id)->where('is_valid', 11)->get();
                if (!empty($encashmentLogs)) {
                    foreach ($encashmentLogs as $encashmentLog) {
                        $encashmentLog->eligible_encashment = $encashmentLog->total_balance;
                        $encashmentLog->save();
                    }
                }
            }
            //
        }
        if ($employee->status == 0) {
            $data['status'] = 1;
            $data['archived_date'] = null;
            $data['nep_archived_date'] = null;
            $user_status['active'] = 1;
            if ($employee->getUser != null) {
                $employee->getUser->update($user_status);
            }
            return $employee->update($data);
        }
    }

    public function find($id)
    {
        return Employee::find($id);
    }

    public function findWithFullNameAndEmail($id)
    {
        return Employee::where('id', $id)->select('first_name', 'last_name', 'personal_email', 'official_email', 'id')->first();
    }

    public function getEmployeeByCode($emp_code)
    {
        $result = Employee::select('id', 'employee_id', 'employee_code', 'first_name', 'middle_name', 'last_name', 'department_id')->where('employee_code', '=', $emp_code)->where('status', '=', '1')->first();
        return $result;
    }

    public function getEmployeeThresholdList($id)
    {
        // $result = Employee::find($id);
        return EmployeeThresholdRelatedDetail::where('employee_id', $id)->get();
    }

    public function findByProvince($provinceid)
    {
        $data = District::where('province_id', '=', $provinceid)->get();
        return $data;
    }



    public function getStates()
    {
        return Province::pluck('province_name', 'id');
    }

    public function getDistrict()
    {
        return District::pluck('district_name', 'id');
    }

    public function getActiveEmployee()
    {

        $result = Employee::select(DB::raw("CONCAT(first_name,' ',last_name) AS employee_name"), 'id')->where('status', '=', '1')->pluck('employee_name', 'id');
        return $result;
    }

    public function getActiveEmployees()
    {
        return Employee::where('status', '1')->get();
    }

    public function checkAndCreateEmployeeLeave($employeeId)
    {
        $leave_year = LeaveYearSetup::currentLeaveYear();
        $employeeModel = Employee::where('id', $employeeId)->first();
        // $filterParms = [];
        // $leaveTypeModels = LeaveType::when(array_keys($filterParms, true), function ($query) use ($filterParms) {
        //     if (isset($filterParms['organization_id'])) {
        //         $query->where('organization_id', $filterParms['organization_id']);
        //     }
        // })->get();

        $employeeModel = Employee::where('id', $employeeId)->first();

        $leaveTypeModels = LeaveType::select('id', 'gender', 'marital_status')->where('organization_id', $employeeModel->organization_id)
            ->whereHas('departments', function ($qry) use ($employeeModel) {
                $qry->where('department_id', $employeeModel->department_id);
            })
            ->whereHas('levels', function ($qry) use ($employeeModel) {
                $qry->where('level_id', $employeeModel->level_id);
            })
            ->where('status', 11)
            ->where('gender', null)->where('marital_status', null)
            ->orWhere('gender', $employeeModel->gender)->orWhere('marital_status', $employeeModel->marital_status)
            ->get();


        if (!empty($leave_year) && $leave_year->id > 0) {
            if (count($leaveTypeModels) > 0) {
                foreach ($leaveTypeModels as $leaveTypeModel) {
                    $employeeLeaveModel = EmployeeLeave::where([
                        'leave_year_id' => $leave_year->id,
                        'employee_id' => $employeeModel->id,
                        'leave_type_id' => $leaveTypeModel->id
                    ])->first();

                    if (empty($employeeLeaveModel)) {
                        $employeeLeaveModel = new EmployeeLeave();
                        $employeeLeaveModel->leave_year_id = $leave_year->id;
                        $employeeLeaveModel->employee_id = $employeeModel->id;
                        $employeeLeaveModel->leave_type_id = $leaveTypeModel->id;
                    }

                    $employee_opening_leave = EmployeeLeaveOpening::getLeaveOpening($leave_year->id, $employeeModel->organization_id, $employeeModel->id, $leaveTypeModel->id);
                    // $employeeLeaveModel->leave_remaining = $leaveTypeModel->number_of_days;  //store total_no_of_leave from opening_leave

                    $employeeLeaveModel->leave_remaining = $employee_opening_leave;
                    $employeeLeaveModel->save();
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function employeeLeaveDetails($employee_id, $leave_type_id = null, $params = [])
    {
        $filter = [];
        $filter['leave_type_id'] = $leave_type_id;
        $employeeModel = Employee::find($employee_id);
        $leave_year = LeaveYearSetup::currentLeaveYear();
        $employee_leave_details = [];
        if (auth()->user()->user_type == 'employee') {
            $filter['showStatus'] = 11;
        }
        $leaveTypeModels = LeaveType::when(true, function ($query) use ($filter, $employeeModel, $params) {
            $query->where('status', 11);
            $query->where('organization_id', $employeeModel->organization_id);
            $query->where('leave_year_id', getCurrentLeaveYearId());
            if (isset($filter['showStatus']) && !empty($filter['showStatus'])) {
                $query->where('show_on_employee', $filter['showStatus']);
            }
            if (isset($filter['leave_type_id']) && !is_null($filter['leave_type_id'])) {
                $query->where('id', $filter['leave_type_id']);
            }
            if (isset($params['half_leave_status']) && !is_null($params['half_leave_status'])) {
                $query->where('half_leave_status', $params['half_leave_status']);
            }
            $query->where(function ($qry) use ($employeeModel) {
                $qry->where('gender', $employeeModel->gender);
                $qry->orWhere('gender', null);
            });
            $query->where(function ($qry) use ($employeeModel) {
                $qry->where('marital_status', $employeeModel->marital_status);
                $qry->orWhere('marital_status', null);
            });
        })->get();
        if (count($leaveTypeModels) > 0) {
            foreach ($leaveTypeModels as $key => $leaveTypeModel) {
                $employeeLeaveModel = EmployeeLeave::where([
                    'leave_year_id' => getCurrentLeaveYearId(),
                    'employee_id' => $employeeModel->id,
                    'leave_type_id' => $leaveTypeModel->id,
                    'is_valid' => 11
                ])->first();
                $dateConverter = new DateConverter();
                $leaveYearList = (new LeaveYearSetupRepository())->getLeaveYearList();
                if ($employeeLeaveModel) {
                    // if($employeeLeaveModel->leave_type_id==18){
                    //     dd($employeeLeaveModel);
                    // }
                    $employee_leave_details[$key]['id'] = $employeeLeaveModel->id;
                    $employee_leave_details[$key]['leave_type'] = $leaveTypeModel->name;
                    $employee_leave_details[$key]['leave_remaining'] = $employeeLeaveModel->leave_remaining;
                    // dd($employee_leave_details);
                    $leave_taken = Leave::where([
                        'organization_id' => $employeeModel->organization_id,
                        'employee_id' => $employeeModel->id,
                        'leave_type_id' => $leaveTypeModel->id
                    ])
                        ->where('date', '>=', $leave_year->start_date_english)
                        ->where('date', '<=', $leave_year->end_date_english)
                        ->whereNotIn('status', [4, 5])
                        ->selectRaw('SUM(CASE WHEN leave_kind = 1 THEN 0.5 ELSE 1 END) as total_leaves')
                        ->first()
                        ->total_leaves;
                    $employee_leave_details[$key]['leave_taken'] = $leave_taken;
                    $filter = [
                        "leave_year_id" => getCurrentLeaveYearId(),
                        "organization_id" => $employeeModel->organization_id,
                        "leave_type_id" => $leaveTypeModel->id,
                        "employee_id" => $employeeModel->id
                    ];
                    // dd($employee_leave_details[$key]['leave_remaining']);
                    $employeeLeaveRemaining = self::getLeaveSummariesMonthly($filter, $employeeModel->organization_id, 30, $leaveYearList[$filter['leave_year_id']], $dateConverter->getNepMonths(), $employeeModel->id)[0];
                    // if($key==1){
                    //     dd($employeeLeaveRemaining);
                    // }
                    // dd($employeeLeaveRemaining);
                    // $employee_leave_details[$key]['total_leave'] = $employeeLeaveModel->leave_remaining + $leave_taken;
                    $employee_leave_details[$key]['leave_earned'] = $employeeLeaveRemaining->prorataLeave;
                    $openinigLeave = 0;
                    $employeeLeaveOpening = EmployeeLeaveOpening::where([
                        'leave_year_id' => getCurrentLeaveYearId(),
                        'employee_id' => $employeeModel->id,
                        'leave_type_id' => $leaveTypeModel->id
                    ])->first();
                    if ($employeeLeaveOpening) {
                        $openinigLeave = $employeeLeaveOpening->opening_leave ?? 0;
                    }
                    $employee_leave_details[$key]['opening_leave'] = $openinigLeave;
                    $employee_leave_details[$key]['total_leave'] = $openinigLeave + $employee_leave_details[$key]['leave_earned'];
                    $employee_leave_details[$key]['leave_remaining'] = $employee_leave_details[$key]['total_leave'] - $leave_taken;
                    $employee_leave_details[$key]['leave_type_id'] = $leaveTypeModel->id;
                    $employee_leave_details[$key]['leaveTypeModel'] = $leaveTypeModel;
                }
            }
        }
        return $employee_leave_details;
    }

    // global function
    function getLeaveSummariesMonthly($filter, $id, $limit, $leaveYear, $monthLists, $employeeIds)
    {
        $totalDaysInYear = 365;
        $leave_year_id = $filter['leave_year_id'];
        $leaveYearSetupDetail = LeaveYearSetup::find($leave_year_id);
        if (!is_null($leaveYearSetupDetail)) {
            $calenderType = $leaveYearSetupDetail->calender_type;
            if ($leaveYearSetupDetail->calender_type == 'nep') {
                $leaveYearStartDate = $leaveYearSetupDetail->start_date;
                $leaveYearEndDate = $leaveYearSetupDetail->end_date;
            } else {
                $leaveYearStartDate = $leaveYearSetupDetail->start_date_english;
                $leaveYearEndDate = $leaveYearSetupDetail->end_date_english;
            }
            $totalDaysInYear = getTotalDaysInLeaveYear($leaveYearStartDate, $leaveYearEndDate, $calenderType);
        }
        $leaveTypeQuery = (new LeaveTypeRepository())->getAllLeaveTypes($id, $leave_year_id);
        if (!empty($filter['leave_type_id'])) {
            $leaveTypeQuery = $leaveTypeQuery->where('id', $filter['leave_type_id']);
        }

        $data['allLeaveTypes'] = (new LeaveTypeRepository())->getAllLeaveTypes($id, $leave_year_id);

        $query = Employee::query();
        $query->where('status', '=', 1);
        $query->where('organization_id', $id);
        // $query->where('employee_id',493);
        if (auth()->user()->user_type == 'employee') {
            $query->where('id', auth()->user()->emp_id);
        } elseif (auth()->user()->user_type == 'supervisor') {
            $employeeIds = Employee::getSubordinates(auth()->user()->id);
            array_push($employeeIds, auth()->user()->emp_id);
            $query->whereIn('id', $employeeIds);
        }
        if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
            if (is_array($filter['employee_id'])) {
                $query->whereIn('employee_id', $filter['employee_id']);
            } else {
                $query->where('employee_id', $filter['employee_id']);
            }
        }
        if (is_int($employeeIds)) {
            $employees = $query->where('id', $employeeIds)->paginate($limit);
        } else {
            $employees = $query->whereIn('id', $employeeIds)->paginate($limit);
        }
        // dd($employees);
        $result = $employees->setCollection($employees->getCollection()->transform(function ($emp) use ($leave_year_id, $id, $filter, $leaveYear, $monthLists, $totalDaysInYear, $leaveYearSetupDetail) {
            // dd($emp);
            $emp->opening_leave = 0;
            $leaveTypeQuery = LeaveType::query();
            $leaveType = $leaveTypeQuery->where([
                'id' => $filter['leave_type_id'],
                'organization_id' => $id,
                'leave_year_id' => $filter['leave_year_id']
            ])->first();
            if ($leaveType) {
                $thresholdLimit = $leaveType->max_encashable_days ?? 0;
                $employeeLeaveOpening = EmployeeLeaveOpening::where('leave_year_id', $leave_year_id)->where('organization_id',  $id)->where('employee_id', $emp->id)->where('leave_type_id', $leaveType->id)->first();
                $openinigBalanceValue = $employeeLeaveOpening->opening_leave ?? 0;
                $emp->opening_leave = $openinigBalanceValue;
                $totalDays = 0;
                $countValue = 0;
                if ($leaveType->prorata_status == 10) {
                    $totalDays = Self::calculateLeaveEarnedTotalDays($emp, $leaveYear, $totalDaysInYear, $leaveYearSetupDetail);
                    $leaveTotalDays = ($leaveType->number_of_days / $totalDaysInYear) ?? 0;

                    $leaveDays = $totalDays * $leaveTotalDays;
                    $countValue = $openinigBalanceValue + $leaveDays  ?? 0;
                    $emp->prorataLeave = bcdiv($leaveDays, 1, 2);
                } else {
                    $countValue = $openinigBalanceValue ?? 0;
                    $prorataIncrement = 0;
                }
                foreach ($monthLists as $key => $mnt) {
                    if ($leaveYearSetupDetail->calender_type == 'nep') {
                        $leaveYearStartArray = explode('-', $leaveYearSetupDetail->start_date);
                        $leaveYearEndArray = explode('-', $leaveYearSetupDetail->end_date);
                        $leaveYearStartYear = $leaveYearStartArray[0];
                        $leaveYearStartMonth = $leaveYearStartArray[1];
                        $leaveYearEndYear = $leaveYearEndArray[0];
                    } else {
                        $leaveYearStartArray = explode('-', $leaveYearSetupDetail->start_date_english);
                        $leaveYearEndArray = explode('-', $leaveYearSetupDetail->end_date_english);
                        $leaveYearStartYear = $leaveYearStartArray[0];
                        $leaveYearStartMonth = $leaveYearStartArray[1];
                        $leaveYearEndYear = $leaveYearEndArray[0];
                    }

                    if ($leaveYearStartYear == $leaveYearEndYear) {
                        $leaveYear = $leaveYearStartYear;
                    } else {
                        if ($key < $leaveYearStartMonth) {
                            $leaveYear = $leaveYearEndYear;
                        } else {
                            $leaveYear = $leaveYearStartYear;
                        }
                    }

                    $date = $leaveYear . '-' . ($key < 10 ? '0' . $key : $key);
                    if ($leaveYearSetupDetail->calender_type == 'nep') {
                        $dateFormat = 'nepali_date';
                    } else {
                        $dateFormat = 'date';
                    }
                    $employeTypeWiseLeave = Leave::where(
                        [
                            'employee_id' => $emp->id,
                            'leave_type_id' => $leaveType->id
                        ]
                    )
                        ->whereBetween($dateFormat, [$date . '-01', $date . '-32'])
                        ->where('status', 3)
                        ->count() ?? 0;
                    $restrictMonthStatus = false;
                    if ($leaveYearSetupDetail->calender_type == 'nep') {
                        $currentDateInNep = explode('-', date_converter()->eng_to_nep_convert(date('Y-m-d')));
                        $currentYear = $currentDateInNep[0] ?? date('Y');
                        $currentMonth = $currentDateInNep[1] ?? 12;
                    } else {
                        $currentYear = date('Y');
                        $currentMonth = date('m');
                    }

                    if ($leaveType->prorata_status == 10) {
                        // $leaveDays = ($leaveDays / date_converter()->getTotalDaysInMonth($currentDateInNep[0], $currentMonth)) * $currentDateInNep[2];
                        $remainingEmployeeLeave = $countValue - $employeTypeWiseLeave;

                        $countValue -= $employeTypeWiseLeave;
                        $remainingLeave = $remainingEmployeeLeave;
                        $employeeLeaveDetails[$key] = [
                            'leave_taken' => $employeTypeWiseLeave,
                            'leave_remaining' => bcdiv($remainingLeave, 1, 2),
                        ];
                    } else {
                        if ($leaveYear == $currentYear) {
                            if ($key <= $currentMonth) {
                                $earnedLeave = Self::getEarnedLeavePerMonth($emp, $leaveType, $leaveYear, $key, $totalDaysInYear, $leaveYearSetupDetail->calender_type);
                                $prorataIncrement += $earnedLeave;
                                $countValue = ($countValue + $earnedLeave) - $employeTypeWiseLeave;
                                $employeeLeaveDetails[$key] = [
                                    'prorata_leave' => bcdiv($earnedLeave, 1, 2),
                                    'leave_taken' => $employeTypeWiseLeave,
                                    'leave_remaining' => bcdiv($countValue, 1, 2),
                                ];
                            } else {
                                $employeeLeaveDetails[$key] = [
                                    'prorata_leave' => null,
                                    'leave_taken' => null,
                                    'leave_remaining' => null,
                                ];
                            }
                        } elseif ($leaveYear < $currentYear) {
                            $earnedLeave = Self::getEarnedLeavePerMonth($emp, $leaveType, $leaveYear, $key, $totalDaysInYear, $leaveYearSetupDetail->calender_type);
                            $prorataIncrement += $earnedLeave;
                            $countValue = ($countValue + $earnedLeave) - $employeTypeWiseLeave;
                            $employeeLeaveDetails[$key] = [
                                'prorata_leave' => bcdiv($earnedLeave, 1, 2),
                                'leave_taken' => $employeTypeWiseLeave,
                                'leave_remaining' => bcdiv($countValue, 1, 2),
                            ];
                        } else {
                            $employeeLeaveDetails[$key] = [
                                'prorata_leave' => null,
                                'leave_taken' => null,
                                'leave_remaining' => null,
                            ];
                        }
                    }

                    // $countValue = $remainingLeave;
                    if ($leaveType->prorata_status != '10' && $restrictMonthStatus && ($currentMonth + 1 > $key)) {
                        continue;
                    }
                }
            }
            $encashable = 0;
            if ($countValue > $thresholdLimit && $leaveType->encashable_status != 10) {
                if (round($countValue - $thresholdLimit, 10) != 0) {
                    $encashable = bcdiv($countValue - (float)$thresholdLimit, 1, 2);
                }
            }
            $emp->totalRemainingLeave = bcdiv($countValue, 1, 2);
            $emp->encashedLeave = bcdiv($encashable, 1, 2);
            $emp->closingLeave = bcdiv($countValue - $encashable, 1, 2);
            if ($leaveType->carry_forward_status == '10') {
                $emp->closingLeave = 0;
            }
            if ($leaveType->prorata_status == 11) {
                $emp->prorataLeave = bcdiv($prorataIncrement, 1, 2);
            }
            $emp->employeeLeaveDetails = $employeeLeaveDetails;
            return $emp;
        }));
        $employees;
        return $employees;
    }



    public function getEmployeeFlow($employee_id)
    {
        $resp = EmployeeApprovalFlow::where('employee_id', $employee_id)->first();
        return $resp;
    }

    public function employeeAppraisalApprovalFlow($employee_id)
    {
        return EmployeeAppraisalApprovalFlow::where('employee_id', $employee_id)->first();
    }

    public function getCountries()
    {
        return Country::pluck('name', 'id');
    }
    public function findCountry($country)
    {
        return Country::where('name', $country)->first();
    }

    public function getEmployeeByOrganization($organization_id, $params = null)
    {
        if ($params) {
            $params['organization_id'] = $organization_id;
            $filter = $params;
            return Employee::when(array_keys($filter, true), function ($query) use ($filter) {
                if (isset($filter['organization_id']) && !is_null($filter['organization_id'])) {
                    $query->where('organization_id', $filter['organization_id']);
                }
                if (isset($filter['department_ids']) && (count($filter['department_ids']) > 0)) {
                    $query->whereIn('department_id', $filter['department_ids']);
                }
                if (isset($filter['designation_ids']) && (count($filter['designation_ids']) > 0)) {
                    $query->whereIn('designation_id', $filter['designation_ids']);
                }
                if (isset($filter['level_ids']) && (count($filter['level_ids']) > 0)) {
                    $query->whereIn('level_id', $filter['level_ids']);
                }

                if (isset($filter['gender']) && !empty($filter['gender'])) {
                    $query->where('gender', $filter['gender']);
                }

                if (isset($filter['employee_id'])) {
                    $query->where('id', '=', $filter['employee_id']);
                }

                if (isset($filter['branch_id'])) {
                    $query->where('branch_id', '=', $filter['branch_id']);
                }

                if (isset($filter['marital_status']) && !empty($filter['marital_status'])) {
                    $query->whereIn('marital_status', $filter['marital_status']);
                }


                if (isset($filter['contract_type']) && !empty($filter['contract_type'])) {
                    if (in_array($filter['contract_type'], [10, 11])) {
                        $query->whereHas('payrollRelatedDetailModel', function ($q) use ($filter) {
                            $q->where('contract_type', $filter['contract_type']);
                        });
                    }
                }

                if (isset($filter['probation_status']) && !empty($filter['probation_status'])) {
                    if (in_array($filter['probation_status'], [10, 11])) {
                        $query->whereHas('payrollRelatedDetailModel', function ($q) use ($filter) {
                            $q->where('probation_status', $filter['probation_status']);
                        });
                    }
                }
            })->where('status', 1)->orderBy('first_name', 'asc')->get();
        } else {
            return Employee::where('organization_id', $organization_id)->where('status', 1)->orderBy('first_name', 'asc')->get();
        }
    }

    public function getEmpNameByOrganization($organization_id)
    {
        $employeeModels = Employee::where('organization_id', $organization_id)->where('status', 1)->get();

        if ($employeeModels->count() > 0) {
            foreach ($employeeModels as $employeeModel) {
                $data[$employeeModel->id] = $employeeModel->full_name;
            }
        }
        return $data;
    }

    /**
     *
     */
    public function getBirthdayList()
    {
        $now = Carbon::now();
        $compile_now_date = date('m-d', strtotime($now));
        $afterDate =  date('m-d', strtotime(Carbon::now() . ' + 7 day'));
        $user =    auth()->user();
        $birth_date = Employee::when(true, function ($query) use ($user) {
            if (auth()->user()->user_type == 'employee') { //employee logic changes
                // $employee = Employee::findOrFail($user->emp_id);
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                // $query->where('department_id', optional(auth()->user()->userEmployer)->department_id);
            }

            if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            }
        })
            ->where(DB::raw("DATE_FORMAT(`dob`,'%m-%d')"), '>=', $compile_now_date)
            ->where(DB::raw("DATE_FORMAT(`dob`,'%m-%d')"), '<=', $afterDate)
            // ->where(DB::raw("DATE_FORMAT(`dob`,'%m-%d')"), '=',  $compile_now_date)

            ->where('status', '=', 1)
            ->select(['id', 'dob as date', 'profile_pic', 'first_name', 'middle_name', 'official_email', DB::raw("DATE_FORMAT(`dob`,'%m-%d') as sort_date"), 'last_name'])
            ->addSelect(DB::raw("'birthday' as type"))
            ->addSelect(DB::raw("'Happy Birthday 🎂' as type_title"))
            ->get()->map(function ($employee) {
                $tempDate = date('Y') . '-' . $employee->sort_date;
                $diff_date = Carbon::parse(Carbon::now()->toDateString())->diffInDays($tempDate);
                if ($diff_date == 0) {
                    $employee->diff_day = 'Today';
                } else {
                    $employee->diff_day = $diff_date . ' Day To Go';
                }
                $user = User::where('emp_id', $employee->id)->first();
                $employee->phone = !is_null($user) ? $user->getOtpPhone() : null;
                return $employee;
            });

        return $birth_date;
    }

    public function getAnniversaryList()
    {
        $now = Carbon::now();
        $compile_now_date = date('m-d', strtotime($now));
        $afterDate =  date('m-d', strtotime(Carbon::now() . ' + 1 day'));
        $user =    auth()->user();
        $results = Employee::when(true, function ($query) use ($user) {
            if (auth()->user()->user_type == 'employee') { //employee logic changes
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                // $query->where('department_id', optional(auth()->user()->userEmployer)->department_id);
            }

            if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            }
        })
            ->where('join_date', '!=', Carbon::now()->toDateString())
            // ->where(DB::raw("DATE_FORMAT(`join_date`,'%m-%d')"), '>=',  $compile_now_date)
            // ->where(DB::raw("DATE_FORMAT(`join_date`,'%m-%d')"), '<=', $afterDate)
            ->where(DB::raw("DATE_FORMAT(`join_date`,'%m-%d')"), '=',  $compile_now_date)
            ->where('status', '=', 1)
            ->select(['id', 'join_date as date', 'profile_pic', 'first_name', 'middle_name', 'official_email', DB::raw("DATE_FORMAT(`join_date`,'%m-%d') as sort_date"), 'last_name'])
            ->addSelect(DB::raw("'anniversary' as type"))
            ->addSelect(DB::raw("'New Joining 👏' as type_title"))
            ->get()->map(function ($employee) {
                $tempDate = date('Y') . '-' . $employee->sort_date;
                $diff_date = Carbon::parse(Carbon::now()->toDateString())->diffInDays($tempDate);
                if ($diff_date == 0) {
                    $employee->diff_day = 'Today';
                } else {
                    $employee->diff_day = $diff_date . ' Day To Go';
                }
                return $employee;
            });
        return $results;
    }

    public function getNewEmployeeList()
    {
        // $beforeDay =  date('Y-m-d', strtotime(Carbon::now() . ' - 7 days'));
        $afterDay =  date('Y-m-d', strtotime(Carbon::now() . ' + 7 days'));

        $user =    auth()->user();
        $results = Employee::when(true, function ($query) use ($user) {
            if (auth()->user()->user_type == 'employee') { //employee logic changes
                $employee = Employee::findOrFail($user->emp_id);
                $query->where('organization_id', $employee->organization_id);
            }

            if (auth()->user()->user_type == 'division_hr') {
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            }
        })
            ->where('status', '=', 1)->where(function ($query) use ($afterDay) {
                // $query->where('join_date', '<=', Carbon::now()->toDateString())
                //     ->where('join_date', '>=', $afterDay)
                $query->where('join_date', '>=', Carbon::now()->toDateString())
                    ->where('join_date', '<=', $afterDay)
                    ->get();
                return $query;
            })
            ->select(['id', 'join_date as date', 'profile_pic', 'first_name', 'middle_name', 'department_id', 'designation_id', DB::raw("DATE_FORMAT(`join_date`,'%m-%d') as sort_date"), 'last_name'])
            ->addSelect(DB::raw("'new employee' as type"))
            ->addSelect(DB::raw("'Work Anniversary 🥳' as type_title"))
            ->get()->map(function ($employee) {
                $tempDate = date('Y') . '-' . $employee->sort_date;
                $diff_date = Carbon::parse(Carbon::now()->toDateString())->diffInDays($tempDate);
                if ($diff_date == 0) {
                    $employee->diff_day = 'Today';
                } else {
                    $employee->diff_day = $diff_date . ' Day Ago';
                }
                return $employee;
            });
        return $results;
    }
    public function getJobEndList()
    {
        $now = Carbon::now();
        $compile_now_date = date('m-d', strtotime($now));
        $afterDate =  date('m-d', strtotime(Carbon::now() . ' + 7 day'));
        $user =    auth()->user();
        $job_end = Employee::when(true, function ($query) use ($user) {
            if (auth()->user()->user_type == 'employee') { //employee logic changes
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                $query->where('department_id', optional(auth()->user()->userEmployer)->department_id);
            }

            if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            }
        })
            ->where(DB::raw("DATE_FORMAT(`end_date`,'%m-%d')"), '>=', $compile_now_date)
            ->where(DB::raw("DATE_FORMAT(`end_date`,'%m-%d')"), '<=', $afterDate)

            ->where('status', '=', 1)
            ->select(['id', 'end_date as date', 'profile_pic', 'first_name', 'middle_name', 'official_email', DB::raw("DATE_FORMAT(`end_date`,'%m-%d') as sort_date"), 'last_name'])
            ->get()->map(function ($employee) {
                $tempDate = date('Y') . '-' . $employee->sort_date;
                $diff_date = Carbon::parse(Carbon::now()->toDateString())->diffInDays($tempDate);
                if ($diff_date == 0) {
                    $employee->diff_day = 'Today';
                } else {
                    $employee->diff_day = $diff_date . ' Day To Go';
                }
                return $employee;
            });
        // dd($job_end);
        return $job_end;
    }

    public function getJobEndAndContractEndList($limit = null)
    {
        $now = Carbon::now();
        $compile_now_date = date('m-d', strtotime($now));
        $compile_end_date =  date('m-d', strtotime(Carbon::now() . ' + 7 day'));
        $filter['date_from'] = $compile_now_date;
        $filter['date_to'] = $compile_end_date;
        $user =    auth()->user();

        $probation_compile_now_date = date('Y-m-d', strtotime($now));
        $probation_compile_end_date = date('Y-m-d', strtotime('+ 30 days', strtotime($now)));

        $filter['contract_now'] = date('Y-m-d', strtotime($now));
        $filter['contract_end'] = date('Y-m-d', strtotime('+ 7 days', strtotime($now)));
        if ($user->user_type == 'employee') {
            $filter['employee_id'] = $user->emp_id;
        }

        $filter['probation_date_from'] = $probation_compile_now_date;
        $filter['probation_date_to'] = $probation_compile_end_date;

        $data['job_end'] = [];
        $jobEnd = Employee::when(true, function ($query) use ($user) {
            if (auth()->user()->user_type == 'employee') { //employee logic changes
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                $query->where('department_id', optional(auth()->user()->userEmployer)->department_id);
            }

            if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            }
        })
            ->where(DB::raw("DATE_FORMAT(`end_date`,'%m-%d')"), '>=', $filter['date_from'])
            ->where(DB::raw("DATE_FORMAT(`end_date`,'%m-%d')"), '<=', $filter['date_to'])

            ->where('status', '=', 1)
            ->select(['id', 'end_date as date', 'profile_pic', 'first_name', 'middle_name', 'official_email', DB::raw("DATE_FORMAT(`end_date`,'%m-%d') as sort_date"), 'last_name'])
            ->get();

        if ($jobEnd) {
            foreach ($jobEnd as $model) {
                $date = date('M d, Y', strtotime($model->date));
                $data['job_end'][] = [
                    'image' => $model->getImage(),
                    'title' => "<b>" . $model->full_name . "</b>'s job period will be ended on " . $date,
                    'link' => route('employee.edit', $model->id),
                    'datetime' => Carbon::parse($model->date)
                ];
            }
        }


        // check for probation period end date
        $data['probationModels'] = [];
        $probationModels = EmployeePayrollRelatedDetail::when(true, function ($query) use ($filter) {
            if (isset($filter['probation_date_from'])) {
                $query->where('probation_end_date', '>=', $filter['probation_date_from']);
            }
            if (isset($filter['probation_date_to'])) {
                $query->where('probation_end_date', '<=', $filter['probation_date_to']);
            }

            if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
                $query->whereHas('employeeModel', function ($q) {
                    $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                });
            }

            if (isset($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }
        })->get();

        if ($probationModels) {
            foreach ($probationModels as $model) {
                $date = date('M d, Y', strtotime($model->probation_end_date));
                $data['probationModels'][] = [
                    'image' => optional($model->employeeModel)->getImage(),
                    'title' => "<b>" . optional($model->employeeModel)->full_name . "</b>'s probation period will be ended on " . $date,
                    'link' => route('employee.edit', $model->employee_id),
                    'datetime' => Carbon::parse($model->probation_end_date)
                ];
            }
        }

        $data['contractModels'] = [];
        $contractModels = EmployeePayrollRelatedDetail::when(array_keys($filter, true), function ($query) use ($filter, $now) {
            if (isset($filter['contract_now'])) {
                $query->where('contract_end_date', '>=', $filter['contract_now']);
            }
            if (isset($filter['contract_end'])) {

                $query->where('contract_end_date', '<=', $filter['contract_end']);
            }
            if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
                $query->whereHas('employeeModel', function ($q) {
                    $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                });
            }
            if (isset($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }
        })->get();
        if ($contractModels) {
            foreach ($contractModels as $model) {
                $date = date('M d, Y', strtotime($model->contract_end_date));
                $data['contractModels'][] = [
                    'image' => optional($model->employeeModel)->getImage(),
                    'title' => "<b>" . optional($model->employeeModel)->full_name . "</b>'s contract period will be ended on " . $date,
                    'link' => route('employee.edit', $model->employee_id),
                    'datetime' => Carbon::parse($model->contract_end_date)
                ];
            }
        }
        $newArray = array_merge($data['probationModels'], $data['job_end'], $data['contractModels']);
        // dd($newArray);
        $collection = new Collection($newArray);
        if ($limit) {
            $sortedData = $collection->sortBy('datetime')->take($limit);
        } else {
            $sortedData = $collection->sortBy('datetime');
        }

        return $sortedData;
    }

    public function getEmployeeByBiometric($biometricId)
    {
        return Employee::select('organization_id', 'id')->where('biometric_id', $biometricId)->where('status', 1)->first();
    }

    public function getEmployeeTimelineModel($employeeId)
    {
        return EmployeeTimeline::with(['careerMobility', 'employeeModel'])->where('employee_id', $employeeId)->orderBy('id', 'DESC')->get();

        return NewEmployeeCareerMobilityTimeline::with('employee')
            ->where('employee_id', $employeeId)->orderBy('id', 'DESC')->get()
            ->transform(function ($model) {
                return [
                    'title' => $model->title,
                    'date' => $model->event_date,
                    'color' => $model->color,
                    'icon' => $model->icon,
                    'branch' => json_decode($model->branch_log, true),
                    'department' => json_decode($model->department_log, true),
                    'designation' => json_decode($model->designation_log, true),
                    'description' => json_decode($model->description, true),
                ];
            });
    }

    /**
     * For internal use
     */
    public function saveEmployeeJoinDetails($employeeModel)
    {
        $data['employee_id'] = $employeeModel->id;
        $data['event_type'] = 'employee';
        $data['event_date'] = $employeeModel->join_date;
        $data['title'] = 'New Join';
        $data['description'] = 'Join' . ' ' . optional($employeeModel->organizationModel)->name;
        $data['icon'] = 'icon-user';
        $data['color'] = 'primary';
        $data['career_mobility_type'] = ' App\Modules\Employee\Entities\Employee';
        $data['career_mobility_type_id'] = $employeeModel->id;

        return NewEmployeeCareerMobilityTimeline::create($data);
    }

    public function updateEmployeeTimelineJoinDate($data, $employeeId)
    {
        $organization = Organization::find($data['organization_id']);
        $employeeModel = Employee::find($employeeId);
        $timelineData = [
            'employee_id' => $employeeId,
            'event_type' => 'new_join_employee',
            'event_date' => $employeeModel->join_date,
            'title' => 'New Join',
            'description' => 'Join' . ' ' . $organization->name,
            'icon' => 'icon-user',
            'color' => 'primary',
            'career_mobility_type' => ' App\Modules\Employee\Entities\Employee',
            'career_mobility_type_id' => $employeeId,
        ];
        NewEmployeeCareerMobilityTimeline::updateOrCreate(
            [
                'employee_id' => $employeeId,
                'title' => 'New Join'
            ],
            $timelineData
        );
    }

    public function saveEmployeeCreatedDetails($employeeModel)
    {
        $timelineData['employee_id'] = $employeeModel->id;
        $timelineData['date'] = $employeeModel->join_date;
        $timelineData['title'] = "New Join";
        $timelineData['description'] = "Join " . optional($employeeModel->organizationModel)->name;
        $timelineData['icon'] = "icon-user";
        $timelineData['color'] = "primary";
        $timelineData['reference'] = "employee";
        $timelineData['reference_id'] = $employeeModel->id;
        return Employee::saveEmployeeTimelineData($employeeModel->id, $timelineData);

        $data['employee_id'] = $employeeModel->id;
        $data['event_type'] = 'new_employee';
        $data['event_date'] = $employeeModel->created_at;
        $data['title'] = 'New Employee';
        $data['description'] = 'New Employee Created';
        $data['icon'] = 'icon-user';
        $data['color'] = 'primary';
        $data['career_mobility_type'] = ' App\Modules\Employee\Entities\Employee';
        $data['career_mobility_type_id'] = $employeeModel->id;

        return NewEmployeeCareerMobilityTimeline::create($data);
    }

    public function getLeaveFromSubsituteDate($employeeId, $sub_date)
    {
        $employeeModel = $this->find($employeeId);
        return $employeeModel->leave->where('substitute_date', $sub_date)->first();
    }

    public function employeeLeaveIncrement($leaveEntity)
    {
        $employeeModel = $this->find($leaveEntity->employee_id);
        $employeeLeave = $employeeModel->employeeleave->where('leave_type_id', $leaveEntity->leave_type_id)->first();
        if ($employeeLeave) {
            $employeeLeave->leave_remaining = $employeeLeave->leave_remaining + 1;
            $employeeLeave->save();
        }
    }

    // public function getEmployeeSetup($filter)
    // {
    //     $result = Employee::with()->where('status', 1)->when(array_keys($filter, true), function ($query) use ($filter) {

    //         if (isset($filter['organization_id'])) {
    //             $query->where('organization_id', $filter['organization_id']);
    //         }

    //         if (isset($filter['level_id'])) {
    //             $query->where('level_id', $filter['level_id']);
    //         }

    //         if (isset($filter['name'])) {
    //             $name = trim($filter['name']);
    //             $check = Str::contains($name, ' ');
    //             if ($check) {
    //                 $fullname = explode(' ', $name);
    //                 if (count($fullname) == 2) {
    //                     $query->whereRaw("concat(first_name, ' ', last_name) like '%" . $name . "' ");
    //                 } else {
    //                     $query->whereRaw("concat(first_name, ' ', middle_name, ' ', last_name) like '%" . $name . "' ");
    //                 }
    //             } else {
    //                 $query->where('first_name', 'like', '%' . $name . '%')->orWhere('middle_name', 'like', '%' . $name . '%')->orWhere('last_name', 'like', '%' . $name . '%');
    //             }
    //         }

    //         if (isset($filter['email'])) {
    //             $query->where('personal_email', $filter['email'])->orWhere('official_email', $filter['email']);
    //         }

    //         if (isset($filter['phone'])) {
    //             $query->where('mobile', $filter['phone']);
    //         }

    //         if (isset($filter['designation_id'])) {
    //             $query->where('designation_id', $filter['designation_id']);
    //         }

    //         if (isset($filter['department_id'])) {
    //             $query->where('department_id', $filter['department_id']);
    //         }

    //         if (isset($filter['employee_id'])) {
    //             $query->where('id', $filter['employee_id']);
    //         }
    //     })
    //         ->orderBy($sort['by'], $sort['sort'])
    //         ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
    //     return $result;
    // }

    public function updateEmployee() {}

    public function employeeApprovalFlowList($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'asc'])
    {
        $result = Employee::query();
        if (auth()->user()->user_type == 'division_hr') {
            $result->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
        }
        $result->when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }

            if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }
        });
        $result = $result->where('status', 1)->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;

        // if ($filter['type'] == 'leave_attendance_document') {

        //     $approvalFlowList = EmployeeApprovalFlow::select('employee_id', 'first_approval_user_id as first_approval', 'last_approval_user_id as last_approval')
        //         ->when(true, function ($query) use ($filter) {
        //             if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
        //                 $query->where('employee_id', $filter['employee_id']);
        //             }

        //             $query->whereHas('employee', function ($q) {
        //                 if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
        //                     $q->where('organization_id', $filter['organization_id']);
        //                 }
        //             });
        //         })
        //         ->selectRaw('"leave_attendance_document" as type')
        //         ->orderBy('id', 'desc')->get();
        // } elseif ($filter['type'] == 'claim_request') {
        //     $approvalFlowList = EmployeeClaimRequestApprovalFlow::select('employee_id', 'first_claim_approval_user_id as first_approval', 'last_claim_approval_user_id as last_approval')->selectRaw('"claim_request" as type')
        //         ->when(true, function ($query) use ($filter) {
        //             if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
        //                 $query->where('employee_id', $filter['employee_id']);
        //             }

        //             $query->whereHas('employee', function ($q) {
        //                 if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
        //                     $q->where('organization_id', $filter['organization_id']);
        //                 }
        //             });
        //         })
        //         ->orderBy('id', 'desc')->get();
        //     // ->map(function ($employee) {
        //     //     $employee->organization_id = $employee->organization_id;
        //     //     return $employee;
        //     // });
        // }

        // // $approvalFlowList = collect($employeeApprovalFlow)->merge(collect($employeeClaimRequestApprovalFlow));
        // $approvalFlowList = collect($approvalFlowList);

        // return $approvalFlowList;
    }

    // public function findMobilityReport($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    // {
    //     $authUser = auth()->user();
    //     if ($authUser->user_type == 'division_hr') {
    //         $filter['organization_id'] = optional(auth()->user()->userEmployer)->organization_id;
    //     }
    //     $mobilityTypes = [
    //         ['model' => EmployeeCareerMobilityAppointment::class, 'type' => 'Appointment', 'date_field' => 'appointment_date'],
    //         ['model' => EmployeeCarrierMobilityConfirmation::class, 'type' => 'Confirmation', 'date_field' => 'confirmation_date'],
    //         ['model' => EmployeeCarrierMobilityDemotion::class, 'type' => 'Demotion', 'date_field' => 'demotion_date'],
    //         ['model' => EmployeeCarrierMobilityPromotion::class, 'type' => 'Promotion', 'date_field' => 'promotion_date'],
    //         ['model' => EmployeeCareerMobilityTransfer::class, 'type' => 'Transfer', 'date_field' => 'transfer_date'],
    //         ['model' => EmployeeCarrierMobilityTemporaryTransfer::class, 'type' => 'Temporary Transfer', 'date_field' => 'transfer_from_date', 'extra_date_field' => 'transfer_to_date'],
    //         ['model' => EmployeeCarrierMobilityProbationaryPeriod::class, 'type' => 'Probationary Period', 'date_field' => 'extension_till_date']
    //     ];

    //     $mobilityData = [];
    //     $typeFilter = request('type_id');
    //     $fullnameFilter = request('employee_id');
    //     $organizationIdFilter = request('organization_id');
    //     $contractTypeFilter = request('contract_type');
    //     $fromDate = request('from_nep_date');
    //     $toDate = request('to_nep_date');

    //     foreach ($mobilityTypes as $config) {
    //         $items = $config['model']::with('employee')->latest()->get()
    //             ->map(function ($item) use ($config) {
    //                 return [
    //                     'id' => $item->id,
    //                     'type' => $config['type'],
    //                     'date' => isset($config['extra_date_field'])
    //                         ? $item->{$config['date_field']} . ' to ' . $item->{$config['extra_date_field']}
    //                         : $item->{$config['date_field']},
    //                     'fullname' => $item->employee->fullname . ' :: ' . $item->employee->employee_code,
    //                     'organization_id' => $item->employee->organization_id,
    //                     'contract_type' => optional($item->employee->payrollRelatedDetailModel)->contract_type ?? null
    //                 ];
    //             })->toArray();

    //         $mobilityData = array_merge($mobilityData, $items);
    //     }

    //     if ($typeFilter) {
    //         $mobilityData = array_filter($mobilityData, fn($item) => $item['type'] === $typeFilter);
    //     }

    //     if ($fullnameFilter) {
    //         $mobilityData = array_filter($mobilityData, function ($item) use ($fullnameFilter) {
    //             foreach ($fullnameFilter as $filter) {
    //                 if (stripos($item['fullname'], $filter) !== false) {
    //                     return true;
    //                 }
    //             }
    //             return false;
    //         });
    //     }

    //     if ($organizationIdFilter) {
    //         $mobilityData = array_filter($mobilityData, fn($item) => $item['organization_id'] === $organizationIdFilter);
    //     }

    //     if ($contractTypeFilter) {
    //         $mobilityData = array_filter($mobilityData, fn($item) => $item['contract_type'] === $contractTypeFilter);
    //     }

    //     if ($fromDate && $toDate) {
    //         $mobilityData = array_filter($mobilityData, fn($item) => $item['date'] >= $fromDate && $item['date'] <= $toDate);
    //     }

    //     return  $this->paginate($mobilityData, $limit);
    // }

    public function findMobilityReport($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $authUser = auth()->user();
        $mobilityTypes = [
            ['model' => EmployeeCareerMobilityAppointment::class, 'type' => 'Appointment', 'date_field' => 'appointment_date'],
            ['model' => EmployeeCarrierMobilityConfirmation::class, 'type' => 'Confirmation', 'date_field' => 'confirmation_date'],
            ['model' => EmployeeCarrierMobilityDemotion::class, 'type' => 'Demotion', 'date_field' => 'demotion_date'],
            ['model' => EmployeeCarrierMobilityPromotion::class, 'type' => 'Promotion', 'date_field' => 'promotion_date'],
            ['model' => EmployeeCareerMobilityTransfer::class, 'type' => 'Transfer', 'date_field' => 'transfer_date'],
            ['model' => EmployeeCarrierMobilityTemporaryTransfer::class, 'type' => 'Temporary Transfer', 'date_field' => 'transfer_from_date', 'extra_date_field' => 'transfer_to_date'],
            ['model' => EmployeeCarrierMobilityProbationaryPeriod::class, 'type' => 'Probationary Period', 'date_field' => 'effective_date'],
            ['model' => Employee::class, 'type' => 'Archived', 'date_field' => 'archived'],
        ];

        $mobilityData = [];
        $typeFilter = request('type_id');
        $fullnameFilter = request('employee_id');
        $organizationIdFilter = request('organization_id');
        $contractTypeFilter = request('contract_type');
        $fromDate = request('from_nep_date');
        $toDate = request('to_nep_date');
        // dd($mobilityTypes);
        foreach ($mobilityTypes as $config) {
            if ($config['model'] == 'App\Modules\Employee\Entities\Employee') {
                $Entity = $config['model']::where('status', 0)->latest()->get();
            } else {
                $Entity = $config['model']::with('employee')->latest()->get();
            }
            // dump($Entity);
            $items = $Entity
                ->map(function ($item) use ($config) {
                    return [
                        'id' => $item->id,
                        'type' => $config['type'],
                        'employee_id' => $config['model'] == 'App\Modules\Employee\Entities\Employee' ? $item->id : $item->employee->id,
                        'remarks' => $config['model'] == 'App\Modules\Employee\Entities\Employee' ? $item->archive_reason : $item->remarks ?? null,
                        'date' => ($config['model'] == 'App\Modules\Employee\Entities\Employee') ? ($item->nep_archived_date ?? $item->archived_date) : (isset($config['extra_date_field'])
                            ? $item->{$config['date_field']} . ' to ' . $item->{$config['extra_date_field']}
                            : $item->{$config['date_field']}),
                        'fullname' => $config['model'] == 'App\Modules\Employee\Entities\Employee' ? $item->full_name : $item->employee->fullname,
                        'emp_code' => $config['model'] == 'App\Modules\Employee\Entities\Employee' ? $item->employee_code : $item->employee->employee_code,
                        'organization_id' => $config['model'] == 'App\Modules\Employee\Entities\Employee' ? $item->organization_id : $item->employee->organization_id,
                        'attachment' => @$item->attachment,
                        'contract_type' => $config['model'] == 'App\Modules\Employee\Entities\Employee' ? $item->employee_code : $item->employee->employee_code,
                        'organization_id' => $config['model'] == 'App\Modules\Employee\Entities\Employee' ? null : optional($item->employee->payrollRelatedDetailModel)->contract_type ?? null,
                        'revert' => @$item->revert,
                        'sort' => $item->created_at,
                        'status_change' => @$item->status_change,
                    ];
                })->toArray();

            $mobilityData = array_merge($mobilityData, $items);
        }
        // dd($mobilityData);


        // if ($typeFilter) {
        //     $mobilityData = array_filter($mobilityData, fn($item) => $item['type'] === $typeFilter);
        // }

        if ($fullnameFilter) {
            $mobilityData = array_filter($mobilityData, function ($item) use ($fullnameFilter) {
                foreach ($fullnameFilter as $filter) {
                    if (stripos($item['fullname'], $filter) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        if ($organizationIdFilter) {
            $mobilityData = array_filter($mobilityData, fn($item) => $item['organization_id'] === $organizationIdFilter);
        }

        if ($contractTypeFilter) {
            $mobilityData = array_filter($mobilityData, fn($item) => $item['contract_type'] === $contractTypeFilter);
        }

        if ($fromDate && $toDate) {
            $mobilityData = array_filter($mobilityData, fn($item) => $item['date'] >= $fromDate && $item['date'] <= $toDate);
        }

        // Sort by 'sort' field descending (latest first)
        usort($mobilityData, function ($a, $b) {
            return strtotime($b['sort']) <=> strtotime($a['sort']);
        });

        return $this->paginate($mobilityData, $limit);
    }


    public  function fetchEmployeeForCareerMobilities()
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['organization_id'] = optional(auth()->user()->userEmployer)->organization_id;
        }
        $mobilityTypes = [
            ['model' => EmployeeCareerMobilityAppointment::class, 'type' => 'Appointment', 'date_field' => 'appointment_date'],
            ['model' => EmployeeCarrierMobilityConfirmation::class, 'type' => 'Confirmation', 'date_field' => 'confirmation_date'],
            ['model' => EmployeeCarrierMobilityDemotion::class, 'type' => 'Demotion', 'date_field' => 'demotion_date'],
            ['model' => EmployeeCarrierMobilityPromotion::class, 'type' => 'Promotion', 'date_field' => 'promotion_date'],
            ['model' => EmployeeCareerMobilityTransfer::class, 'type' => 'Transfer', 'date_field' => 'transfer_date'],
            ['model' => EmployeeCarrierMobilityTemporaryTransfer::class, 'type' => 'Temporary Transfer', 'date_field' => 'transfer_from_date', 'extra_date_field' => 'transfer_to_date'],
            ['model' => EmployeeCarrierMobilityProbationaryPeriod::class, 'type' => 'Probationary Period', 'date_field' => 'extension_till_date']
        ];

        $mobilityData = [];
        foreach ($mobilityTypes as $config) {
            $items = $config['model']::with('employee')->latest()->get()
                ->mapWithKeys(function ($item) {
                    $key = $item->employee->fullname . ' :: ' . $item->employee->employee_code;
                    $value = $item->employee->fullname . ' :: ' . $item->employee->employee_code;
                    return [$key => $value]; // Return as key-value pair
                })
                ->toArray();

            $mobilityData = array_merge($mobilityData, $items);
        }
        return $mobilityData;
    }

    public function deleteCarrierMobility($id, $type)
    {

        $models = [
            'Appointment' => EmployeeCarrierMobilityAppointment::class,
            'Confirmation' => EmployeeCarrierMobilityConfirmation::class,
            'Demotion' => EmployeeCarrierMobilityDemotion::class,
            'Promotion' => EmployeeCarrierMobilityPromotion::class,
            'Transfer' => EmployeeCareerMobilityTransfer::class,
            'Temporary Transfer' => EmployeeCarrierMobilityTemporaryTransfer::class,
            'Probationary Period' => EmployeeCarrierMobilityProbationaryPeriod::class,
        ];

        if (isset($models[$type])) {
            $model = $models[$type]::findOrFail($id);
            $eventType = strtolower(str_replace(' ', '_', $type));

            NewEmployeeCareerMobilityTimeline::where('event_type', $eventType)
                ->where('employee_id', $model->employee_id)
                ->delete();

            $model->delete();
        }
    }

    public function findEndDateReport($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['organization_id'] = optional(auth()->user()->userEmployer)->organization_id;
        }

        $query = Employee::query();

        $result =  $query->rightJoin('employee_payroll_related_details', 'employees.id', '=', 'employee_payroll_related_details.employee_id')
            ->select(
                'employees.*',
                'employee_payroll_related_details.contract_type',
                'employee_payroll_related_details.contract_start_date',
                'employee_payroll_related_details.contract_end_date',
                'employee_payroll_related_details.probation_status'
            )
            ->where('employee_payroll_related_details.contract_type', 11)
            ->when(array_keys($filter, true), function ($query) use ($filter) {
                if (isset($filter['organization_id'])) {
                    $query->where('employees.organization_id', $filter['organization_id']);
                }

                if (setting('calendar_type') == 'BS') {
                    if (!empty($filter['from_nep_date'])) {
                        $fromDate = date_converter()->nep_to_eng_convert($filter['from_nep_date']);
                        $query->where('employee_payroll_related_details.contract_start_date', '>=', $fromDate);
                    }
                    if (!empty($filter['to_nep_date'])) {
                        $toDate = date_converter()->nep_to_eng_convert($filter['to_nep_date']);
                        $query->where('employee_payroll_related_details.contract_end_date', '<=', $toDate);
                    }
                } else {
                    if (isset($filter['date_range'])) {
                        $filterDates = explode(' - ', $filter['date_range']);
                        $query->where('employee_payroll_related_details.contract_start_date', '>=', $filterDates[0]);
                        $query->where('employee_payroll_related_details.contract_end_date', '<=', $filterDates[1]);
                    }
                }

                if (isset($filter['employee_id'])) {
                    $query->where('employees.id', $filter['employee_id']);
                }
            })
            ->orderBy('employee_payroll_related_details.contract_start_date', 'DESC')
            ->paginate($limit ?? env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function findProbationEndDateReport($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['organization_id'] = optional(auth()->user()->userEmployer)->organization_id;
        }

        // $query = Employee::query();

        $result = Employee::query()
            ->rightJoin('employee_payroll_related_details', 'employees.id', '=', 'employee_payroll_related_details.employee_id')
            ->select(
                'employees.*',
                'employee_payroll_related_details.contract_type',
                'employee_payroll_related_details.contract_start_date',
                'employee_payroll_related_details.contract_end_date',
                'employee_payroll_related_details.probation_status',
                'employee_payroll_related_details.probation_period_days',
                'employee_payroll_related_details.join_date',
                DB::raw('DATE_ADD(employees.join_date, INTERVAL employee_payroll_related_details.probation_period_days DAY) as probation_end_date')
            )
            // Change the whereRaw condition to check for probation end dates within the next 30 days
            ->whereRaw("DATE_ADD(employees.join_date, INTERVAL employee_payroll_related_details.probation_period_days DAY) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)")
            ->when(array_keys($filter, true), function ($query) use ($filter) {
                if (isset($filter['organization_id'])) {
                    $query->where('employees.organization_id', $filter['organization_id']);
                }

                if (setting('calendar_type') == 'BS') {
                    if (!empty($filter['from_nep_date'])) {
                        $fromDate = date_converter()->nep_to_eng_convert($filter['from_nep_date']);
                        $query->where('employee_payroll_related_details.probation_end_date', '>=', $fromDate);
                    }
                    if (!empty($filter['to_nep_date'])) {
                        $toDate = date_converter()->nep_to_eng_convert($filter['to_nep_date']);
                        $query->where('employee_payroll_related_details.probation_end_date', '<=', $toDate);
                    }
                } else {
                    if (isset($filter['date_range'])) {
                        $filterDates = explode(' - ', $filter['date_range']);
                        $query->where('employee_payroll_related_details.probation_end_date', '>=', $filterDates[0]);
                        $query->where('employee_payroll_related_details.probation_end_date', '<=', $filterDates[1]);
                    }
                }

                if (isset($filter['employee_id'])) {
                    $query->where('employees.id', $filter['employee_id']);
                }
            })
            ->orderBy('employee_payroll_related_details.created_at', 'DESC')
            ->paginate($limit ?? env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function findDocExpiryDateReport($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['organization_id'] = optional(auth()->user()->userEmployer)->organization_id;
        }

        $result = VisaAndImmigrationDetail::when(array_keys($filter, true), function ($query) use ($filter) {

            if (isset($filter['organization_id'])) {
                $query->whereHas('employeeModel', function ($q) use ($filter) {
                    $q->where('organization_id', $filter['organization_id']);
                });
            }

            // if(setting('calendar_type') == 'BS'){
            //     if (isset($filter['from_nep_date']) && !empty($filter['from_nep_date'])) {
            //        $query->where('end_date', '>=', date_converter()->nep_to_eng_convert($filter['from_nep_date']));
            //     }
            //     if (isset($filter['to_nep_date']) && !empty($filter['to_nep_date'])) {
            //        $query->where('end_date', '<=', date_converter()->nep_to_eng_convert($filter['to_nep_date']));
            //     }
            // }else{
            if (isset($filter['visa_expiry_date'])) {
                $filterDates = explode(' - ', $filter['visa_expiry_date']);
                $query->where('visa_expiry_date', '>=', $filterDates[0]);
                $query->where('visa_expiry_date', '<=', $filterDates[1]);
            }
            // }

            if (isset($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }
        })->whereNotNull('visa_expiry_date')->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function getListOrganizationwise($orgId)
    {
        $data = [];
        $models = Employee::when(true, function ($query) use ($orgId) {
            $query->where('status', 1)->where('organization_id', $orgId)->where('is_user_access', 1);
            $query->whereHas('getUser', function ($q) {
                $q->where('user_type', '!=', 'employee');
            });
        })->get();

        if ($models->count() > 0) {
            foreach ($models as $employeeModel) {
                $data[$employeeModel->id] = $employeeModel->full_name;
            }
        }
        return $data;
    }


    public function getEmployeeByIDs($employeeIds)
    {
        $employees = Employee::whereIn('id', $employeeIds)->get();
        return $employees->mapWithKeys(function ($employee) {
            return [$employee->id => $employee->getFullName()];
        });
    }

    public function getLatestShiftGroup($employeeId)
    {
        $employeeShift = EmployeeShift::where('employee_id', $employeeId)->latest()->first();
        if ($employeeShift) {
            $group = ShiftGroup::find($employeeShift->group_id);
        } else {
            $group = null;
        }
        return $group;
    }

    // calculation of leave earned in case of prorata
    public function getEarnedLeavePerMonth($emp, $leaveType, $leaveYear, $month, $totalDaysInYear, $calenderType)
    {
        $totalDays = 0;

        $empJoiningDateArray = explode('-', $emp->nepali_join_date);
        $empJoiningDate = $emp->nepali_join_date;
        $empEndingDate = null;
        $empEndingDateArray = null;
        if (!is_null($emp->payrollRelatedDetailModel)) {
            if (optional($emp->payrollRelatedDetailModel)->job_type == 12) {
                $contract_start_date = optional($emp->payrollRelatedDetailModel)->contract_start_date;
                $contract_end_date = optional($emp->payrollRelatedDetailModel)->contract_end_date;

                $contract_start_date_nep = !is_null($contract_start_date) ? date_converter()->eng_to_nep_convert_two_digits($contract_start_date) : $emp->nepali_join_date;
                $contract_end_date_nep = !is_null($contract_end_date) ?  date_converter()->eng_to_nep_convert_two_digits($contract_end_date) : $emp->nep_end_date;
                $empJoiningDateArray = explode('-', $contract_start_date_nep);
                $empEndingDateArray = explode('-', $contract_end_date_nep);
                $empJoiningDate = $contract_start_date_nep;
                $empEndingDate = $contract_end_date_nep;
                // $startingMonth = (int)"$empJoiningDateArray[1]";
                // $endingMonth = (int)"$empEndingDateArray[1]";
            }
        }
        if (!is_null($emp->dob) && !is_null($emp->retirement_age)) {
            $date1 = Carbon::createFromDate($emp->dob);
            $retirementDate = $date1->copy()->addYears($emp->retirement_age)->format('Y-m-d');
            $retirement_date_nepali = date_converter()->eng_to_nep_convert($retirementDate);
            $empEndingDateArray = explode('-', $retirement_date_nepali);
            $empEndingDate = $retirement_date_nepali;
        }

        if (!is_null($emp->nep_archived_date)) {
            $empEndingDateArray = explode('-', $emp->nep_archived_date);
            $empEndingDate = $emp->nep_archived_date;
        }
        if ($calenderType == 'eng') {
            $empJoiningDate = date_converter()->nep_to_eng_convert($empJoiningDate);
            $empJoiningDateArray = explode('-', $empJoiningDate);
            if (!is_null($empEndingDate)) {
                $empEndingDate = date_converter()->nep_to_eng_convert($empEndingDate);
                $empEndingDateArray = explode('-', $empEndingDate);
            }
        }
        $totalDays = Self::calculateLeaveEarnedTotalDaysPerMonth($empJoiningDateArray, $empEndingDateArray, $leaveYear, $month, $leaveType->advance_allocation, $calenderType);
        // dd($totalDays);
        return ($leaveType->number_of_days / $totalDaysInYear) * $totalDays;
    }



    public function calculateLeaveEarnedTotalDaysPerMonth($empJoiningDate, $empEndingDate, $leaveYear, $month, $advance_allocation, $calenderType)
    {
        $totalDays = 0;
        if ($calenderType == 'nep') {
            $currentDateInNep = explode('-', date_converter()->eng_to_nep_convert(date('Y-m-d')));
            $currentYear = (int)$currentDateInNep[0];
            $currentMonth = (int)$currentDateInNep[1];
            $totalDaysInMonth = date_converter()->getTotalDaysInMonth($leaveYear, $month);
        } else {
            $currentYear = (int)date('Y');
            $currentMonth = (int)date('m');
            $totalDaysInMonth = Carbon::now()->month($month)->daysInMonth;
        }
        // Validate month
        if ($month < 1 || $month > 12) {
            return 0;
        }
        // Extract joining date
        $join_year = (int)$empJoiningDate[0];
        $join_month = (int)$empJoiningDate[1];
        $join_day = (int)$empJoiningDate[2];
        // Get total days in the given month and year

        // Validate joining day
        if ($join_day < 1 || $join_day > 33) {
            return 0;
        }
        // Parse ending date if not null
        if (!is_null($empEndingDate)) {
            $end_year = (int)$empEndingDate[0];
            $end_month = (int)$empEndingDate[1];
            $end_day = (int)$empEndingDate[2];

            // Validate end day
            if ($end_day < 1 || $end_day > 31) {
                return 0;
            }

            // Case 1: Employee spans across the leave year fully
            if ($join_year < $leaveYear && $end_year > $leaveYear) {
                $totalDays = $totalDaysInMonth;
                if (($leaveYear == $currentYear) && ($month == $currentMonth)) {
                    $todayEnglishDate = date('Y-m-d');
                    $todayNepaliDate = date_converter()->eng_to_nep_convert($todayEnglishDate);
                    $todayDate = explode('-', $todayNepaliDate);
                    $todayDay = (int)$todayDate[2];
                    // If employee is still working or ending after today
                    if (
                        is_null($empEndingDate) ||
                        ($end_year == $leaveYear && $end_month == $month && $end_day > $todayDay) ||
                        ($end_year > $leaveYear || ($end_year == $leaveYear && $end_month > $month))
                    ) {

                        if ($join_year < $leaveYear || ($join_year == $leaveYear && $join_month < $month)) {
                            if ($advance_allocation == 10) {
                                $totalDays = min($totalDays, $todayDay);
                            }
                        } elseif ($join_year == $leaveYear && $join_month == $month) {
                            $daysFromJoin = $todayDay - $join_day + 1;
                            if ($advance_allocation == 10) {
                                $totalDays = max(0, min($totalDays, $daysFromJoin));
                            }
                        } else {
                            $totalDays = 0;
                        }
                    } elseif ($end_year == $leaveYear && $end_month == $month && $end_day <= $todayDay) {
                        // Already ended this month before today, no change
                    } else {
                        $totalDays = 0;
                    }
                }
                return $totalDays;
            }

            // Case 2: Employee both joined and ended within leaveYear
            if ($join_year == $leaveYear && $end_year == $leaveYear) {
                if ($join_month == $month && $end_month == $month) {
                    $totalDays = ($end_day - $join_day) + 1;
                } elseif ($join_month == $month && $end_month > $month) {
                    $totalDays = ($totalDaysInMonth - $join_day) + 1;
                } elseif ($join_month < $month && $end_month == $month) {
                    $totalDays = $end_day;
                } elseif ($join_month < $month && $end_month > $month) {
                    $totalDays = $totalDaysInMonth;
                } else {
                    $totalDays = 0;
                }
            }

            // Case 3: Joined in leaveYear, ended later
            elseif ($join_year == $leaveYear && $end_year > $leaveYear) {
                if ($join_month == $month) {
                    $totalDays = ($totalDaysInMonth - $join_day) + 1;
                } elseif ($join_month < $month) {
                    $totalDays = $totalDaysInMonth;
                } else {
                    $totalDays = 0;
                }
            }

            // Case 4: Joined earlier, ended in leaveYear
            elseif ($join_year < $leaveYear && $end_year == $leaveYear) {
                if ($end_month == $month) {
                    $totalDays = $end_day;
                } elseif ($end_month > $month) {
                    $totalDays = $totalDaysInMonth;
                } else {
                    $totalDays = 0;
                }
            }

            // Case 5: Joined and ended outside leaveYear
            else {
                $totalDays = 0;
            }
        } else {
            // Employee hasn't ended yet
            if ($join_year == $leaveYear) {
                if ($join_month == $month) {
                    $totalDays = ($totalDaysInMonth - $join_day) + 1;
                } elseif ($join_month < $month) {
                    $totalDays = $totalDaysInMonth;
                } else {
                    $totalDays = 0;
                }
            } elseif ($join_year < $leaveYear) {
                $totalDays = $totalDaysInMonth;
            } else {
                $totalDays = 0;
            }
        }

        // Ensure totalDays isn't negative
        $totalDays = max(0, $totalDays);

        // Adjust if calculating for the current month
        if (($leaveYear == $currentYear) && ($month == $currentMonth)) {
            $todayEnglishDate = date('Y-m-d');
            if ($calenderType == 'nep') {
                $todayNepaliDate = date_converter()->eng_to_nep_convert($todayEnglishDate);
                $todayDate = explode('-', $todayNepaliDate);
                $todayDay = (int)$todayDate[2];
            } else {
                $todayDay = (int)date('d');
            }
            // If employee is still working or ending after today
            if (
                is_null($empEndingDate) ||
                ($end_year == $leaveYear && $end_month == $month && $end_day > $todayDay) ||
                ($end_year > $leaveYear || ($end_year == $leaveYear && $end_month > $month))
            ) {
                if ($join_year < $leaveYear || ($join_year == $leaveYear && $join_month < $month)) {
                    if ($advance_allocation == 10) {
                        $totalDays = min($totalDays, $todayDay) + 1;
                    } else {
                        $totalDays += 0;
                    }
                } elseif ($join_year == $leaveYear && $join_month == $month) {
                    $daysFromJoin = $todayDay - $join_day + 1;
                    if ($advance_allocation == 10) {
                        $totalDays = max(0, min($totalDays, $daysFromJoin)) + 1;
                    } else {
                        $totalDays += 0;
                    }
                } else {
                    $totalDays = 0;
                }
            } elseif ($end_year == $leaveYear && $end_month == $month && $end_day <= $todayDay) {
                // Already ended this month before today, no change
            } else {
                $totalDays = 0;
            }
        }

        return $totalDays;
    }
    // calculation of leave earned in case of prorata end

    // calculation of total days in case of flat
    public function calculateLeaveEarnedTotalDays($emp, $leaveYear, $totalDaysInYear, $leaveYearSetupDetail)
    {
        $totalDays = 0;

        $empJoiningDate = $emp->nepali_join_date;
        $empEndingDate = null;
        $currentDateInNep = explode('-', date_converter()->eng_to_nep_convert(date('Y-m-d')));
        if (!is_null($emp->payrollRelatedDetailModel)) {
            if (optional($emp->payrollRelatedDetailModel)->job_type == 12) {
                $contract_start_date = optional($emp->payrollRelatedDetailModel)->contract_start_date;
                $contract_end_date = optional($emp->payrollRelatedDetailModel)->contract_end_date;

                $contract_start_date_nep = !is_null($contract_start_date) ? date_converter()->eng_to_nep_convert_two_digits($contract_start_date) : $emp->nepali_join_date;
                $contract_end_date_nep = !is_null($contract_end_date) ?  date_converter()->eng_to_nep_convert_two_digits($contract_end_date) : $emp->nep_end_date;
                $empJoiningDate = $contract_start_date_nep;
                $empEndingDate = $contract_end_date_nep;
            }
        }
        if (!is_null($emp->dob) && !is_null($emp->retirement_age)) {
            $date1 = Carbon::createFromDate($emp->dob);
            $retirementDate = $date1->copy()->addYears($emp->retirement_age)->format('Y-m-d');
            $retirement_date_nepali = date_converter()->eng_to_nep_convert($retirementDate);
            $empEndingDate = $retirement_date_nepali;
        }

        if (!is_null($emp->nep_archived_date)) {
            $empEndingDate = $emp->nep_archived_date;
        }


        $emp_join_date = Carbon::parse(date_converter()->nep_to_eng_convert($empJoiningDate));
        if (!is_null($empEndingDate)) {
            $emp_end_date = Carbon::parse(date_converter()->nep_to_eng_convert($empEndingDate));
        }
        $leaveYearStartDate = Carbon::parse(date_converter()->nep_to_eng_convert($leaveYearSetupDetail->start_date));
        $leaveYearEndDate = Carbon::parse(date_converter()->nep_to_eng_convert($leaveYearSetupDetail->end_date));

        if ($emp_join_date < $leaveYearStartDate) {
            if (!is_null($emp_end_date ?? null)) {
                if (($emp_end_date > $leaveYearStartDate) && ($emp_end_date < $leaveYearEndDate)) {
                    $totalDays = $leaveYearStartDate->diffInDays($emp_end_date) + 1;
                } else {
                    $totalDays = $totalDaysInYear;
                }
            } else {
                $totalDays = $totalDaysInYear;
            }
        } else {
            if ((isset($emp_end_date) && ($emp_end_date < $leaveYearEndDate))) {
                $totalDays = $emp_join_date->diffInDays($emp_end_date) + 1;
            } elseif ($emp_join_date > $leaveYearEndDate) {
                $totalDays = 0;
            } else {
                $totalDays = $emp_join_date->diffInDays($leaveYearEndDate) + 1;
            }
        }

        return $totalDays;
    }
    // calculation of total days in case of flat end

    public function calculateLeaveEarnedTotalDaysProrata($emp, $leaveYear, $totalDaysInYear, $leaveYearSetupDetail, $advanceAllocation)
    {
        $totalDays = 0;

        $empJoiningDate = $emp->nepali_join_date;
        $empEndingDate = null;

        if (!is_null($emp->payrollRelatedDetailModel)) {
            if (optional($emp->payrollRelatedDetailModel)->job_type == 12) {
                $contract_start_date = optional($emp->payrollRelatedDetailModel)->contract_start_date;
                $contract_end_date = optional($emp->payrollRelatedDetailModel)->contract_end_date;

                $contract_start_date_nep = !is_null($contract_start_date) ? date_converter()->eng_to_nep_convert_two_digits($contract_start_date) : $emp->nepali_join_date;
                $contract_end_date_nep = !is_null($contract_end_date) ?  date_converter()->eng_to_nep_convert_two_digits($contract_end_date) : $emp->nep_end_date;
                $empJoiningDate = $contract_start_date_nep;
                $empEndingDate = $contract_end_date_nep;
            }
        }
        if (!is_null($emp->dob) && !is_null($emp->retirement_age)) {
            $date1 = Carbon::createFromDate($emp->dob);
            $retirementDate = $date1->copy()->addYears($emp->retirement_age)->format('Y-m-d');
            $retirement_date_nepali = date_converter()->eng_to_nep_convert($retirementDate);
            $empEndingDate = $retirement_date_nepali;
        }
        if (!is_null($emp->nep_archived_date)) {
            $empEndingDate = $emp->nep_archived_date;
        }

        $currentDate = Carbon::parse(date('Y-m-d'));
        $emp_join_date = Carbon::parse(date_converter()->nep_to_eng_convert($empJoiningDate));
        $leaveYearStartDate = Carbon::parse(date_converter()->nep_to_eng_convert($leaveYearSetupDetail->start_date));
        $leaveYearEndDate = Carbon::parse(date_converter()->nep_to_eng_convert($leaveYearSetupDetail->end_date));
        if (!is_null($empEndingDate)) {
            $emp_end_date = Carbon::parse(date_converter()->nep_to_eng_convert($empEndingDate));
            if ($emp_end_date > $currentDate) {
                if ($advanceAllocation == 11) {
                    $currentNepDateArray = explode('-', date_converter()->eng_to_nep_convert($currentDate));
                    $nepEndDate = $currentNepDateArray[0] . '-' . $currentNepDateArray[1] . '-' . date_converter()->getTotalDaysInMonth($currentNepDateArray[0], $currentNepDateArray[1]);

                    $emp_end_date = date_converter()->nep_to_eng_convert($nepEndDate);
                } else {
                    $emp_end_date = $currentDate->addDay();
                }
            }
        } else {
            if ($currentDate < $leaveYearEndDate) {
                if ($advanceAllocation == 11) {
                    if ($leaveYearSetupDetail->calender_type == 'nep') {
                        $currentNepDateArray = explode('-', date_converter()->eng_to_nep_convert($currentDate));
                        $nepEndDate = $currentNepDateArray[0] . '-' . $currentNepDateArray[1] . '-' . date_converter()->getTotalDaysInMonth($currentNepDateArray[0], $currentNepDateArray[1]);
                        $emp_end_date = date_converter()->nep_to_eng_convert($nepEndDate);
                    } else {
                        $emp_end_date = Carbon::now()->endOfMonth();
                    }
                } else {
                    $emp_end_date = $currentDate->addDay();
                }
            }
        }
        if ($emp_join_date < $leaveYearStartDate) {
            if (!is_null($emp_end_date ?? null)) {
                if (($emp_end_date > $leaveYearStartDate) && ($emp_end_date < $leaveYearEndDate)) {
                    $totalDays = $leaveYearStartDate->diffInDays($emp_end_date) + 1;
                } else {
                    $totalDays = $totalDaysInYear;
                }
            } else {
                $totalDays = $totalDaysInYear;
            }
        } else {
            if ((isset($emp_end_date) && ($emp_end_date < $leaveYearEndDate))) {
                $totalDays = $emp_join_date->diffInDays($emp_end_date) + 1;
            } elseif ($emp_join_date > $leaveYearEndDate) {
                $totalDays = 0;
            } else {
                $totalDays = $emp_join_date->diffInDays($leaveYearEndDate) + 1;
            }
        }
        if ($leaveYearStartDate > $currentDate) {
            $totalDays = 0;
        }
        return $totalDays;
    }
}
