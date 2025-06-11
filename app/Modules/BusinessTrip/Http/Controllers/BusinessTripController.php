<?php

namespace App\Modules\BusinessTrip\Http\Controllers;

use PDF;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\Setting\Entities\Level;
use App\Modules\Dropdown\Entities\Field;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Setting\Entities\Designation;
use App\Modules\Tada\Entities\TransportationType;
use App\Modules\BusinessTrip\Entities\BusinessTrip;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Setting\Repositories\LevelInterface;
use App\Modules\Tada\Repositories\TadaTypeInterface;
use App\Modules\Setting\Entities\TravelAllowanceSetup;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Setting\Repositories\DesignationInterface;
use App\Modules\BusinessTrip\Entities\SettigWiseAllowanceSetup;
use App\Modules\BusinessTrip\Repositories\BusinessTripInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\BusinessTrip\Entities\BusinessTripAllowanceSetup;
use App\Modules\BusinessTrip\Repositories\TravelRequestTypeInterface;

class BusinessTripController extends Controller
{
    private $employee;
    private $businessTrip;
    private $travelRequestType;
    private $organization;
    private $branch;
    private $level;
    private $designation;
    private $tadaType;


    public function __construct(
        EmployeeInterface $employee,
        BusinessTripInterface $businessTrip,
        TravelRequestTypeInterface $travelRequestType,
        OrganizationInterface $organization,
        BranchInterface $branch,
        LevelInterface $level,
        DesignationInterface $designation,
        TadaTypeInterface $tadaType
    ) {
        $this->employee = $employee;
        $this->businessTrip = $businessTrip;
        $this->travelRequestType = $travelRequestType;
        $this->organization = $organization;
        $this->branch = $branch;
        $this->level = $level;
        $this->designation = $designation;
        $this->tadaType = $tadaType;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        // // $filter['isParent'] = true;
        // $filter['authUser'] = auth()->user();

        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];

        $data['employeeList'] = $this->employee->getList();
        $data['statusList'] =  BusinessTrip::STATUS;
        $data['travelTypes'] =  BusinessTrip::TRAVEL_TYPES;
        // $data['allStatus'] = $status;
        if (in_array(auth()->user()->user_type, ['super_admin', 'hr', 'division_hr'])) {
            unset($data['statusList'][2]);
        }
        $data['organizationList'] = $this->organization->getList();
        $data['typeList'] = $this->travelRequestType->getList();
        $data['businessTrips'] = $this->businessTrip->findAll(25, $filter, $sort);
        // $data['type'] = $this->attendanceRequest->getTypes();
        // $data['kind'] = $this->attendanceRequest->getKinds();

