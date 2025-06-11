<?php

namespace App\Modules\Onboarding\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use App\Modules\Setting\Entities\MrfApprovalFlow;
use App\Modules\Onboarding\Http\Requests\MrfRequest;
use App\Modules\Onboarding\Repositories\MrfInterface;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Onboarding\Entities\ManpowerRequisitionForm;
use App\Modules\Onboarding\Entities\MrfStatusDetail;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Repositories\DepartmentInterface;
use App\Modules\Setting\Repositories\DesignationInterface;

class MrfController extends Controller
{
    private $mrfObj;
    private $organizationObj;
    private $employeeObj;
    private $dropdownObj;
    private $department;
    private $designation;


    /**
     * LeaveController constructor.
     * @param LeaveInterface $leave
     * @param DropdownInterface $dropdown
     * @param EmploymentInterface $employment
     * @param FieldInterface $field
     */
    public function __construct(
        MrfInterface $mrfObj,
        OrganizationInterface $organizationObj,
        EmployeeInterface $employeeObj,
        DropdownInterface $dropdownObj,
        DepartmentInterface $department,
        DesignationInterface $designation
    ) {
        $this->mrfObj = $mrfObj;
        $this->organizationObj = $organizationObj;
        $this->employeeObj = $employeeObj;
        $this->dropdownObj = $dropdownObj;
        $this->department = $department;
        $this->designation = $designation;
    }

    /**
     *
     */
    public function getCurrentUserDetail()
    {
        return User::where('id', Auth::user()->id)->first();
    }

    /**
     *
     */
    public function index(Request $request)
    {
        $filter = $request->all();

        $authUser = auth()->user();
        if (auth()->user()->user_type == 'division_hr') {
            $filter['organization'] = optional($authUser->userEmployer)->organization_id;
        }

        if (isset($filter['organization'])) {
            $organizationId = $filter['organization'];
        } elseif ($authUser->user_type == 'super_admin' || $authUser->user_type == 'admin') {
            // do nothing
        } else {
            $organizationId = optional($authUser->userEmployer)->organization_id;
        }

        if (isset($organizationId)) {
            $approvalEmployeeModel = MrfApprovalFlow::where('organization_id', $organizationId)->first();
            if ($approvalEmployeeModel) {
                if ($authUser->emp_id == $approvalEmployeeModel->first_approval_emp_id && is_null($approvalEmployeeModel->second_approval_emp_id) && is_null($approvalEmployeeModel->third_approval_emp_id)) {
                    $statusList = [
                        '8' => 'Accept',
                        '4' => 'Reject'
                    ];
                } elseif ($authUser->emp_id == $approvalEmployeeModel->first_approval_emp_id) {
                    $filter['statuses'] = ['1'];
                    $statusList = [
                        '5' => 'Forward',
                        '4' => 'Reject'
                    ];
                } elseif ($authUser->emp_id == $approvalEmployeeModel->second_approval_emp_id) {
                    $filter['statuses'] = ['5'];
                    $statusList = [
                        '6' => 'Forward',
                        '4' => 'Reject'
                    ];
                } elseif ($authUser->emp_id == $approvalEmployeeModel->third_approval_emp_id) {
                    $filter['statuses'] = ['6'];
                    $statusList = [
                        '7' => 'Forward',
                        '4' => 'Reject'
                    ];
                } elseif ($authUser->emp_id == $approvalEmployeeModel->fourth_approval_emp_id) {
                    $filter['statuses'] = ['7'];
                    $statusList = [
                        '8' => 'Accept',
                        '4' => 'Reject'
                    ];
                } else {
                    // do nothing
                }
            }
        }

        $data['mrfModels'] = $this->mrfObj->findAll(20, $filter);
        $data['organizationList'] = $this->organizationObj->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        $data['divisionList'] = $this->dropdownObj->getFieldBySlug('division');
        $data['departmentList'] = $this->department->getList();
        $data['designationList'] = $this->designation->getList();
        $data['statusList'] = isset($statusList) ? $statusList : ManpowerRequisitionForm::statusList();
        return view('onboarding::mrf.index', $data);
    }

