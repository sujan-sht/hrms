<?php

namespace App\Modules\Setting\Http\Controllers;

use App\Modules\Attendance\Entities\AttendanceLog;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Http\Requests\DeviceManagementRequest;
use App\Modules\Setting\Repositories\DeviceManagementInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DeviceManagementController extends Controller
{
    protected $deviceManagement;
    protected $organization;

    public function __construct(
        DeviceManagementInterface $deviceManagement,
        OrganizationInterface $organization
    ) {
        $this->deviceManagement = $deviceManagement;
        $this->organization = $organization;
    }

    public function index(Request $request)
    {
        $filter = $request->all();
        $currentDate = date_format(date_create("2023-06-15"), "Y-m-d");
        $agoDate = date('Y-m-d', strtotime('-7 days'));
        $data['deviceModels'] = $this->deviceManagement->findAll(20, $filter)->map(function ($deviceModel) use ($agoDate) {
            $logs = AttendanceLog::selectRaw('date, COUNT(*) as total')
                ->whereDate('date', '>=', $agoDate)
                ->where([
                    'ip_address' => $deviceModel->ip_address
                ])->orderBy('date')->groupBy('date')->get();
            $deviceModel->logs = json_encode($logs->toArray());


            return $deviceModel;
        });
        return view('setting::device-management.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['organizations'] = $this->organization->findAll()->pluck('name', 'id');
        return view('setting::device-management.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(DeviceManagementRequest $request)
    {
        $data = $request->all();

        try {
            $this->deviceManagement->save($data);

            toastr()->success('Device Management Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('deviceManagement.index'));
    }

    public function storeAjax(Request $request)
    {
        if ($request->ajax()) {

            DB::beginTransaction();
            try {

                $inputData = $request->all();
                $validator = Validator::make($inputData, [
                    'organization_id' => 'required',
                    'ip_address' => 'required',
                    'port' => 'required',
                    'device_id' => 'required',
                    'communication_password' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => $validator->errors()->first(),
                    ]);
                }

                $this->deviceManagement->save($inputData);
                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Device Management Created Successfully'
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error($th->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage() . " Line No: " . $th->getLine()
                ]);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['organizations'] = $this->organization->findAll()->pluck('name', 'id');
        $data['deviceModel'] = $this->deviceManagement->find($id);
        return view('setting::device-management.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(DeviceManagementRequest $request, $id)
    {
        $data = $request->all();

        try {
            $this->deviceManagement->update($id, $data);
            toastr()->success('Device Management Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('deviceManagement.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->deviceManagement->delete($id);

            toastr()->success('Device Management Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('deviceManagement.index'));
    }

    /**
     * Get all device list
     * Used for api request
     */
    public function getDeviceList()
    {
        $data['device'] = $this->deviceManagement->findAllActiveDevice();
        if ($data['device']->count() <= 0) {
            $data['device'] = null;
        }

        return Response()->json($data['device']);
    }
}
