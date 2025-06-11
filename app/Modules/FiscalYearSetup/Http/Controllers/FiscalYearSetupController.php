<?php

namespace App\Modules\FiscalYearSetup\Http\Controllers;

use App\Modules\FiscalYearSetup\Http\Requests\CreateFiscalYearSetupRequest;
use App\Modules\FiscalYearSetup\Repositories\FiscalYearSetupInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

// use Illuminate\Support\Facades\Auth;

class FiscalYearSetupController extends Controller
{
    protected $fiscalYearSetup;

    public function __construct(FiscalYearSetupInterface $fiscalYearSetup)
    {
        $this->fiscalYearSetup = $fiscalYearSetup;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['fiscalYearSetupModels'] = $this->fiscalYearSetup->findAll(20, $filter, $sort);
        return view('fiscalyearsetup::fiscalYearSetup.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        return view('fiscalyearsetup::fiscalYearSetup.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateFiscalYearSetupRequest $request)
    {
        $inputData = $request->all();
        if ($inputData['start_date_english'] == null && !is_null($inputData['start_date'])) {
            $inputData['start_date_english'] = date_converter()->nep_to_eng_convert($inputData['start_date']);
        }
        if ($inputData['end_date_english'] == null && !is_null($inputData['end_date'])) {
            $inputData['end_date_english'] = date_converter()->nep_to_eng_convert($inputData['end_date']);
        }
        try {
            $checkfiscalYear =  $this->fiscalYearSetup->getFiscalYear();
            if ($checkfiscalYear) {

                if ($checkfiscalYear->status == 1 && $inputData['status'] == "1") {
                    toastr()->error('Previous Fiscal Year should be Inactive first !!!');
                    return redirect()->back();
                }
            }


            $this->fiscalYearSetup->create($inputData);
            toastr()->success('Fiscal Year Created Successfully');
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->route('fiscalYearSetup.index');
    }

    public function storeAjax(Request $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                $inputData = $request->all();

                $validator = Validator::make($inputData, [
                    'fiscal_year' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'status' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => $validator->errors()->first(),
                    ]);
                }

                if ($inputData['start_date_english'] == null && !is_null($inputData['start_date'])) {
                    $inputData['start_date_english'] = date_converter()->nep_to_eng_convert($inputData['start_date']);
                }
                if ($inputData['end_date_english'] == null && !is_null($inputData['end_date'])) {
                    $inputData['end_date_english'] = date_converter()->nep_to_eng_convert($inputData['end_date']);
                }

                $checkFiscalYear =  $this->fiscalYearSetup->getFiscalYear();
                if ($checkFiscalYear) {
                    if ($checkFiscalYear->status == 1 && $inputData['status'] == "1") {
                        return response()->json([
                            'status' => false,
                            'message' => 'Previous Fiscal Year should be Inactive first !!!'
                        ]);
                    }
                }
                $this->fiscalYearSetup->create($inputData);
                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Fiscal Year Created Successfully'
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
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('fiscalyearsetup::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['fiscalYearSetupModel'] = $this->fiscalYearSetup->findOne($id);
        return view('fiscalyearsetup::fiscalYearSetup.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CreateFiscalYearSetupRequest $request, $id)
    {
        $data = $request->all();
        try {
            $checkfiscalYear =  $this->fiscalYearSetup->getFiscalYear();
            if ($checkfiscalYear && $checkfiscalYear->id != $id) {
                $checkfiscalYear->status = 0;
                $checkfiscalYear->is_sync = 10;
                $checkfiscalYear->save();
                // if ($data['status'] == 1) {
                //     toastr()->error('Fiscal Year Already Activated!!!');
                //     return redirect()->back();
                // }
            }
            if ($data['start_date_english'] == null && !is_null($data['start_date'])) {
                $data['start_date_english'] = date_converter()->nep_to_eng_convert($data['start_date']);
            }
            // if (!is_null($data['start_date'])) {
            //     $data['start_date_english'] = date_converter()->nep_to_eng_convert($data['start_date']);
            // }
            // if (!is_null($data['end_date'])) {
            //     $data['end_date_english'] = date_converter()->nep_to_eng_convert($data['end_date']);
            // }
            if ($data['end_date_english'] == null && !is_null($data['end_date'])) {
                $data['end_date_english'] = date_converter()->nep_to_eng_convert($data['end_date']);
            }
            $this->fiscalYearSetup->update($id, $data);

            toastr()->success('Fiscal Year Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->fiscalYearSetup->delete($id);

            toastr()->success('Fiscal Year Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    function getFiscalYearById($id)
    {
        $fiscalYearModel = $this->fiscalYearSetup->findOne($id);
        return response()->json([
            'status' => true,
            'data' => $fiscalYearModel
        ]);
    }
}
