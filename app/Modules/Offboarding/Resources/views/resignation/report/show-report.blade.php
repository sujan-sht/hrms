@extends('admin::layout')
@section('title') Clearance Report @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Clearance Report</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

{{-- @include('offboarding::resignation.partial.advance_filter') --}}

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Clearance Report of Resignation Employee</h6>
            All the Resignation and Clearance Information will be listed below. You can only view the data.
        </div>
        <div class="mt-1 mr-2">

        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>Responsible Name</th>
                                <th>Clearance Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clearanceModels as $Key => $clearanceModel)
                                @foreach ($clearanceModel->clearanceResponsible as $key => $value)
                                    <tr>
                                        <td>{{ optional($value->employee)->getFullName() }}</td>
                                        <td>{{ optional($value->clearance)->title }}</td>
                                        @php
                                            $employeeClearance = $value
                                                ->employeeClearance()
                                                ->where('offboard_resignation_id', $resignationModel->id)
                                                ->first();
                                        @endphp

                                        @if ($employeeClearance == null)
                                            <td><span class="text-danger">Unverified</span></td>
                                        @else
                                            <td>{!! $employeeClearance->status == 11
                                                ? '<span class="text-success">Verified</span>'
                                                : '<span class="text-danger">Unverified</span>' !!}</td>
                                        @endif

                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Resignation Employee Detail</legend>

                <div class="col-lg-12">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Employee Name:</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right mt-1">
                            <div class="input-group">
                                {{ optional($resignationModel->employeeModel)->getFullName() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Organization:</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right mt-1">
                            <div class="input-group">
                                {{ optional($resignationModel->employeeModel)->organizationModel->name }}
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-12">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Sub-Function:</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right mt-1">
                            <div class="input-group">
                                {{ optional($resignationModel->employeeModel)->department->title }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Designation:</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right mt-1">
                            <div class="input-group">
                                {{ optional($resignationModel->employeeModel)->designation->title }}
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-12">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Last Working Date:</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right mt-1">
                            <div class="input-group">
                                {{ $resignationModel->last_working_date }}
                            </div>
                        </div>
                    </div>
                </div>

                <legend class="text-uppercase font-size-sm font-weight-bold">Letter Issued Detail</legend>
                <div class="col-lg-12">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Date Issued:</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right mt-1">
                            <div class="input-group">
                                {{ $resignationModel->issued_date }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Remark:</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right mt-1">
                            <div class="input-group">
                                {{ $resignationModel->issued_remark }}
                            </div>
                        </div>
                    </div>
                </div>

                <legend class="text-uppercase font-size-sm font-weight-bold">Letter Received Detail</legend>
                <div class="col-lg-12">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Date Received:</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right mt-1">
                            <div class="input-group">
                                {{ $resignationModel->received_date }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Received By:</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right mt-1">
                            <div class="input-group">
                                {{ $resignationModel->received_by }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Received Remark:</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right mt-1">
                            <div class="input-group">
                                {{ $resignationModel->received_remark }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

@endSection
