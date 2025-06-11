<?php

namespace App\Modules\Api\Http\Controllers;

use App\Modules\Api\Transformers\EmployeeResource;
use App\Modules\Api\Transformers\OrganizationResource;
use App\Modules\Branch\Entities\Branch;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Services\ApiService;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class OrganizationController extends ApiController
{

    protected $apiService;

    public function __construct(
        ApiService $apiService
    )
    {
        $this->apiService = $apiService;
    }
    public function overview()
    {
        try {
            $userModel = auth()->user();
            $organizationId = optional($userModel->userEmployer)->organization_id;

            $data['branchCount'] = Branch::where('organization_id', $organizationId)->count();
            $data['departmentCount'] = Employee::select('department_id')->where('organization_id', $organizationId)->distinct()->get()->count();
            $data['levelCount'] = Employee::select('level_id')->where('organization_id', $organizationId)->distinct()->get()->count();
            $data['employeeCount'] = Employee::where('organization_id', $organizationId)->where('status', 1)->count();
            $data['organization'] = new OrganizationResource(Organization::find($organizationId));

            return  $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function codeOfConduct()
    {
        try {
            $userModel = auth()->user();

            $organizationId = optional($userModel->userEmployer)->organization_id;
            $data = Organization::find($organizationId);
            return  $this->respond([
                'status' => true,
                'data' => new OrganizationResource($data)
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function directory(Request $request)
    {
        try {
            $userModel = auth()->user();

            $organizationId = optional($userModel->userEmployer)->organization_id;
            $data = Employee::where('status', 1)->where('organization_id', $organizationId)->get();
            return  $this->respond([
                'status' => true,
                'data' => EmployeeResource::collection($data)
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function getRequiredFields(){
        $data['organizations'] = [];
        $data['branches'] = [];
        $data['employees'] = [];


        $organizations = Organization::get();
        $branches = Branch::get();
        $employees = Employee::get();

        foreach($organizations as $organization){
            array_push($data['organizations'],[
                'id' =>  $organization->id,
                'name' => $organization->name
            ]);
        }
        foreach($branches as $branch){
            array_push($data['branches'],[
                'id' =>  $branch->id,
                'organization_id' =>  $branch->organization_id,
                'name' => $branch->name
            ]);
        }
        foreach($employees as $employee){
            array_push($data['employees'],[
                'id' =>  $employee->id,
                'organization_id' =>  $employee->organization_id,
                'branch_id' =>  $employee->branch_id,
                'name' => $employee->full_name
            ]);
        }
        return  $this->respond([
            'status' => true,
            'data' => $data,
            'message' => 'Data Retrieved Succesfully'
        ]);
    }

    public function organizationStore(Request $request){

        // Log::info('info',['data'=>$request->all()]);
        try {
            $data = [];
            $id = $request->input('id');
            $data['name'] = $request->input('name');
            $data['email'] = $request->input('email');
            $data['address'] = $request->input('address');

            Organization::updateOrCreate(['id' => $id], $data);

            // $organization = Organization::create($data);

            return response()->json(['message' => 'Record successfully created or updated', 'status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error while sync Organization: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred', 'status' => 'error'], 500);
        }

    }

    public function storeAllOraganization(Request $request){
        // Log::info('info',['data'=>$request->all()]);
        try {
            $datas =$request->all();
            foreach($datas as $key => $data){
            $org = [];
            // $org['id'] = $request->input('id');
            $org['name'] = $data['company_name'] ?? null;
            $org['email'] = $data['email']?? null;
            $org['address'] = $data['address']?? null;
            $org['phone'] = $data['contact'] ?? null;
            $org['mobile'] = $data['mobile'] ?? null;
            $org['fax'] = $data['fax']?? null;

            Organization::updateOrCreate(['id' => $data['id']], $org);
            // Log::info('info',['error'=>$d]);
            }
            $this->updateSyncFlagOraganizationSetting();

            return response()->json(['message' => 'Record successfully created or updated', 'status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error while sync Organization: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred', 'status' => 'error'], 500);
        }

    }


    public function updateSyncFlagOraganizationSetting(){
        $setting = Setting::find(1);
        $flagOrganization = $setting['flag_organization'];
        if($flagOrganization == 1){
            $setting['flag_organization'] = 0;
            $setting->save();
        }

    }

    public function getAllOrganization(Request $request){
        $setting = Setting::find(1);
        $hostName = $setting['sync_host_name'] ?? null;
        $organization = Organization::get()->toArray();

        $response = $this->apiService->sendAllOrganizationData($organization,$hostName);

        // Log::info('info',['data'=>$organization]);
        // $response = $this->apiService->sendAllOrganizationData($organization,$hostName);
    }

}
