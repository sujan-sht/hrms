@extends('admin::employee.layout')
@section('title')Attendance @stop
@section('breadcrum')Request Attendance @stop
@section('scripts')
    <script src="{{ asset('admin/global/js/plugins/ui/moment/moment.min.js')}}"></script>
    <script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js')}}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/picker_date.js')}}"></script>
    <script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script>
    <script src="{{asset('employee/validation/attendanceRequest.js')}}"></script>
@stop

@section('content')
<div class="box add-request">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6><a href="{{route('claimRequest.attendance-adjust')}}"><i class="fa fa-chevron-circle-left"></i></a>
                        Edit Attendance Request</h6>
                </div>
                <div class="card-body">
                    <h5>Attendance Request Details</h5>
                    {!! Form::model($attendanceRequest, ['route'=>['attendanceRequest.update', $attendanceRequest->id], 'method'=>'PATCH','class'=>'form-horizontal','role'=>'form', 'id' => 'daily_attendance_submit']) !!}
                        @include('employeerequest::employee.request.attendance.partial.action')

                        <hr />
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-primary mr-2" type="submit" value="submit" name="btn_name">Submit</button>
                                {{--<button class="btn btn-default mr-2" type="submit" value="submit_new" name="btn_name">Submit and New</button>
                                <button class="btn btn-default" type="button"  onclick = "cancel()" >Cancel</button>--}}
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop