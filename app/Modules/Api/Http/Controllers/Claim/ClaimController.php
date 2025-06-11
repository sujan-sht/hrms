<?php

namespace App\Modules\Api\Http\Controllers\Claim;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\Api\Transformers\ClaimResource;
use App\Modules\Api\Transformers\EmployeeResource;
use App\Modules\Tada\Entities\Tada;
use App\Modules\Tada\Entities\TadaDetail;
use App\Modules\Tada\Entities\TadaType;
use App\Modules\Tada\Repositories\BillRepository;
use App\Modules\Tada\Repositories\TadaInterface;
use App\Modules\Tada\Repositories\TadaRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ClaimController extends ApiController
{
    protected $claimObj;

    public function __construct(
        TadaInterface $claimObj
    ) {
        $this->claimObj = $claimObj;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {
            $userModel = auth()->user();
            $filter = $request->all();
            $claimList = Tada::when($filter, function ($query) use ($filter) {
                if (isset($filter['from_date']) && !empty($filter['from_date'])) {
                    $query->where('eng_from_date', '>=', $filter['from_date']);
                }
                if (isset($filter['to_date']) && !empty($filter['to_date'])) {
                    $query->where('eng_to_date', '<=', $filter['to_date']);
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
                'data' => ClaimResource::collection($claimList),
                
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function getDropDown()
    {
        try {
            $data['statusList'] = setObjectIdAndName(Tada::statusList());
            $data['tadaTypeList'] = TadaType::select('id', 'title as name')->where('status', 1)->where('type', 1)->get();
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

        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'eng_from_date' => 'required',
                    'eng_to_date' => 'required',
                    'details.*.type_id' => 'required',
                    'details.*.amount' => 'required'
                ]
            );
            if ($validateUser->fails()) {
                return $this->respondValidatorFailed($validateUser);
            }

            $date_converter = new DateConverter();
            $from_date = explode('-', $inputData['eng_from_date']);
            $to_date = explode('-', $inputData['eng_to_date']);

            $start_date_eng = $date_converter->eng_to_nep($from_date[0], $from_date[1], $from_date[2]);
            $inputData['nep_from_date'] = $start_date_eng['year'] . '-' . sprintf("%02d", $start_date_eng['month']) . '-' . sprintf("%02d", $start_date_eng['date']);

            $end_date_eng = $date_converter->eng_to_nep($to_date[0], $to_date[1], $to_date[2]);
            $inputData['nep_to_date'] = $end_date_eng['year'] . '-' . sprintf("%02d", $end_date_eng['month']) . '-' . sprintf("%02d", $end_date_eng['date']);

            $inputData['created_by'] = auth()->user()->id;
            $inputData['status'] = 1;

            if ($request->hasFile('excel_file')) {
                $inputData['excel_file'] = (new TadaRepository())->uploadExcel($inputData['excel_file']);
            }
            //Multiple Bills
            if ($request->hasFile('bills')) {
                $bill_files = $request->file('bills');
                $bill_images = (new BillRepository())->uploadBills($bill_files);
            }
            
            $tada = (new TadaRepository())->save($inputData);
            // send notification
            // (new TadaRepository())->sendNotification($tada, 'Tada');

            if (isset($bill_images)) {
                (new BillRepository())->saveBills($bill_images, $tada->id);
            }

            //save tada details
            $details = [];
            if (isset($inputData['details'])) {
                foreach ($inputData['details'] as $detailData) {
                    $details[] = new TadaDetail([
                        'type_id' => $detailData['type_id'] ?? null,
                        'amount' => $detailData['amount'] ?? 0,
                        'remark' => $detailData['remark'] ?? null,
                    ]);
                }
                if (isset($details)) {
                    $tada->tadaDetails()->saveMany($details);
                }
            }

            // if (isset($inputData['amount'])) {
            //     for ($i = 0; $i < count($inputData['amount']); $i++) {

            //         if (isset($inputData['amount'][$i]) && isset($inputData['type_id'][$i]) && !empty($inputData['amount'][$i])) {

            //             $details[$i] = new TadaDetail([
            //                 'type_id' => $inputData['type_id'][$i] ?? null,
            //                 'amount' => $inputData['amount'][$i] ?? 0,
            //                 'remark' => $inputData['remark'][$i] ?? null,
            //             ]);
            //         }
            //     }

            //     if (isset($details)) {
            //         $tada->tadaDetails()->saveMany($details);
            //     }
            // }

            return $this->respond([
                'status' => true,
                'message' => 'Claim Details Created Successfully',
                'data' => new ClaimResource($tada)
            ]);
        } catch (QueryException $e) {
            $this->respondWithError($e->getMessage());
        }
    }

    public function storeAttachment(Request $request)
    {
        $inputData = $request->all();

        try {
            $validated = Validator::make(
                $request->all(),
                [
                    'id' => 'required',
                ]
            );
            if ($validated->fails()) {
                return $this->respondValidatorFailed($validated);
            }

            if ($request->hasFile('excel_file')) {
                $inputData['excel_file'] = $this->claimObj->uploadExcel($inputData['excel_file']);
            }

            // Multiple Bills
            if ($request->hasFile('bills')) {
                $bill_files = $inputData['bills'];
                $bill_images = (new BillRepository())->uploadBills($bill_files);
            }
            
            $this->claimObj->update($inputData['id'], $inputData);

            if (isset($bill_images)) {
                (new BillRepository())->saveBills($bill_images, $inputData['id']);
            }

            $claimModel = Tada::find($inputData['id']);
            $data = [
                'claimDetail' => new ClaimResource($claimModel),
            ];

            return $this->respondSuccess($data);
        } catch (QueryException $e) {
            $this->respondWithError($e->getMessage());
        }
    }

    public function view($id)
    {
        try {
            $claim = Tada::find($id);
            return $this->respond([
                'status' => true,
                'data' => new ClaimResource($claim)
            ]);
        } catch (ModelNotFoundException $e) {
            $this->respondWithError($e->getMessage());
        } catch (QueryException $e) {
            $this->respondWithError($e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request, $id)
    {
        try {
            $inputData = $request->all();

            $date_converter = new DateConverter();
            $from_date = explode('-', $inputData['eng_from_date']);
            $to_date = explode('-', $inputData['eng_to_date']);

            $start_date_eng = $date_converter->eng_to_nep($from_date[0], $from_date[1], $from_date[2]);
            $inputData['nep_from_date'] = $start_date_eng['year'] . '-' . sprintf("%02d", $start_date_eng['month']) . '-' . sprintf("%02d", $start_date_eng['date']);

            $end_date_eng = $date_converter->eng_to_nep($to_date[0], $to_date[1], $to_date[2]);
            $inputData['nep_to_date'] = $end_date_eng['year'] . '-' . sprintf("%02d", $end_date_eng['month']) . '-' . sprintf("%02d", $end_date_eng['date']);

            $inputData['updated_by'] = auth()->user()->id;

            if (isset($inputData['is_agree']) && $inputData['is_agree'] == 1) {
                $inputData['is_agree'] = 1;
            } else {
                $inputData['is_agree'] = 0;
            }

            $this->claimObj->update($id, $inputData);
            $tada = $this->claimObj->find($id);
            
            //save tada details
            $tada->tadaDetails()->delete();

            $details = [];
            if (isset($inputData['details'])) {
                foreach ($inputData['details'] as $detailData) {
                    $details[] = new TadaDetail([
                        'type_id' => $detailData['type_id'] ?? null,
                        'amount' => $detailData['amount'] ?? 0,
                        'remark' => $detailData['remark'] ?? null,
                    ]);
                }
                if (isset($details)) {
                    $tada->tadaDetails()->saveMany($details);
                }
            }

            return $this->respond([
                'status' => true,
                'message' => 'Tada Details Updated Successfully',
                'data' => new ClaimResource($tada)
            ]);
        } catch (\Throwable $e) {
            $this->respondWithError($e->getMessage());
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
            $claim = Tada::find($id);
            $claim->delete();
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }

        return $this->respondObjectDeleted($id);
    }
}
