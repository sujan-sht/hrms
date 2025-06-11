<?php

namespace App\Modules\Api\Http\Controllers;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeePayrollRelatedDetail;
use App\Modules\Employee\Services\ApiService;
use App\Modules\Setting\Repositories\SettingInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class EmployeeController extends ApiController
{

    protected $apiService;
    protected $setting;


    public function __construct(
        ApiService $apiService,
        SettingInterface $setting

    )
    {
        $this->apiService = $apiService;
        $this->setting = $setting;

    }

    /**
     * save employee data, come from erp
     *
     */
    public function employeeStore(Request $request){
        // Log::info('info',['data'=>$request->all()]);
        try {
            $data = [];

            $data['organization_id'] = $request->input('organization_id');
            $data['first_name'] = $request->input('first_name');
            $data['middle_name'] = $request->input('middle_name');
            $data['last_name'] = $request->input('last_name');
            $data['signature'] = $request->input('signature');
            $data['phone'] = $request->input('phone');
            $data['mobile'] = $request->input('mobile');
            $data['department_id'] = $request->input('department_id');
            $data['job_title'] = $request->input('job_title');
            $data['gender'] = $request->input('gender');
            $data['status'] = $request->input('status');
            $data['dob'] = $request->input('dob');
            $data['probation_status'] = $request->input('probation_period');
            $data['is_user_access'] = $request->input('is_user_access');
            $data['join_date'] = $request->input('join_date');

            Employee::updateOrCreate(['id' => $request->input('id')], $data);

            $this->EmployeePayrollRelatedDetailStore($request->input('id'), $data);

            return response()->json(['message' => 'Record successfully created or updated', 'status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error while sync Organization: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred', 'status' => 'error'], 500);
        }
    }

    /**
     * save employee all data, come from erp
     *
     */
    public function storeAllEmployee(Request $request){
        try {
            $datas =$request->all();
            foreach($datas as $key => $data){
                $emp = [];
                $emp['organization_id'] = $data['organization_id']??null;
                $emp['first_name'] = $data['first_name']??null;
                $emp['middle_name'] = $data['middle_name']??null;
                $emp['last_name'] = $data['last_name']??null;
                $emp['signature'] = $data['signature'] ?? null;
                $emp['phone'] = $data['phone']??null;
                $emp['mobile'] = $data['mobile']??null;
                $emp['department_id'] = $data['department_id']??null;
                $emp['job_title'] = $data['job_title']??null;
                $emp['gender'] = $data['gender']??null;
                $emp['status'] = $data['status']??null;
                $emp['dob'] = $data['dob']??null;
                $emp['is_user_access'] = $data['is_user_access']??null;
                // $emp['probation_status'] = $data['is_probation_period']??0;
                // $emp['join_date'] = $data['join_date']??null;
                // $emp['contract_type'] = $data['contract_type']??null;

                Employee::updateOrCreate(['id' => $data['id']], $emp);

                // $this->EmployeePayrollRelatedDetailStore($request->input('id'), $data);
            }
            $this->updateSyncFlagEmployeeSetting();

            return response()->json(['message' => 'Record successfully created or updated', 'status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error while sync Organization: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred', 'status' => 'error'], 500);
        }
    }

    public function EmployeePayrollRelatedDetailStore($employeeId, $data){
        $model = EmployeePayrollRelatedDetail::where('employee_id', $employeeId)->first();
        $emp = [];
        $emp['contract_type'] = 11;
        // $emp['probation_status'] = ($data['probation_status']== 1)?11:10;
        $emp['probation_status'] = 10;
        $emp['join_date'] = $data['join_date'];
        if ($model) {
            $model->update($data);
            EmployeePayrollRelatedDetail::find($model->id);
        } else {
            $data['employee_id'] = $employeeId;
            EmployeePayrollRelatedDetail::create($data);
        }

    }

    public function updateSyncFlagEmployeeSetting(){
        $setting = $this->setting->find(1);
        $flagEmployee = $setting['flag_employee'];
        if($flagEmployee == 1){
            $setting['flag_employee'] = 0;
            $setting->save();
        }

    }
    /**
     * send all employee hrms to erp
     *
     */
    public function getAllEmployee(){

        $setting = $this->setting->find(1);
        $hostName = $setting['sync_host_name'] ?? null;
        $employee = Employee::get()->toArray();
        $response = $this->apiService->sendAllEmployeeData($employee,$hostName);
    }

}
