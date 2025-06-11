<?php

namespace App\Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\Setting\Enum\TravelTypeEnum;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Setting\Entities\TravelAllowanceSetup;
use App\Modules\Setting\Http\Requests\TravelAllowanceStoreRequest;

class TravelAllowanceSetupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $travelAllowanceSetup;
    public function __construct(TravelAllowanceSetup $travelAllowanceSetup)
    {
        $this->travelAllowanceSetup=$travelAllowanceSetup;
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
        $travelAllowanceSetup= $this->travelAllowanceSetup->first();
        // dd($travelAllowanceSetup);
        return view('setting::travelallowance.index',compact('types','travelAllowanceSetup'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(TravelAllowanceStoreRequest $request)
    {
        DB::beginTransaction();
        try{
            $data=$request->all();
            $this->travelAllowanceSetup= $this->travelAllowanceSetup->first();
            $message='Updated';
            if(!$this->travelAllowanceSetup){
                $this->travelAllowanceSetup=new TravelAllowanceSetup();
                $message='Setup';
            }
            $this->travelAllowanceSetup->fill($data);
            $this->travelAllowanceSetup->save();
            DB::commit();
            toastr()->success('Travel Allowance '.$message.' Successfully !!');
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
