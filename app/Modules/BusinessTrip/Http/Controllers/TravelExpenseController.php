<?php

namespace App\Modules\BusinessTrip\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Tada\Entities\ErType;
use App\Modules\Dropdown\Entities\Field;
use App\Modules\Tada\Entities\ExpenseHead;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\BusinessTrip\Entities\BusinessTrip;
use App\Modules\BusinessTrip\Entities\TravelExpense;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\BusinessTrip\Http\Requests\TravelExpenseRequest;

class TravelExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    private $employee;

    public function __construct(
        EmployeeInterface $employee
    ) {
        $this->employee = $employee;
    }
    public function index()
    {
        $lists = TravelExpense::latest()
            ->with('employee')
            ->paginate(20);

        $data = [
            'travelTypes' =>   BusinessTrip::TRAVEL_TYPES,
            'lists' => $lists
        ];

        return view('businesstrip::travelexpense.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $fileds =  Field::where('slug', 'currency_list')->with('dropdownValue')->first();
        $data = [
            'travelExpense' => null,
            'id' => null,
            'erTypes' => ErType::pluck('title', 'id')->toArray(),
            'expenseHeads' => ExpenseHead::pluck('title', 'id')->toArray(),
            'travelTypes' => TravelExpense::TRAVEL_TYPES,
            'employees' => $this->employee->getList(),
            'currencyLists' => $fileds->dropdownValue ?? []
        ];

        return view('businesstrip::travelexpense.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(TravelExpenseRequest $request)
    {
        $data = $request->validated();
        $expense_details = null;
        if (count($request->er_type) > 0) {
            $expense_details = json_encode([
                'er_type' => $request->er_type,
                'location' => $request->location,
                'date' => $request->date,
                'ticket_bill_no' => $request->ticket_bill_no,
                'expenses_head' => $request->expenses_head,
                'conversion_rate' => $request->conversion_rate,
                'foreign_currency_type' => $request->foreign_currency_type,
                'amount' => $request->amount,
                'remark' => $request->remark,
            ]);
        }
        $data['expense_details'] = $expense_details;

        try {
            TravelExpense::create($data);
            toastr('Travel Request Create Successfully', 'success');
            return redirect()->route('travelexpense.index');
        } catch (\Throwable $t) {
            toastr('Error While Adding Travel Request', 'error');
            dd($t);
            return back();
        }
        return redirect()->route('travelexpense.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */

    public function edit($id)
    {
        $fileds =  Field::where('slug', 'currency_list')->with('dropdownValue')->first();
        $travelExpense = TravelExpense::findOrFail($id);
        $data = [
            'id' => null,
            'erTypes' => ErType::pluck('title', 'id')->toArray(),
            'expenseHeads' => ExpenseHead::pluck('title', 'id')->toArray(),
            'travelTypes' => TravelExpense::TRAVEL_TYPES,
            'employees' => $this->employee->getList(),
            'currencyLists' => $fileds->dropdownValue ?? [],
            'travelExpense' => $travelExpense
        ];

        return view('businesstrip::travelexpense.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(TravelExpenseRequest $request, $id)
    {

        $travelExpense = TravelExpense::findOrFail($id);
        $data = $request->validated();
        $expense_details = null;
        if (count($request->er_type) > 0) {
            $expense_details = json_encode([
                'er_type' => $request->er_type,
                'location' => $request->location,
                'date' => $request->date,
                'ticket_bill_no' => $request->ticket_bill_no,
                'expenses_head' => $request->expenses_head,
                'conversion_rate' => $request->conversion_rate,
                'foreign_currency_type' => $request->foreign_currency_type,
                'amount' => $request->amount,
                'remark' => $request->remark,
            ]);
        }
        $data['expense_details'] = $expense_details;

        try {
            $travelExpense->update($data);
            toastr('Travel Request Updated Successfully', 'success');
            return redirect()->route('travelexpense.index');
        } catch (\Throwable $t) {
            toastr('Error While Update Travel Request', 'error');
            dd($t);
            return back();
        }
        return redirect()->route('travelexpense.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $travelExpense = TravelExpense::findOrFail($id);
        $travelExpense->delete();
        toastr('Travel Request Updated Successfully', 'success');
        return back();
    }
}
