@extends('admin::layout')
@section('title')Employee Shift @stop
@section('breadcrum')HR Activities Setup / Shift Management / Shift Analytics @stop

@section('content')
<!-- Form inputs -->
<div class="card">
    <div class="card-header bg-teal-400 header-elements-inline">
        <h5 class="card-title">Shift Chart</h5>
        <div class="header-elements">
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th rowspan="2">Employees</th>
                        @foreach ($shifts as $shift)
                        <th colspan="2">{{ $shift->title }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($shifts as $shift)
                        <th>{{ $shift->start_time }}</th>
                        <th>{{ $shift->end_time }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Days</th>
                        @foreach ($shifts as $shift)
                        @php
                        $shiftDays = explode(',', $shift->days);
                        @endphp
                        <th colspan="2">
                            @foreach ($days as $key => $day)
                            <a href="{{ route('employeeshift.changeday', ['shift_id' => $shift->id, 'day' => $key]) }}" title="Change Day Preference">
                                <span class="badge badge-{{ array_search($key, $shiftDays) !== false ? 'success' : 'danger' }}">{{ $key }}</span>
                            </a>
                            @endforeach
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                    <tr class="text-center">
                        <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                        @foreach ($shifts as $shift)
                        @php
                        $check = $shift->checkShift($employee->id, $shift->id);
                        @endphp
                        <td colspan="2">
                            @if ($check)
                            <a data-toggle="modal" data-target="#modal_theme_warning" data-placement="bottom" data-popup="tooltip" data-original-title="Remove Employee Shift" class="text-center remove-employee-shift btn btn-outline-danger btn-sm text-danger" link="{{ route('employeeshift.remove', ['employee_id' => $employee->id, 'shift_id' => $shift->id]) }}"> Remove
                                {{-- <i class="icon-cancel-circle2 text-danger"></i> --}}
                            </a>
                            @else
                            <a data-toggle="modal" data-target="#modal_theme_warning" data-placement="bottom" data-popup="tooltip" data-original-title="Add Employee Shift" class="text-center add-employee-shift btn btn-outline-success btn-sm text-success" link="{{ route('employeeshift.add', ['employee_id' => $employee->id, 'shift_id' => $shift->id]) }}"> Assign
                                {{-- <i class="icon-plus-circle2 text-success"></i> --}}
                            </a>
                        </a>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
@if (auth()->user()->userType == 'super_admin')
    <span style="margin: 5px;float: right;">
        @if($employees->total() != 0)
        {{ $employees->links() }}
        @endif
    </span>
@endif


<!-- Warning modal -->
<div id="modal_theme_warning" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h6 class="modal-title">Are you sure to make changes to shift ?</h6>
            </div>

            <div class="modal-body">
                <a class="btn btn-success get_link" href="">Yes</a> &nbsp; | &nbsp;
                <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /warning modal -->


<script type="text/javascript">
    $('document').ready(function() {
        $('.remove-employee-shift').on('click', function() {
            var link = $(this).attr('link');
            console.log(link);
            $('.get_link').attr('href', link);
        });

        $('.add-employee-shift').on('click', function() {
            var link = $(this).attr('link');
            $('.get_link').attr('href', link);
        });
    });
</script>

@endsection
