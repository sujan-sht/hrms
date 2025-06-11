<?php

namespace App\Modules\Payroll\Http\Controllers;

use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Payroll\Entities\TaxSlab;
use App\Modules\Payroll\Repositories\TaxSlabSetupInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TaxSlabController extends Controller
{
    protected $organization;
    protected $branch;
    protected $taxSlab;

    public function __construct(
        OrganizationInterface $organization,
        BranchInterface $branch,
        TaxSlabSetupInterface $taxSlab
    ) {
        $this->organization = $organization;
        $this->branch = $branch;
        $this->taxSlab = $taxSlab;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        // $filter=$request->all();
        // $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['taxSlabList']=[];
        // if(isset($filter['organization_id'])){
            // $data['taxSlabList'] = $this->taxSlab->getTaxSlabFromOrganization($filter['organization_id']);
            $data['taxSlabList'] = $this->taxSlab->getTaxSlabFromOrganization();
            // dd( $data['taxSlabList']);
        // }
        // dd($data['taxSlabList']);
        return view('payroll::tax-slab.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $requestData = $request->except(['_token','organization_id']);
        // dd($requestData);
        try {
            foreach ($requestData as $key => $value) {
                foreach ($value as $k => $v) {
                    $insertData = $v;
                    // $insertData['organization_id'] = $request->organization_id;
                    $insertData['type'] = $key;
                    $insertData['order'] = $k;
                    $this->taxSlab->updateOrCreate($insertData);
                }

            }
            toastr()->success('Tax Slab Created Successfully');

        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');

        }
        return redirect(route('taxSlab.index'));


    }

}
