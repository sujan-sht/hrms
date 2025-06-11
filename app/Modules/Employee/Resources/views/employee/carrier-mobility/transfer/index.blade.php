@extends('admin::layout')
@section('title') Employee Career Mobility Transfer @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Employee Career Mobility Transfer</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('employee::employee.carrier-mobility.transfer.partial.advance-filter', [
    'route' => route('employee.careerMobilityTransfer.index'),
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
                    'route' => 'employee.careerMobilityTransfer.store',
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
                            <label class="col-form-label col-lg-3">Job Title :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right text-success">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text alpha-success text-success border-success"><i
                                                class="icon-users2"></i></span>
                                    </span>
                                    <input id="job_title" placeholder="Enter Job Title"
                                        class="form-control border-success" name="job_title" type="text"
                                        value="{{ $employee->job_title }}" aria-required="true"
                                        aria-describedby="job_title-error" aria-invalid="false">
                                    <div class="form-control-feedback"><i class="icon-checkmark4 text-success"></i>
                                    </div>
                                </div><em id="job_title-error" class="error help-block"></em>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Letter Issue Date:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <x-utilities.date-picker :date="null" mode="both" default="nep"
                                    nepDateAttribute="letter_issue_date" engDateAttribute="eng_letter_issue_date" />

                                {{-- <div class="input-group">
                                    {!! Form::text('letter_issue_date', @$employeeCareerMobilityTransfer->letter_issue_date, [
                                        'id' => 'letter_issue_date',
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control nepali-transfer-picker',
                                    ]) !!}
                                </div> --}}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Transferred Unit:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('branch_id', $filteredBranchList, null, [
                                        'placeholder' => 'Select Unit',
                                        'class' => 'form-control select-search',
                                    ]) !!}

                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3"> Transferred Date:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <x-utilities.date-picker :date="null" mode="both" default="nep"
                                    nepDateAttribute="transfer_date" engDateAttribute="eng_transfer_date" />

                                {{-- <div class="input-group">
                                    {!! Form::text('transfer_date', @$employeeCareerMobilityTransfer->transfer_date, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'id' => 'transfer_from',
                                        'class' => 'form-control nepali-transfer-picker',
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
                                    {!! Form::text('effective_date', @$employeeCareerMobilityTransfer->effective_date, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'id' => 'effective_date',
                                        'class' => 'form-control nepali-transfer-picker',
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
<script type="text/javascript">
    $('document').ready(function() {

        $('#submitData').on('click', function() {
            var type = $('.type').val();
            var organization_id = $('#organizationId').val();
            var date = $('#date').val();
            var employee_id = $(this).data('employee_id');
            if (type == 1) {
                if (date && organization_id && type) {
                    $('#date-error-message').hide();
                    $('#organization-error-message').hide();
                    $('#appendLeaveDetail').html("");
                    $('#updateLeave').modal('show');
                    $.ajax({
                        type: "get",
                        url: "{{ route('employee.carrierMobility.appendLeaveDetail') }}",
                        data: {
                            date: date,
                            employee_id: employee_id,
                            organization_id: organization_id,
                            type_id: type
                        },
                        success: function(res) {
                            // console.log(res);
                            $('#appendLeaveDetail').append(res.data);
                        }
                    });
                } else {
                    $('#date-error-message').show();
                    $('#organization-error-message').show();
                }

            } else {
                $('#carrierMobilityFormSubmit').submit();
            }
        });

    });
</script>


<script>
    $(document).ready(function() {
        // Initialize both datepickers
        $('.nepali-transfer-picker').nepaliDatePicker({
            ndpYear: true,
            ndpMonth: true,
            ndpTriggerButton: false
        });

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
