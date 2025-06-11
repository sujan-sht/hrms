<?php

namespace App\Modules\Onboarding\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use App\Modules\Onboarding\Http\Requests\InterviewLevelRequest;
use App\Modules\Onboarding\Repositories\InterviewLevelInterface;

class InterviewLevelController extends Controller
{
    private $interviewLevelObj;

    /**
     * Constructor
     */
    public function __construct(
        InterviewLevelInterface $interviewLevelObj
    ) {
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
        
        $data['interviewLevelModels'] = $this->interviewLevelObj->findAll(20, $filter);

        return view('onboarding::interview-level.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['isEdit'] = false;

        return view('onboarding::interview-level.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(InterviewLevelRequest $request)
    {
        $inputData = $request->all();

        try {
            $this->interviewLevelObj->create($inputData);
            
            toastr()->success('Data Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('interviewLevel.index'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $data['interviewLevelModel'] = $this->interviewLevelObj->findOne($id);
        
        return view('onboarding::interview-level.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['interviewLevelModel'] = $this->interviewLevelObj->findOne($id);

        return view('onboarding::interview-level.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(InterviewLevelRequest $request, $id)
    {
        $inputData = $request->all();

        try {
            $this->interviewLevelObj->update($id, $inputData);

            toastr()->success('Data Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('interviewLevel.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->interviewLevelObj->delete($id);

            toastr()->success('Data Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

}
