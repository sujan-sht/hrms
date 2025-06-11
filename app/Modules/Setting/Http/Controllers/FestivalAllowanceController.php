<?php

namespace App\Modules\Setting\Http\Controllers;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Setting\Repositories\DashainAllowanceSetupInterface;
use App\Modules\Setting\Repositories\FestivalAllowanceSetupRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FestivalAllowanceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

     protected $festivalAllowance;

     public function __construct(FestivalAllowanceSetupRepository $festivalAllowance)
     {
         $this->festivalAllowance = $festivalAllowance;
     }
    public function index()
    {
        return view('setting::festival-allowance-setup.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['festivalAllowance'] = $this->festivalAllowance->find(1);
        $dateConverter = new DateConverter();
        // for($i=$firstYear; $i<=$lastYear; $i++) {
        //     $yearArray[$i] = $i;
        // }
        $data['monthList'] = $dateConverter->getEngMonths();
        $data['nepaliMonthList'] = $dateConverter->getNepMonths();

        if ($data['festivalAllowance'] == null) {
            $data['is_edit'] = false;
            $data['btnType']='Save';
            return view('setting::festival-allowance-setup.index',$data);
        } else {
            $data['is_edit'] = true;
            $data['btnType']='Update';
            return view('setting::festival-allowance-setup.index',$data);
        }
        return view('setting::festival-allowance-setup.index');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        // dd($data);

        try{
            
            $this->festivalAllowance->save($data);
            toastr()->success('Festival Allowance Setup Created Successfully');

        }catch(\Throwable $e){
             toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('festivalAllowance.create'));
        //
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
        $data = $request->all();
        // dd($data);
        try{
            $this->festivalAllowance->update($id,$data);
             toastr()->success('Festival Allowance Updated Successfully');
        }catch(\Throwable $e){
            // dd($e);
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('festivalAllowance.create'));
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
