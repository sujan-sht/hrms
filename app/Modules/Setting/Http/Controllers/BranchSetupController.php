<?php

namespace App\Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\Setting\Enum\TravelTypeEnum;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Setting\Entities\BranchSetupSetting;

class BranchSetupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $branchSetupSetting;
    public function __construct(BranchSetupSetting $branchSetupSetting)
    {
        $this->branchSetupSetting=$branchSetupSetting;
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
        
        $branchSetupSetting= $this->branchSetupSetting->first();
        // dd($branchSetupSetting);
        return view('setting::branchSetup.index',compact('branchSetupSetting'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $data=$request->all();
            $this->branchSetupSetting= $this->branchSetupSetting->first();
            $message='Updated';
            if(!$this->branchSetupSetting){
                $this->branchSetupSetting=new BranchSetupSetting();
                $message='Setup';
            }
            $this->branchSetupSetting->fill($data);
            $this->branchSetupSetting->save();
            DB::commit();
            toastr()->success('Branch Setting '.$message.' Successfully !!');
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
