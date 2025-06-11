<?php

namespace App\Modules\Advance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Advance\Repositories\AdvanceInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Advance\Repositories\AdvancePaymentLedgerInterface;

class AdvancePaymentLedgerController extends Controller
{
    protected $advanceObj;
    protected $employeeObj;
    protected $advancePaymentLedgerObj;

    /**
     * Constructor
     */
    public function __construct(
        AdvanceInterface $advanceObj,
        EmployeeInterface $employeeObj,
        AdvancePaymentLedgerInterface $advancePaymentLedgerObj
    ) {
        $this->advanceObj = $advanceObj;
        $this->employeeObj = $employeeObj;
        $this->advancePaymentLedgerObj = $advancePaymentLedgerObj;
    }

    /**
     * 
     */
    public function index(Request $request)
    {
        // $inputData = $request->all();

        // $data['title'] = 'Advances';
        // $data['employeeList'] = $this->employeeObj->getList();
        // $data['advanceModels'] = $this->advanceObj->findAll(20, $inputData);

        // return view('advance::advance.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        // $data['isEdit'] = false;
        // $data['title'] = 'Advance';
        // $data['employeeList'] = $this->employeeObj->getList();
        // $data['monthList'] =  Attendance::MONTHS;

        // return view('advance::advance.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // $inputData = $request->all();
        
        // try {
        //     $this->advanceObj->create($inputData);

        //     toastr()->success('Data Created Successfully');
        // } catch (\Throwable $e) {
        //     toastr()->error($e->getMessage());
        // }

        // return redirect(route('advance.index'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        // $data['advanceModel'] = $this->advanceObj->findOne($id);

        // return view('advance::advance.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        // $data['isEdit'] = true;
        // $data['title'] = 'Advance';
        // $data['advanceModel'] = $this->advanceObj->findOne($id);
        // $data['employeeList'] = $this->employeeObj->getList();
        // $data['monthList'] =  Attendance::MONTHS;

        // return view('advance::advance.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        // $inputData = $request->all();

        // try {
        //     $this->advanceObj->update($id, $inputData);

        //     toastr()->success('Data Updated Successfully');
        // } catch (\Throwable $e) {
        //     toastr()->error($e->getMessage());
        // }

        // return redirect(route('advance.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        // try {
        //     $this->advanceObj->delete($id);
        //     toastr()->success('Data Deleted Successfully');
        // } catch (\Throwable $e) {
        //     toastr()->error('Something Went Wrong !!!');
        // }

        // return redirect()->back();
    }

    /**
     * 
     */
    public function report(Request $request)
    {
        $inputData = $request->all();

        $filter = [];
        if(isset($inputData['employee'])) {
            $filter['employee'] = $inputData['employee'];
        }

        $data['employeeList'] = $this->employeeObj->getList();
        $data['advancePaymentLedgerModels'] = $this->advancePaymentLedgerObj->findAll(20, $filter);

        return view('advance::advance-payment-ledger.report', $data);
    }
}
