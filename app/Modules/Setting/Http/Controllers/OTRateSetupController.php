<?php

namespace App\Modules\Setting\Http\Controllers;

use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Payroll\Repositories\IncomeSetupInterface;
use App\Modules\Setting\Entities\OtRateSetup;
use App\Modules\Setting\Repositories\OTRateSetupInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class OTRateSetupController extends Controller
{
    private $organizationObj;
    private $otSetupObj;
    private $incomeSetupObj;
    public function __construct(
        OrganizationInterface $organizationObj,
        OTRateSetupInterface $otSetupObj,
        IncomeSetupInterface $incomeSetupObj
    ) {
        $this->organizationObj = $organizationObj;
        $this->otSetupObj = $otSetupObj;
        $this->incomeSetupObj = $incomeSetupObj;
    }

    public function index(Request $request)
    {
        $filter = ($request->all());
        // dd($filter);
        $data['organizationList'] = $this->organizationObj->getList();
        $data['otType'] = OtRateSetup::OT_TYPE;
        if (isset($filter['organization_id'])) {
            $organization_id = $filter['organization_id'];
            $data['incomeList'] = $this->incomeSetupObj->getList(['organizationId' => $organization_id]);
            $data['otRateSetupModel'] = $this->otSetupObj->findOtRateByOrganization($filter);
            // dd($data['otRateSetupModel']);
            if (count($data['otRateSetupModel']) > 0) {
                $data['is_edit'] = true;
                $data['btnType']='Update';
            } else {
                $data['is_edit'] = false;
                $data['btnType']='Save';
            }
        }
        
        return view('setting::ot-rate-setup.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('setting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData = $request->all();
        DB::beginTransaction();
        try {
            foreach($inputData['ot_type'] as $key=>$value){

                $data['organization_id'] = $inputData['organization_id'];
                $data['ot_basis'] = $inputData['ot_basis'];
                $data['ot_type'] = $value;
                if( $data['ot_basis'] == 2){
                    $data['rate'] = $inputData['rate'][$key];
                }
                else{
                    $data['times_value'] = $inputData['times_value'][$key];
                }
                $data['is_min_ot_requirement'] = $inputData['is_min_ot_requirement'];
                if(isset($inputData['is_min_ot_requirement'])){
                    if($inputData['is_min_ot_requirement'] == 11){
                        $data['min_ot_time'] = $inputData['min_ot_time'];
                    }else{
                        $data['min_ot_time'] = null;
                    }
                }
                $otSetupModel = $this->otSetupObj->create($data);
                if( $data['ot_basis'] == 1){
                    foreach($inputData['income_setup_id'][$value] as $inc => $income){
                        // dd($income);
                        $otdetail['ot_rate_setup_id'] =   $otSetupModel->id;
                        $otdetail['income_setup_id'] = $income;
                        $this->otSetupObj->createOtDetail($otdetail);
                    }
                }
            }
            DB::commit();

            toastr()->success('Data Created Successfully');
        } catch (\Throwable $e) {
            DB::rollback();
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('otRateSetup.index'));
       
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
    public function update(Request $request)
    {
        $inputData = $request->all();
        try{
            foreach($inputData['ot_type'] as $key=>$value){
                // dd($value);
                $data['organization_id'] = $inputData['organization_id'];
                $data['ot_type'] = $value;
                $data['ot_basis'] = $inputData['ot_basis'];
                if( $data['ot_basis'] == 2){
                    $data['rate'] = $inputData['rate'][$key];
                    $data['times_value'] = null;
                }
                else{
                    $data['rate'] = null;
                    $data['times_value'] = $inputData['times_value'][$key];
                }
                $data['is_min_ot_requirement'] = $inputData['is_min_ot_requirement'];
                if(isset($data['is_min_ot_requirement'])){
                    if($data['is_min_ot_requirement'] == 11){
                        $data['min_ot_time'] = $inputData['min_ot_time'];
                    }else{
                        $data['min_ot_time'] = null;
                    }
                }
                $otRateModel = $this->otSetupObj->findOne($data);
                $otRateModel->update($data);
                $this->otSetupObj->deleteChild($otRateModel->id);
                if( $data['ot_basis'] == 1){
                    foreach($inputData['income_setup_id'][$key] as $inc => $income){
                        // dd($income);
                        $otdetail['ot_rate_setup_id'] =   $otRateModel->id;
                        $otdetail['income_setup_id'] = $income;
                        $this->otSetupObj->createOtDetail($otdetail);
                    }
                }
                
               
            }
            toastr()->success('OT Rate Setup Updated Successfully');

        }catch(\Throwable $e){
             toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('otRateSetup.index'));
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
