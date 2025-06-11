@extends('admin::layout')
@section('title') Employee Career Mobility Demotion @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Employee Career Mobility Demotion</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('employee.careerMobilityDemotion.index') }}" method="GET">
            <div class="row">
                <div class="col-md-3">
                    <label class="d-block font-weight-semibold">Select Organization:</label>
                    <div class="input-group">
                        {!! Form::select('org_id', $organizationList, null, [
                            'class' => 'form-control select-search organizationWisePermanentEmployeeFetch',
                            'placeholder' => 'Select Organization',
                        ]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                    {!! Form::select('employee_id', $employeeList, $value = request('employee_id') ?: null, [
                        'placeholder' => 'Select Permanent Employee',
                        'class' => 'form-control select-search employee-filter',
                        'required',
                    ]) !!}
                </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ request()->url() }}" class="btn bg-secondary text-white">
                    <i class="icons icon-reset mr-1"></i>Reset
                </a>
            </div>
        </form>

    </div>
</div>


@if (!empty($employee))
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-elements-inline text-light bg-secondary">
                    <h5 class="card-title">Current Details</h5>
                    <div class="header-elements">
                    </div>
                </div>
                <div class="card-body">
                    @include('employee::employee.carrier-mobility.partial.current_details')

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-elements-inline text-light bg-secondary">
                    <h5 class="card-title">New Details</h5>
                </div>
                {!! Form::open([
                    'route' => 'employee.careerMobilityDemotion.store',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'id' => 'employee-careerMobility-demotion-store',
                    'role' => 'form',
                    'files' => false,
                ]) !!}

                <input type="hidden" name="employee_id" value="{{ request()->employee_id }}">

                @php
                    if (setting('calendar_type') == 'BS') {
                        $classData = 'form-control nepali-calendar';
                    } else {
                        $classData = 'form-control daterange-single';
                    }
                @endphp
                <div class="form-group row mt-2 p-3">
                    <div class="col-md-12">
                        @if ($employee->payrollRelatedDetailModel != null)
                            @php

                                if (!is_null($employee->payrollRelatedDetailModel->contract_type)) {
                                    $contractType =
                                        App\Modules\Leave\Entities\LeaveType::CONTRACT[
                                            $employee->payrollRelatedDetailModel->contract_type
                                        ];
                                } else {
                                    $contractType = null;
                                }
                            @endphp
                            @if (!is_null($contractType))
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3">Contract Type:</label>
                                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            <select name="contract_type"
                                                class="form-control select-search contractTypeDemotion">
                                                @foreach ($contractTypes as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ $value == $contractType ? 'selected' : '' }}>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row contract-date">
                                    <label class="col-form-label col-lg-3"> From Date:</label>
                                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::text('contract_start_date', $employee->payrollRelatedDetailModel->contract_start_date, [
                                                'class' => 'form-control nepaliDateForPromotion contract_start_date',
                                                'placeholder' => 'e.g: YYYY-MM-DD',
                                            ]) !!}

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row contract-date">
                                    <label class="col-form-label col-lg-3"> To Date:</label>
                                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::text('contract_end_date', $employee->payrollRelatedDetailModel->contract_end_date, [
                                                'class' => 'form-control nepaliDateForPromotion contract_end_date',
                                                'placeholder' => 'e.g: YYYY-MM-DD',
                                            ]) !!}

                                        </div>
                                    </div>
                                </div>
                            @endif

                        @endif
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Letter Issue Date:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('letter_issue_date', @$employeeCareerMobilityDemotion->letter_issue_date, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control nepaliDateForPromotion',
                                    ]) !!}

                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Location:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('location', @$employeeCareerMobilityDemotion->location, [
                                        'placeholder' => 'Enter Location',
                                        'class' => 'form-control',
                                    ]) !!}

                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Sub-Function:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('department_id', $remainingDepartment, @$employee->department_id, [
                                        'class' => 'form-control select-search',
                                        'placeholder' => 'Select Sub-Function',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Demotion Date:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('demotion_date', @$employeeCareerMobilityDemotion->demotion_date, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control nepaliDateForPromotion',
                                    ]) !!}

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Demotion To:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <select name="demotion_to" class="form-control select-search">
                                        <option value="" selected>Select Designation</option>
                                        @foreach ($remainingDesignation as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ $key == @$employee->designation_id ? 'selected' : '' }}>
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body card-temporary-address">
                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-labeled btn-labeled-left" id="submitData"
                                data-employee_id={{ request()->employee_id }}><b><i
                                        class="icon-database-insert"></i></b>Save Record</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
@endif



@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.nepaliDateForPromotion').nepaliDatePicker({
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 10
        });

    });
</script>


<script>
    $(document).ready(function() {
        function toggleContractDate(selectedValue) {
            if (selectedValue === '12' || selectedValue === '10' || selectedValue === '') {
                $('.contract-date').addClass('d-none');
                $('.contract_end_date, .contract_start_date').val('');
            } else {
                $('.contract-date').removeClass('d-none');
            }
        }

        // Event listener for select change
        $('.contractTypeDemotion').on('change', function() {
            toggleContractDate($(this).val());
        });

        // Initial check on page load
        toggleContractDate($('.contractTypeDemotion').val());
    });
</script>


@endSection