        return view('businesstrip::business-trip.index', $data);
    }


    public function getEmployee(Request $request)
    {
        $employee = Employee::with(['branchModel', 'level', 'designation', 'department'])->find($request->id);

        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        return response()->json([
            'first_name'   => $employee->first_name,
            'middle_name'  => $employee->middle_name,
            'last_name'    => $employee->last_name,
            'employee_code'    => $employee->employee_code,
            'phone'        => $employee->phone,
            'branch'       => optional($employee->branchModel)->name,
            'department'   => optional($employee->department)->title,
            'designation'  => optional($employee->designation)->title,
            'level' => $employee->level ?? '',
            'functional_title' => $employee->job_title
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $fileds =  Field::where('slug', 'currency_list')->with('dropdownValue')->first();
        $data['employees'] = $this->employee->getList();
        $data['travelTypes'] = BusinessTrip::TRAVEL_TYPES;
        $data['typeList'] = $this->travelRequestType->getList();
        $data['transpotationTypes'] = TransportationType::pluck('title', 'id')->toArray();
        $data['id'] = null;
        $data['currencyLists'] = $fileds->dropdownValue ?? [];
        return view('businesstrip::business-trip.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $foreignCurrencyDetail = null;
        if ($request->type_id == 2) {
            $foreignCurrencyDetail = json_encode([
                'foreign_currency_type' => $request->foreign_currency_type,
                'note' => $request->note,
                'qty' => $request->qty,
                'conversion_rate' => $request->conversion_rate,
                'amount' => $request->amount,
            ]);
        }
        try {
            // dd( setting('calendar_type'));
            $data = $request->except(['_token', 'foreign_currency_type', 'note', 'qty', 'conversion_rate', 'amount']);

            $data['from_date_nep'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['from_date']) : $data['from_date'];
            $data['to_date_nep'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['to_date']) : $data['to_date'];

            $data['from_date'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['eng_from_date']) : $data['from_date'];
            $data['to_date'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['eng_to_date']) : $data['to_date'];

            $data['from_date'] = $request->eng_from_date;
            $data['to_date'] = $request->eng_to_date;

            // $data['from_date_nep'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['eng_from_date']) : $data['from_date'];
            // $data['to_date_nep'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['eng_to_date']) : $data['to_date'];
            $data['status'] = 1;
            $data['foreign_currency_detail'] = $foreignCurrencyDetail;

            if ($request->hasFile('document')) {
                $file = $request->file('document');

                // Generate random filename with original extension
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                // Store file in the 'public' disk under your custom path
                $path = $file->storeAs(BusinessTrip::IMAGE_PATH, $filename, 'public');

                // Store full URL to the document
                $data['document'] = asset('storage/' . $path);
            }
            // Check for existing date range conflict
            $conflict = BusinessTrip::where('employee_id', $data['employee_id'])
                ->where('status', '!=', 4)
                ->where(function ($query) use ($data) {
                    $query->whereBetween('from_date', [$data['from_date'], $data['to_date']])
                        ->orWhereBetween('to_date', [$data['from_date'], $data['to_date']])
                        ->orWhere(function ($query) use ($data) {
                            $query->where('from_date', '<=', $data['from_date'])
                                ->where('to_date', '>=', $data['to_date']);
                        });
                })
                ->exists();

            if ($conflict) {
                toastr('The employee already has a travel request scheduled within this date range.', 'error');
                return redirect()->back()->withInput();
            }

            $startDate = Carbon::parse($data['from_date']);
            $endDate = Carbon::parse($data['to_date']);
            $data['request_days'] = $startDate->diffInDays($endDate) + 1;
            $data['eligible_amount'] = 0;

            $model = BusinessTripAllowanceSetup::where('employee_id', $data['employee_id'])->where('type_id', $data['type_id'])->first();
            if (isset($model) && isset($model->per_day_allowance) && $model->per_day_allowance > 0) {
                $data['eligible_amount'] = $data['request_days'] * $model->per_day_allowance;
            }
            $trip = $this->businessTrip->save($data);
            if (isset($trip)) {
                $trip['enable_mail'] = setting('enable_mail');
                $this->businessTrip->sendMailNotification($trip);
            }
            toastr('Travel Request Updated Successfully', 'success');
        } catch (\Throwable $th) {
            toastr('Error While Adding Travel Request', 'error');
        }
        return redirect()->route('businessTrip.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data['businessTrip'] = $this->businessTrip->find($id);
        return view('businesstrip::business-trip.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $fileds =  Field::where('slug', 'currency_list')->with('dropdownValue')->first();
        $data['employees'] = $this->employee->getList();
        $data['typeList'] = $this->travelRequestType->getList();
        $data['businessTrip'] = $this->businessTrip->find($id);
        $data['employees'] = $this->employee->getList();
        $data['travelTypes'] = BusinessTrip::TRAVEL_TYPES;
        $data['typeList'] = $this->travelRequestType->getList();
        $data['transpotationTypes'] = TransportationType::pluck('title', 'id')->toArray();
        $data['currencyLists'] = $fileds->dropdownValue ?? [];
        $data['id'] = $id;
        return view('businesstrip::business-trip.edit', $data);
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
            $foreignCurrencyDetail = null;
            if ($request->type_id == 2) {
                $foreignCurrencyDetail = json_encode([
                    'foreign_currency_type' => $request->foreign_currency_type,
                    'note' => $request->note,
                    'qty' => $request->qty,
                    'conversion_rate' => $request->conversion_rate,
                    'amount' => $request->amount,
                ]);
                $data['foreign_currency_detail'] = $foreignCurrencyDetail;
            }
            $data['from_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['from_date_nep']) : $data['from_date'];
            $data['to_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['to_date_nep']) : $data['to_date'];

            $data['from_date_nep'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['from_date']) : $data['from_date_nep'];
            $data['to_date_nep'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['to_date']) : $data['to_date_nep'];

            $startDate = Carbon::parse($data['from_date']);
            $endDate = Carbon::parse($data['to_date']);
            $data['request_days'] = $startDate->diffInDays($endDate) + 1;

            if ($request->hasFile('document')) {
                $file = $request->file('document');

                // Generate random filename with original extension
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                // Store file in the 'public' disk under your custom path
                $path = $file->storeAs(BusinessTrip::IMAGE_PATH, $filename, 'public');

                // Store full URL to the document
                $data['document'] = asset('storage/' . $path);
            }

            $conflict = BusinessTrip::where('employee_id', $data['employee_id'])
                ->where('status', '!=', 4)
                ->where('id', '!=', $id)
                ->where(function ($query) use ($data) {
                    $query->whereBetween('from_date', [$data['from_date'], $data['to_date']])
                        ->orWhereBetween('to_date', [$data['from_date'], $data['to_date']])
                        ->orWhere(function ($query) use ($data) {
                            $query->where('from_date', '<=', $data['from_date'])
                                ->where('to_date', '>=', $data['to_date']);
                        });
                })
                ->exists();

            if ($conflict) {
                toastr('The employee already has a travel request scheduled within this date range.', 'error');
                return redirect()->back()->withInput();
            }

            $data['eligible_amount'] = 0;
            $model = BusinessTripAllowanceSetup::where('employee_id', $data['employee_id'])->where('type_id', $data['type_id'])->first();
            if (isset($model) && isset($model->per_day_allowance) && $model->per_day_allowance > 0) {
                $data['eligible_amount'] = $data['request_days'] * $model->per_day_allowance;
            }
            $this->businessTrip->update($id, $data);
            toastr('Travel Request Updated Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Updating Travel Request', 'error');
        }
        return redirect()->route('businessTrip.index');
    }

    public function updateStatus(Request $request)
    {
        try {
            $data = $request->except('_token');
            unset($data['employee_id']);
            $this->businessTrip->update($data['id'], $data);

            $businessTrip = $this->businessTrip->find($data['id']);
            $businessTrip['enable_mail'] = setting('enable_mail');
            $this->businessTrip->sendMailNotification($businessTrip);
            toastr('Travel Request Status Updated Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Updating Travel Request Status', 'error');
        }
        return redirect()->back();
    }

    public function updateClaimStatus(Request $request)
    {
        try {
            $data = $request->except('_token');
            $this->businessTrip->update($data['id'], $data);
            toastr('Travel Request Claim Status Updated Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Updating Travel Request Claim Status', 'error');
        }
        return redirect()->route('businessTrip.index');
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->businessTrip->delete($id);
            toastr('Travel Request Deleted Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Deleting Travel Request', 'error');
        }
        return redirect()->route('businessTrip.index');
    }

    public function teamRequests(Request $request)
    {
        if (auth()->user()->user_type != 'supervisor') {
            toastr('User have to be supervisor', 'error');
            return redirect()->back();
        }
        $filter = $request->all();

        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['title'] = 'Team Travel Requests';
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employee->getList();
        $data['statusList'] = BusinessTrip::STATUS;
        unset($data['statusList'][5]);

        $data['businessTrips'] = $this->businessTrip->findTeamBusinessTripRequests(20, $filter, $sort);
        return view('businesstrip::business-trip.team-requests', $data);
    }

    public function allowanceSetup(Request $request)
    {
        $filter = ($request->all());
        $data['title'] = 'Assign Employeee Allowance';
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['employeePluck'] = $this->employee->getList();
        $data['typeList'] = $this->travelRequestType->getList();
        $data['emps'] = $this->businessTrip->empAllowanceSetup($data, $filter, null);
        return view('businesstrip::allowance-setup.index', $data);
    }

    public function allowanceSetupTest(Request $request)
    {
        $filter = ($request->all());
        $data['title'] = 'Assign Employeee Allowance';
        $data['organizationList'] = $this->organization->getList();
        $data['typeList'] = $this->travelRequestType->getList();
        $travelAllowanceType = TravelAllowanceSetup::first();
        if (!$travelAllowanceType) {
            toastr('Set Travel allowance setup first', 'error');
            return redirect()->route('allowance.create');
        }
        switch ($travelAllowanceType->allowance_type) {
            case "1": //Employee
                $filterValue = [
                    'filterName' => 'Employee',
                    'filterDatas' => $this->employee->getList(),
                ];
                $columns = [
                    'Employee Name'
                ];
                break;
            case "2": //Level
                $filterValue = [
                    'filterName' => 'Level',
                    'filterDatas' => $this->level->getList()
                ];
                $columns = [
                    'Title',
                    'Short Code'
                ];
                break;
            case "3": //Designation
                $filterValue = [
                    'filterName' => 'Designation',
                    'filterDatas' => $this->designation->getList()
                ];
                $columns = [
                    'Title',
                    'Short Code'
                ];
                break;
            default:
                break;
        }
        $data['columns'] = $columns;
        $data['filterValue'] = $filterValue;
        $data['getSetWiseAllowaceSetups'] = $this->businessTrip->getSetWiseAllowaceSetup($data, $travelAllowanceType, $filter, null);
        $data['travelAllowanceType'] = $travelAllowanceType;
        return view('businesstrip::allowance-setup.indextest', $data);
    }

    public function filterBussinessTrip(Request $request)
    {
        try {
            // dd($request->all());
            $organization = Organization::where('id', $request->orgId)->first();
            if (!$organization) {
                throw new Exception();
            }
            $filter['organization_id'] = $request->orgId;
            $filterData = $request->filterData;
            switch ($filterData['filterName']) {
                case 'Level':
                    $data = $organization->levels->pluck('title', 'id');
                    break;
                case 'Designation':
                    $data = $organization->designations->pluck('title', 'id');
                    break;
                case 'Employee':
                    $data = $this->employee->filterList($filter);
                    break;
                default:
                    throw new Exception();
                    break;
            }
            $content = $this->afterFilter($filterData, $data, $request);
            $response = [
                'error' => false,
                'data' => $content,
                'msg' => 'Filter Data !!'

            ];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Something Went Wrong !!'

            ];
            return response()->json($response, 200);
        }
    }

    public function afterFilter($filterValue, $data, $request)
    {
        $html = '';
        $html .= '<div class="mb-3" id="filterTrip"><label for="example-email" class="form-label">' . $filterValue['filterName'] . '</label>';
        $html .= '<select name="' . $filterValue['filterName'] . '" id="' . $filterValue['filterName'] . '" class="form-control select2">';
        $html .= '<option value="">Select ' . $filterValue['filterName'] . '</option>';
        foreach ($data as $key => $value) {
            $selectedString = '';
            if ($request->requestValue == $key) {
                $selectedString = 'selected';
            }
            $html .= '<option value="' . $key . '"' . $selectedString . '>' . $value . '</option>';
        }
        $html .= '</select></div>';
        return $html;
    }

    public function storeEmployeeAllowanceTest(Request $request)
    {
        try {
            $data = $request->except('_token');
            $allowance_type = $request->allowance_type;
            $travelAllowanceSetUp = TravelAllowanceSetup::where('id', $allowance_type)->first();
            if (!$allowance_type || !$travelAllowanceSetUp) {
                throw new Exception();
            }
            $travelWiseVariable = $this->businessTrip->arrangeData($travelAllowanceSetUp->allowance_type);
            if (!empty($data['setups'])) {
                foreach ($data['setups'] as $idValue => $type) {
                    if (!empty($type)) {
                        foreach ($type as $type_id => $per_day_allowance) {
                            $model = SettigWiseAllowanceSetup::where($travelWiseVariable, $idValue)->where('travel_setup_type', $travelAllowanceSetUp->allowance_type)->where('type_id', $type_id)->first();
                            if (isset($model)) {
                                $model->update(['per_day_allowance' => $per_day_allowance]);
                            } else {
                                $inputData = [
                                    $travelWiseVariable => $idValue,
                                    'travel_setup_type' => $travelAllowanceSetUp->allowance_type,
                                    'type_id' => $type_id,
                                    'per_day_allowance' => $per_day_allowance,
                                ];
                                SettigWiseAllowanceSetup::create($inputData);
                            }
                        }
                    }
                }
            }
            toastr('Allowance setup done successfully', 'success');
        } catch (\Throwable $th) {
            toastr('Error While doing allowance setup', 'error');
        }
        return redirect()->back();
    }

    public function bussinessTripReport(Request $request)
    {
        $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];

        $data['employeeList'] = $this->employee->getList();
        $data['statusList'] =  BusinessTrip::STATUS;
        $data['organizationList'] = $this->organization->getList();
        $data['typeList'] = $this->travelRequestType->getList();
        $data['businessTrips'] = $this->businessTrip->getAllowanceData(25, $filter);
        return view('businesstrip::allowance-setup.bussiness-report', $data);
    }


    public function storeEmployeeAllowance(Request $request)
    {
        try {
            $data = $request->except('_token');
            if (!empty($data['setups'])) {
                foreach ($data['setups'] as $employee_id => $type) {
                    if (!empty($type)) {
                        foreach ($type as $type_id => $per_day_allowance) {
                            // if(isset($setup['per_day_allowance'])){
                            $model = BusinessTripAllowanceSetup::where('employee_id', $employee_id)->where('type_id', $type_id)->first();

                            if (isset($model)) {
                                $model->update(['per_day_allowance' => $per_day_allowance]);
                            } else {
                                $inputData = [
                                    'employee_id' => $employee_id,
                                    'type_id' => $type_id,
                                    'per_day_allowance' => $per_day_allowance,
                                ];
                                BusinessTripAllowanceSetup::create($inputData);
                            }
                            // }
                        }
                    }
                }
            }
            toastr('Allowance setup done successfully', 'success');
        } catch (\Throwable $th) {
            toastr('Error While doing allowance setup', 'error');
        }
        return redirect()->route('businessTrip.allowanceSetup');
    }



    public function postProcessData(Request $request)
    {
        $inputData = $request->all();
        $startDateNep = $inputData['params']['startDate'];
        $endDateNep = $inputData['params']['endDate'];
        $employeeId = $inputData['params']['id'];
        $businessTripId = $inputData['params']['businessTripId'] ?? null;
        // Convert Nepali dates to English if the calendar type is BS
        $startDate = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($startDateNep) : $startDateNep;
        $endDate = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($endDateNep) : $endDateNep;

        // Ensure dates are converted back to Nepali if the calendar type is AD
        $startDateNep = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($startDate) : $startDateNep;
        $endDateNep = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($endDate) : $endDateNep;


        $numberOfDays = DateTimeHelper::DateDiffInDay($startDate, $endDate) + 1;


        $conflictQuery = BusinessTrip::where('employee_id', $employeeId)
            ->where('status', '!=', 4)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('from_date', [$startDate, $endDate])
                    ->orWhereBetween('to_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('from_date', '<=', $startDate)
                            ->where('to_date', '>=', $endDate);
                    });
            });

        // If in edit mode, exclude the current travel request from the conflict check
        if ($businessTripId) {
            $conflictQuery->where('id', '!=', $businessTripId);
        }

        if (setting('calendar_type') == "AD") {
            $conflictingTrips = $conflictQuery->get(['from_date', 'to_date']);
        } else {
            $conflictingTrips = $conflictQuery->get(['from_date_nep', 'to_date_nep']);
        }

        $response = [];

        if ($conflictingTrips->isNotEmpty()) {
            $data['status'] = false;
            if (setting('calendar_type') == "AD") {
                $conflictingDates = $conflictingTrips->map(function ($trip) {
                    return $trip->from_date . ' to ' . $trip->to_date;
                })->implode(', ');
            } else {
                $conflictingDates = $conflictingTrips->map(function ($trip) {
                    return $trip->from_date_nep . ' to ' . $trip->to_date_nep;
                })->implode(', ');
            }


            $data['finalMessage'] = 'There is already a travel request scheduled on the following dates: ' . $conflictingDates;
        } else {
            $data['status'] = true;
            $response['numberOfDays'] = $numberOfDays;
            $data['finalMessage'] = "The total number of days you are applying for is " . $numberOfDays;
        }

        $response['noticeList'] = view('businesstrip::business-trip.partial.notice-list', $data)->render();

        return  json_encode($response);
    }

    public function cancelRequest(Request $request)
    {
        try {
            $data = $request->except('_token');
            $this->businessTrip->update($data['id'], $data);

            // $businessTrip = $this->businessTrip->find($data['id']);
            // $businessTrip['enable_mail'] = setting('enable_mail');
            // $this->businessTrip->sendMailNotification($businessTrip);
            toastr('Travel Request Cancelled Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Cancelling Travel Request', 'error');
        }
        return redirect()->back();
    }

    public function downloadPDF($id)
    {
        $data['businessTripModel'] = $this->businessTrip->find($id);
        $pdf = PDF::loadView('exports.business-trip-details-report', $data)->setPaper('a4', 'landscape');
        return $pdf->download('travel-request-details-report.pdf');
    }
}
