<?php

namespace App\Modules\Tada\Http\Controllers;

use App\Modules\Tada\Entities\ExpenseHead;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExpenseHeadController extends Controller
{
    public function index()
    {
        $lists = ExpenseHead::latest()->paginate(20);
        return view('tada::expense-head.index', compact('lists'));
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        ExpenseHead::updateOrCreate(
            ['id' => $request->id],
            ['title' => $request->title]
        );
        toastr()->success('Expense Head Created Successfully.');
        return back();
    }


    public function delete($id)
    {
      ExpenseHead::findOrFail($id)->delete();
        toastr()->success('Expense Head Deleted Successfully.');
        return back();
    }
}
