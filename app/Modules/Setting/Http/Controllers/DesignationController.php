<?php

namespace App\Modules\Setting\Http\Controllers;

use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Entities\DesignationOrganization;
use App\Modules\Setting\Repositories\DesignationInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DesignationController extends Controller
{
    private $organization;
    private $designation;


    public function __construct(
        OrganizationInterface $organization,
        DesignationInterface $designation
    ) {
        $this->organization = $organization;
        $this->designation = $designation;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];

        $data['organizationList'] = $this->organization->getList();
        $data['designations'] = $this->designation->findAll(25, $filter, $sort);
        return view('setting::designation.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['organizationList'] = $this->organization->getList();
        $data['isEdit'] = false;
        return view('setting::designation.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $data = $request->except('_token');
            if (isset($data['display_short_code'])) {
                $data['display_short_code'] = 1;
            }
            $this->designation->save($data);

            toastr('Designation added Successfully', 'success');
        } catch (\Throwable $th) {
            toastr('Error While Adding Designation', 'error');
        }
        return redirect()->back();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */

    public function viewDetail($id)
    {
        $data['designation'] = $this->designation->find($id);
        return view('setting::designation.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['organizationList'] = $this->organization->getList();
        $data['designation'] = $this->designation->find($id);
        $data['id'] = $id;
        $data['isEdit'] = true;
        return view('setting::designation.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            if (isset($data['display_short_code'])) {
                $data['display_short_code'] = 1;
            } else {
                $data['display_short_code'] = 0;
            }
            $this->designation->update($id, $data);
            toastr('Designation Updated Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Updating Designation', 'error');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->designation->delete($id);

            toastr('Designation Deleted Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Deleting Designation', 'error');
        }
        return redirect()->route('designation.index');
    }
}
