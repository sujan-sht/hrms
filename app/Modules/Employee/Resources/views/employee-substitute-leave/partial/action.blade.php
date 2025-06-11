<div class="card">
    <div class="card-body">
        <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
        <div class="form-group row">
            @if (auth()->user()->user_type == 'supervisor' || auth()->user()->user_type == 'employee')
            {!! Form::hidden('employee_id', auth()->user()->emp_id, ['class'=>'employee']) !!}
            @else
            <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-4">Employee :<span class="text-danger"> *</span></label>
                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::select('employee_id', $employeeList, null, [
                            'placeholder' => 'Select Employee',
                            'class' => 'form-control select-search employee',
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-lg-6 mb-3">
                <div class="row">
                    @if (setting('two_step_substitute_leave') == 11)
                    <label class="col-form-label col-lg-4">Date of Request :<span class="text-danger"> *</span></label>

                    @else
                    <label class="col-form-label col-lg-4">Date of Claim :<span class="text-danger"> *</span></label>
                    @endif
                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            @if (leaveYearSetup('calendar_type') == 'BS')
                            {!! Form::text('nepali_date', $isEdit ? $employeeSubstituteLeaveModel->nepali_date : null, [
                            'placeholder' => 'YYYY-MM-DD',
                            'class' => 'form-control nepali-calendar',
                            'autocomplete' => 'off',
                            ]) !!}
                            @else
                            {!! Form::text('date', $isEdit ? $employeeSubstituteLeaveModel->date : null, [
                            'id' => 'date',
                            'placeholder' => 'YYYY-MM-DD',
                            'class' => 'form-control',
                            'autocomplete' => 'off',
                            ]) !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::hidden('leave_kind', 2, []) !!}

            {{-- <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-4">Leave Category :<span class="text-danger">
                            *</span></label>
                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <div class="p-1 rounded radio-group">
                                @foreach ($leaveKindList as $key => $leaveKind)
                                @php
                                if ($key == '4') {
                                break;
                                }
                                @endphp
                                <div class="custom-control custom-radio custom-control-inline">
                                    {{ Form::radio('leave_kind', $key, false, ['class' => 'custom-control-input
                                    leaveKind', 'id' => 'radio' . $key]) }}
                                    <label class="custom-control-label" for="radio{{ $key }}">{{ $leaveKind }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @if ($errors->has('leave_kind'))
                        <div class="error text-danger">{{ $errors->first('leave_kind') }}</div>
                        @endif
                    </div>

                </div>
            </div> --}}

            <div class="col-lg-12">
                <div class="card-body d-none" id="attendance-box">
                    <ul class="list-group list-group-flush">
                         <li class="list-group-item  py-2 text-danger" id="attendance-error">

                        </li>

                        <li class="list-group-item  py-2 ">
                            <strong>Check-in:</strong> <span id="checkin" class="text-success pl-3">-</span>
                            <input type="hidden" name="checkin" id="checkinInput">
                        </li>
                        <li class="list-group-item  py-2 ">
                            <strong>Check-out:</strong> <span id="checkout" class="text-success pl-3">-</span>
                            <input type="hidden" name="checkout" id="checkoutInput">

                        </li>
                        <li class="list-group-item  py-2 ">
                            <strong>Total Working Hours:</strong> <span id="total_hr" class="text-success pl-3">-</span>
                            <input type="hidden" name="total_working_hr" id="totalWorkingHr">

                        </li>
                    </ul>
                </div>



            </div>

            <div class="col-lg-12 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-2">Remark :<span class="text-danger"> *</span></label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::textarea('remark', null, ['rows' => 5, 'placeholder' => 'Write remark..', 'class'
                            => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::hidden('status', '1', []) !!}
            <!-- @if (auth()->user()->user_type == 'employee')
{!! Form::hidden('status', '1', []) !!}
@else
<div class="col-lg-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Status :</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('status', $statusList, null, ['class' => 'form-control select-search']) !!}
                            </div>
                        </div>
                    </div>
                </div>
@endif -->
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
<script src="{{ asset('admin/validation/employeeSubstituteLeave.js') }}"></script>

<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

<script>
    $(document).ready(function () {
        $('.employee').on('change', function () {
            var employee_id = $(this).val();
            if (employee_id) {
                $.ajax({
                    type: 'GET',
                    url: "{{ route('substituteLeave.minSubstituteDate') }}",
                    dataType: 'json',
                    data: {
                        'employee_id': employee_id
                    },
                    success: function (resp) {
                        if (resp && resp != '') {
                            substituteDate(resp);
                        } else {
                            substituteDate('');
                        }
                    }
                });
            }
        });

        function substituteDate(minDate) {
            // Remove existing daterangepicker if already applied
            $('#date').data('daterangepicker')?.remove();

            $('#date').daterangepicker({
                parentEl: '.content-inner',
                singleDatePicker: true,
                showDropdowns: true,
                minDate: minDate,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                maxDate: moment()
            });

            // Rebind apply event only once per initialization
            $('#date').off('apply.daterangepicker').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));

                let selectedDate = picker.startDate.format('YYYY-MM-DD');
                let employee_id = $('.employee').val();
                        $('#attendance-box').removeClass('d-none').removeClass('text-danger');

                $.ajax({
                    type: 'GET',
                    url: "{{ route('substituteLeave.getAttendance') }}",
                    dataType: 'json',
                    data: {
                        employee_id: employee_id,
                        date: selectedDate
                    },
                    success: function (resp) {

                        if (resp && resp.id) {
                             $('#attendance-error')
                            .addClass('d-none');

                            $('#checkin').text(resp.checkin ?? '-');
                            $('#checkout').text(resp.checkout ?? '-');
                            $('#total_hr').text(resp.total_working_hr ?? '-');

                            $('#checkinInput').val(resp.checkin ?? '');
                            $('#checkoutInput').val(resp.checkout ?? '');
                            $('#totalWorkingHr').val(resp.total_working_hr ?? '-');
                        } else {
                            $('#checkin').text('-');
                            $('#checkout').text('-');
                            $('#total_hr').text('-');
                        }
                    },
                    error: function (xhr) {

                        $('#attendance-error')
                            .removeClass('d-none')
                            .text('Attendance record not found');

                        console.error('API failed:', xhr.responseText);
                    }
                });
            });
        }

        $('.employee').trigger('change');
    });
</script>
@endsection
