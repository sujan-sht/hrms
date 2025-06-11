<?php

namespace App\Modules\Api\Http\Controllers\Request;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\Api\Transformers\RequestResource;
use App\Modules\Tada\Entities\TadaDetail;
use App\Modules\Tada\Entities\TadaRequest;
use App\Modules\Tada\Entities\TadaRequestDetail;
use App\Modules\Tada\Entities\TadaType;
use App\Modules\Tada\Http\Requests\EmpRequestRequest;
use App\Modules\Tada\Repositories\TadaRequestRepository;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RequestController extends ApiController
{
    public function index(Request $request)
    {
        try {
            $userModel = auth()->user();
            $filter = $request->all();
            $data = TadaRequest::when($filter, function ($query) use ($filter) {
                // if (isset($filter['from_date']) && !empty($filter['from_date'])) {
                //     $query->where('eng_from_date', '>=', $filter['from_date']);
                // }

                if (isset($filter['eng_request_date']) && !empty($filter['eng_request_date'])) {
                    $query->where('eng_request_date', $filter['eng_request_date']);
                }
                if (isset($filter['status']) && !empty($filter['status'])) {
                    $query->where('status', $filter['status']);
                }
                if (isset($filter['title']) && !empty($filter['title'])) {
                    $query->where('title', 'like', '%' . $filter['title'] . '%');
                }
            })
                ->where('employee_id', $userModel->emp_id)
                ->orderBy('id', 'desc')
                ->get();
            return  $this->respond([
                'status' => true,
                'data' => RequestResource::collection($data)
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function getDropDown()
    {
        try {
            $data['statusList'] = setObjectIdAndName(TadaRequest::statusList());
            $data['tadaTypeList'] = TadaType::select('id', 'title as name')->where('status', 1)->where('type', 0)->get();
            return  $this->respond([
                'status' => true,
                'data' => $data
            ]);
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
        $inputData = $request->all();

        $validateUser = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'employee_id' => 'required',
                'eng_request_date' => 'required',
                'request_code' => 'required',
                'details.*.type_id' => 'required',
                'details.*.amount' => 'required'
            ]
        );
        if ($validateUser->fails()) {
            return $this->respondValidatorFailed($validateUser);
        }

        try {

            $date_converter = new DateConverter();
            $inputData['nep_request_date'] = $date_converter->eng_to_nep_convert($inputData['eng_request_date']);

            $inputData['created_by'] = auth()->user()->id;
            $inputData['status'] = 1;

            if (isset($inputData['is_agree']) && $inputData['is_agree'] == 1) {
                $inputData['is_agree'] = 1;
            } else {
                $inputData['is_agree'] = 0;
            }

            $tada = (new TadaRequestRepository())->save($inputData);

            // send notification
            // $this->tada->sendNotification($tada, 'TadaRequest');

            //save tadaRequest details
            $details = [];
            if (isset($inputData['details'])) {
                foreach ($inputData['details'] as $detailData) {
                    $details[] = new TadaRequestDetail([
                        'tada_request_id' => $tada->id,
                        'type_id' => $detailData['type_id'] ?? null,
                        'amount' => $detailData['amount'] ?? 0,
                        'remark' => $detailData['remark'] ?? null,
                    ]);
                }
                if (isset($details)) {
                    $tada->tadaDetails()->saveMany($details);
                }
            }
            // $details = [];
            // if (isset($data['amount'])) {
            //     for ($i = 0; $i < count($data['amount']); $i++) {
            //         if (isset($data['amount'][$i]) && isset($data['type_id'][$i]) && !empty($data['amount'][$i])) {
            //             $details[$i] = new TadaRequestDetail([
            //                 'tada_request_id' => $tada->id,
            //                 'type_id' => $data['type_id'][$i] ?? null,
            //                 'amount' => $data['amount'][$i] ?? 0,
            //                 'remark' => $data['remark'][$i] ?? null,
            //             ]);
            //         }
            //     }

            //     if (isset($details)) {
            //         $tada->tadaDetails()->saveMany($details);
            //     }
            // }
            $resultData['requestDetail'] = $tada;
            return $this->respondSuccess($resultData);
        } catch (QueryException $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function view($id)
    {
        try {
            $claim = TadaRequest::find($id);
            return $this->respond([
                'status' => true,
                'data' => new RequestResource($claim)
            ]);
        } catch (ModelNotFoundException $e) {
            $this->respondWithError($e->getMessage());
        } catch (QueryException $e) {
            $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(REquest $request, $id)
    {
        $inputData = $request->all();
        $validateUser = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'employee_id' => 'required',
                'eng_request_date' => 'required',
                'request_code' => 'required'
            ]
        );
        if ($validateUser->fails()) {
            return $this->respondValidatorFailed($validateUser);
        }

        try {
            $date_converter = new DateConverter();
            $inputData['nep_request_date'] = $date_converter->eng_to_nep_convert($inputData['eng_request_date']);

            $inputData['updated_by'] = auth()->user()->id;

            if (isset($inputData['is_agree']) && $inputData['is_agree'] == 1) {
                $inputData['is_agree'] = 1;
            } else {
                $inputData['is_agree'] = 0;
            }

            $tada = TadaRequest::find($id);
            $tada->update($inputData);
            
            $tada->tadaDetails()->delete();

            //save tada details
            $details = [];
            if (isset($inputData['details'])) {
                foreach ($inputData['details'] as $detailData) {
                    $details[] = new TadaRequestDetail([
                        'tada_request_id' => $tada->id,
                        'type_id' => $detailData['type_id'] ?? null,
                        'amount' => $detailData['amount'] ?? 0,
                        'remark' => $detailData['remark'] ?? null,
                    ]);
                }
                if (isset($details)) {
                    $tada->tadaDetails()->saveMany($details);
                }
            }

            $resultData['requestDetail'] = $tada;
            return $this->respondSuccess($resultData);
        } catch (QueryException $e) {
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
            $request = TadaRequest::find($id);
            $request->delete();
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }

        return $this->respondObjectDeleted($id);
    }
}
