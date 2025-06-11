<?php

namespace App\Modules\Setting\Http\Controllers;

use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Repositories\LeaveEncashmentSetupInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LeaveEncashmentSetupController extends Controller
{
    private $organization;
    private $leaveEncashmentSetup;

    public function __construct(
        OrganizationInterface $organization,
        LeaveEncashmentSetupInterface $leaveEncashmentSetup
    ) {
        $this->organization = $organization;
        $this->leaveEncashmentSetup = $leaveEncashmentSetup;
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
        $data['leaveEncashmentSetups'] = $this->leaveEncashmentSetup->findAll(25, $filter, $sort);
        return view('setting::leave-encashment-setup.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['organizationList'] = $this->organization->getList();
        $data['monthList'] = date_converter()->getNepMonths();
        $data['isEdit'] = false;
        $data['leaveEncashmentSetup'] = null;
        return view('setting::leave-encashment-setup.create', $data);
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
            $this->leaveEncashmentSetup->save($data);
            toastr('Leave Encashment Setup added Successfully', 'success');
        } catch (\Throwable $th) {
            toastr('Error While Adding Leave Encashment Setup', 'error');
        }
        return redirect()->route('leaveEncashmentSetup.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['organizationList'] = $this->organization->getList();
        $data['monthList'] = date_converter()->getNepMonths();
        $data['leaveEncashmentSetup'] = $this->leaveEncashmentSetup->find($id);
        $data['isEdit'] = true;
        return view('setting::leave-encashment-setup.edit', $data);
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
            $this->leaveEncashmentSetup->update($id, $data);
            toastr('Leave Encashment Setup Updated Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Updating Leave Encashment Setup', 'error');
        }
        return redirect()->route('leaveEncashmentSetup.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->leaveEncashmentSetup->delete($id);
            toastr('Leave Encashment Setup Deleted Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Deleting Leave Encashment Setup', 'error');
        }
        return redirect()->route('leaveEncashmentSetup.index');
    }
}
