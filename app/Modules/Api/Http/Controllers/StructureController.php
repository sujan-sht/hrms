<?php

namespace App\Modules\Api\Http\Controllers;

use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\Api\Transformers\StructureResource;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\OrganizationalStructure\Entities\OrganizationalStructureDetail;
use App\Modules\OrganizationalStructure\Repositories\OrganizationalStructureInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StructureController extends ApiController
{
    private $structure;
    public function __construct(
        OrganizationalStructureInterface $structure
    ) {
        $this->structure = $structure;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {
            $structures = $this->structure->findAll();
            $structureData = StructureResource::collection($structures);
            return $this->respond([
                'status' => true,
                'data' => $structureData
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function getDropdown()
    {
        try {
            $data['employeeList'] = setObjectIdAndName((new EmployeeRepository)->getList(1));
            return  $this->respond(['data' => $data]);
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
        $validate = Validator::make(
            $inputData,
            [
                'title' => 'required',
                'root_employee_id' => 'required',
            ]
        );
        if($validate->fails()){
            return $this->respondValidatorFailed($validate);
        }
        try {
            $inputData['created_by'] = auth()->user()->id;
            $organizationalStructure = $this->structure->save($inputData);

            $orgStructuralDetailArray = [];
            foreach ($inputData['structure_details'] as $detail) {
                $orgStructuralDetailArray = [
                    'org_structure_id' => $organizationalStructure->id,
                    'employee_id' => $detail['employee_id'],
                    'parent_employee_id' => $detail['parent_employee_id'],
                ];
                OrganizationalStructureDetail::create($orgStructuralDetailArray);
            }
            return $this->respond([
                'status' => true,
                'message' => 'Organizational Structure has been created Successfully',
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
                'title' => 'required',
                'root_employee_id' => 'required',
            ]
        );
        if ($validate->fails()) {
            return $this->respondValidatorFailed($validate);
        }
        try {
            $data = $request->all();
            $data['updated_by'] = auth()->user()->id;

            $orgStructure = $this->structure->update($id, $data);
            if (!empty($data['structure_details'])) {

                $orgStructure  = $this->structure->find($id);
                $orgStructure->orgStructureDetail()->delete();
                foreach ($data['structure_details'] as $detail) {
                    $orgStructureDetailArray = [
                        'org_structure_id' => $id,
                        'employee_id' => $detail['employee_id'],
                        'parent_employee_id' => $detail['parent_employee_id'],
                    ];
                    $orgStructure->orgStructureDetail()->saveMany([new OrganizationalStructureDetail($orgStructureDetailArray)]);
                }
            }

            return  $this->respond([
                'status' => true,
                'message' => 'Organizational Structure has been updated Successfully',
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
        
    }
}
