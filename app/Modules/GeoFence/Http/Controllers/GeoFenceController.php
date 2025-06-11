<?php

namespace App\Modules\GeoFence\Http\Controllers;

use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\GeoFence\Entities\GeoFence;
use App\Modules\GeoFence\Repositories\GeoFenceInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Repositories\DepartmentInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GeoFenceController extends Controller
{
    protected $geoFence;
    private $organization;
    private $employee;
    private $dropdown;
    private $branch;
    private $department;


    public function __construct(GeoFenceInterface $geoFence, OrganizationInterface $organization, EmployeeInterface $employee, DropdownInterface $dropdown, BranchInterface $branch, DepartmentInterface $department)
    {
        $this->geoFence = $geoFence;
        $this->organization = $organization;
        $this->employee = $employee;
        $this->dropdown = $dropdown;
        $this->branch = $branch;
        $this->department = $department;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data['geofences'] = $this->geoFence->findAll();
        return view('geofence::index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('geofence::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $datas = $request->multi;

            foreach ($datas as $key => $data) {
                $this->geoFence->save($data);
            }
            toastr('GeoFence Locations added!', 'success');
            return redirect()->route('geoFence.index');
        } catch (Exception $e) {
            toastr('Something went wrong!', 'error');
            return redirect()->back();
        }
    }

    public function storeAjax(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->multi;
            foreach ($data as  $data) {
                $this->geoFence->save($data);
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'GeoFence Locations added!',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Store GeoFence Error: " . $th->getMessage());
            return response()->json([
                'status' => false,
                'message' => $th->getMessage() . " Line No: " . $th->getLine(),
            ]);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('geofence::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['geoFence'] = $this->geoFence->find($id);
        return view('geofence::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->except('_token', 'location_name');
        try {
            $this->geoFence->update($id, $data);
            toastr('GeoFence updated successfully!', 'success');
            return redirect()->route('geoFence.index');
        } catch (Exception $e) {
            toastr('Something went wrong!', 'error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $geofence = $this->geoFence->delete($id);
            toastr('GeoFence Location Deleted Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Deleting GeoFence Location', 'error');
        }
        return redirect()->back();
    }

    public function allocationList($id)
    {

        $data['geofenceAllocations'] = $this->geoFence->allocationList($id);
        $data['geofence_id'] = $id;
        return view('geofence::allocation.index', $data);
    }
    public function allocateForm($id)
    {
        $data['geofence_id'] = $id;
        $geofence = $this->geoFence->findAllocation($id);
        $data['geofenceAllocations'] = [];
        if (isset($geofence)) {
            $data['geofenceAllocations'] = $geofence->geofenceAllocation;
        }
        $data['isEdit'] = false;
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['departmentList'] = $this->department->getList();
        $data['employeeList'] = [];
        return view('geofence::allocation.create', $data);
    }

    public function filterOrgDepartmentwise(Request $request)
    {
        try {
            $employees = Employee::getEmployeesOrganizationDepartmentwise($request->organization_id, $request->department_id, $request->branch_id);
            return json_encode($employees);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function checkExists(Request $request)
    {
        $data = $request->all();
        try {
            return $this->geoFence->checkAllocationExists($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function allocate(Request $request, $id)
    {
        $inputData = $request->except('_token');
        try {
            $this->geoFence->geofenceAllocation($inputData);
            toastr()->success('Geofence Allocated Successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong !!!');
        }
        return redirect()->route('geoFence.allocationList', $id);
    }

    public function editAllocation($geofence_id, $id)
    {
        $data['geoFenceAllocation'] = $this->geoFence->findAllocation($id);
        $data['geofence_id'] = $geofence_id;
        $data['isEdit'] = true;

        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['departmentList'] = $this->department->getList();
        $data['employeeList'] = $this->employee->getList();
        return view('geofence::allocation.edit', $data);
    }

    public function updateAllocation(Request $request, $geofence_id, $id)
    {
        $data = $request->except('_token');
        try {
            $this->geoFence->updateAllocation($id, $data);
            toastr('GeoFence Allocation updated successfully!', 'success');
        } catch (Exception $e) {
            toastr('Something went wrong!', 'error');
        }
        return redirect()->route('geoFence.allocationList', $geofence_id);
    }

    public function destroyAllocation($geofence_id, $id)
    {
        try {
            $this->geoFence->destroyAllocation($id);
            toastr()->success('Geofence Allocation Deleted Successfully');
        } catch (\Throwable $th) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    public function cloneDay(Request $request)
    {
        $data = $request->all();
        $count = $data['count'] + 1;
        $employeeList = [];
        $departmentList = $this->department->getList();

        return response()->json([
            'data' => view('geofence::allocation.partial.clone', compact(['count', 'departmentList', 'employeeList']))->render(),
        ]);
    }
}
