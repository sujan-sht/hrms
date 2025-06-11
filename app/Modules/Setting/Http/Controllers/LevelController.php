<?php

namespace App\Modules\Setting\Http\Controllers;

use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Entities\LevelDesignation;
use App\Modules\Setting\Entities\LevelOrganization;
use App\Modules\Setting\Repositories\DepartmentInterface;
use App\Modules\Setting\Repositories\DesignationInterface;
use App\Modules\Setting\Repositories\LevelInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LevelController extends Controller
{
    private $organization;
    private $designation;
    private $level;


    public function __construct(
        OrganizationInterface $organization,
        DesignationInterface $designation,
        LevelInterface $level
    ) {
        $this->organization = $organization;
        $this->designation = $designation;
        $this->level = $level;
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
        $data['designationList'] = $this->designation->getList();
        $data['levels'] = $this->level->findAll(25, $filter, $sort);
        return view('setting::level.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['organizationList'] = $this->organization->getList();
        $data['designationList'] = $this->designation->getList();
        $data['isEdit'] = false;
        return view('setting::level.create', $data);
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
            $this->level->save($data);
            toastr('Grade added Successfully', 'success');
        } catch (\Throwable $th) {
            toastr('Error While Adding Grade', 'error');
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
        $data['level'] = $this->level->find($id);
        return view('setting::level.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['organizationList'] = $this->organization->getList();
        $data['designationList'] = $this->designation->getList();
        $data['level'] = $this->level->find($id);
        $data['id'] = $id;
        $data['isEdit'] = true;
        return view('setting::level.edit', $data);
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
            $this->level->update($id, $data);
            toastr('Grade Updated Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Updating Grade', 'error');
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
            $this->level->delete($id);
            toastr('Grade Deleted Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Deleting Grade', 'error');
        }
        return redirect()->route('level.index');
    }
}
