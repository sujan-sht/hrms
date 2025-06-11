<?php

namespace App\Modules\OrganizationalStructure\Http\Controllers;

use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\OrganizationalStructure\Entities\OrganizationalStructureDetail;
use App\Modules\OrganizationalStructure\Http\Requests\CreateOrgStructureRequest;
use App\Modules\OrganizationalStructure\Repositories\OrganizationalStructureInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OrganizationalStructureController extends Controller
{
    protected $organization;
    protected $employee;
    protected $organizationalStructure;

    public function __construct(
        OrganizationInterface $organization,
        EmployeeInterface $employee,
        OrganizationalStructureInterface $organizationalStructure

    ) {
        $this->organization = $organization;
        $this->employee = $employee;
        $this->organizationalStructure = $organizationalStructure;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $search = $request->all();
        $data['orgStructures'] = $this->organizationalStructure->findAll(20, $search);
        return view('organizationalstructure::organizational-structure.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['is_edit'] = false;
        $data['employeeList'] = $data['allEmployeeList'] = $this->employee->getList(1);
        $data['orgStructureDetail'] = [];
        return view('organizationalstructure::organizational-structure.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateOrgStructureRequest $request)
    {
        $inputData = $request->all();
        try {
            $organizationalStructure = $this->organizationalStructure->save($inputData);

            $orgStructuralDetailArray = [];
            foreach ($inputData['structure_details'] as $detail) {
                $orgStructuralDetailArray = [
                    'org_structure_id' => $organizationalStructure->id,
                    'employee_id' => $detail['employee_id'],
                    'parent_employee_id' => $detail['parent_employee_id'],
                ];
                OrganizationalStructureDetail::create($orgStructuralDetailArray);
            }
            // foreach ($inputData['structure_details']['employee_id'] as $key => $detail) {
            //     $parentEmployeeId = $inputData['structure_details']['parent_employee_id'][$key];
            //     if($detail && $parentEmployeeId){
            //         $orgStructuralDetailArray = [
            //             'org_structure_id' => $organizationalStructure->id,
            //             'employee_id' => $detail,
            //             'parent_employee_id' => $parentEmployeeId,
            //         ];
            //         OrganizationalStructureDetail::create($orgStructuralDetailArray);
            //     }
            // }
            toastr()->success('Organizational Structure Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something went wrong');
        }
        return redirect(route('organizationalStructure.index'));

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $orgStructure = $this->organizationalStructure->find($id);
        $orgStructureDetails = $this->organizationalStructure->getOrgStructureDetails($id);

        $orgStructureArr = [];
        $orgStructureArr[0][] = ['v' => optional($orgStructure->rootEmployee)->full_name, 'f' => '<div style="color:black; font-weight:bold; white-space:nowrap">'.optional($orgStructure->rootEmployee)->full_name.'</div><div style="color:red; font-style:italic; white-space:nowrap">'.optional(optional($orgStructure->rootEmployee)->designation)->title.'</div><div style="color:blue; font-style:italic">['.optional(optional($orgStructure->rootEmployee)->organizationModel)->name.']</div>'];

        $orgStructureArr[0][] = '';
        $orgStructureArr[0][] = '';

        foreach ($orgStructureDetails as $key => $orgStructureDetail) {

            $orgStructureArr[$key + 1][] = ['v' => optional($orgStructureDetail->employee)->full_name, 'f' => '<div style="color:black; font-weight:bold; white-space:nowrap">'.optional($orgStructureDetail->employee)->full_name.'</div><div style="color:red; font-style:italic; white-space:nowrap">'.optional(optional($orgStructureDetail->employee)->designation)->title.'</div><div style="color:blue; font-style:italic">['.optional(optional($orgStructureDetail->employee)->organizationModel)->name.']</div>'];

            $orgStructureArr[$key + 1][] = optional($orgStructureDetail->parentEmployee)->full_name;
            $orgStructureArr[$key + 1][] = '';
        }

        // $orgStructureArr = [   
        //     [['v'=>'Mike', 'f'=>'<div style="color:green; font-weight:900; white-space:nowrap">Mike</div><div style="color:red; font-style:italic">President</div><div style="color:blue; font-style:italic">(Bidhee)</div>'], '', 'The President'],
        //     ['Jim', 'Mike', 'VP'],
        //     ['Alice', 'Mike', ''],
        //     ['Bob', 'Jim', 'Bob Sponge'],
        //     [['v'=>'hy', 'f'=>'<div style="color:green; font-weight:900; white-space:nowrap">Ravi Deshraj Joshi Shrestha</div><div style="color:red; font-style:italic">President</div><div style="color:blue; font-style:italic">(Bidhee)</div>'], 'Bob', '']
        // ];

        $data['orgStructureArr'] = $orgStructureArr;
        return view('organizationalstructure::organizational-structure.view-chart', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['is_edit'] = true;
        $data['orgStructure'] = $orgStructure = $this->organizationalStructure->find($id);
        $data['orgStructureDetails'] = $this->organizationalStructure->getOrgStructureDetails($id);
        $editData['root_emp_id'] = $orgStructure['root_employee_id'];
        $data['employeeList'] = $this->employee->getListExceptSelectedEmployee($editData, 1);

        $data['allEmployeeList'] = $this->employee->getList(1);
        return view('organizationalstructure::organizational-structure.edit', $data);
    }

    // /**
    //  * Update the specified resource in storage.
    //  * @param Request $request
    //  * @param int $id
    //  * @return Renderable
    //  */
    public function update(CreateOrgStructureRequest $request, $id)
    {
        $updateData = $request->all();
        try {
            $orgStructure = $this->organizationalStructure->update($id, $updateData);
            if (!empty($updateData['structure_details'])) {

                $orgStructure  = $this->organizationalStructure->find($id);
                $orgStructure->orgStructureDetail()->delete();
                foreach ($updateData['structure_details'] as $detail) {
                    $orgStructureDetailArray = [
                        'org_structure_id' => $id,
                        'employee_id' => $detail['employee_id'],
                        'parent_employee_id' => $detail['parent_employee_id'],
                    ];
                    $orgStructure->orgStructureDetail()->saveMany([new OrganizationalStructureDetail($orgStructureDetailArray)]);
                }

                // $orgStructure  = $this->organizationalStructure->find($id);
                // $orgStructure->orgStructureDetail()->delete();

                // foreach ($updateData['structure_details']['employee_id'] as $key => $detail) {
                //     $parentEmployeeId = $updateData['structure_details']['parent_employee_id'][$key];
                //     if($detail && $parentEmployeeId){
                //         $orgStructuralDetailArray = [
                //             'org_structure_id' => $id,
                //             'employee_id' => $detail,
                //             'parent_employee_id' => $parentEmployeeId,
                //         ];
                //         OrganizationalStructureDetail::create($orgStructuralDetailArray);
                //     }
                // }
            }
            toastr()->success('Organization Structure Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something went wrong');
        }
        return redirect(route('organizationalStructure.index'));
    }

    // /**
    //  * Remove the specified resource from storage.
    //  * @param int $id
    //  * @return Renderable
    //  */
    public function destroy($id)
    {
        try {
            $this->organizationalStructure->deleteOrgStructureDetails($id);
            $this->organizationalStructure->delete($id);
            toastr()->success('Organizational Structure Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }
        return redirect(route('organizationalStructure.index'));
    }

    public function cloneDay(Request $request)
    {
        $data = $request->all();
        $count = $data['count'] + 1;
        $employeeList = $this->employee->getListExceptSelectedEmployee($data, 1);
        $allEmployeeList = $this->employee->getList(1);
        return response()->json([
            'data' => view('organizationalstructure::organizational-structure.partial.clone', compact(['count', 'employeeList', 'allEmployeeList']))->render(),
        ]);
    }

    public function getOtherEmployeeList(Request $request)
    {
        $data = $request->all();
        $otherEmployeeList = $this->employee->getListExceptSelectedEmployee($data, 1);
        return json_encode($otherEmployeeList);
    }
}
