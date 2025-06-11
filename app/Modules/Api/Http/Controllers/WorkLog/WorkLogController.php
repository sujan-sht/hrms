<?php

namespace App\Modules\Api\Http\Controllers\WorkLog;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Modules\Worklog\Entities\Worklog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Api\Transformers\WorkLogResource;
use App\Modules\Api\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Dropdown\Repositories\DropdownRepository;
use App\Modules\Worklog\Entities\WorklogDetail;

class WorkLogController extends ApiController
{
    public function getDropDown()
    {
        try {
            $statusList = Worklog::STATUS;
            unset($statusList[6]);
            $data['statusList'] = setObjectIdAndName($statusList);
            $data['projectList'] = setObjectIdAndName((new DropdownRepository())->getFieldBySlug('project'));
            return  $this->respondSuccess($data);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $filter = $request->all();

        $authUser = auth()->user();

        try {
            $data = null;
            $employeeId = $authUser->emp_id;

            $query = Worklog::query();

            if (isset($filter['from_date']) && isset($filter['to_date'])) {
                $query->where('date', '>=', $filter['from_date']);
                $query->where('date', '<=', $filter['to_date']);
            }

            if(isset($employeeId)) {
                $query->whereHas('workLogDetail', function ($qry) use ($employeeId) {
                    $qry->where('employee_id', $employeeId);
                });
            }

            $workLogModels = $query->orderBy('date', 'DESC')->get();

            $workLogDetail = [];
            if(count($workLogModels) > 0) {
                foreach ($workLogModels as $workLogModel) {
                    foreach ($workLogModel->workLogDetail as $workLogDetailModel) {
                        $workLogDetail[] = $workLogDetailModel->array_data;
                    }
                }
            } 
            
            $filtered = $collection = collect($workLogDetail);
            if(isset($filter['status'])) {
                $filtered = $collection->where('status', $filter['status'])->values();
            }
            
            $data['workLogDetail'] = $filtered;
            return $this->respondSuccess($data);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                // 'project_id' => 'required',
                'hours' => 'required',
                'status' => 'required',
                'date' => 'required',
            ]
        );

        if ($validate->fails()) {
            return $this->respondValidatorFailed($validate);
        }

        try {
            $data['employee_id'] = optional(auth()->user()->userEmployer)->id;
            $worklog = Worklog::create($data);
            if($worklog) {
                $workLogDetailData = $data;
                $workLogDetailData['worklog_id'] = $worklog->id;
                $workLogDetailModel = WorklogDetail::create($workLogDetailData);
            }

            return $this->respond([
                'status' => true,
                'message' => 'Work log Created Successfully',
                'data' => $workLogDetailModel->array_data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function view($id)
    {
        try {
            $activUserModel = auth()->user();
            $workLogDetailModel = WorklogDetail::where('employee_id', $activUserModel->emp_id)->findOrFail($id);

            return $this->respond([
                'status' => true,
                'message' => 'Executed Successfully.',
                'data' => $workLogDetailModel->array_data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function edit($id, Request $request)
    {
        $inputData = $request->all();

        try {
            $activUserModel = auth()->user();
            $workLogDetailModel = WorklogDetail::where('employee_id', $activUserModel->emp_id)->find($id);
            $result = $workLogDetailModel->update($inputData);
            if($result) {
                $model = Worklog::where('id', $workLogDetailModel->worklog_id)->first();
                $model->date = $inputData['date'];
                $model->save();
            }
            return $this->respond([
                'status' => true,
                'message' => 'Work log Updated Successfully',
                'data' => $workLogDetailModel->array_data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound($e->getMessage());
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
            $activUserModel = auth()->user();
            WorklogDetail::where('employee_id', $activUserModel->emp_id)->find($id)->delete();
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }

        return $this->respond([
            'status' => true,
            'message' => 'Deleted Successfully.',
            'data' => null
        ]);
    }
}
