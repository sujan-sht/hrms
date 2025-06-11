<?php

namespace App\Modules\Grievance\Http\Controllers;

use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Grievance\Entities\Grievance;
use App\Modules\Grievance\Entities\GrievanceEmployee;
use App\Modules\Grievance\Entities\GrievanceMeta;
use App\Modules\Grievance\Exports\GrievanceReport;
use App\Modules\Grievance\Repositories\GrievanceInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Repositories\DepartmentInterface;
use App\Modules\Setting\Repositories\DesignationInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class GrievanceController extends Controller
{
    protected $branchObj;
    protected $dropdown;
    protected $employee;
    protected $grievance;
    protected $organizationObj;
    protected $department;
    protected $designation;

    public function __construct(
        BranchInterface $branchObj,
        EmployeeInterface $employee,
        DropdownInterface $dropdown,
        GrievanceInterface $grievance,
        OrganizationInterface $organizationObj,
        DepartmentInterface $department,
        DesignationInterface $designation
    ) {
        $this->employee = $employee;
        $this->branchObj = $branchObj;
        $this->dropdown = $dropdown;
        $this->grievance = $grievance;
        $this->organizationObj = $organizationObj;
        $this->department = $department;
        $this->designation = $designation;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data['grievances'] = $this->grievance->findAll();
        $data['statusList'] = Grievance::STATUS;
        return view('grievance::grievance.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['employeeList'] = $this->employee->getList();
        // $data['divisionTypeList'] = $this->dropdown->getFieldBySlug('division');
        $data['divisionTypeList'] = $this->organizationObj->getList();
        $data['designationList'] = $this->designation->getList();
        $data['departmentList'] = $this->department->getList();
        $data['is_employee'] = false;
        $data['employee'] = '';

        if (auth()->user()->user_type == 'employee') {
            $data['is_employee'] = true;
            $data['employee'] = auth()->user()->userEmployer;
        }

        return view('grievance::grievance.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData = ($request->except(['_token']));
        try {

            $inputData['created_by'] = Auth::user()->id;
            if ($request->hasFile('attachment')) {
                $inputData['attachment'] = $this->grievance->upload($request->attachment);
            }

            $grievance = $this->grievance->save($inputData);
            if ($inputData['is_anonymous'] == 10) {
                $grievanceEmployee = new GrievanceEmployee($inputData['employee']);
                $grievance->grievanceEmployee()->save($grievanceEmployee);
            }

            $type = '';
            switch ($inputData['subject_type']) {
                case '1':
                    $type = 'subject';
                    break;
                case '2':
                    $type = 'disciplinary';
                    break;
                case '3':
                    $type = 'suggestion';
                    break;
                case '4':
                    $type = 'other';
                    break;

                default:
                    # code...
                    break;
            }

            $inputMetaData = $this->setMetaArray($inputData, $type);
            $grievance->grievanceMetas()->saveMany($inputMetaData);
            toastr()->success('Grievance Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }
        return redirect()->route('grievance.index');
    }

    public function setMetaArray($array, $type)
    {
        $metaArray = [];
        foreach ($array[$type] as $key => $value) {
            $metaArray[] = new GrievanceMeta([
                'subject_type' => $array['subject_type'],
                'key' => $key,
                'value' => $value
            ]);
        }
        return $metaArray;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function view($id)
    {
        $data['grievance'] = $this->grievance->find($id);
        return view('grievance::grievance.view', $data);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $grievance = $this->grievance->find($id);
            if ($grievance->grievanceEmployee()->exists()) {
                $grievance->grievanceEmployee->delete();
            }
            $grievance->grievanceMetas()->delete();
            $grievance->delete();
            toastr()->success('Grievance Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->route('grievance.index');
    }

    public function findEmployee(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->employee->find($request->employee_id);
            return json_encode($data);
        }
    }

    public function updateStatus(Request $request)
    {
        $inputData = $request->all();
        try {

            switch ($request->status) {
                case '1':
                    $inputData['remark'] = null;
                    break;
                default:
                    $inputData['resolved_date'] = null;
                    break;
            }

            $this->grievance->update($request->id, $inputData);
            toastr('Grievance Status Updated Successfully', 'success');
        } catch (\Throwable $th) {
            toastr($th->getMessage(), 'error');
        }
        return redirect()->back();
    }

    public function exportAll(Request $request)  {
        $filter = $request->all();
        $data['grievance'] = Grievance::with(['grievanceEmployee','grievanceMetas'=>function($query){
            // $query->pluck('value','key');
        }])->get()
        ->each(function ($grievance) {
            $grievance->grievanceMetas = $grievance->grievanceMetas->pluck('value', 'key');
        })
        ->groupBy('subject_type');


        return Excel::download(new GrievanceReport($data), 'grievance-report.xlsx');
    }
}
