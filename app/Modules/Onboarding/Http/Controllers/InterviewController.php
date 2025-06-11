<?php

namespace App\Modules\Onboarding\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use App\Modules\Onboarding\Entities\Interview;
use App\Modules\Onboarding\Repositories\MrfInterface;
use App\Modules\Onboarding\Http\Requests\InterviewRequest;
use App\Modules\Onboarding\Repositories\ApplicantInterface;
use App\Modules\Onboarding\Repositories\InterviewInterface;
use App\Modules\Onboarding\Repositories\InterviewLevelInterface;

class InterviewController extends Controller
{
    private $interviewObj;
    private $mrfObj;
    private $applicantObj;
    private $interviewLevelObj;

    /**
     * Constructor
     */
    public function __construct(
        InterviewInterface $interviewObj,
        MrfInterface $mrfObj,
        ApplicantInterface $applicantObj,
        InterviewLevelInterface $interviewLevelObj
    ) {
        $this->interviewObj = $interviewObj;
        $this->mrfObj = $mrfObj;
        $this->applicantObj = $applicantObj;
        $this->interviewLevelObj = $interviewLevelObj;
    }

    /**
     *
     */
    public function getCurrentUserDetail()
    {
        return User::where('id', Auth::user()->id)->first();
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

        $data['interviewModels'] = $this->interviewObj->findAll(20, $filter);
        // dd($data['interviewModels']);
        $data['statusList'] = Interview::statusList();

        return view('onboarding::interview.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $inputData = $request->all();
        if (isset($inputData['applicant'])) {
            $data['applicantId'] = $inputData['applicant'];
            $data['applicantModel'] = $this->applicantObj->findOne($inputData['applicant']);
        }

        $data['isEdit'] = false;
        $data['applicantList'] = $this->applicantObj->getList();
        // dd($data['applicantList']);
        $data['interviewLevelList'] = $this->interviewLevelObj->getList();
        $data['statusList'] = Interview::statusList();

        return view('onboarding::interview.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(InterviewRequest $request)
    {
        $inputData = $request->all();
        $inputData['date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($inputData['date']) : $inputData['date'];

        try {
            $model = $this->interviewObj->create($inputData);
            $this->interviewObj->sendMailNotification($model);

            toastr()->success('Data Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('interview.index'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $model = $this->interviewObj->findOne($id);

        $data['isEdit'] = true;
        $data['interviewModel'] = $model;
        $data['applicantModel'] = $this->applicantObj->findOne($model->applicant_id);
        $data['applicantList'] = $this->applicantObj->getList();
        $data['interviewLevelList'] = $this->interviewLevelObj->getList();
        $data['statusList'] = Interview::statusList();

        return view('onboarding::interview.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(InterviewRequest $request, $id)
    {
        $inputData = $request->all();
        $inputData['date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($inputData['date']) : $inputData['date'];

        try {
            $this->interviewObj->update($id, $inputData);

            toastr()->success('Data Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('interview.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->interviewObj->delete($id);

            toastr()->success('Data Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    /**
     *
     */
    public function updateStatus(Request $request)
    {
        $inputData = $request->all();

        try {
            $this->interviewObj->update($inputData['id'], $inputData);

            toastr()->success('Status Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }
}
