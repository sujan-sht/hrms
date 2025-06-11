<?php

namespace App\Modules\Insurance\Http\Controllers;

use App\Modules\Insurance\Entities\Insurance;
use App\Modules\Insurance\Entities\InsuranceType;
use App\Modules\Insurance\Services\InsuranceService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $insurances = Insurance::with('type')->latest()->get();
        return view('insurance::index', compact('insurances'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $insuranceTypes = InsuranceType::pluck('title', 'id');
        return view('insurance::create', compact('insuranceTypes'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $insuranceService = new InsuranceService();
            $data = $insuranceService->typeWiseStore($request);
            Insurance::create($data);
            DB::commit();
            toastr()->success('Insurance created successfully !!!');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Store Insurance Error: " . $th->getMessage());
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->route('insurance.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $insurance = Insurance::with('type')->findOrFail($id);
        return view('insurance::show', compact('insurance'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $insurance = Insurance::with('type')->findOrFail($id);
        $insuranceTypes = InsuranceType::pluck('title', 'id');
        return view('insurance::edit', compact('insurance', 'insuranceTypes'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $insurance = Insurance::findOrFail($id);
        DB::beginTransaction();
        try {

            $insuranceService = new InsuranceService();
            $data = $insuranceService->typeWiseStore($request);
            $insurance->update($data);
            DB::commit();
            toastr()->success('Insurance updated successfully !!!');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Store Insurance Error: " . $th->getMessage());
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->route('insurance.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $insurance = Insurance::findOrFail($id);

        if (isset($insurance->document_upload) && !is_null($insurance->document_upload)) {
            unlink(public_path() . '/uploads/insurance/' . $insurance->document_upload);
        }
        $insurance->delete();
        toastr()->success('Deleted Successfully.');
        return redirect()->route('insurance.index');
    }
}
