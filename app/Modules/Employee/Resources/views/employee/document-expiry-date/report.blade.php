@extends('admin::layout')

@section('breadcrum')
    <a class="breadcrumb-item active">Document Expiry Date Report</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@inject('dateHelper', '\App\Helpers\DateTimeHelper')

@section('content')
    @include('employee::employee.document-expiry-date.filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">Document Expiry Date Report</h6>
                All the Document Expiry Date Details Information will be listed below. You can view the data.
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
                        <th>Document Issued Date</th>
                        <th>Document Expiry Date</th>
                        <th>NOD</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visaDetails as $key => $visaDetail)
                        <tr>
                            <td>#{{ $visaDetails->firstItem() + $key }} </td>
                            <td class="d-flex text-nowrap">
                                <div class="media">
                                    <div class="mr-3">
                                        <a href="#">
                                            <img src="{{ optional($visaDetail->employeeModel)->getImage() }}"
                                                class="rounded-circle" width="40" height="40" alt="">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="media-title font-weight-semibold">
                                            {{ optional($visaDetail->employeeModel)->full_name }}</div>
                                        <span class="text-muted">ID:
                                            {{ optional($visaDetail->employeeModel)->employee_code }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ optional(optional($visaDetail->employeeModel)->organizationModel)->name }}</td>
                            <td>{{ optional(optional($visaDetail->employeeModel)->department)->title }}</td>
                            <td>{{ optional(optional($visaDetail->employeeModel)->designation)->title }}</td>
                            @if (setting('calendar_type') == 'BS')
                                <td>
                                    @if (!is_null($visaDetail->issued_date))
                                        {{ date_converter()->eng_to_nep_convert($visaDetail->issued_date) }}
                                    @endif
                                </td>
                                <td>
                                    @if (!is_null($visaDetail->visa_expiry_date))
                                        {{ date_converter()->eng_to_nep_convert($visaDetail->visa_expiry_date) }}
                                    @endif
                                </td>
                            @else
                                <td>
                                    {{ $visaDetail->issued_date }}
                                </td>
                                <td>
                                    {{ $visaDetail->visa_expiry_date }}
                                </td>
                            @endif

                            <td>{{ $dateHelper->DateDiffInDay(date('Y-m-d'), $visaDetail->visa_expiry_date) }}</td>
                            <td>
                                <a class="btn btn-outline-secondary btn-icon mx-1"
                                    href="{{ route('employee.showDocumentDetail', $visaDetail->id) }}" data-popup="tooltip"
                                    data-placement="top" data-original-title="Extend expiry date or delete document">
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
                {{ $visaDetails->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
@endsection
