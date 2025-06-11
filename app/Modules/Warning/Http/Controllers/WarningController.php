<?php

namespace App\Modules\Warning\Http\Controllers;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Warning\Entities\Warning;
use App\Modules\Warning\Http\Requests\WarningRequest;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Modules\Notification\Entities\Notification;
use App\Modules\User\Entities\User;
use App\Modules\Warning\Repositories\WarningInterface;
use App\Modules\Admin\Entities\MailSender;
use App\Modules\Setting\Entities\Setting;

class WarningController extends Controller
{
    protected $organization;
    protected $employee;
    protected $warning;



    public function __construct(OrganizationInterface $organization, EmployeeInterface $employee, WarningInterface $warning){
        $this->organization = $organization;
        $this->employee = $employee;
        $this->warning = $warning;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data['filter']=$request->all();
        $data['warnings']= $this->warning->findAll(10,$data['filter']);
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employee->getList();


        return view('warning::index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employee->getList();

        return view('warning::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(WarningRequest $request)
    {

        try{
            $input_data=$request->all();
            if (!empty($request->employee_id)) {
                $input_data['employee_id'] = json_encode($request->employee_id);
            }
            if (setting('calendar_type') == 'BS'){
                $input_data['date'] = date_converter()->nep_to_eng_convert($request->date);
            }
            // dd(auth()->user()->user_type);
            if(auth()->user()->user_type == 'supervisor' || auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'employee'){
                $input_data['organization_id']=auth()->user()->userEmployer->organization_id;
            }

            $warning=$this->warning->create($input_data);
            $this->sendMailNotification($warning);
            toastr()->success('Warning Created Successfully');
        }catch(\Throwable $e){
            toastr()->error($e->getMessage());
        }

        return redirect(route('warning.index'));

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request, $id)
    {
        $filter=$request->all();


        $data['warning']=Warning::find($id);
        return view('warning::view',$data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['warning']=Warning::find($id);
        $data['organization_id'] = $data['warning']->organization_id;
        $data['employee_id'] = json_decode($data['warning']->employee_id);
        $data['organizationList'] = $this->organization->getList();

        if(!is_null($data['organization_id']) && (!is_null($data['employee_id']))){
            $data['employeeList'] = Employee::whereIn('id', $data['employee_id'])->get()->pluck('full_name', 'id');
        }
        return view('warning::edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(WarningRequest $request, $id)
    {
        try{
            $input_data=$request->all();
            $room=Warning::find($id);
            if (!empty($request->employeeId)) {
                $input_data['employee_id'] = json_encode($request->employeeId);
            }
            if(auth()->user()->user_type != 'super_admin' || auth()->user()->user_type != 'hr' || auth()->user()->user_type != 'admin'){
                $input_data['organization_id']=auth()->user()->userEmployer->organization_id;
            }
            $room->update($input_data);
            toastr()->success('Warning Updated Successfully');
        }catch(\Throwable $e){
            toastr()->error($e->getMessage());
        }

        return redirect(route('warning.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try{
            $room=Warning::find($id);
            $room->delete();
            toastr()->success('Warning Deleted Successfully');
        }catch(\Throwable $e){
            toastr()->error($e->getMessage());
        }

        return redirect(route('warning.index'));
    }


    public function sendMailNotification($model)
    {
        $authUser = auth()->user();



        if ($authUser->user_type == 'super_admin') {
            $authorName = $authUser->first_name;
        } else {
            $authorName = optional($authUser->userEmployer)->full_name;
        }
        $mailArray=[];
        // dd($model);
        if (!empty(json_decode($model->employee_id, true))) {
            $employeeLists = Employee::whereIn('id', json_decode($model->employee_id))->get();
            foreach($employeeLists as $employeeModel){
                if(emailSetting(1) == 11){
                    // send email to employee who needs leave
                    $notified_user_email = User::getUserEmail(optional($employeeModel->getUser)->id);
                    if (isset($notified_user_email) && !empty($notified_user_email)) {
                        $notified_user_fullname = Employee::getName(optional($employeeModel->getUser)->id);

                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $model->description ?? null,
                            'date' => $model->date ?? null,
                            'ref_no' => $model->ref_no ?? null,
                            'reg_no' => $model->reg_no ?? null,
                            'notified_user_fullname' => $notified_user_fullname,
                            'subject' => $model->title,
                            'setting' => Setting::first(),
                        );
                        $mailArray[] = $details;
                    }
                }
            }

        }  elseif (!is_null($model->organization_id)) {
            $organization = $model->organization_id;
            $employeeLists = Employee::where(function ($query) use ($organization) {
                $query->where('status', '1');
                if ($organization) {
                    $query->where('organization_id', $organization);
                }
            })->get();
        }


        $playerArrayId = [];
        foreach ($employeeLists as $employee) {
            if (is_null($employee->getUser)) continue;
            $userModel = optional($employee->getUser);

            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employee->getUser)->id;
            $notificationData['message'] = "A Warning letter has been issued to you by " . $authUser->user_type . ' ' . "Sub-Function";
            $notificationData['link'] = route('warning.view', $model->id);
            $notificationData['type'] = 'warning';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            if ($userModel->device) {
                $playerArrayId[] = optional($userModel->device)->os_player_id;
            }
        }
        // Send all email at once
        if (count($mailArray) > 0) {
            $mail = new MailSender();
            foreach ($mailArray as $mailDetail) {
                try {
                    $mail->sendMail('admin::mail.warning', $mailDetail);

                } catch (\Throwable $th) {
                    continue;
                }
            }
        }


        return true;
    }


}
