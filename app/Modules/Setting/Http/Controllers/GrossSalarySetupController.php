<?php

namespace App\Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\Setting\Enum\TravelTypeEnum;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Setting\Entities\GrossSalarySetupSetting;
use App\Modules\Setting\Http\Requests\GrossSallarySettingStoreRequest;

class GrossSalarySetupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $grossSalarySetupSetting;
    public function __construct(GrossSalarySetupSetting $grossSalarySetupSetting)
    {
        $this->grossSalarySetupSetting=$grossSalarySetupSetting;
    }
    public function index()
    {
        return view('setting::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $types=TravelTypeEnum::getAllValues();
        $grossSalarySetupSetting= $this->grossSalarySetupSetting->first();
        // dd($grossSalarySetupSetting);
        return view('setting::grosssallery.index',compact('types','grossSalarySetupSetting'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(GrossSallarySettingStoreRequest $request)
    {
        DB::beginTransaction();
        try{
            $data=$request->all();
            $this->grossSalarySetupSetting= $this->grossSalarySetupSetting->first();
            $message='Updated';
            if(!$this->grossSalarySetupSetting){
                $this->grossSalarySetupSetting=new GrossSalarySetupSetting();
                $message='Setup';
            }
            $this->grossSalarySetupSetting->fill($data);
            $this->grossSalarySetupSetting->save();
            DB::commit();
            toastr()->success('Gross Salary Setting '.$message.' Successfully !!');
            return redirect()->back();
        }catch(\Throwable $th){
            DB::rollBack();
            toastr()->error('Something Went Wrong !!!');
            return redirect()->back();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('setting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('setting::edit');
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
