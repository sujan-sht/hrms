<?php

namespace App\Modules\Onboarding\Http\Controllers;

use App\Modules\Onboarding\Entities\OfferLetter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use App\Modules\Onboarding\Http\Requests\OfferLetterRequest;
use App\Modules\Onboarding\Repositories\ApplicantInterface;
use App\Modules\Onboarding\Repositories\OfferLetterInterface;

class OfferLetterController extends Controller
{
    private $offerLetterObj;
    private $applicantObj;

    /**
     * Constructor
     */
    public function __construct(
        OfferLetterInterface $offerLetterObj,
        ApplicantInterface $applicantObj
    ) {
        $this->offerLetterObj = $offerLetterObj;
        $this->applicantObj = $applicantObj;
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
        if(auth()->user()->user_type == 'division_hr') {
            $filter['organization'] = optional(auth()->user()->userEmployer)->organization_id;
        }

        $data['offerLetterModels'] = $this->offerLetterObj->findAll(20, $filter);
        $data['statusList'] = OfferLetter::statusList();

        return view('onboarding::offer-letter.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $inputData = $request->all();

        $data['isEdit'] = false;
        $data['evaluationId'] = $inputData['evaluation'];
        $data['statusList'] = OfferLetter::statusList();

        return view('onboarding::offer-letter.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(OfferLetterRequest $request)
    {
        $inputData = $request->all();

        try {
            $this->offerLetterObj->create($inputData);

            toastr()->success('Data Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('offerLetter.index'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $data['offerLetterModel'] = $this->offerLetterObj->findOne($id);

        return view('onboarding::offer-letter.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $offerLetterModel = $this->offerLetterObj->findOne($id);
        $data['offerLetterModel'] = $offerLetterModel;
        $data['evaluationId'] = $offerLetterModel->evaluation_id;
        $data['statusList'] = OfferLetter::statusList();

        return view('onboarding::offer-letter.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(OfferLetterRequest $request, $id)
    {
        $inputData = $request->all();
        try {
            $offerLetter = $this->offerLetterObj->findOne($id);
            $update = $this->offerLetterObj->update($id, $inputData);

            if ($update) {
                $applicant_id = $offerLetter->evaluationModel->applicant_id;
                $data['status'] = 3;
                $this->applicantObj->update($applicant_id, $data);
            }
            toastr()->success('Data Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('offerLetter.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->offerLetterObj->delete($id);

            toastr()->success('Data Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {
        $inputData = $request->all();
        try {
            $this->offerLetterObj->update($inputData['id'], $inputData);
            toastr()->success('Status Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }
}
