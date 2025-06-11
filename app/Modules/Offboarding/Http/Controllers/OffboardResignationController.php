<?php

namespace App\Modules\Offboarding\Http\Controllers;

use App\Modules\Setting\Repositories\SettingInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Offboarding\Entities\OffboardClearance;
use App\Modules\Offboarding\Entities\OffboardResignation;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Offboarding\Http\Requests\OffboardResignationRequest;
use App\Modules\Offboarding\Repositories\OffboardClearanceInterface;
use App\Modules\Offboarding\Repositories\OffboardResignationInterface;
use Illuminate\Support\Facades\Artisan;

class OffboardResignationController extends Controller
{
    private $organizationObj;
    private $employeeObj;
    private $resignationObj;
    private $clearanceObj;
    private $setting;

    /**
     * Constructor
     */
    public function __construct(
        OrganizationInterface $organizationObj,
        EmployeeInterface $employeeObj,
        OffboardResignationInterface $resignationObj,
        OffboardClearanceInterface $clearanceObj,
        SettingInterface $setting
    ) {
        $this->organizationObj = $organizationObj;
        $this->employeeObj = $employeeObj;
        $this->resignationObj = $resignationObj;
        $this->clearanceObj = $clearanceObj;
        $this->setting = $setting;
    }

    /**
     *
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        $authUser = auth()->user();
        if(auth()->user()->user_type == 'division_hr') {
            $filter['organization'] = optional(auth()->user()->userEmployer)->organization_id;
        }
        if ($authUser->user_type == 'supervisor' || $authUser->user_type == 'employee') {
            $filter['employee_id'] = $authUser->emp_id;
        }

        $data['resignationModels'] = $this->resignationObj->findAll(20, $filter);
        $data['employeeList'] = $this->employeeObj->getList();
        $data['statusList'] = OffboardResignation::getStatusList();

        return view('offboarding::resignation.index', $data);
    }

    /**
     *
     */
    public function teamRequest(Request $request)
    {
        $filter = $request->all();

        $data['resignationModels'] = $this->resignationObj->findTeamRequest(20, $filter);
        $data['employeeList'] = $this->employeeObj->getList();
        $data['statusList'] = OffboardResignation::getStatusList();

        return view('offboarding::resignation.index-team', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $inputData = $request->all();

        $authUser = auth()->user();
        if ($authUser->user_type == 'employee') {
            $data['employeeId'] = $authUser->emp_id;
        }

        if (isset($inputData['employee'])) {
            $data['employeeId'] = $inputData['employee'];
        }

        $data['isEdit'] = false;
        $data['employeeList'] = $this->employeeObj->getList();

        return view('offboarding::resignation.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(OffboardResignationRequest $request)
    {
        $inputData = $request->all();
        $setting = $this->setting->getdata();

        try {
            $inputData['last_working_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($inputData['last_working_date']) : $inputData['last_working_date'];
            if ($request->hasFile('attachment')) {
                $inputData['attachment'] = $this->resignationObj->upload($inputData['attachment']);
            }

            $model = $this->resignationObj->create($inputData);

            if ($model) {
                $model['enable_mail'] = $setting->enable_mail;
                $this->resignationObj->sendMailNotification($model);
                toastr()->success('Data created successfully');
            }
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('resignation.index'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $resignationModel = $this->resignationObj->findOne($id);

        $data['resignationModel'] = $resignationModel;

        return view('offboarding::resignation.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['resignationModel'] = $this->resignationObj->findOne($id);
        $data['employeeList'] = $this->employeeObj->getList();

        return view('offboarding::resignation.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(OffboardResignationRequest $request, $id)
    {
        $inputData = $request->all();

        try {
            $inputData['last_working_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($inputData['last_working_date']) : $inputData['last_working_date'];

            if ($request->hasFile('attachment')) {
                $inputData['attachment'] = $this->resignationObj->upload($inputData['attachment']);
            }
            $this->resignationObj->update($id, $inputData);

            toastr()->success('Data Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('resignation.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->resignationObj->delete($id);

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
        $setting = $this->setting->getdata();

        try {
            $result = $this->resignationObj->update($inputData['id'], $inputData);

            if ($result) {
                $model = $this->resignationObj->findOne($inputData['id']);
                $data = $this->clearanceObj->findAll();
                $resignationModel = $this->resignationObj->findOne($inputData['id']);
                $model['enable_mail'] = $setting->enable_mail;
                $this->resignationObj->sendMailNotification($model);
                if ($inputData['status'] == 3) {
                    $data['enable_mail'] = $setting->enable_mail;
                    $this->clearanceObj->sendMailNotification($data, $resignationModel);
                }
                toastr()->success('Status Updated Successfully');
            }
        } catch (\Throwable $e) {
            // dd($e);
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    public function showReport($id)
    {
        $data['clearanceModels'] = $this->clearanceObj->findAll();
        $data['resignationModel'] = $this->resignationObj->findOne($id);
        return view('offboarding::resignation.report.show-report', $data);
    }

    public function letterIssued(Request $request)
    {
        $inputData = $request->all();
        try {
            $result = $this->resignationObj->update($inputData['id'], $inputData);
            toastr()->success('Letter Issued Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    public function letterReceived(Request $request)
    {
        $inputData = $request->all();

        // try {
            $result = $this->resignationObj->update($inputData['id'], $inputData);
            toastr()->success('Letter Received Successfully');
        // } catch (\Throwable $e) {
        //     toastr()->error('Something Went Wrong !!!');
        // }

        return redirect()->back();
    }

    public function terminateEmployee()
    {
        Artisan::call('terminate:resigned-user');
    }
}
