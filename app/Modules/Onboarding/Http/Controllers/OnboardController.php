<?php

namespace App\Modules\Onboarding\Http\Controllers;

use App\Modules\BoardingTask\Entities\BoardingTask;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Onboarding\Repositories\MrfInterface;
use App\Modules\Onboarding\Http\Requests\OnboardRequest;
use App\Modules\Onboarding\Repositories\OnboardInterface;
use App\Modules\Onboarding\Repositories\ApplicantInterface;
use App\Modules\BoardingTask\Repositories\BoardingTaskInterface;
use App\Modules\Onboarding\Entities\Applicant;
use App\Modules\Onboarding\Entities\Onboard;
use Illuminate\Support\Facades\DB;

class OnboardController extends Controller
{
    protected $boardingTaskObj;
    protected $onboardObj;
    protected $mrfObj;
    protected $applicantObj;

    /**
     *
     */
    public function __construct(
        BoardingTaskInterface $boardingTaskObj,
        OnboardInterface $onboardObj,
        MrfInterface $mrfObj,
        ApplicantInterface $applicantObj
    ) {
        $this->boardingTaskObj = $boardingTaskObj;
        $this->onboardObj = $onboardObj;
        $this->mrfObj = $mrfObj;
        $this->applicantObj = $applicantObj;
    }

    /**
     *
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        if(auth()->user()->user_type == 'division_hr') {
            $filter['organization'] = optional(auth()->user()->userEmployer)->organization_id;
        }
        // $data['onboardModels'] = $this->onboardObj->findAll(20, $filter);
        $data['mrfList'] = $this->mrfObj->getListWithTitle();
        $data['applicantList'] = $this->applicantObj->getList();
        $data['boardingTaskList'] = $this->boardingTaskObj->getList();

        // return view('onboarding::onboard.index', $data);

        // $data['listModels'] = $this->onboardObj->findAll(null, $filter)->groupBy(['manpower_requisition_form_id', 'applicant_id']);
        $bordingtasks = BoardingTask::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['task']) && !empty($filter['task'])) {
                $query->where('id', $filter['task']);
            }
        })->get();
        $data['preboardingtasks'] = $preboardingtasks = $bordingtasks->where('category',1);
        $data['boardingtasks'] = $boardingtasks = $bordingtasks->where('category',2);
        $data['postboardingtasks'] = $postboardingtasks = $bordingtasks->where('category',3);

        $data['applicantModels'] = Applicant::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization']) && !empty($filter['organization'])) {
                $query->whereHas('mrfModel',function($query){
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            });
            }
            if (isset($filter['mrf']) && !empty($filter['mrf'])) {
                $query->whereHas('mrfModel', function ($query) use ($filter) {
                    $query->where('manpower_requisition_form_id', $filter['mrf']);
                });
            }
            if (isset($filter['applicant']) && !empty($filter['applicant'])) {
                $query->where('id', $filter['applicant']);
            }
        })->with('mrfModel')->get()->map(function ($applicant) {
            $applicant->boarding =  Onboard::where('applicant_id', $applicant->id)->where('onboard_date', '!=', NULL)->pluck('boarding_task_id')->toArray();
            return $applicant;
        });


        // dd($data);
        return view('onboarding::onboard.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['boardingTaskModels'] = $this->boardingTaskObj->getList('Onboarding');
        $data['mrfList'] = $this->mrfObj->getListWithTitle();
        $data['applicantList'] = $this->applicantObj->getList();
        $data['statusList'] = [
            1 => 'Mark Completed'
        ];

        return view('onboarding::onboard.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(OnboardRequest $request)
    {
        $inputData = $request->all();
        try {
            $this->onboardObj->create($inputData);
            toastr()->success('Data Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('onboard.index'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $data['onboardModel'] = $this->onboardObj->findOne($id);

        return view('onboarding::onboard.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request)
    {
        $inputData = $request->all();

        $data['isEdit'] = true;
        $data['onboardModel'] = Onboard::where(['manpower_requisition_form_id'=>$inputData['mrf'], 'applicant_id'=>$inputData['applicant']])->first();
        $data['onboardModels'] = Onboard::where(['manpower_requisition_form_id'=>$inputData['mrf'], 'applicant_id'=>$inputData['applicant']])->get();
        $data['boardingTaskModels'] = $this->boardingTaskObj->getListWithData('Onboarding', $inputData['mrf'], $inputData['applicant']);
        $data['mrfList'] = $this->mrfObj->getListWithTitle();
        $data['applicantList'] = $this->applicantObj->getList();
        $data['statusList'] = [
            1 => 'Mark Completed'
        ];

        return view('onboarding::onboard.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(OnboardRequest $request)
    {
        $data = $request->all();
        // Start a database transaction
        DB::beginTransaction();
        try {
            // flush old data
            Onboard::where(['manpower_requisition_form_id'=>$data['manpower_requisition_form_id'], 'applicant_id'=>$data['applicant_id']])->delete();
            // <i class="icon-plus2"></i> Add data
            $this->onboardObj->create($data);
            // Commit the transaction if everything goes well
            DB::commit();

            toastr()->success('Data Updated Successfully');
        } catch (\Throwable $e) {
            // Rollback the transaction if there is an error
            DB::rollBack();
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('onboard.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->onboardObj->delete($id);
            toastr()->success('Data Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

}

