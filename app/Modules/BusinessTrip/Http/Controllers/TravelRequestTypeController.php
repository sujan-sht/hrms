<?php

namespace App\Modules\BusinessTrip\Http\Controllers;

use App\Helpers\DateTimeHelper;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\BusinessTrip\Entities\BusinessTrip;
use App\Modules\BusinessTrip\Entities\BusinessTripAllowanceSetup;
use App\Modules\BusinessTrip\Entities\TravelRequestType;
use App\Modules\BusinessTrip\Repositories\BusinessTripInterface;
use App\Modules\BusinessTrip\Repositories\TravelRequestTypeInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Organization\Repositories\OrganizationInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PDF;

class TravelRequestTypeController extends Controller
{
    private $employee;
    private $travelRequestType;
    private $organization;
    private $branch;


    public function __construct(
        EmployeeInterface $employee,
        TravelRequestTypeInterface $travelRequestType,
        OrganizationInterface $organization,
        BranchInterface $branch
    ) {
        $this->employee = $employee;
        $this->travelRequestType = $travelRequestType;
        $this->organization = $organization;
        $this->branch = $branch;
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
        $data['statusList'] =  TravelRequestType::STATUS;
        $data['travelRequestTypes'] = $this->travelRequestType->findAll(25, $filter, $sort);
        return view('businesstrip::travel-request-type.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['statusList'] =  TravelRequestType::STATUS;
        return view('businesstrip::travel-request-type.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        try {
            $data = $request->except('_token');
            $this->travelRequestType->save($data);
          
            toastr('Travel Request Type stored successfully', 'success');
        } catch (\Throwable $th) {
            toastr('Error While Adding Travel Request Type', 'error');
        }
        return redirect()->route('travelRequestType.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data['travelRequestType'] = $this->travelRequestType->find($id);
        return view('businesstrip::travel-request-type.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['statusList'] =  TravelRequestType::STATUS;
        $data['travelRequestType'] = $this->travelRequestType->find($id);
        return view('businesstrip::travel-request-type.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            $this->travelRequestType->update($id, $data);
            toastr('Travel Request Type Updated Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Updating Travel Request', 'error');
        }
        return redirect()->route('travelRequestType.index');
    }

    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $isDelete = $this->travelRequestType->delete($id);
            if($isDelete){
                BusinessTrip::where('type_id', $id)->delete();
                BusinessTripAllowanceSetup::where('type_id', $id)->delete();
            }
            toastr('Travel Request Type Deleted Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Deleting Travel Request', 'error');
        }
        return redirect()->route('travelRequestType.index');
    }
}
