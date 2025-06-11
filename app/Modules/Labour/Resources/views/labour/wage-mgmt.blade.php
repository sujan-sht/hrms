@extends('admin::layout')

@section('breadcrum')
    <a href="{{ route('monthlyAttendance') }}" class="breadcrumb-item">Attendance</a>
    <a class="breadcrumb-item active">List</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@inject('atdReportRepo', '\App\Modules\Attendance\Repositories\AttendanceReportRepository')


@section('content')



    @include('labour::labour.partial.wage-mgmt-filter')


    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Attendance</h6>
                All the Attendance Information will be listed below. You can view the data.
            </div>
            <div class="mt-1">
                <a href="{{ route('labour.exportWage', request()->all()) }}" class="btn btn-success"><i
                        class="icon-file-excel"></i> Export</a>
            </div>
        </div>
    </div>
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Name</th>
                        <th>Organization</th>
                        <th>Skill Type</th>
                        <th>Rate Per Day</th>
                        <th>Total Days</th>
                        <th>Total Working Days</th>
                        <th>Absent Days</th>
                        <th>Payable Days</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($laboursList->count() > 0)
                        @foreach ($laboursList as $key => $labour)
                            @php
                                $presentDays = $labour->countPresentDays($labour->id, $startDate, $endDate);
                                $paymentExist = $labour->checkIfPaymentExists($labour->id, $year, $month);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $labour->full_name }}</td>
                                <td>{{ $labour->organizationModel->name }}</td>
                                <td>{{ $labour->skillType->category }}</td>
                                <td>{{ $labour->skillType->daily_wage }}</td>
                                <td>{{ $days }}</td>
                                <td>{{ $days }}</td>
                                <td>{{ $days - $presentDays }}</td>
                                <td>{{ $presentDays }}</td>
                                <td>
                                    <a data-toggle="modal" data-target="#payout"
                                        class="btn btn-outline-warning btn-icon payout mx-1" data-id="{{ $labour->id }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Payout"
                                        data-days="{{ $presentDays }}">
                                        <i class="icon-cash3"></i>
                                    </a>
                                    @if ($paymentExist)
                                        <a class="btn btn-outline-primary btn-icon mx-1" data-popup="tooltip"
                                            data-placement="top" data-original-title="Print"
                                            href="{{ route('labour.printPaySlip', ['id' => $labour->id, 'nep_year' => request()->nep_year, 'nep_month' => request()->nep_month]) }}">
                                            <i class="icon-printer"></i>
                                        </a>

                                        <a data-toggle="modal" data-target="#viewPayslip"
                                            class="btn btn-outline-success btn-icon mx-1 viewPayslip"
                                            data-id="{{ $labour->id }}" data-popup="tooltip" data-placement="top"
                                            data-original-title="View" data-year="{{ request()->nep_year }}"
                                            data-month="{{ request()->nep_month }}">
                                            <i class="icon-eye"></i>
                                        </a>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
        </div>

        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                @if (isset($laboursList))
                    {{ $laboursList->appends(request()->all())->links() }}
                @endif
            </span>
        </div>

    </div>

    <div id="payout" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Payout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    {!! Form::open([
                        'route' => 'labour.payment',
                        'method' => 'POST',
                        'class' => 'form-horizontal updateLeaveStatusForm',
                        'role' => 'form',
                    ]) !!}
                    {!! Form::hidden('emp_id', null, ['id' => 'empId']) !!}
                    {!! Form::hidden('total_worked_days', null, ['id' => 'totalWorkedDays']) !!}
                    {!! Form::hidden('calendar_type', 'nep') !!}
                    {{-- @if (request()->calendar_type == 'nep') --}}

                    {!! Form::hidden('nep_year', request()->nep_year) !!}
                    {!! Form::hidden('nep_month', request()->nep_month) !!}
                    {{-- @else
                        {!! Form::hidden('eng_year', request()->eng_year) !!}

                        {!! Form::hidden('eng_month', request()->eng_month) !!}
                    @endif --}}
                    {!! Form::hidden('date', today()) !!}


                    <div class="form-group">

                        <div id="statusMessage" class="row mt-3">
                            <label class="col-form-label col-lg-3">Payable Amount :</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('payable_amount', null, [
                                        'class' => 'form-control tds_deducted_amount',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">

                        <div id="statusMessage" class="row mt-3">
                            <label class="col-form-label col-lg-3">Paid Amount :</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('paid_amount', null, [
                                        'class' => 'form-control tds_deducted_amount',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">

                        <div id="statusMessage" class="row mt-3">
                            <label class="col-form-label col-lg-3">Remarks :</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::textarea('remarks', null, [
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn bg-success text-white">Pay</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>


    <div id="viewPayslip" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">View</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body appendPayslip">


                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.checkAbsent').change(function() {
                var that = $(this)
                if (that.is(':checked')) {
                    that.closest(".text-center").find('.checkAbsent').val(11)
                    that.closest(".text-center").find('.absentData').val(11)
                } else {
                    that.closest(".text-center").find('.checkAbsent').val(10)
                    that.closest(".text-center").find('.absentData').val(10)
                }
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
                        $('.tds_deducted_amount').val(resp * total_worked_days);
                    }
                });
            });

            $('.viewPayslip').on('click', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var nep_year = $(this).data('year');
                var nep_month = $(this).data('month');



                $.ajax({
                    url: "{{ route('labour.viewPayslip') }}",
                    method: 'GET',
                    data: {
                        employee_id: id,
                        nep_year: nep_year,
                        nep_month: nep_month
                    },
                    success: function(resp) {
                        $('.appendPayslip').html(resp);
                    }
                });
            });
        })
    </script>
@endsection
