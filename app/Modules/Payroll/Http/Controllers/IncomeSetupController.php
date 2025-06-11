<?php

namespace App\Modules\Payroll\Http\Controllers;

use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Payroll\Entities\EmployeeSetup;
use App\Modules\Payroll\Repositories\EmployeeSetupInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use App\Modules\Payroll\Repositories\IncomeSetupInterface;
use Illuminate\Routing\Controller;

class IncomeSetupController extends Controller
{
    protected $incomeSetup;
    protected $employeeSetup;
    protected $organization;
    protected $branch;

    public function __construct(IncomeSetupInterface $incomeSetup, EmployeeSetupInterface $employeeSetup, OrganizationInterface $organization, BranchInterface $branch)
    {
        $this->incomeSetup = $incomeSetup;
        $this->employeeSetup = $employeeSetup;
        $this->organization = $organization;
        $this->branch = $branch;
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

        // if(isset($inputData['organization_id'])) {
        //     $filter['organizationId'] = $inputData['organization_id'];
        // }

        if(isset($inputData['organization_id']) && isset($inputData['branch_id'])) {
            $filter['organizationId'] = $inputData['organization_id'];
            $filter['branchId'] = $inputData['branch_id'];
        }

        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['incomeSetupModels'] = $this->incomeSetup->findAll(20, $filter, $sort);

        return view('payroll::income-setup.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['organizationList'] = $this->organization->getList();
        $data['salaryType'] = [1 => 'Basic salary', 2 => 'Gross salary', 3 => 'Grade'];
        return view('payroll::income-setup.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData = $request->all();
        // dd($inputData);
        $incomeData = $request->except('percentage','salary_type');

        try {
            $incomeModel = $this->incomeSetup->save($incomeData);
            foreach($inputData['percentage'] as $key=>$value){
                if($value){
                    $incomeDataDetail['income_setup_id'] = $incomeModel->id;
                    $incomeDataDetail['percentage'] = $value;
                    $incomeDataDetail['salary_type'] = $inputData['salary_type'][$key];
                    $this->incomeSetup->saveDetail($incomeDataDetail);
                }
            }
            toastr()->success('Income Setup Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('incomeSetup.index'));
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
        $data['salaryType'] = [1 => 'Basic salary', 2 => 'Gross salary', 3 => 'Grade'];
        $data['organizationList'] = $this->organization->getList();
        $data['incomeSetupModel'] = $this->incomeSetup->find($id);

        return view('payroll::income-setup.edit', $data);
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
        $incomeData = $request->except('percentage','salary_type');


        try {
            $this->incomeSetup->update($id, $incomeData);
            $this->incomeSetup->deleteChild($id);
            foreach($data['percentage'] as $key=>$value){
                if($value){
                    $incomeDataDetail['income_setup_id'] = $id;
                    $incomeDataDetail['percentage'] = $value;
                    $incomeDataDetail['salary_type'] = $data['salary_type'][$key];
                    $this->incomeSetup->saveDetail($incomeDataDetail);
                }
            }

            toastr()->success('Income Setup Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('incomeSetup.index'));
    }

    public function checkIncomeOrder(Request $request)
    {
        $data = $request->all();
        $validator = \Validator::make($request->all(), [
            'order' => 'required|unique:income_setups,order,' . $data['id'],
        ]);
        if ($validator->fails()) {
            return  json_encode(false);
            // dd($validator->errors()->all());
        }
        return  json_encode(true);
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->incomeSetup->delete($id);
            EmployeeSetup::where('reference', 'income')->where('reference_id', $id)->delete();
            toastr()->success('Income Setup Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }
}
