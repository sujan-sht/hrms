@extends('admin::layout')
@section('title') Employee Career Mobility Transfer @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Employee Career Mobility Transfer</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('employee::employee.carrier-mobility.transfer.partial.advance-filter', [
    'route' => route('employee.careerMobilityTemporaryTransfer.index'),
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
                    'route' => 'employee.careerMobilityTemporaryTransfer.store',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'id' => 'employee-careerMobility-transfer-store',
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
                            <label class="col-form-label col-lg-3">Transferred Unit:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('branch_id', $filteredBranchList, @$employeeCareerMobilityTemporaryTransfer->branch_id, [
                                        'placeholder' => 'Select Unit',
                                        'class' => 'form-control select-search',
                                    ]) !!}

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Letter Issue Date:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <x-utilities.date-picker :date="null" mode="both" default="nep"
                                    nepDateAttribute="letter_issue_date" engDateAttribute="eng_letter_issue_date" />

                                {{-- <div class="input-group">
                                    {!! Form::text('letter_issue_date', @$employeeCareerMobilityTemporaryTransfer->letter_issue_date, [
                                        'id' => 'letter_issue_date',
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div> --}}
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-form-label col-lg-3"> Transfer From:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <x-utilities.date-picker :date="null" mode="both" default="nep"
                                    nepDateAttribute="transfer_from_date" engDateAttribute="eng_transfer_from_date" />

                                {{-- <div class="input-group">
                                    {!! Form::text('transfer_from_date', @$employeeCareerMobilityTemporaryTransfer->transfer_from_date, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'id' => 'transfer_from',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div> --}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3"> Transfer To:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <x-utilities.date-picker :date="null" mode="both" default="nep"
                                    nepDateAttribute="transfer_to_date" engDateAttribute="eng_transfer_to_date" />

                                {{-- <div class="input-group">
                                    {!! Form::text('transfer_to_date', @$employeeCareerMobilityTemporaryTransfer->transfer_to_date, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'id' => 'transfer_to',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div> --}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3"> Effective Date:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <x-utilities.date-picker :date="null" mode="both" default="nep"
                                    nepDateAttribute="effective_date" engDateAttribute="eng_effective_date" />

                                {{-- <div class="input-group">
                                    {!! Form::text('effective_date', @$employeeCareerMobilityTemporaryTransfer->effective_date, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'id' => 'effective_date',
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
            $('#transfer_from').nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                ndpTriggerButton: false,
                disableBefore: newDate,
                onChange: function() {
                    var appointmentDate = document.getElementById("transfer_from").value;
                    let newDateOfAppoint = addOneDay(appointmentDate);
                    transferTo(newDateOfAppoint)
                }

            });
        }

        function transferTo(date) {
            $('#transfer_to').nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                ndpTriggerButton: false,
                disableBefore: date,
                onChange: function() {
                    var appointmentDate = document.getElementById("transfer_to").value;
                    let newDateOfAppoint = addOneDay(appointmentDate);
                    effectiveDate(newDateOfAppoint)
                }
            });
        }

        function effectiveDate(date) {
            $('#effective_date').nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                ndpTriggerButton: false,
                disableBefore: date,
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
