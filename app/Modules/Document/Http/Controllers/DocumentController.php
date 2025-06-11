<?php

namespace App\Modules\Document\Http\Controllers;

use App\Modules\Document\Entities\Document;
use App\Modules\Document\Entities\DocumentAttachment;
use App\Modules\Document\Entities\DocumentEmployee;
use App\Modules\Document\Entities\DocumentOrganization;
use App\Modules\Document\Entities\DocumentOrganizationDepartment;
use App\Modules\Document\Http\Requests\DocumentRequest;
use App\Modules\Document\Http\Requests\UpdateDocumentRequest;
use App\Modules\Document\Repositories\DocumentInterface;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Repositories\DepartmentInterface;
use App\Modules\User\Entities\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    private $document;
    private $employee;
    private $organization;
    private $dropdown;
    private $department;

    /**
     * DocumentController constructor.
     * @param DocumentInterface $document
     */
    public function __construct(
        DocumentInterface $document,
        EmployeeInterface $employee,
        OrganizationInterface $organization,
        DropdownInterface $dropdown,
        DepartmentInterface $department

    ) {
        $this->document = $document;
        $this->employee = $employee;
        $this->organization = $organization;
        $this->dropdown = $dropdown;
        $this->department = $department;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $filter = $request->all();

        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];

        // $userInfo = Auth::user();
        // $data['user_type'] = $user_type = $userInfo->user_type;
        // $id = (($user_type == 'super_admin' || $user_type == 'hr')) ? '' : $userInfo->emp_id;

        // if ($user_type == 'employee' || $user_type == 'supervisor') {
        //     $filter['employee_id'] = $userInfo->userEmployer->id;
        // }

        $data['statusList'] = Document::statusList();
        $data['documentModels'] = $this->document->findAll(20, $filter, $sort);

        return view('document::document.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['statusList'] = Document::statusList();

        if (auth()->user()->user_type == 'employee') {
            $data['employeeList'] = Employee::getEmployeesOrganizationDepartmentwise(optional(auth()->user()->userEmployer)->organization_id, optional(auth()->user()->userEmployer)->department_id, null);
        } elseif (auth()->user()->user_type == 'supervisor') {
            $data['employeeList'] = $this->employee->getList();  //list of subordinates if logged in as supervisor
        } elseif (auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'division_hr') {
            $data['employeeList'] = $this->employee->getList();
            $data['organizationList'] = $this->organization->getList();
            $data['departmentList'] = $this->department->getList();
        }
        return view('document::document.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(DocumentRequest $request)
    {
        $inputData = $request->except('_token');
        try {
            $inputData['created_by'] = Auth::user()->id;
            $documentData = $this->document->create($inputData);

            if ($documentData) {
                //store files
                if ($request->has('attachments')) {
                    foreach ($inputData['attachments'] as $attachment) {
                        $this->uploadAttachment($documentData->id, $attachment);
                    }
                }
                //

                //store employee
                if ($request->has('employees')) {
                    foreach ($inputData['employees'] as $employee) {
                        $this->saveEmployeeDocument($documentData->id, $employee);
                    }
                }
                //

                if ($request->has('method_type')) {
                    if ($inputData['method_type'] == 1) {       //if department chosen
                        if (isset($inputData['departmentIds']) && !empty($inputData['departmentIds'])) {
                            $docOrg['document_id'] = $documentData->id;
                            $docOrg['organization_id'] = null;
                            $docOrgData = DocumentOrganization::create($docOrg);

                            foreach ($inputData['departmentIds'] as $departmentId) {
                                $docOrgDepartment['document_organization_id'] = $docOrgData->id;
                                $docOrgDepartment['department_id'] = $departmentId;
                                DocumentOrganizationDepartment::create($docOrgDepartment);

                                $employeeIds = Employee::getEmployeeDepartmentWise($departmentId);
                                if ($employeeIds) {
                                    foreach ($employeeIds as $employeeId) {
                                        $docEmployee['document_id'] = $documentData->id;
                                        $docEmployee['employee_id'] = $employeeId;
                                        DocumentEmployee::create($docEmployee);
                                    }
                                }
                            }
                        }
                    } elseif ($inputData['method_type'] == 2) {   //if employee chosen
                        if (isset($inputData['employeeIds']) && !empty($inputData['employeeIds'])) {

                            $docOrg['document_id'] = $documentData->id;
                            $docOrg['organization_id'] = $inputData['organization_id'];
                            $docOrgData = DocumentOrganization::create($docOrg);

                            foreach ($inputData['employeeIds'] as $employeeId) {
                                $docEmployee['document_id'] = $documentData->id;
                                $docEmployee['employee_id'] = $employeeId;
                                DocumentEmployee::create($docEmployee);
                            }
                        }
                    }
                }
            }


            toastr()->success('Document Created Successfully');
        } catch (\Throwable $th) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('document.index'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data['documentModel'] = $this->document->findOne($id);
        return view('document::document.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['statusList'] = Document::statusList();
        if (auth()->user()->user_type == 'employee') {
            $data['employeeList'] = Employee::getEmployeesOrganizationDepartmentwise(optional(auth()->user()->userEmployer)->organization_id, optional(auth()->user()->userEmployer)->department_id, null);
        } elseif (auth()->user()->user_type == 'supervisor') {
            $data['employeeList'] = $this->employee->getList();  //list of subordinates if logged in as supervisor
        } elseif (auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'hr') {
            $data['employeeList'] = $this->employee->getList();
            $data['organizationList'] = $this->organization->getList();
            $data['departmentList'] = $this->department->getList();
        }

        $data['documentModel'] = $documentModel = $this->document->findOne($id);

        if (auth()->user()->user_type == 'employee' || auth()->user()->user_type == 'supervisor') {
            $data['documentEmployees'] = $this->document->getDocumentEmployeeList($id);
        }
        // elseif (auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'super_admin') {
        //     if ($documentModel['method_type'] == 1) {          //department
        //         $departmentList = $documentModel->documentOrganization;
        //     } elseif ($documentModel['method_type'] == 2) {    //employee
        //     }
        // }

        return view('document::document.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateDocumentRequest $request, $id)
    {
        $inputData = $request->except('_token');

        try {
            $inputData['updated_by'] = Auth::user()->id;
            $this->document->update($id, $inputData);

            $document = $this->document->findOne($id);

            $employees = [];
            if (!empty($inputData['employees'])) {
                $employees = $inputData['employees'];
            }
            $document->employees()->sync($employees);

            toastr()->success('Document Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('document.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $document = $this->document->delete($id);
            if ($document) {
                DocumentAttachment::where('document_id', $id)->delete();
                DocumentEmployee::where('document_id', $id)->delete();
                $docOrg = DocumentOrganization::where('document_id', $id)->first();
                $docOrgDepartment = DocumentOrganization::where('document_id', $id)->delete();
                if (isset($docOrg) && $docOrgDepartment) {
                    DocumentOrganizationDepartment::where('document_organization_id', $docOrg->id)->delete();
                }
            }
            toastr()->success('Document Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    public function uploadAttachment($id, $file)
    {
        $fileDetail = DocumentAttachment::saveFile($file);
        $modelData['document_id'] = $id;
        $modelData['title'] = $fileDetail['filename'];
        $modelData['extension'] = $fileDetail['extension'];
        $modelData['size'] = $fileDetail['size'];

        DocumentAttachment::create($modelData);
    }

    public function saveEmployeeDocument($id, $employeeId)
    {
        $modelData['document_id'] = $id;
        $modelData['employee_id'] = $employeeId;

        DocumentEmployee::create($modelData);
    }

    public function sharedDocumentList(Request $request)
    {
        $filter = $request->all();

        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];

        $data['statusList'] = Document::statusList();
        $data['sharedDocumentModels'] = $this->document->sharedList(20, $filter, $sort);

        return view('document::shared-with-me.index', $data);
    }
}
