<?php

namespace App\Modules\Labour\Http\Controllers;

use App\Exports\LabourWageExport;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Attendance\Entities\LabourAttendanceMonthly;
use App\Modules\Attendance\Repositories\AttendanceReportInterface;
use App\Modules\Labour\Entities\Labour;
use App\Modules\Labour\Entities\LabourPayment;
use App\Modules\Labour\Entities\SkillSetup;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Repositories\SettingInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class LabourController extends Controller
{
    protected $organization;
    protected $attendanceReport;
    protected $settingObj;


    public function __construct(
        OrganizationInterface $organization,
        SettingInterface $settingObj,
        AttendanceReportInterface $attendanceReport
    ) {
        $this->organization = $organization;
        $this->attendanceReport = $attendanceReport;
        $this->settingObj = $settingObj;

    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data['labours'] = Labour::paginate(10);
        $data['organizations'] = $this->organization->findAll()->pluck('name', 'id');
        return view('labour::labour.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['skills'] = SkillSetup::pluck('category', 'id');
        $data['organizations'] = $this->organization->findAll()->pluck('name', 'id');
        return view('labour::labour.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        $inputData = $request->validate([
            'organization' => 'required',
            'first_name' => 'required|string|max:200',
            'middle_name' => 'nullable',
            'last_name' => 'required|string|max:200',
            'skill_type' => 'required',
            'pan_no' => 'nullable',
            'join_date' => 'nullable',
            'attachment' => 'nullable',
            'description' => 'nullable'
        ]);
        if (setting('calendar_type') == 'BS') {
            $inputData['join_date'] = date_converter()->nep_to_eng_convert($request->join_date);
        }
        try {
            if(isset($inputData['attachment']) && $inputData['attachment'] != null){
                $imageName = $inputData['attachment']->getClientOriginalName();
                $fileName = date('Y-m-d-h-i-s') . '-' . preg_replace('[ ]', '-', $imageName);
        
                $inputData['attachment']->move(public_path() . Labour::FILE_PATH, $fileName);
                $inputData['attachment']=$fileName;
            }
            
            Labour::create($inputData);
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }
        toastr()->success('Labour Created Successfully.');
        return redirect()->route('labour.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('labour::labour.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['labour'] = Labour::find($id);
        $data['skills'] = SkillSetup::pluck('category', 'id');
        $data['organizations'] = $this->organization->findAll()->pluck('name', 'id');
        return view('labour::labour.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $inputData = $request->validate([
            'organization' => 'required',
            'first_name' => 'required|string|max:200',
            'middle_name' => 'nullable',
            'last_name' => 'required|string|max:200',
            'skill_type' => 'required',
            'pan_no' => 'nullable',
            'join_date' => 'nullable',
            'attachment' => 'nullable',
            'description' => 'nullable'
        ]);
        if (setting('calendar_type') == 'BS') {
            $inputData['join_date'] = date_converter()->nep_to_eng_convert($request->join_date);
        }
        try {
            if(isset($inputData['attachment']) && $inputData['attachment'] != null){
                $imageName = $inputData['attachment']->getClientOriginalName();
                $fileName = date('Y-m-d-h-i-s') . '-' . preg_replace('[ ]', '-', $imageName);
        
                $inputData['attachment']->move(public_path() . Labour::FILE_PATH, $fileName);
                $inputData['attachment']=$fileName;
            }
            $data['labour'] = Labour::find($id);
            $data['labour']->update($inputData);
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }
        toastr()->success('Labour edited Successfully.');
        return redirect()->route('labour.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $skill = Labour::find($id);
            $skill->delete();
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }
        toastr()->success('Labour deleted Successfully.');
        return redirect()->route('labour.index');
    }

    public function paymentStore(Request $request)
    {

        $inputData = $request->all();
        if($inputData['calendar_type'] == 'nep'){
            LabourPayment::create([
                'employee_id' => $inputData['emp_id'],
                'payable_amount' => $inputData['payable_amount'],
                'paid_amount' => $inputData['paid_amount'],
                'nep_year' => $inputData['nep_year'],
                'nep_month' => $inputData['nep_month'],
                'remarks' => $inputData['remarks'],
                'paid_date' => $inputData['date']
            ]);
        }else{
            LabourPayment::create([
                'employee_id' => $inputData['emp_id'],
                'payable_amount' => $inputData['payable_amount'],
                'paid_amount' => $inputData['paid_amount'],
                'eng_year' => $inputData['eng_year'],
                'eng_month' => $inputData['eng_month'],
                'remarks' => $inputData['remarks'],
                'paid_date' => $inputData['date']
            ]);
        }
        toastr()->success('Payment done Successfully.');
        return back();
    
       
    }

    public function printPaySLip(Request $request)
    {
        $inputData=$request->all();
        $data['payrollEmployee'] = Labour::find($inputData['id']);
        $data['paySlip']=LabourPayment::where('employee_id',$inputData['id'])->where('nep_year',$inputData['nep_year'])->where('nep_month',$inputData['nep_month'])->first();
        $data['setting'] = $this->settingObj->getData();
        $data['days'] = date_converter()->getTotalDaysInMonth($data['paySlip']->nep_year, $data['paySlip']->nep_month);
        $nepStartDate = $data['paySlip']->nep_year . '-' . $data['paySlip']->nep_month . '-01';
        $data['startDate']= date_converter()->nep_to_eng_convert($nepStartDate);
        $nepEndDate = $data['paySlip']->nep_year . '-' . $data['paySlip']->nep_month . '-' . $data['days'];
        $data['endDate']= date_converter()->nep_to_eng_convert($nepEndDate);

        return view('labour::labour.partial.payslip', $data);
    }

    public function viewPayslip(Request $request)
    {
        $inputData=$request->all();
        $data['payrollEmployee'] = Labour::find($inputData['employee_id']);
        $data['paySlip']=LabourPayment::where('employee_id',$inputData['employee_id'])->where('nep_year',$inputData['nep_year'])->where('nep_month',$inputData['nep_month'])->first();
       
        $data['setting'] = $this->settingObj->getData();
        $data['days'] = date_converter()->getTotalDaysInMonth($data['paySlip']->nep_year, $data['paySlip']->nep_month);
        $nepStartDate = $data['paySlip']->nep_year . '-' . $data['paySlip']->nep_month . '-01';
        $data['startDate']= date_converter()->nep_to_eng_convert($nepStartDate);
        $nepEndDate = $data['paySlip']->nep_year . '-' . $data['paySlip']->nep_month . '-' . $data['days'];
        $data['endDate']= date_converter()->nep_to_eng_convert($nepEndDate);

        return view('labour::labour.partial.payslip', $data);
    }

    public function wageManagement(Request $request)
    {
        $filter = $request->all();
        // dd($filter);
        $calendarType = $request->calendar_type ?? 'nep';
        $dateConverter = new DateConverter();
        $data['laboursList'] = Labour::query();
        if (isset($calendarType)) {
            $year = $calendarType == 'eng' ? $request->eng_year : $request->nep_year;
            $month = $calendarType == 'eng' ? $request->eng_month : $request->nep_month;

            $data['show'] = true;
            $data['field'] = $calendarType == 'eng' ? 'date' : 'nepali_date';
            $data['year'] = $year;
            $data['month'] = $month;
            $data['calendarType'] = $calendarType;
            if ($calendarType == 'nep') {
                $data['days'] = $dateConverter->getTotalDaysInMonth($year, $month);
                $nepStartDate = $year . '-' . $month . '-01';
                $data['startDate']= $dateConverter->nep_to_eng_convert($nepStartDate);
                $nepEndDate = $year . '-' . $month . '-' . $data['days'];
                $data['endDate']= $dateConverter->nep_to_eng_convert($nepEndDate);

            } else {
                $data['days'] = Carbon::parse($year . '-' . $month)->daysInMonth;
            }

           
            if(isset($filter['org_id']) && $filter['org_id']!=''){
                $data['laboursList']->where('organization',$filter['org_id']);
            }
            if(isset($filter['emp_id']) && $filter['emp_id']!= ''){
                // dd($filter['emp_id']);
                $data['laboursList']->where('id',$filter['emp_id']);
            }
            $data['laboursList'] = $data['laboursList']->paginate(20);

          
        }
        // dd($data['emps']);
        $data['labours'] = Labour::getLabourList();
        $data['organizationList'] = $this->organization->getList();

        $data['eng_years'] = $dateConverter->getEngYears();
        $data['nep_years'] = $dateConverter->getNepYears();
        $data['eng_months'] = $dateConverter->getEngMonths();
        $data['nep_months'] = $dateConverter->getNepMonths();
        
        return view('labour::labour.wage-mgmt',$data);
    }

    public function downloadPayslip(Request $request)
    {
        $inputData = $request->all();
        LabourPayment::where('emp_id', $inputData['emp_id']);
    }

    public function getDailyWage(Request $request)
    {
        $employee = Labour::find($request->employee_id);
        $daily_wage = optional($employee->skillType)->daily_wage;
        return $daily_wage;
    }

    // labour site attendance
    public function viewLabourMonthly(Request $request)
    {
        $filter = $request->all();

        if (isset($request->calendar_type)) {
            $year = $request->calendar_type == 'eng' ? $request->eng_year : $request->nep_year;
            $month = $request->calendar_type == 'eng' ? $request->eng_month : $request->nep_month;

            $data['field'] = $request->calendar_type == 'eng' ? 'date' : 'nepali_date';
            $data['year'] = $year;
            $data['month'] = $month;
            $data['calendarType'] = $request->calendar_type ?? 'nep';
            if ($request->calendar_type == 'nep') {
                $data['days'] = date_converter()->getTotalDaysInMonth($year, $month);
            } else {
                $data['days'] = Carbon::parse($year . '-' . $month)->daysInMonth;
            }

            //Update no. of days of current month
            if ($data['calendarType'] == 'eng' && $data['year'] == date('Y') && $data['month'] == date('n')) {
                $data['days'] = date('d');
            } elseif ($data['calendarType'] == 'nep') {
                $nepDateArray = date_converter()->eng_to_nep(date('Y'), date('m'), date('d'));
                if ($data['year'] == $nepDateArray['year'] && $data['month'] == $nepDateArray['month']) {
                    $data['days'] = $nepDateArray['date'];
                }
            }
            //

            $checkDate = [
                'calendarType' => $request->calendar_type,
                'year' => $year,
                'month' => $month,
            ];
            $getDate = $this->restrictFutureDate($checkDate);
            if ($getDate) {
                $data['emps'] = $this->attendanceReport->labourSiteAttendanceMonthly($data, $filter, null);
            } else {
                $data['emps'] = [];
            }
        }
        if (auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'hr') {
            $data['employees'] = Labour::getLabourList();
        }
        $data['organizationList'] = $this->organization->getList();
        $data['eng_years'] = date_converter()->getEngYears();
        $data['nep_years'] = date_converter()->getNepYears();
        $data['eng_months'] = date_converter()->getEngMonths();
        $data['nep_months'] = date_converter()->getNepMonths();
        return view('labour::labour.form-monthly-labour', $data);
    }

    public function restrictFutureDate($checkDate)
    {
        if ($checkDate['calendarType'] == 'nep') {
            $nepDateArray = date_converter()->eng_to_nep(date('Y'), date('m'), date('d'));
            if ($checkDate['year'] > $nepDateArray['year']) {
                toastr()->error('Invalid Year');
                return false;
            } elseif ($checkDate['year'] == $nepDateArray['year'] && $checkDate['month'] > $nepDateArray['month']) {
                toastr()->error('Invalid Month');
                return false;
            }
        } elseif ($checkDate['calendarType'] == 'eng') {
            if ($checkDate['year'] > date('Y')) {
                toastr()->error('Invalid Year');
                return false;
            } elseif ($checkDate['year'] == date('Y') && $checkDate['month'] > date('n')) {
                toastr()->error('Invalid Month');
                return false;
            }
        }
        return true;
    }

    public function updateLabourMonthly(Request $request)
    {
        $data = $request->except('_token');
        $field = 'date';
        if ($data['calendar_type'] == 'nep') {
            $field = 'nepali_date';
        }
        try {
            if (!empty($data['siteAttendance'])) {
                foreach ($data['siteAttendance'] as $employeeId => $attendanceData) {
                    foreach ($attendanceData as $date => $attendance) {
                        $divisionAtdMonthly = $this->attendanceReport->findSiteLabourAtdMonthly($employeeId, $field, $date);

                        if (isset($divisionAtdMonthly)) {
                            if ($divisionAtdMonthly['is_present'] != $attendance['is_present']) {
                                $divisionAtdMonthly->update(['is_present' => $attendance['is_present']]);
                            }
                        } else {
                            if ($field == 'date') {
                                $eng_date = $date;
                                $nep_date = date_converter()->eng_to_nep_convert($date);
                            } else {
                                $eng_date = date_converter()->nep_to_eng_convert($date);
                                $nep_date = $date;
                            }
                            $formattedData = [
                                'employee_id' => $employeeId,
                                'date' => $eng_date,
                                'nepali_date' => $nep_date,
                                'is_present' => $attendance['is_present']
                            ];
                            $this->attendanceReport->saveSiteLabourAtdMonthly($formattedData);
                        }
                    }
                }
            }
            toastr('success', 'Monthly Site Attendance updated successfully !!!');
        } catch (\Throwable $th) {
            toastr('error', 'Something went wrong !!!');
        }
        return redirect()->back();
    }

    public function archiveLabour(Request $request)
    {
        $inputData=$request->all();
        try{
            $labour=Labour::find($inputData['labour_id']);
            $labour->update([
                'is_archived'=>1,
                'archived_date'=>$inputData['archived_date'],
                'reason' => $inputData['reason'],
                'other_desc' => $inputData['other_desc']
            ]);
            toastr('success', 'Labour moved to archive successfully !!!');
        } catch (\Throwable $th) {
            toastr('error', 'Something went wrong !!!');
        }
        return redirect()->back();

    }

    public function activeLabour($id)
    {
        
        try{
            $labour=Labour::find($id);
            $labour->update([
                'is_archived'=>0,
                'archived_date'=>null,
                'reason' => null,
                'other_desc' => null
            ]);
            toastr('success', 'Labour moved to active successfully !!!');
        } catch (\Throwable $th) {
            toastr('error', 'Something went wrong !!!');
        }
        return redirect()->back();

    }


    public function exportWage(Request $request)
    {
        $inputData=$request->all();
        $data['labours']=Labour::query();
        if(isset($inputData['org_id'])){
            $data['labours']->where('organization',$inputData['org_id']);
        }
        if(isset($inputData['emp_id'])){
            $data['labours']->where('id',$inputData['emp_id']);
        }
        $data['nep_year']=$inputData['nep_year'];
        $data['nep_month']=$inputData['nep_month'];
        $data['days'] = date_converter()->getTotalDaysInMonth($data['nep_year'], $data['nep_month']);
        $nepStartDate = $data['nep_year'] . '-' . $data['nep_month'] . '-01';
        $data['startDate']= date_converter()->nep_to_eng_convert($nepStartDate);
        $nepEndDate = $data['nep_year'] . '-' . $data['nep_month'] . '-' . $data['days'];
        $data['endDate']= date_converter()->nep_to_eng_convert($nepEndDate);
        return Excel::download(new LabourWageExport($data), 'labourWage.xlsx');
        toastr('Please Filter first to download Excel Report', 'warning');
        return back();
    }


    
}
