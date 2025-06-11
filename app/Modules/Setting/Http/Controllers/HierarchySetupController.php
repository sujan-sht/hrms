<?php

namespace App\Modules\Setting\Http\Controllers;

use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Http\Requests\HierarchySetupRequest;
use App\Modules\Setting\Repositories\HierarchySetupInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HierarchySetupController extends Controller
{
    protected $organization;
    protected $hierarchySetup;


    public function __construct(OrganizationInterface $organization, HierarchySetupInterface $hierarchySetup)
    {
        $this->organization = $organization;
        $this->hierarchySetup = $hierarchySetup;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        $data['organizationList'] = $this->organization->getList();
        $data['hierarchySetupModels'] = $this->hierarchySetup->findAll(20, $filter);

        return view('setting::hierarchy-setup.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        $organizationList = $this->organization->getList();
        $hierarchySetupList = $this->hierarchySetup->getList()->toArray();

        foreach ($organizationList as $org_id => $org_name) {
            if (in_array($org_id, $hierarchySetupList)) {
                unset($organizationList[$org_id]);
            }
        }
        $data['organizationList'] = $organizationList;
        return view('setting::hierarchy-setup.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(HierarchySetupRequest $request)
    {
        $inputData = $request->except('_token');
        try {
            $this->hierarchySetup->save($inputData);
            toastr()->success('Data Created Successfully!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong!!');
        }
        return redirect(route('hierarchySetup.index'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data['hierarchySetupModel'] = $this->hierarchySetup->find($id);
        return view('setting::hierarchy-setup.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['hierarchySetupModel'] = $this->hierarchySetup->find($id);

        $data['organizationList'] = $this->organization->getList();
        return view('setting::hierarchy-setup.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(HierarchySetupRequest $request, $id)
    {
        $inputData = $request->except('_token');

        try {
            $this->hierarchySetup->update($id, $inputData);

            toastr()->success('Data Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('hierarchySetup.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->hierarchySetup->delete($id);

            toastr()->success('Data Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }
}
