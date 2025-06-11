<?php

namespace App\Modules\Poll\Http\Controllers;

use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Poll\Entities\Poll;
use App\Modules\Poll\Repositories\PollInterface;
use App\Modules\Poll\Repositories\PollOptionInterface;
use App\Modules\Setting\Repositories\DepartmentInterface;
use App\Modules\Setting\Repositories\LevelInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    private $poll;
    private $pollOption;
    private $organization;
    private $dropdown;
    private $department;
    private $level;

    public function __construct(
        PollInterface $poll,
        PollOptionInterface $pollOption,
        OrganizationInterface $organization,
        DropdownInterface $dropdown,
        DepartmentInterface $department,
        LevelInterface $level
    ) {
        $this->poll = $poll;
        $this->pollOption = $pollOption;
        $this->organization = $organization;
        $this->dropdown = $dropdown;
        $this->department = $department;
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
            'sort' => 'desc'
        ];
        $data['pollModels'] = $this->poll->findAll(10, $filter, $sort);
        $data['multipleOptionStatus'] = Poll::multipleOptionStatus();
        return view('poll::poll.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['multipleOptionStatus'] = Poll::multipleOptionStatus();
        return view('poll::poll.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $data['created_by'] = Auth::user()->id;
            if($data['type'] == 1){
                $data['start_date'] = date('Y-m-d');
            }else{
                $data['start_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['start_date']) : $data['start_date'];
            }
            $data['expiry_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['expiry_date']) : $data['expiry_date'];

            $newPoll = $this->poll->save($data);

            foreach($data['options'] as $option)
            {
                if($option != null)
                {
                    $optionData['poll_id'] = $newPoll->id;
                    $optionData['option'] = $option;
                    $this->pollOption->save($optionData);
                }
            }
            toastr()->success('Poll Added Successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong !!!');
        }
        return redirect()->route('poll.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('poll::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['pollModel'] = $this->poll->find($id);
        $data['isEdit'] = true;
        $data['multipleOptionStatus'] = Poll::multipleOptionStatus();
        return view('poll::poll.edit', $data);
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
            if($data['type'] == 1){
                $data['start_date'] = date('Y-m-d');
            }else{
                $data['start_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['start_date']) : $data['start_date'];
            }
            $data['expiry_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['expiry_date']) : $data['expiry_date'];

            $update = $this->poll->update($id, $data);

            // $this->poll->find($id)->options()->delete();
            if($update){
                $this->pollOption->checkAndUpdate($data['options'], $id);
            }

            toastr()->success('Poll Updated Successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong !!!');
        }
        return redirect()->route('poll.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $isDelete = $this->poll->delete($id);
            if($isDelete){
                $this->pollOption->delete($id);
                $this->poll->deletePollResponse($id);
                $this->poll->deletePollParticipants($id);
            }
            toastr()->success('Poll Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    public function addMoreOption()
    {
        $view = view('poll::poll.partial.add-more-option')->render();
        return response()->json(['result' => $view]);
    }

    public function storePollResponse(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $request->except('_token');
                $data['employee_id'] = optional(auth()->user()->userEmployer)->id;
                $resp = $this->poll->checkAndUpdateResponse($data);
                return json_encode($resp);
               
            } catch (\Throwable $th) {
                toastr()->error('Something Went Wrong !!!');
                return redirect()->back();
            }
        }
    }

    public function viewReport(Request $request)
    {
        $sort = [
            'by' => 'id',
            'sort' => 'desc'
        ];
        $filter = $request->all();
        $pollModels = $this->poll->findAll(null, $filter, $sort);

        $report = [];
        foreach ($pollModels as $pollModel) {
            $report[$pollModel->id]['poll_name'] = $pollModel->question;
            $report[$pollModel->id]['total_responses'] = $pollModel->responses->count();

            foreach ($pollModel->options as $pollOption) {
                $report[$pollModel->id]['responses'][$pollOption->option] = $pollOption->responses->count();
            }
        }
        $data['pollFinalReports'] = $report;
        return view('poll::poll.report.overall', $data);
    }

    public function viewEmployeeReport(Request $request)
    {
        $sort = [
            'by' => 'id',
            'sort' => 'desc'
        ];
        $filter = $request->all();
        $pollFilter = [
            'organization_id' => optional(auth()->user()->userEmployer)->organization_id,
            'department_id' => optional(auth()->user()->userEmployer)->department_id,
            'level_id' => optional(auth()->user()->userEmployer)->level_id,
            'checkPollParticipant' => true,
            'status' => $filter['status']
        ];
        $pollModels = $this->poll->findAll(null, $pollFilter, $sort);
        $report = [];
        foreach ($pollModels as $pollModel) {
            $isVoted = $this->poll->checkResponseSubmitted($pollModel->id, optional(auth()->user()->userEmployer)->id);
            if(isset($isVoted) && !empty($isVoted)){
                $report[$pollModel->id]['isVoted'] = 'yes';
            }else{
                $report[$pollModel->id]['isVoted'] = 'no';
            }

            $isExpired = 'no';
            if($pollModel['expiry_date'] && ($pollModel['expiry_date'] < date('Y-m-d'))){
                $isExpired = 'yes';
            }

            $report[$pollModel->id]['isExpired'] = $isExpired;
            $report[$pollModel->id]['poll_name'] = $pollModel->question;
            $report[$pollModel->id]['total_responses'] = $pollModel->responses->count();

            foreach ($pollModel->options as $pollOption) {
                $report[$pollModel->id]['responses'][$pollOption->id] = $pollOption->responses->count();
            }
        }
        $data['pollFinalReports'] = $report;
        return view('poll::poll.report.employee-overall', $data);
    }

    public function allocateForm($id) {
        $data['poll_id'] = $id;
        $data['organizationList'] = $this->organization->getList();
        $data['departmentList'] = $this->department->getList();
        $data['levelList'] = $this->level->getList();
        return view('poll::poll.allocation.create', $data);
    }

    public function allocate(Request $request)
    {
        $inputData = $request->except('_token');
        try {
            $this->poll->updateOrCreatePollParticipant($inputData);
            toastr()->success('Poll Allocated Successfully !!!');
        } catch (\Throwable $th) {
            dd($th);
            toastr()->error('Something went wrong !!!');
        }
        return redirect()->route('poll.index');
    }
    public function allocationList(Request $request)
    {
        $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'desc'
        ];
        $data['pollModels'] = $this->poll->findAll(5, $filter, $sort);
        return view('poll::poll.allocation.index', $data);
    }
}
