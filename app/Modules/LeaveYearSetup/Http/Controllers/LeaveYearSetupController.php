<?php

namespace App\Modules\LeaveYearSetup\Http\Controllers;

use App\Modules\LeaveYearSetup\Repositories\LeaveYearSetupInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LeaveYearSetupController extends Controller
{
    protected $leaveYearSetup;

    public function __construct(LeaveYearSetupInterface $leaveYearSetup)
    {
        $this->leaveYearSetup = $leaveYearSetup;
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
        $data['leaveYearSetupModels'] = $this->leaveYearSetup->findAll(20, $filter, $sort);
        return view('leaveyearsetup::leaveYearSetup.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        return view('leaveyearsetup::leaveYearSetup.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData = $request->all();
        // if($inputData['start_date_english'] == null && !is_null($inputData['start_date'])){
        //     $inputData['start_date_english'] = date_converter()->nep_to_eng_convert($inputData['start_date']);
        // }
        // if($inputData['end_date_english'] == null && !is_null($inputData['end_date'])){
        //     $inputData['end_date_english'] = date_converter()->nep_to_eng_convert($inputData['end_date']);
        // }
        try {
            $checkleaveYear =  $this->leaveYearSetup->getLeaveYear();
            if ($checkleaveYear) {
                if ($checkleaveYear->status == 1 && $inputData['status'] == "1") {
                    toastr()->error('Previous Leave Year should be Inactive first !!!');
                    return redirect()->back();
                }
            }

            if($inputData['calender_type'] == 'nep'){
                $inputData['start_date_english'] = date_converter()->nep_to_eng_convert($inputData['start_date']);
                $inputData['end_date_english'] = date_converter()->nep_to_eng_convert($inputData['end_date']);
            }else{
                $inputData['start_date'] = date_converter()->eng_to_nep_convert($inputData['start_date_english']);
                $inputData['end_date'] = date_converter()->eng_to_nep_convert($inputData['end_date_english']);
            }
            $this->leaveYearSetup->create($inputData);
            toastr()->success('Leave Year Created Successfully');
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('leaveYearSetup.index'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('leaveyearsetup::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['leaveYearSetupModel'] = $this->leaveYearSetup->findOne($id);
        return view('leaveyearsetup::leaveYearSetup.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        try {
            $checkleaveYear =  $this->leaveYearSetup->getLeaveYear();
            if ($checkleaveYear && $checkleaveYear->id != $id) {
                $checkleaveYear->status = 0;
                // $checkleaveYear->is_sync = 10;
                $checkleaveYear->save();
            }
            if($data['calender_type'] == 'nep'){
                $data['start_date_english'] = date_converter()->nep_to_eng_convert($data['start_date']);
                $data['end_date_english'] = date_converter()->nep_to_eng_convert($data['end_date']);
            }else{
                $data['start_date'] = date_converter()->eng_to_nep_convert($data['start_date_english']);
                $data['end_date'] = date_converter()->eng_to_nep_convert($data['end_date_english']);
            }
            // if($data['start_date_english'] == null && !is_null($data['start_date'])){
            //     $data['start_date_english'] = date_converter()->nep_to_eng_convert($data['start_date']);
            // }
            // if($data['end_date_english'] == null && !is_null($data['end_date'])){
            //     $data['end_date_english'] = date_converter()->nep_to_eng_convert($data['end_date']);
            // }
            $this->leaveYearSetup->update($id, $data);

            toastr()->success('Leave Year Updated Successfully');
        } catch (\Throwable $e) {
            dd($e);
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('leaveYearSetup.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->leaveYearSetup->delete($id);

            toastr()->success('Leave Year Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    function getLeaveYearById($id)  {
        $leaveYearModel = $this->leaveYearSetup->findOne($id);
        return response()->json([
            'status'=>true,
            'data'=>$leaveYearModel
        ]);
    }
}
