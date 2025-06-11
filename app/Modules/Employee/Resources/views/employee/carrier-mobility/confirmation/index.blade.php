@extends('admin::layout')
@section('title') Employee Career Mobility Confirmation @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Employee Career Mobility Confirmation</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('employee::employee.carrier-mobility.confirmation.partial.advance-filter', [
    'route' => route('employee.careerMobilityConfirmation.index'),
])

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
                    'route' => 'employee.careerMobilityConfirmation.store',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'id' => 'employee-careerMobility-confirmation-store',
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


                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Designation:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <select name="designation_id" class="form-control select-search">
                                        <option value="" selected>Select Designation</option>
                                        @foreach ($remainingDesignation as $id => $designation)
                                            <option value="{{ $id }}">
                                                {{ $designation }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Contract Type:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('contract_type', $contractTypeList, null, [
                                        'placeholder' => 'Select Contract Type',
                                        'class' => 'form-control select-search',
                                        'id' => 'contract_type',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row d-none contract-date">
                            <label class="col-form-label col-lg-3">Contract Start Date:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <x-utilities.date-picker :date="null" mode="both" default="nep"
                                    nepDateAttribute="contract_start_date" engDateAttribute="eng_contract_start_date" />

                                {{-- <div class="input-group">
                                    {!! Form::text('contract_start_date', null, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control daterange-single contract_start_date',
                                    ]) !!}
                                </div> --}}
                            </div>
                        </div>
                        <div class="form-group row d-none contract-date">
                            <label class="col-form-label col-lg-3">Contract End Date:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <x-utilities.date-picker :date="null" mode="both" default="nep"
                                    nepDateAttribute="contract_end_date" engDateAttribute="eng_contract_end_date" />

                                {{-- <div class="input-group">
                                    {!! Form::text('contract_end_date', null, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control daterange-single contract_end_date',
                                    ]) !!}
                                </div> --}}
                            </div>
                        </div>



                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Letter Issue Date:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <x-utilities.date-picker :date="null" mode="both" default="nep"
                                    nepDateAttribute="letter_issue_date" engDateAttribute="eng_letter_issue_date" />

                                {{-- <div class="input-group">
                                    {!! Form::text('letter_issue_date', @$employeeCareerMobilityConfirmation->letter_issue_date, [
                                        'id' => 'letter_issue_date',
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div> --}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3"> Confirmation Date:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <x-utilities.date-picker :date="null" mode="both" default="nep"
                                    nepDateAttribute="confirmation_date" engDateAttribute="eng_confirmation_date" />

                                {{-- <div class="input-group">
                                    {!! Form::text('confirmation_date', @$employeeCareerMobilityConfirmation->confirmation_date, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'id' => 'appointment_date',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div> --}}
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
<script type="text/javascript"></script>

<script>
    $(document).ready(function() {
        $('#contract_type').on('change', function() {
            var val = $(this).val();
            if (val == '12' || val == '') {
                $('.contract-date').addClass('d-none');
                $('.contract_end_date').val('');
                $('.contract_start_date').val('');
            } else if (val == '10') {
                $('.contract-date').addClass('d-none');
                ('.contract_end_date').val('');
                $('.contract_start_date').val('');
            } else {
                $('.contract-date').removeClass('d-none');
            }
        });

    })
</script>
<script>
    $(document).ready(function() {

        // Initialize both datepickers
        $('#letter_issue_date').nepaliDatePicker({
            ndpYear: true,
            ndpMonth: true,
            ndpTriggerButton: false,
            onChange: function() {
                var elm = document.getElementById("letter_issue_date").value;
                appointmentDate(elm)
            }
        });

        function appointmentDate(minDate) {
            let newDate = addOneDay(minDate);
            $('#appointment_date').nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                ndpTriggerButton: false,
                disableBefore: newDate,

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
</script>


@endSection
