<?php

namespace App\Modules\Offboarding\Http\Controllers;

use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Offboarding\Entities\OffboardClearanceResponsible;
use App\Modules\Offboarding\Entities\OffboardEmployeeClearance;
use App\Modules\Offboarding\Repositories\OffboardClearanceInterface;
use App\Modules\Offboarding\Repositories\OffboardResignationInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OffboardClearanceController extends Controller
{
    private $organizationObj;
    private $employeeObj;
    private $clearanceObj;
    private $resignationObj;

    /**
     * Constructor
     */
    public function __construct(
        OrganizationInterface $organizationObj,
        EmployeeInterface $employeeObj,
        OffboardClearanceInterface $clearanceObj,
        OffboardResignationInterface $resignationObj
    ) {
        $this->organizationObj = $organizationObj;
        $this->employeeObj = $employeeObj;
        $this->clearanceObj = $clearanceObj;
        $this->resignationObj = $resignationObj;
    }

    public function index(Request $request)
    {
        $filter = $request->all();
        // dd($filter);
        if(auth()->user()->user_type == 'division_hr') {
            $filter['organization'] = optional(auth()->user()->userEmployer)->organization_id;
        }

        $data['clearanceModels'] = $this->clearanceObj->findAll(20, $filter);
        return view('offboarding::clearance.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        // $data['employeeList'] = $this->employeeObj->getList();
        $data['organizationList'] = $this->organizationObj->getList();
        return view('offboarding::clearance.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData = $request->all();
        try {
            $clearance = $this->clearanceObj->create($inputData);

            if (isset($inputData['organization_id'])) {
                for ($i = 0; $i < count($inputData['organization_id']); $i++) {
                    $details = [
                        'offboard_clearance_id' =>  $clearance->id,
                        'organization_id' => $inputData['organization_id'][$i],
                        'employee_id' => $inputData['employee_id'][$i],
                    ];
                    $clearance->clearanceResponsible()->create($details);
                }
            }

            toastr()->success('Data Created Successfully');
        } catch (\Throwable $e) {
            // dd($e);
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('clearance.index'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data['clearanceModel'] = $this->clearanceObj->findOne($id);
        // dd( $data['clearanceModel']);
        return view('offboarding::clearance.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['clearanceModel'] = $this->clearanceObj->findOne($id);
        // $data['employeeList'] = $this->employeeObj->getList();
        $data['organizationList'] = $this->organizationObj->getList();
        return view('offboarding::clearance.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $inputData = $request->all();
        // dd($inputData);
        $clearance = $this->clearanceObj->findOne($id);
        // $clearance->clearanceResponsible()->delete($id);

        try {
            $this->clearanceObj->update($id, $inputData);

            OffboardClearanceResponsible::where('offboard_clearance_id', $id)->delete();
            // dd($clearance);


            if (isset($inputData['organization_id'])) {
                for ($i = 0; $i < count($inputData['organization_id']); $i++) {
                    $details = [
                        'offboard_clearance_id' =>  $clearance->id,
                        'organization_id' => $inputData['organization_id'][$i],
                        'employee_id' => $inputData['employee_id'][$i],
                    ];
                    $clearance->clearanceResponsible()->create($details);
                }
            }

            toastr()->success('Clearance Updated Successfully');
        } catch (\Throwable $e) {
            // dd($e);
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('clearance.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->clearanceObj->delete($id);
            toastr()->success('Data Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    public function getRepeaterForm(Request $request)
    {
        // $data['employeeList'] = $this->employeeObj->getList();
        $data['organizationList'] = $this->organizationObj->getList();
        $data['employeeList'] = [];
        $data['count'] = 1;
        $view = view('offboarding::clearance.partial.repeater-form', $data)->render();
        return response()->json(['result' => $view]);
    }

    public function showemployeeClearance($id)
    {
        $data['clearanceModel'] = $this->clearanceObj->findOne($id);
        $reg_id = $_GET['reg_id'];
        $data['resignationModel'] = $this->resignationObj->findone($reg_id);
        $data['$employeeClearance'] = OffboardEmployeeClearance::where('offboard_clearance_id', $id)->where('offboard_resignation_id', $reg_id)->where('offboard_clearance_responsible_id', $_GET['responsible_id'])->first();
        $data['user_id'] = auth()->user()->id;
        return view('offboarding::clearance.show-employee', $data);
    }

    public function storeEmployeeClearance(Request $request)
    {
        $inputData = $request->all();
        try {
            if (isset($inputData['status'])) {
                if (count($inputData['status']) > 0) {
                    foreach ($inputData['status'] as $employeeId => $value) {
                        $offboardClearanceData['offboard_clearance_id'] = $inputData['offboard_clearance_id'][$employeeId];
                        $offboardClearanceData['offboard_clearance_responsible_id'] = $inputData['offboard_clearance_responsible_id'][$employeeId];
                        $offboardClearanceData['employee_id'] = $inputData['employee_id'][$employeeId];
                        $offboardClearanceData['offboard_resignation_id'] = $inputData['offboard_resignation_id'][$employeeId];
                        $offboardClearanceData['status'] = $inputData['status'][$employeeId];

                        $employeeClearance = OffboardEmployeeClearance::where('employee_id', $inputData['employee_id'][$employeeId])->where('offboard_clearance_id', $inputData['offboard_clearance_id'][$employeeId])->where('offboard_clearance_responsible_id', $inputData['offboard_clearance_responsible_id'][$employeeId])->where('offboard_resignation_id', $inputData['offboard_resignation_id'][$employeeId])->first();
                        if ($employeeClearance) {
                            $employeeClearance->update($offboardClearanceData);
                        } else {
                            $this->clearanceObj->createEmployeeClearance($offboardClearanceData);
                        }
                    }
                }
            }
            toastr()->success('Employee Clearance Updated Successfully');
        } catch (\Throwable $e) {
            // dd($e);
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('resignation.index'));
    }
}
