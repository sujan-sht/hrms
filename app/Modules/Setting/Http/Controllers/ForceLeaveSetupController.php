<?php

namespace App\Modules\Setting\Http\Controllers;

use App\Modules\Setting\Repositories\ForceLeaveSetupInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ForceLeaveSetupController extends Controller
{
    private $forceLeaveSetup;

    public function __construct(
        ForceLeaveSetupInterface $forceLeaveSetup
    ) {
        $this->forceLeaveSetup = $forceLeaveSetup;
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

        $data['forceLeaveSetups'] = $this->forceLeaveSetup->findAll(25, $filter, $sort);
        return view('setting::force-leave-setup.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        return view('setting::force-leave-setup.create', $data);
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
            $this->forceLeaveSetup->save($data);
            toastr('Force Leave Setup added Successfully', 'success');
        } catch (\Throwable $th) {
            toastr('Error While Adding Force Leave Setup', 'error');
        }
        return redirect()->route('forceLeaveSetup.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['forceLeaveSetup'] = $this->forceLeaveSetup->find($id);
        $data['isEdit'] = true;
        return view('setting::force-leave-setup.edit', $data);
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
            $this->forceLeaveSetup->update($id, $data);
            toastr('Force Leave Setup Updated Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Updating Force Leave Setup', 'error');
        }
        return redirect()->route('forceLeaveSetup.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->forceLeaveSetup->delete($id);
            toastr('Force Leave Setup Deleted Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Deleting Force Leave Setup', 'error');
        }
        return redirect()->route('forceLeaveSetup.index');
    }
}
