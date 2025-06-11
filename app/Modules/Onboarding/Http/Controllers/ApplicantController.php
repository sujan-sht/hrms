<?php

namespace App\Modules\Onboarding\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ApplicantExport;
use Illuminate\Routing\Controller;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Admin\Entities\MailSender;
use App\Modules\Onboarding\Entities\Applicant;
use App\Modules\Onboarding\Repositories\MrfInterface;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Onboarding\Http\Requests\ApplicantRequest;
use App\Modules\Onboarding\Repositories\ApplicantInterface;
use App\Modules\Onboarding\Repositories\InterviewInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;

class ApplicantController extends Controller
{
    private $mrfObj;
    private $applicantObj;
    private $organizationObj;
    private $employeeObj;
    private $dropdownObj;
    private $interviewObj;

    /**
     * Constructor
     */
    public function __construct(
        ApplicantInterface $applicantObj,
        MrfInterface $mrfObj,
        OrganizationInterface $organizationObj,
        EmployeeInterface $employeeObj,
        DropdownInterface $dropdownObj,
        InterviewInterface $interviewObj
    ) {
        $this->applicantObj = $applicantObj;
        $this->mrfObj = $mrfObj;
        $this->organizationObj = $organizationObj;
        $this->employeeObj = $employeeObj;
        $this->dropdownObj = $dropdownObj;
        $this->interviewObj = $interviewObj;
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

        $data['applicantModels'] = $this->applicantObj->findAll(20, $filter);
        $data['mrfList'] = $this->mrfObj->getList();
        $data['genderList'] = Applicant::genderList();
        $data['sourceList'] = Applicant::sourceList();
        $data['statusList'] = Applicant::statusList();

        return view('onboarding::applicant.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $inputData = $request->all();

        if (isset($inputData['mrf'])) {
            $data['mrfId'] = $inputData['mrf'];
        }

        $data['isEdit'] = false;
        $data['mrfList'] = $this->mrfObj->getListWithTitle();
        $data['genderList'] = Applicant::genderList();
        $data['sourceList'] = Applicant::sourceList();
        $data['statusList'] = Applicant::statusList();

        return view('onboarding::applicant.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(ApplicantRequest $request)
    {
        $inputData = $request->all();

        try {
            if ($request->hasFile('resume')) {
                $inputData['resume'] = $this->applicantObj->uploadResume($inputData['resume']);
            }
            if ($request->hasFile('cover_letter')) {
                $inputData['cover_letter'] = $this->applicantObj->uploadCoverLetter($inputData['cover_letter']);
            }
            $model = $this->applicantObj->create($inputData);
            if($model) {
                // send email to all hr
                $notified_user_email = $model->email;
                if (isset($notified_user_email) && !empty($notified_user_email)) {
                    $mailDetail = array(
                        'email' => $notified_user_email,
                        'subject' => 'Welcome to '.setting('company_name'),
                        'notified_user_fullname' => $model->full_name,
                        'setting' => Setting::first()
                    );
                    $mail = new MailSender();
                    $mail->sendMail('admin::mail.mrf_reply', $mailDetail);
                }
            }
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        $currentUser = $this->getCurrentUserDetail();
        if ($currentUser->user_type == 'employee') {
            toastr()->success('Your application has been submitted successfully');
            return redirect(route('dashboard'));
        }

        return redirect(route('applicant.index'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $applicantModel = $this->applicantObj->findOne($id);

        $interviewFilter = ['applicant' => $applicantModel->id];
        $data['interviewModels'] = $this->interviewObj->findAll(null, $interviewFilter);
        $data['applicantModel'] = $applicantModel;

        return view('onboarding::applicant.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['applicantModel'] = $this->applicantObj->findOne($id);
        $data['mrfList'] = $this->mrfObj->getListWithTitle();
        $data['genderList'] = Applicant::genderList();
        $data['sourceList'] = Applicant::sourceList();
        $data['statusList'] = Applicant::statusList();

        return view('onboarding::applicant.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(ApplicantRequest $request, $id)
    {
        $inputData = $request->all();

        try {
            if ($request->hasFile('resume')) {
                $inputData['resume'] = $this->applicantObj->uploadResume($inputData['resume']);
            }
            if ($request->hasFile('cover_letter')) {
                $inputData['cover_letter'] = $this->applicantObj->uploadCoverLetter($inputData['cover_letter']);
            }
            $this->applicantObj->update($id, $inputData);

            toastr()->success('Data Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('applicant.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->applicantObj->delete($id);

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
            $this->applicantObj->update($inputData['id'], $inputData);

            toastr()->success('Status Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    /**
     * 
     */
    public function exportExcel() 
    {
        toastr()->success('Exported Successfully');

        return Excel::download(new ApplicantExport, 'applicants.xlsx');
    }
}
