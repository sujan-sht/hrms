<?php

namespace App\Modules\Api\Http\Controllers\OrganizationLatest;

use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\Api\Transformers\OrganizationLatestResource;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Repositories\SettingInterface;
use Dotenv\Exception\ValidationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends ApiController
{
    private $organization;
    private $employee;
    private $setting;

    public function __construct(
        OrganizationInterface $organization,
        EmployeeInterface $employee,
        SettingInterface $setting
        
    ) {
        $this->organization = $organization;
        $this->employee = $employee;
        $this->setting = $setting;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {
            $organizations = $this->organization->findAll();
            $organizationData = OrganizationLatestResource::collection($organizations);
            return $this->respond([
                'status' => true,
                'data' => $organizationData
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('api::create');
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
            $validate = Validator::make(
                $inputData,
                [
                    'name' => 'required',
                    'address' => 'required'
                ]
            );
            if($validate->fails()){
                return $this->respondValidatorFailed($validate);
            }

            $total = $this->organization->findAll()->count();
            if ($total >= 4) {
                return $this->respondUnauthorized('You cannot add more Organizations. Please contact technical team for more detail.',400);
            }

            if ($request->hasFile('image')) {
                $inputData['image'] = $this->organization->upload($inputData['image']);
            }

            if ($request->hasFile('letter_head')) {
                $inputData['letter_head'] = $this->organization->uploadLetterhead($inputData['letter_head']);
            }
            $this->organization->create($inputData);
            return $this->respond([
                'status' => true,
                'message' => 'Organization has been created Successfully',
            ]);
        } catch (\Throwable $th) {
            return $this->respondInvalidQuery($th->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('api::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('api::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'address' => 'required'
            ]
        );
        if ($validate->fails()) {
            return $this->respondValidatorFailed($validate);
        }
        try {
            $data = $request->all();

            if ($request->hasFile('image')) {
                $data['image'] = $this->organization->upload($data['image']);
            }
 
            if ($request->hasFile('letter_head')) {
                $data['letter_head'] = $this->organization->uploadLetterhead($data['letter_head']);
            }
           $organization = $this->organization->findOne($id);
           $organization->update($data);

            return  $this->respond([
                'status' => true,
                'message' => 'Organization has been updated Successfully',
                // 'data' => new OrganizationLatestResource($organization)
            ]);
        } catch (\Throwable $e) {
            return $this->respondWithError($e->getMessage());
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
            $checkEmployee = $this->employee->getEmployeeByOrganization($id);
            if ($checkEmployee->isNotEmpty() && $checkEmployee->count() > 0) {
                return $this->respondUnauthorized('There are employees under this organization so you cannot delete this organization !!!',400);
            } else {
                $this->organization->delete($id);
                return $this->respondObjectDeleted($id);
            }

        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }
    }

    public function createSetting()
    {
        try {
            $data['setting'] = $this->setting->find(1);
            $data['statusList'] = setObjectIdAndName([10 => 'No', 11 => 'Yes']);
            $data['calendarTypeList'] = setObjectIdAndName(['AD'=>'AD','BS'=>"BS"]);

            if ($data['setting'] == null) {
                $data['isEdit'] = false;
                $data['btnType']='Save';
            } else {
                $data['isEdit'] = true;
                $data['btnType']='Update';
            }
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }


    public function storeSetting(Request $request)
    {
        $data = $request->all();
        try{
            if($request->hasFile('company_logo')){
                $data['company_logo'] = $this->setting->upload($data['company_logo']);
            }
            $this->setting->save($data);
            return $this->respond([
                'status' => true,
                'message' => 'Setting has been saved Successfully',
            ]);

        }catch(\Throwable $e){
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function updateSetting(Request $request, $id)
    {
        $data = $request->all();
        try{
            if ($request->hasFile('company_logo')) {
                $data['company_logo'] = $this->setting->upload($data['company_logo']);
            }
            $this->setting->update($id,$data);
            return $this->respond([
                'status' => true,
                'message' => 'Setting has been updated Successfully',
            ]);
        }catch(\Throwable $e){
            return $this->respondWithError($e->getMessage());
        }
    }    
}
