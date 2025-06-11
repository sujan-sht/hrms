<?php

namespace App\Modules\ApprovalFlow\Http\Controllers;

use App\Modules\ApprovalFlow\Http\Requests\CreateApprovalFlowRequest;
use App\Modules\ApprovalFlow\Repositories\ApprovalFlowInterface;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Setting\Repositories\DepartmentInterface;
use App\Modules\User\Repositories\UserInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ApprovalFlowController extends Controller
{
    protected $dropdown;
    protected $approvalFlow;
    protected $user;
    protected $department;

    public function __construct(DropdownInterface $dropdown, ApprovalFlowInterface $approvalFlow, UserInterface $user, DepartmentInterface $department)
    {
        $this->dropdown = $dropdown;
        $this->approvalFlow = $approvalFlow;
        $this->user = $user;
        $this->department = $department;
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
        $data['approvalFlowModels'] = $this->approvalFlow->findAll(20, $filter, $sort);
        $data['departmentList'] = $this->department->getList();

        return view('approvalflow::approvalFlow.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['departmentList'] = $this->department->getList();
        $data['userList'] = $this->user->getAllActiveUserList();

        return view('approvalflow::approvalFlow.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateApprovalFlowRequest $request)
    {
        $inputData = $request->all();
        $inputData['created_by'] = Auth::user()->id;
        try {
            $this->approvalFlow->create($inputData);
            toastr()->success('Approval Flow Created Successfully');
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('approvalFlow.index'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('approvalflow::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['departmentList'] = $this->department->getList();
        $data['userList'] = $this->user->getAllActiveUserList();
        $data['approvalFlowModel'] = $this->approvalFlow->findOne($id);
        return view('approvalflow::approvalFlow.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CreateApprovalFlowRequest $request, $id)
    {
        $data = $request->all();
        $data['updated_by'] = Auth::user()->id;

        try {
            $this->approvalFlow->update($id, $data);

            toastr()->success('Approval Flow Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('approvalFlow.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->approvalFlow->delete($id);

            toastr()->success('Approval Flow Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    public function fetchDepartmentApprovals(Request $request)
    {
        $approvalData = $this->approvalFlow->fetchApprovals($request->department_id);
        if (is_null($approvalData)) {
            $final_approval_data = [];
        } else {
            $final_approval_data = [
                'first_approval_user_id' => $approvalData['first_approval_user_id'],
                'last_approval_user_id' => $approvalData['last_approval_user_id'],
            ];
        }
        return json_encode($final_approval_data);
    }
}
