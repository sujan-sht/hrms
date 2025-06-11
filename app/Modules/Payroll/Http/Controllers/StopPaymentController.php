<?php

namespace App\Modules\Payroll\Http\Controllers;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Payroll\Repositories\StopPaymentInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StopPaymentController extends Controller
{
    private $stopPaymentObj;
    private $organizationObj;
    private $employeeObj;
    public function __construct(StopPaymentInterface $stopPaymentObj,OrganizationInterface $organizationObj,EmployeeInterface $employeeObj){
        $this->stopPaymentObj = $stopPaymentObj;
        $this->organizationObj = $organizationObj;
        $this->employeeObj = $employeeObj;
    }
   
    public function index()
    {
        $data['stopPayments'] = $this->stopPaymentObj->findAll();
        return view('payroll::stop-payment.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['organizationList'] = $this->organizationObj->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        return view('payroll::stop-payment.create',$data);
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
            $cal = new DateConverter();
            if(isset($data['nep_from_date']) && $data['nep_from_date'] ) {
                $data['from_date'] = $cal->nep_to_eng_convert($data['nep_from_date']);
            } else {
                $data['nep_from_date'] = $cal->eng_to_nep_convert($data['from_date']);
            }
            if(isset($data['nep_to_date']) && $data['nep_to_date']) {
                $data['to_date'] = $cal->nep_to_eng_convert($data['nep_to_date']);
            } else {
                $data['nep_to_date'] = $cal->eng_to_nep_convert($data['to_date']);
            }
            $this->stopPaymentObj->save($data);
            toastr()->success('StopPayment Created Successfully');
        } catch(\throwable $t) {
            // toastr()->error($t->getMessage());
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('stopPayment.index'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('payroll::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('payroll::edit');
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
