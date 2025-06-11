@extends('admin::layout')

@section('breadcrum')
    <a class="breadcrumb-item active">Job End Date Report</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@inject('dateHelper', '\App\Helpers\DateTimeHelper')

@section('content')
    @include('employee::employee.job-end-date.filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">Job End Date Report</h6>
                All the Job End Date Details Information will be listed below. You can view the data.
            </div>
            {{-- <div class="mt-1">
                <a href="{{ route('exportRegularAttendance', request()->all()) }}" class="btn btn-success rounded-pill"><i
                        class="icon-file-excel"></i> Export</a>
            </div> --}}
        </div>
    </div>
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Employee</th>
                        <th>Organization</th>
                        <th>Sub-Function</th>
                        <th>Designation</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        {{-- <th>Job Period</th>
                        <th>Contract Period</th>
                        <th>Probation Period</th> --}}
                        <th>NOD</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $key => $employee)
                        <tr>
                            <td>#{{ $employees->firstItem() + $key }} </td>
                            <td class="d-flex text-nowrap">
                                <div class="media">
                                    <div class="mr-3">
                                        <a href="#">
                                            <img src="{{ $employee->getImage() }}" class="rounded-circle" width="40"
                                                height="40" alt="">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="media-title font-weight-semibold">{{ $employee->full_name }}</div>
                                        <span class="text-muted">ID: {{ $employee->employee_code }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ optional($employee->organizationModel)->name }}</td>
                            <td>{{ optional($employee->department)->title }}</td>
                            <td>{{ optional($employee->designation)->title }}</td>

                            <td>
                                {{ $employee->contract_start_date }}
                                {{-- {{ setting('calendar_type') == 'BS' ? $employee->nepali_join_date : $employee->join_date }} --}}
                            </td>
                            <td>
                                {{ $employee->contract_end_date }}

                                {{-- {{ setting('calendar_type') == 'BS' ? $employee->nep_end_date : $employee->end_date }} --}}
                            </td>

                            {{-- <td>
                                @if (setting('calendar_type') == 'BS')
                                    {{ $employee->nepali_join_date ?? '' }}
                                    <br>
                                    {{ $employee->nep_end_date ?? ''}}
                                @else
                                    {{ $employee->join_date ?? ''}}
                                    <br>
                                    {{ $employee->end_date ?? ''}}
                                @endif
                            </td>
                            <td>
                                @if (setting('calendar_type') == 'BS')
                                    {{ isset(optional($employee->payrollRelatedDetailModel)->contract_start_date) ? date_converter()->eng_to_nep(optional($employee->payrollRelatedDetailModel)->contract_start_date) : ''}} -  {{ isset(optional($employee->payrollRelatedDetailModel)->contract_end_date) ? date_converter()->eng_to_nep(optional($employee->payrollRelatedDetailModel)->contract_end_date) : '' }}
                                @else
                                    {{ optional($employee->payrollRelatedDetailModel)->contract_start_date }} -  {{ optional($employee->payrollRelatedDetailModel)->contract_end_date }}
                                @endif
                            </td>
                            <td>
                                @if (setting('calendar_type') == 'BS')
                                    {{ isset(optional($employee->payrollRelatedDetailModel)->probation_end_date) ? date_converter()->eng_to_nep(optional($employee->payrollRelatedDetailModel)->probation_end_date) : ''}}
                                @else
                                    {{ optional($employee->payrollRelatedDetailModel)->probation_end_date ?? '' }}
                                @endif
                            </td> --}}

                            <td>{{ $dateHelper->DateDiffInDay(date('Y-m-d'), $employee->contract_end_date) }}</td>
                            <td>
                                <a class="btn btn-outline-secondary btn-icon mx-1"
                                    href="{{ route('employee.showJobDetail', $employee->id) }}" data-popup="tooltip"
                                    data-placement="top" data-original-title="Change Job End Date">
                                    <i class="icon-arrow-up52"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $employees->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
@endsection
