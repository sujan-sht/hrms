<?php

namespace App\Modules\Api\Http\Controllers;

use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\Api\Transformers\BranchResource;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Organization\Repositories\OrganizationRepository;
use Dotenv\Exception\ValidationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchController extends ApiController
{
    private $branch;
    public function __construct(
        BranchInterface $branch
    ) {
        $this->branch = $branch;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {
            $branches = $this->branch->findAll();
            $branchData = BranchResource::collection($branches);
            return $this->respond([
                'status' => true,
                'data' => $branchData
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function getDropdown()
    {
        try {
            $data['organizationList'] =setObjectIdAndName((new OrganizationRepository)->getList());
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
        try {
            $validate = Validator::make(
                $inputData,
                [
                    'organization_id' => 'required',
                    'name' => 'required',
                ]
            );
            if($validate->fails()){
                return $this->respondValidatorFailed($validate);
            }
            $inputData['created_by'] = auth()->user()->id;
            $this->branch->create($inputData);
            return $this->respond([
                'status' => true,
                'message' => 'Branch has been created Successfully',
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
                'organization_id' => 'required',
                'name' => 'required',
            ]
        );
        if ($validate->fails()) {
            return $this->respondValidatorFailed($validate);
        }
        try {
            $data = $request->all();
            $data['updated_by'] = auth()->user()->id;

           $branch = $this->branch->findOne($id);
           $branch->update($data);

            return  $this->respond([
                'status' => true,
                'message' => 'Branch has been updated Successfully',
                // 'data' => new BranchResource($branch)
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
            $branch = $this->branch->findOne($id);
            $branch->delete();
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }

        return $this->respondObjectDeleted($id);
    }
}
