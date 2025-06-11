<?php

namespace App\Modules\Advance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Advance\Repositories\AdvanceInterface;
use App\Modules\Advance\Repositories\AdvancePaymentLedgerInterface;
use App\Modules\Advance\Repositories\AdvanceSettlementPaymentInterface;

class AdvanceSettlementPaymentController extends Controller
{
    protected $advanceObj;
    protected $employeeObj;
    protected $advanceSettlementPaymentObj;
    protected $advancePaymentLedgerObj;

    /**
     * Constructor
     */
    public function __construct(
        AdvanceInterface $advanceObj,
        AdvanceSettlementPaymentInterface $advanceSettlementPaymentObj,
        AdvancePaymentLedgerInterface $advancePaymentLedgerObj
    ) {
        $this->advanceObj = $advanceObj;
        $this->advanceSettlementPaymentObj = $advanceSettlementPaymentObj;
        $this->advancePaymentLedgerObj = $advancePaymentLedgerObj;
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('advance::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('advance::create');
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
            $result = $this->advanceSettlementPaymentObj->create($inputData);
            if($result) {
                $advanceModel = $this->advanceObj->findOne($request->advance_id);
                $advanceModel->remaining_amount -= $result->amount;
                if($advanceModel->remaining_amount <= 0) {
                    $advanceModel->status = 3;
                } else {
                    $advanceModel->status = 2;
                }

                if($advanceModel->save()) {
                    $advancePaymentLedgerData['advance_id'] = $advanceModel->id;
                    $advancePaymentLedgerData['date'] = $inputData['date'];
                    $advancePaymentLedgerData['description'] = 'Payment received from '. optional($advanceModel->employeeModel)->full_name;
                    $advancePaymentLedgerData['credit'] = $inputData['amount'];
                    $advancePaymentLedgerData['balance'] = $inputData['amount'];
                    $this->advancePaymentLedgerObj->create($advancePaymentLedgerData);
                    toastr()->success('Data Created Successfully');
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            toastr()->error($th->getMessage());
        }
        
        return redirect()->back();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('advance::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('advance::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
