@extends('admin::employee.layout')
@section('title')Claim & Request Management @stop
@section('breadcrum')Claim & Request Management @stop

@section('content')

@inject('employee_shift', '\App\Modules\Shift\Repositories\EmployeeShiftRepository')
@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@inject('shiftSeason', '\App\Modules\Shift\Entities\ShiftSeason')
@inject('newShiftEmployee', '\App\Modules\NewShift\Entities\NewShiftEmployee')
@inject('shiftGrpRepo', '\App\Modules\Shift\Repositories\ShiftGroupRepository')

<div class="box">
    <div class="row">
        <div class="col-12">
            <div class="card mb-2">
                <div class="card-header">
                    <h6 class="float-left">Claim Overtime</h6>
                    @if($menuRoles->assignedRoles('employeeRequest.create'))
                        <a href="{{route('employeeRequest.create')}}" class="btn btn-primary float-right text-white" type="button">Add
                            Request</a>
                    @endif
                </div>
                <div class="card-body table-content">
                    <table class="table">
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Check-in</th>
                            <th scope="col">Check-out</th>
                            <th scope="col">Overtime (hrs.)</th>
                            <th scope="col">Total Time (hrs.)</th>
                            <th scope="col">Requested By</th>
                            <th scope="col" colspan="2">Remarks</th>
                            <th scope="col">Status</th>
                        </tr>
                        @if($attendanceRequest->total() != 0)
                            @foreach($attendanceRequest as $key=>$value)
                                @php
                                    $request_type_value="Over-Time Request";

                                    if($value->request_status == "pending" ){
                                        $request_status_value="Pending";
                                        $request_status_icon="icon-sync";
                                        $request_status_action=false;
                                    } elseif ($value->request_status == "approved"){
                                        $request_status_value="Approved";
                                        $request_status_icon="icon-checkmark3";
                                        $request_status_action=true;
                                    } elseif ($value->request_status == "rejected"){
                                        $request_status_value="Rejected";
                                        $request_status_icon="icon-cross2";
                                        $request_status_action=true;
                                    } elseif ($value->request_status == "forward"){
                                        $request_status_value="Forward";
                                        $request_status_icon="icon-forward3";
                                        $request_status_action=false;
                                    }

                                    $day = date('D', strtotime(optional($value->getDailyAttendance)->date));
                                    $official_Entry_time = \Carbon\Carbon::parse('10:00:00');
                                    $official_Exit_time = \Carbon\Carbon::parse('17:00:00');
                                    $official_work = $official_Entry_time->diffInMinutes($official_Exit_time, false);
                                    $ot_hr = 0;

                                    $grace_time = 30;
                                    $ot_grace_period = 'mins';
                                    $employeeShift_resp = $employee_shift->employeeShift(optional($value->getDailyAttendance)->emp_id, $day);
                                    $shift = optional($employeeShift_resp->getShift);

                                    $newShiftEmp = $newShiftEmployee->getShiftEmployee(optional($value->getDailyAttendance)->emp_id, optional($value->getDailyAttendance)->date);
                                    if (isset($newShiftEmp)) {
                                        $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                                        if (isset($rosterShift) && isset($rosterShift->shift_group_id)) {
                                            $shift = $empUpdatedShift = optional($shiftGrpRepo->find($rosterShift->shift_group_id)->shift);

                                        }else{
                                            $empUpdatedShift = '';
                                        }
                                    }else{
                                        $empUpdatedShift = '';
                                    }
                                    if ($shift !== null) {
                                        $official_Entry_time = \Carbon\Carbon::parse(optional($shift->getShiftDayWise($day, $seasonalShiftId))->start_time);
                                        $official_Exit_time = \Carbon\Carbon::parse(optional($shift->getShiftDayWise($day, $seasonalShiftId))->end_time);
                                        $official_work = $official_Entry_time->diffInMinutes($official_Exit_time, false);
                                        $grace_time = !empty($employeeShift_resp->getGroup) ? optional($employeeShift_resp->getGroup)->ot_grace : 30;
                                        $ot_grace_period = !empty($employeeShift_resp->getGroup) ? optional($employeeShift_resp->getGroup)->ot_grace_period : 'mins';
                                    }

                                    $working_hr = 8;
                                    if (optional($value->getDailyAttendance)->checkin !== null && optional($value->getDailyAttendance)->checkout !== null) {
                                        $enter_Time = \Carbon\Carbon::parse(optional($value->getDailyAttendance)->checkin);
                                        $exit_Time = \Carbon\Carbon::parse(optional($value->getDailyAttendance)->checkout);
                                        if (!empty($exit_Time) && !empty($enter_Time)) {
                                            $working_hr = $enter_Time->diffInMinutes($exit_Time, false);
                                            $lateOrEarly_checkin = $official_Entry_time->diffInMinutes($enter_Time, false);
                                            $lateOrEarly_checkout = $official_Exit_time->diffInMinutes($exit_Time, false);
                                        }

                                        $workedhr = round($working_hr / 60, 1);
                                        if($ot_grace_period == 'mins') {
                                            $grace_time = (float) round($grace_time / 60, 1);
                                        }
                                        $off_work = (float) round($official_work / 60, 1) + (float) $grace_time;
                                        $worked = (float) $workedhr;
                                        if ($worked > $off_work) {
                                            $ot_hr = $worked - (float) round($official_work / 60, 1);
                                            $ot_request_status = true;
                                        } else {
                                            $ot_request_status = false;
                                        }
                                    }

                                @endphp
                                <tr>
                                    <td scope="row">{{optional($value->getDailyAttendance)->date}} <span>{{date('l', strtotime(optional($value->getDailyAttendance)->date))}}</span> </td>
                                    <td><span class="check-in">{{date('g:i A', strtotime(optional($value->getDailyAttendance)->checkin))}}</span></td>
                                    <td><span class="check-out">{{date('g:i A', strtotime(optional($value->getDailyAttendance)->checkout))}}</span></td>
                                    <td class="text-center">{{$ot_hr}}</td>
                                    <td class="text-center">{{$workedhr}}</td>
                                    <td>{{optional($value->getRequestedBY)->first_name ." ".optional($value->getRequestedBY)->middle_name." ".optional($value->getRequestedBY)->last_name}}</td>
                                    <td colspan="2">{{$value->request_reason}}</td>
                                    <td>
                                        @if($value->requested_by == auth()->user()->id)
                                            <span data-popup="tooltip" data-original-title="{{$request_status_value}}"  data-placement="bottom"
                                                class=" rounded-round">
                                            <i class="{{$request_status_icon}}"></i>
                                            </span>
                                        @else
                                            <a data-toggle="modal" data-target="#modal_theme_warning"
                                            class="btn bg-warning btn-icon rounded-round attendance-request-type"
                                            link="{{route('attendanceRequest.update',$value->id)}}"
                                            foward-status="{{$value['foward_status']}}" data-placement="bottom"
                                            data-popup="tooltip"
                                            data-original-title="Update Request Status"><i class="icon-pen"></i></a>
                                        @endif
                                        {{--<div class="dropdown">
                                            <a href="#" class="action" id="actionMenu" data-toggle="dropdown"
                                                aria-expanded="true">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionMenu">
                                                <a class="dropdown-item" href="#">
                                                    <i class="fa fa-eye"></i> View</a>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fa fa-edit"></i> Edit</a>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fa fa-trash"></i> Delete</a>
                                            </div>
                                        </div>--}}
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9">No Attendance Request Information !!!</td>
                            </tr>
                        @endif

                    </table>
                    <span style="margin: 5px;float: right;">
                        @if($attendanceRequest->total() != 0)
                            {!! $attendanceRequest->appends(\Request::except('page'))->render() !!}
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
