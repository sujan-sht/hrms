<?php

namespace App\Modules\Insurance\Http\Controllers;

use App\Modules\Insurance\Entities\InsuranceType;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class InsuranceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $insuranceTypes = InsuranceType::latest()->get();
        return view('insurance::insurance.type.index', compact('insuranceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('insurance::type-create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = InsuranceType::create($request->all());
        if ($data) {
            toastr()->success('Created Successfully.');
        } else {
            toastr()->error('not created Insurance Type');
        }
        return redirect()->route('insurance.type.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $insuranceType = InsuranceType::findOrFail($id);
        return view('insurance::type-create', compact('insuranceType'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $insuranceType = InsuranceType::findOrFail($id);
        $insuranceType->update($request->all());
        if ($insuranceType) {
            toastr()->success('Updated Successfully.');
        } else {
            toastr()->error('not updated Insurance Type');
        }
        return redirect()->route('insurance.type.index');
    }

    public function show(Request $request)
    {
        $insuranceType = InsuranceType::findOrFail($request->id);

        return response()->json(['status' => true, 'data' => $insuranceType]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $insuranceType = InsuranceType::findOrFail($id);
        $insuranceType->delete();
        toastr()->success('Deleted Successfully.');
        return redirect()->route('insurance.type.index');
    }
}
