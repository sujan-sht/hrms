<?php

namespace App\Modules\Payroll\Http\Controllers;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Payroll\Entities\EmployeeBonusSetup;
use App\Modules\Payroll\Repositories\BonusSetupInterface;
use App\Modules\Payroll\Repositories\IncomeSetupInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BonusSetupController extends Controller
{
    protected $organization;
    protected $bonusSetup;
    protected $incomeSetup;

    public function __construct(OrganizationInterface $organization,BonusSetupInterface $bonusSetup,IncomeSetupInterface $incomeSetup)
    {
        $this->organization = $organization;
        $this->bonusSetup = $bonusSetup;
        $this->incomeSetup = $incomeSetup;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $inputData = $request->all();

        $filter = [];
        $sort = ['by' => 'order', 'sort' => 'ASC'];

        if(isset($inputData['organization_id'])) {
            $filter['organizationId'] = $inputData['organization_id'];
        }

        $data['organizationList'] = $this->organization->getList();
        $data['bonusSetupModels'] = $this->bonusSetup->findAll(20, $filter, $sort);
        return view('payroll::bonus-setup.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $dateConverter = new DateConverter();
        $data['isEdit'] = false;
        $data['organizationList'] = $this->organization->getList();
        $data['incomeList'] = $this->incomeSetup->getList();
        $data['salaryType'] = [1 => 'Basic salary', 2 => 'Gross salary'];
        $data['monthList'] = $dateConverter->getEngMonths();
        $data['nepaliMonthList'] = $dateConverter->getNepMonths();
        return view('payroll::bonus-setup.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData = $request->all();
        $bonusData = $request->except('percentage','income_id');
        // dd($inputData);
        try {
            $bonusModel = $this->bonusSetup->save($bonusData);
            foreach($inputData['percentage'] as $key=>$value){
                if($value && $inputData['income_id'][$key]){
                    $bonusDataDetail['bonus_setup_id'] = $bonusModel->id;
                    $bonusDataDetail['percentage'] = $value;
                    $bonusDataDetail['income_id'] = $inputData['income_id'][$key];
                    $this->bonusSetup->saveDetail($bonusDataDetail);
                }
            }
            toastr()->success('Bonus Setup Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('bonusSetup.index'));
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
        $data['isEdit'] = true;
        $data['salaryType'] = [1 => 'Basic salary', 2 => 'Gross salary'];
        $data['organizationList'] = $this->organization->getList();
        $data['monthList'] = date_converter()->getEngMonths();
        // $data['incomeList'] = $this->incomeSetup->getList();
        $data['nepaliMonthList'] = date_converter()->getNepMonths();
        $data['bonusSetupModel'] = $this->bonusSetup->find($id);

        return view('payroll::bonus-setup.edit', $data);
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
        $bonusData = $request->except('percentage','income_id');

        try {
            $this->bonusSetup->update($id, $bonusData);
            $this->bonusSetup->deleteChild($id);
            foreach($data['percentage'] as $key=>$value){
                if($value && $data['income_id'][$key]){
                    $bonusDataDetail['bonus_setup_id'] = $id;
                    $bonusDataDetail['percentage'] = $value;
                    $bonusDataDetail['income_id'] = $data['income_id'][$key];
                    $this->bonusSetup->saveDetail($bonusDataDetail);
                }
            }
            toastr()->success('Bonus Setup Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('bonusSetup.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->bonusSetup->delete($id);
            EmployeeBonusSetup::where('bonus_setup_id', $id)->delete();
            toastr()->success('Bonus Setup Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }
}
