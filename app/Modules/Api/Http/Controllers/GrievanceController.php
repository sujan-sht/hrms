<?php

namespace App\Modules\Api\Http\Controllers;

use App\Modules\Api\Service\Grievance\GrievanceService;
use App\Modules\Api\Transformers\GrievanceResource;
use App\Modules\Dropdown\Repositories\DropdownRepository;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\Grievance\Entities\Grievance;
use App\Modules\Grievance\Entities\GrievanceEmployee;
use App\Modules\Grievance\Entities\GrievanceMeta;
use App\Modules\Grievance\Repositories\GrievanceRepository;
use App\Modules\Organization\Repositories\OrganizationRepository;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GrievanceController extends ApiController
{
    /**
     * 
     */
    public function dropdown() {
        try {
            $grievanceService = new GrievanceService();
            $data['remainAnonymous'] = setObjectIdAndName($grievanceService->remainAnonymous());
            $data['subjectTypeList'] = setObjectIdAndName($grievanceService->subjectTypeList());
            $data['misconductTypeList'] = setObjectIdAndName($grievanceService->misconductTypeList());
            $data['isWitness'] = setObjectIdAndName($grievanceService->isWitness());

            $data['employeeList'] = setObjectIdAndName((new EmployeeRepository())->getList());
            $data['divisionTypeList']=setObjectIdAndName((new OrganizationRepository())->getList());
            $data['designationList'] = setObjectIdAndName((new DropdownRepository())->getFieldBySlug('designation'));
            $data['departmentList'] = setObjectIdAndName((new DropdownRepository())->getFieldBySlug('department'));
            
            return $this->respondSuccess($data);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {
            $userModel = Auth::user();
            $grievances = Grievance::when(true, function ($query) use ($userModel) {                    
                if (auth()->user()->user_type == 'hr') {
                    $query->whereHas('user.userEmployer', function ($query) use ($userModel) {
                        $orgn_id = optional($userModel->userEmployer)->organization_id;
                        $query->where('organization_id', $orgn_id);
                    });
                    $query->orWhere('created_by', 1);
                }
                elseif (auth()->user()->user_type == 'division_hr') {
                    $query->whereHas('user.userEmployer', function ($query) use ($userModel) {
                        $orgn_id = optional($userModel->userEmployer)->organization_id;
                        $query->where('organization_id', $orgn_id);
                    });
                } else {
                    $query->where('created_by', $userModel->id);
                }
            })
            ->orderBy('id', 'desc')
            ->get();

            $data = GrievanceResource::collection($grievances);
            return $this->respondSuccess($data);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function store(Request $request) {
        $inputData = $request->all();
        try {
            $validate = Validator::make(
                $request->all(),
                [
                    'is_anonymous' => 'required',
                    'subject_type' => 'required',
                ]
            );
            if ($validate->fails()) {
                return $this->respondValidatorFailed($validate);
            }
            
            $inputData['created_by'] = Auth::user()->id;
            if ($request->hasFile('attachment')) {
                $inputData['attachment'] = (new GrievanceRepository())->upload($request->attachment);
            }

            $grievance = (new GrievanceRepository())->save($inputData);

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

            return $this->respond([
                'status' => true,
                'message' => 'Grievance Created Successfully',
                'data' => $grievance
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
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
}
