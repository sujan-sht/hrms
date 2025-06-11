@extends('admin::layout')

@section('title')
    Division Attendance Form
@endsection

@section('breadcrum')
    <a href="{{ route('attendanceRequest.index') }}" class="breadcrumb-item">Attendance</a>
    <a class="breadcrumb-item active">Division Attendance Form </a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


    @include('attendance::site-attendance.partial.filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">Division Attendance Form</h6>
                All the Division Attendance Information will be listed below. You can Modify the data.
            </div>
        </div>
    </div>

    {!! Form::open(['route'=>'siteAttendance.updateForm','method'=>'POST','class'=>'form-horizontal','role'=>'form','files' => true]) !!}
        <div class="card card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-light btn-slate">
                            <th>S.N</th>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Is Absent?</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Shift Hour</th>
                            <th>Worked Hour</th>
                            <th>OT Hour</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 0; @endphp
                        @foreach ($divisionAttendanceReport as $employee)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $employee->full_name }}</td>
                                <td>{{ setting('calendar_type') == 'BS' ? date_converter()->eng_to_nep_convert($employee->date) : $employee->date }}</td>
                                <td>
                                    @php
                                        $checked = false;
                                        if(isset($employee->is_absent) && $employee->is_absent == 11){
                                            $checked = true;
                                        }
                                    @endphp

                                    {!! Form::checkbox('formAttendance['.$employee->id.'][is_absent]', $checked ? 11 : 10 , $checked ? 'checked' : '', ['class'=>'checkAbsent']) !!}
                                    {!! Form::hidden('formAttendance['.$employee->id.'][is_absent]', $checked ? 11 : 10, ['class' => 'absentData']) !!}
                                </td>
                                <td>
                                    @php
                                        if ($employee->checkin != '') {
                                            $checkin_time = date('H:i', strtotime($employee->checkin));
                                        }else{
                                            $checkin_time = $employee->checkin;
                                        }
                                    @endphp
                                    {!! Form::time('formAttendance['.$employee->id.'][checkin]', $value = $checkin_time, ['class' => 'form-control checkin']) !!}
                                </td>

                                <td>
                                    @php
                                        if ($employee->checkout != '') {
                                            $checkout_time = date('H:i', strtotime($employee->checkout));
                                        }else{
                                            $checkout_time = $employee->checkout;
                                        }
                                    @endphp
                                    {!! Form::time('formAttendance['.$employee->id.'][checkout]', $value = $checkout_time, ['class' => 'form-control checkout']) !!}
                                </td>

                                <td>
                                    {!! Form::text('formAttendance['.$employee->id.'][actual_hr]', $value = $employee->shift_hr, ['class' => 'form-control shiftHr', 'readonly']) !!}
                                </td>
                                <td>
                                    {!! Form::text('formAttendance['.$employee->id.'][worked_hr]', $value = $employee->worked_hr, ['class' => 'form-control workedHr', 'readonly']) !!}
                                </td>
                                <td>
                                    {!! Form::text('formAttendance['.$employee->id.'][ot_hr]', $value = $employee->ot_hr, ['class' => 'form-control otHr']) !!}
                                </td>
                                <td>
                                    {!! Form::text('formAttendance['.$employee->id.'][remarks]',  $employee->remarks ?? null, ['class' => 'form-control', 'placeholder' => 'Remarks here..']) !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label>Status :</label>
                        </div>
                        <div class="col-md-8">
                            @php
                                $statusList = [1=>'Draft',2=>'Final'];
                            @endphp
                            {!! Form::select('status', $statusList, $employee->status ?? null, [
                                'class' => 'form-control select2',
                                'placeholder' => 'Select Status',
                                'required',
                                'id' => 'status'
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::hidden('date', $date, []) !!}
        </div>

        <div class="text-center saveBtn">
            {{-- <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                        class="icon-backward2"></i></b>Go Back</a> --}}
            <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
            class="icon-database-insert"></i></b>Save Record</button>
        </div>
    {!! Form::close() !!}
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            var status = $('#status').val()
            if(status && status == 2){
                $('.saveBtn').hide()
            }

            $('.checkin').on('change', function () {
                var that = $(this)
                findWorkedOTHour(that)
            })

            $('.checkout').on('change', function () {
                var that = $(this)
                findWorkedOTHour(that)
            })

            function findWorkedOTHour(that) {
                var checkinTime = that.closest("tr").find('.checkin').val()
                var checkoutTime = that.closest("tr").find('.checkout').val()
                var finalWorkedHr = 0
                var otHr = 0

                if(checkinTime && checkoutTime){
                    //convert time into date object
                    var inTime = new Date('1970-01-01T' + checkinTime)
                    var outTime = new Date('1970-01-01T' + checkoutTime)
                    var workedHr = (outTime - inTime) / (1000*60*60)
                    var finalWorkedHr = workedHr.toFixed(1)
                }

                var shiftHr = that.closest("tr").find('.shiftHr').val()
                if(finalWorkedHr > 0 && shiftHr > 0){
                    var otHr = finalWorkedHr - shiftHr
                }
                that.closest("tr").find('.workedHr').val(finalWorkedHr)
                that.closest("tr").find('.otHr').val(otHr.toFixed(1))
            }

            $('.checkAbsent').change(function () {
                var that = $(this)
                if(that.is(':checked')){
                    that.closest("tr").find('.checkin').val(null)
                    that.closest("tr").find('.checkout').val(null)
                    that.closest("tr").find('.workedHr').val(0)
                    that.closest("tr").find('.otHr').val(0)

                    that.closest("tr").find('.checkAbsent').val(11)
                    that.closest("tr").find('.absentData').val(11)
                }else{
                    that.closest("tr").find('.checkAbsent').val(10)
                    that.closest("tr").find('.absentData').val(10)
                }
            })

            //prevent form submit when enter key is pressed
            $('form').on('keypress', function (e) {
                //key code is 13 for enter
                if (e.which === 13) {
                    e.preventDefault()
                    return false
                }
            })
        })
    </script>
@endsection
