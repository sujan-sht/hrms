@extends('admin::layout')

@section('breadcrum')
    <a class="breadcrumb-item">Attendance</a>
    <a class="breadcrumb-item active">Monthly Site Attendance</a>
@endsection

@section('css')
    <style>
        .table thead th {
            position: sticky;
            /* top: 0; */
            /* background-color: #fff; */
            /* z-index: 1; */
        }
    </style>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    @include('attendance::site-attendance.partial.filter-form-monthly')

    {!! Form::open(['route'=>'siteAttendance.updateMonthly','method'=>'POST','class'=>'form-horizontal','role'=>'form']) !!}

        {!! Form::hidden('calendarType', $calendarType, []) !!}

        <div class="card card-body">
            <div class="media align-items-center align-items-md-start flex-column flex-md-row">
                <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                    <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
                </a>
                <div class="media-body text-center text-md-left">
                    <h6 class="media-title font-weight-semibold">Monthly Site Attendance</h6>
                    By default, all days are pre-selected to save time. Please review and untick as needed.
                </div>

                {{-- <div class="mt-1">
                    <a href="{{ route('exportDailyAttendanceReport', request()->all()) }}"
                        class="btn btn-success rounded-pill export-btn"><i class="icon-file-excel"></i> Export</a>
                </div> --}}
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate text-center">
                        <th>S.N</th>
                        <th>Employee Name</th>
                        @for ($i = 1; $i <= $days; $i++)
                            @php
                                $date = $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $i);
                            @endphp
                            <th class="text-nowrap">{{ $date }}
                                <p>
                                    @php
                                        if (request()->get('calendar_type') == 'nep') {
                                            $date = date_converter()->nep_to_eng_convert($date);
                                        }
                                    @endphp
                                    {{ date('D', strtotime($date)) }}
                                </p>
                            </th>
                        @endfor
                        <th>Total No Of Days</th>
                        <th>Total No Of Worked Days</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @if ($emps->isNotEmpty()) --}}
                        @foreach ($emps as $key => $emp)
                            @php
                                $count_worked_days = 0;
                            @endphp
                            <tr>
                                <td>#{{ $emps->firstItem() + $key }} </td>
                                <td class="d-flex text-nowrap">
                                    <div class="media">
                                        <div class="mr-3">
                                            <a href="#">
                                                <img src="{{ $emp->getImage() }}" class="rounded-circle" width="40"
                                                    height="40" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-title font-weight-semibold">{{ $emp->full_name }}</div>
                                            <span class="text-muted">ID: {{ $emp->employee_code }}</span>
                                        </div>
                                    </div>
                                </td>
                                @foreach ($emp['date'] as $fullDate=>$data)
                                    <th>
                                        <div class="text-center">
                                            @php
                                                $checked = false;

                                                if(isset($data['is_present']) && $data['is_present'] == 11){
                                                    $count_worked_days = $count_worked_days+1;
                                                    $checked = true;
                                                }
                                            @endphp
                                            {!! Form::checkbox('siteAttendance['.$emp->id.']['.$fullDate.'][is_present]', $checked ? 11 : 10 , $checked ? 'checked' : '', ['class'=>'checkAbsent']) !!}

                                            {!! Form::hidden('siteAttendance['.$emp->id.']['.$fullDate.'][is_present]', $checked ? 11 : 10, ['class' => 'absentData']) !!}
                                        </div>
                                    </th>
                                @endforeach
                                <td>{{ $days }}</td>
                                <td>{{ $count_worked_days }}</td>

                            </tr>
                        @endforeach
                    {{-- @else
                        <tr>
                            <td colspan = "40">No Data found !</td>
                        </tr>
                    @endif --}}
                </tbody>
            </table>
        </div>

        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{-- {{ $emps->appends(request()->all())->links() }} --}}
            </span>
        </div>

        <div class="text-center mt-3 saveBtn">
            <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>Save Record</button>
        </div>
    {!! Form::close() !!}


@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.checkAbsent').change(function () {
                var that = $(this)
                if(that.is(':checked')){
                    that.closest(".text-center").find('.checkAbsent').val(11)
                    that.closest(".text-center").find('.absentData').val(11)
                }else{
                    that.closest(".text-center").find('.checkAbsent').val(10)
                    that.closest(".text-center").find('.absentData').val(10)
                }
            });

        })
    </script>
@endsection
