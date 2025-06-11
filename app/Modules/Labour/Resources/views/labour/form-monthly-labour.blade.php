@extends('admin::layout')

@section('breadcrum')
    <a class="breadcrumb-item">Attendance</a>
    <a class="breadcrumb-item active">Monthly Labour Attendance</a>
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

    @include('labour::labour.partial.filter-form-labour')

    {!! Form::open(['route'=>'siteLabourAttendance.updateMonthly','method'=>'POST','class'=>'form-horizontal','role'=>'form']) !!}

    {!! Form::hidden('calendar_type', 'nep', []) !!}


        <div class="card card-body">
            <div class="media align-items-center align-items-md-start flex-column flex-md-row">
                <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                    <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
                </a>
                <div class="media-body text-center text-md-left">
                    <h6 class="media-title font-weight-semibold">Monthly Labour Attendance</h6>
                    By default, all days are pre-selected to save time. Please review and untick as needed.
                </div>

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

                        @foreach ($emps as $key => $emp)
                        @php
                            $count_worked_days=0;
                        @endphp
                        <tr id="emp-{{ $emp->id }}">
                            <td>#{{ $emps->firstItem() + $key }}</td>
                            <td class="d-flex text-nowrap">
                                <div class="media">
                                    <div class="media-body">
                                        <div class="media-title font-weight-semibold">{{ $emp->full_name }}</div>
                                    </div>
                                </div>
                            </td>
                            @foreach ($emp['date'] as $fullDate => $data)
                                <th>
                                    <div class="text-center">
                                        @if($emp->archived_date != null)
                                            @if ($emp->archived_date <= date_converter()->nep_to_eng_convert($fullDate))
                                            @else
                                                @php
                                                    $checked = isset($data['is_present']) && $data['is_present'] == 11;
                                                    if ($checked) { $count_worked_days++; }
                                                @endphp
                                                {!! Form::checkbox('siteAttendance[' . $emp->id . '][' . $fullDate . '][is_present]', $checked ? 11 : 10, $checked ? 'checked' : '', ['class' => 'checkAbsent']) !!}
                                                {!! Form::hidden('siteAttendance[' . $emp->id . '][' . $fullDate . '][is_present]', $checked ? 11 : 10, ['class' => 'absentData']) !!}
                                            @endif
                                        @else
                                            @php
                                                $checked = isset($data['is_present']) && $data['is_present'] == 11;
                                                if ($checked) { $count_worked_days++; }
                                            @endphp
                                            {!! Form::checkbox('siteAttendance[' . $emp->id . '][' . $fullDate . '][is_present]', $checked ? 11 : 10, $checked ? 'checked' : '', ['class' => 'checkAbsent']) !!}
                                            {!! Form::hidden('siteAttendance[' . $emp->id . '][' . $fullDate . '][is_present]', $checked ? 11 : 10, ['class' => 'absentData']) !!}
                                        @endif
                                    </div>
                                </th>
                            @endforeach
                            <th>
                                <div class="text-center">
                                    {{ $days }}
                                </div>
                            </th>
                            <th class="work-days">
                                <div class="text-center">{{ $count_worked_days }}</div>
                            </th>
                        </tr>
                        @endforeach

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
            // Function to update the total worked days count
            function updateWorkedDays(empId) {
                let totalWorkedDays = 0;

                // Loop through each checkbox for the specific employee
                $('#emp-' + empId + ' .checkAbsent').each(function() {
                    if ($(this).is(':checked')) {
                        totalWorkedDays++; // Count the checked days
                    }
                });

                // Update the worked days count
                $('#emp-' + empId + ' .work-days').text(totalWorkedDays);
            }

            // Handle checkbox state change
            $('.checkAbsent').change(function() {
                var empId = $(this).closest('tr').attr('id').split('-')[1]; // Get employee ID from the row ID

                // Update the worked days count for the employee
                updateWorkedDays(empId);

                // Update the hidden field to reflect the checkbox value
                if ($(this).is(':checked')) {
                    $(this).val(11); // Worked day
                    $(this).closest('.text-center').find('.absentData').val(11);
                } else {
                    $(this).val(10); // Absent day
                    $(this).closest('.text-center').find('.absentData').val(10);
                }
            });

            // Initialize worked days count on page load
            $('tr').each(function() {
                var empId = $(this).attr('id').split('-')[1]; // Get employee ID from the row ID
                updateWorkedDays(empId); // Update worked days for each employee
            });

            $('.payout').on('click', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var total_worked_days = $(this).data('days');
                $('#empId').val(id);
                $('#totalWorkedDays').val(total_worked_days);

                $.ajax({
                    url: "{{ route('labour.getDailyWage') }}",
                    method: 'GET',
                    data: {
                        employee_id: id
                    },
                    success: function(resp) {
                        $('.tds_deducted_amount').val(resp*total_worked_days);
                    }
                });
            });
        })
    </script>
@endsection