    /**
     *
     */
    public function history(Request $request)
    {
        $filter = $request->all();

        $data['mrfModels'] = $this->mrfObj->findAll(20, $filter);
        $data['organizationList'] = $this->organizationObj->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        $data['divisionList'] = $this->dropdownObj->getFieldBySlug('division');
        $data['departmentList'] = $this->department->getList();
        $data['designationList'] = $this->designation->getList();
        $data['statusList'] = isset($statusList) ? $statusList : ManpowerRequisitionForm::statusList();

        return view('onboarding::mrf.history', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $currentUserModel = $this->getCurrentUserDetail();
        if ($currentUserModel->user_type == 'employee') {
            $data['organizationId'] = optional(auth()->user()->userEmployer)->organization_id;
            $data['employeeId'] = $currentUserModel->emp_id;
        } elseif ($currentUserModel->user_type == 'supervisor') {
            $data['organizationId'] = optional(auth()->user()->userEmployer)->organization_id;
        } else {
            // do nothing
        }

        $data['isEdit'] = false;
        $data['organizationList'] = $this->organizationObj->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        if (isset(auth()->user()->userEmployer->organization_id)) {
            $data['authEmployeeList'] = $this->employeeObj->getEmpNameByOrganization(auth()->user()->userEmployer->organization_id);
        } else {
            $data['authEmployeeList'] = [];
        }
        $data['divisionList'] = $this->dropdownObj->getFieldBySlug('division');
        $data['departmentList'] = $this->department->getList();
        $data['designationList'] = $this->designation->getList();
        $data['mrfTypeList'] = ManpowerRequisitionForm::typeList();
        $data['statusList'] = ManpowerRequisitionForm::statusList();
        return view('onboarding::mrf.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(MrfRequest $request)
    {
        $inputData = $request->all();
        try {
            $inputData['start_date'] = date('Y-m-d');
            $inputData['end_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($inputData['end_date']) : $inputData['end_date'];
            $result = $this->mrfObj->create($inputData);
            if ($result) {
                $this->mrfObj->sendMailNotification($result);
                toastr()->success('Data Created Successfully');
            }
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('mrf.index'));
    }




    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $mrfModel = $this->mrfObj->findOne($id);

        $data['mrfModel'] = $mrfModel;
        $data['organizationList'] = $this->organizationObj->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        $data['divisionList'] = $this->dropdownObj->getFieldBySlug('division');
        $data['departmentList'] = $this->department->getList();
        $data['designationList'] = $this->designation->getList();
        $data['mrfTypeList'] = ManpowerRequisitionForm::typeList();
        $data['statusList'] = ManpowerRequisitionForm::statusList();
        $data['mrfApprovalFlowModel'] = MrfApprovalFlow::getOrganizationwiseApprovalFlow($mrfModel->organization_id);

        if (auth()->user()->user_type == 'employee') {
            return view('onboarding::mrf.show', $data);
        } else {
            return view('onboarding::mrf.show-detail', $data);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['mrfModel'] = $mrfModel = $this->mrfObj->findOne($id);
        $data['organizationList'] = $this->organizationObj->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        // if (isset(auth()->user()->userEmployer->organization_id)) {
        //     $data['authEmployeeList'] = $this->employeeObj->getEmpNameByOrganization(auth()->user()->userEmployer->organization_id);
        // } else {
        //     $data['authEmployeeList'] = [];
        // }
        $inputData = [
            'organization_id' => $mrfModel->organization_id,
            'department_id' => $mrfModel->department,
            'designation_id' => $mrfModel->designation,
        ];


        $data['authEmployeeList'] = Employee::getOrganizationwiseEmployees($inputData);
        $data['divisionList'] = $this->dropdownObj->getFieldBySlug('division');
        $data['departmentList'] = $this->department->getList();
        $data['designationList'] = $this->designation->getList();
        $data['mrfTypeList'] = ManpowerRequisitionForm::typeList();
        $data['statusList'] = ManpowerRequisitionForm::statusList();
        $data['statusList'] = ManpowerRequisitionForm::statusList();

        return view('onboarding::mrf.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(MrfRequest $request, $id)
    {
        $data = $request->all();
        $data['end_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['end_date']) : $data['end_date'];

        try {
            $this->mrfObj->update($id, $data);

            toastr()->success('Data Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('mrf.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->mrfObj->delete($id);

            toastr()->success('Data Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    /**
     *
     */
    public function updateStatus(Request $request)
    {
        $inputData = $request->all();

        try {
            $result = $this->mrfObj->update($inputData['id'], $inputData);
            if ($result) {
                $model = $this->mrfObj->findOne($inputData['id']);
                $this->mrfObj->sendMailNotification($model);

                $data['mrf_id'] = $inputData['id'];
                $data['status'] = $inputData['status'];
                $data['action_by'] = auth()->user()->emp_id;
                $data['action_datetime'] = date('Y-m-d H:i:s');
                $data['action_remark'] = $inputData['remark'];
                MrfStatusDetail::create($data);
            }

            toastr()->success('Status Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    public function closeMRF($id)
    {
        try {
            $data['status'] = 10;
            $this->mrfObj->update($id, $data);

            toastr()->success('MRF status closed successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }
}
