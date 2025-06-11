@extends('admin::layout')
@section('title') Employee Career Mobility Probationary Period @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Employee Career Mobility Probationary Period</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('employee.careerMobilityExtensionOfProbationaryPeriod.index') }}" method="GET">
            <div class="row">
                <div class="col-md-3">
                    <label class="d-block font-weight-semibold">Select Organization:</label>
                    <div class="input-group">
                        {!! Form::select('org_id', $organizationList, request('org_id') ?: null, [
                            'class' => 'form-control select-search',
                            'id' => 'org_id',
                            'placeholder' => 'Select Organization',
                        ]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                    {!! Form::select('employee_id', @$employeeList, $value = request('employee_id') ?: null, [
                        'placeholder' => 'Select  Employee',
                        'id' => 'employee_id',
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
                    'route' => 'employee.careerMobilityExtensionOfProbationaryPeriod.store',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'id' => 'extensionOfProbationaryPeriodForm',
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
                                            <select name="contract_type" class="form-control select-search" disabled>
                                                @foreach ($contractTypes as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ $value == $contractType ? 'selected' : '' }}>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                </div>
                            @endif

                        @endif
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Letter Issue Date:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <x-utilities.date-picker :date="@$employeeCarrierMobilityProbationaryPeriod->nep_letter_issue_date" mode="both" default="nep"
                                    nepDateAttribute="nep_letter_issue_date" engDateAttribute="letter_issue_date" />

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Extension From Date: <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <x-utilities.date-picker :date="$employees->nep_extension_from_date ?? request('nep_extension_from_date')" mode="both" default="nep"
                                    nepDateAttribute="nep_extension_from_date" engDateAttribute="extension_from_date" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Extension Till Date: <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <x-utilities.date-picker :date="$employees->nep_extension_till_date ?? request('extension_till_date')" mode="both" default="nep"
                                    nepDateAttribute="nep_extension_till_date" engDateAttribute="extension_till_date" />
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
        $('#org_id').on('change', function() {
            let orgId = $(this).val();
            let employeeSelect = $('#employee_id');

            employeeSelect.html('<option value="">Loading...</option>');

            if (orgId) {
                $.ajax({
                    url: '{{ route('get.contractemployees.by.organization') }}',
                    type: 'GET',
                    data: {
                        org_id: orgId
                    },
                    success: function(response) {
                        employeeSelect.empty().append(
                            '<option value="">Select Employee</option>');
                        $.each(response, function(key, value) {
                            employeeSelect.append('<option value="' + key + '">' +
                                value + '</option>');
                        });
                        employeeSelect.select2();
                    },
                    error: function() {
                        employeeSelect.html(
                            '<option value="">Error loading employees</option>');
                    }
                });
            } else {
                employeeSelect.html('<option value="">Select Employee</option>');
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const oldEndDate = document.getElementById('old_end-date')?.value;
        if (oldEndDate) {
            const [bsYear, bsMonth, bsDay] = oldEndDate.split('-').map(Number);

            const adDate = NepaliFunctions.BS2AD({
                year: bsYear,
                month: bsMonth,
                day: bsDay
            });

            const dateObj = new Date(adDate.year, adDate.month - 1, adDate.day);
            dateObj.setDate(dateObj.getDate() + 1);

            const newBS = NepaliFunctions.AD2BS({
                year: dateObj.getFullYear(),
                month: dateObj.getMonth() + 1,
                day: dateObj.getDate()
            });

            const formattedBS =
                `${newBS.year}-${String(newBS.month).padStart(2, '0')}-${String(newBS.day).padStart(2, '0')}`;
            const formattedAD =
                `${dateObj.getFullYear()}-${String(dateObj.getMonth() + 1).padStart(2, '0')}-${String(dateObj.getDate()).padStart(2, '0')}`;

            $("#nep_extension_from_date").val(formattedBS).trigger('change');
            $('input[name="nep_extension_from_date"]').val(formattedBS);
            $("#extension_from_date").val(formattedAD).trigger('change');
            $('input[name="extension_from_date"]').val(formattedAD);

        }



    });
</script>
{{-- <script>
    $(document).ready(function() {
        $('#letter_issue_date').nepaliDatePicker({
            ndpYear: true,
            ndpMonth: true,
            ndpTriggerButton: false,
            onChange: function() {
                var elm = document.getElementById("letter_issue_date").value;
                extensionDate(elm)
            }
        });

        function extensionDate(minDate) {
            let newDate = addOneDay(minDate);
            $('#extension_till_date').nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                ndpTriggerButton: false,
                disableBefore: newDate,
                onChange: function() {
                    var appointmentDate = document.getElementById("extension_till_date").value;
                }

            });
        }

        function addOneDay(fullDate) {
            let date = fullDate;
            let parts = date.split("-"); // Split by "-"
            let year = parts[0];
            let month = parts[1];
            let day = parseInt(parts[2], 10) + 1; // Add 1 to the day

            let newDate = `${year}-${month}-${day}`;
            return newDate;

        }

    });
</script> --}}


@endSection
