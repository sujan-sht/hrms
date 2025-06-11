<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

                <div class="row">
                    {{-- @if (auth()->user()->user_type != 'employee')
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Employee<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::select('employee_id', $employees, request()->get('employee_id') ? request()->get('employee_id') : null, [
                                        'id' => 'employee_id',
                                        'class' => 'form-control select-search',
                                        'data-toggle' => 'select2',
                                        'placeholder' => 'Select Employee'
                                    ]) !!}
                                    @if ($errors->first('employee_id') != null)
                                        <ul class="parsley-errors-list filled" aria-hidden="false">
                                            <li class="parsley-required">{{ $errors->first('employee_id') }}</li>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else --}}
                        <input type="hidden" name="employee_id" value="{{ auth()->user()->emp_id }}" id = 'employee_id'>
                    {{-- @endif --}}

                    @if (setting('calendar_type') == 'BS')
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Date<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::text('nepali_date', request()->get('nepali_date') ? request()->get('nepali_date') : null, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control nepali-calendar',
                                        'id' => 'nepFromDate',
                                        'readonly',
                                        'required'
                                    ]) !!}
                                </div>
                                <span class="errorStartDate"></span>
                            </div>
                        </div>

                        {{-- <div class="col-md-6 mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">To Date<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::text('to_date_nep', request()->get('to_date_nep') ? request()->get('to_date_nep') : null, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control nepali-calendar toDate',
                                        'id' => 'nepToDate',
                                        'readonly',
                                    ]) !!}
                                </div>
                            </div>
                        </div> --}}
                    @else
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Date<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::text('date', request()->get('date') ? request()->get('date') : null, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control daterange-single',
                                        'id' => 'fromDate',
                                        'readonly',
                                        'required'
                                    ]) !!}
                                </div>
                                <span class="errorStartDate"></span>
                            </div>
                        </div>

                        {{-- <div class="col-md-6 mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">To Date<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::text('to_date', request()->get('to_date') ? request()->get('to_date') : null, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control daterange-single',
                                        'id' => 'toDate',
                                        'readonly',
                                    ]) !!}
                                </div>
                            </div>
                        </div> --}}
                    @endif

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Shift Group<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::select('shift_group_id', $shiftGroupLists, request()->get('shift_group_id') ? request()->get('shift_group_id') : null, [
                                    'id' => 'shift_group_id',
                                    'class' => 'form-control select-search',
                                    'data-toggle' => 'select2',
                                    'placeholder' => 'Select Shift Group',
                                    'required'
                                ]) !!}
                                @if ($errors->first('shift_group_id') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('shift_group_id') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- @if (auth()->user()->user_type == 'employee')
                        <div class="col-md-6">
                    @else
                        <div class="col-md-6 mt-3">
                    @endif
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Start Time <span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                @php
                                    if (isset($request->start_time)) {
                                        $startTime = date('H:i', strtotime($request->start_time));
                                    } else {
                                        $startTime = ' ';
                                    }
                                @endphp
                                <div class="input-group">
                                    {!! Form::time('start_time', $value = $startTime, ['id' => 'startTime', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">End Time <span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                @php
                                    if (isset($request->end_time)) {
                                        $endTime = date('H:i', strtotime($request->end_time));
                                    } else {
                                        $endTime = ' ';
                                    }
                                @endphp
                                <div class="input-group">
                                    {!! Form::time('end_time', $value = $endTime, ['id' => 'endTime', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Time (In minutes)</label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('ot_time', request()->get('ot_time') ? request()->get('ot_time') : null, ['class' => 'form-control', 'readonly', 'id'=>'otTime']) !!}


                                @if ($errors->first('ot_time') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('ot_time') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div id="noticeList">

                    </div> --}}

                </div>
                
                {{-- <div class="row">
                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Remarks <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::textarea('remarks', request()->get('remarks') ? request()->get('remarks') : null, ['class' => 'form-control', 'placeholder' => 'Enter remarks']) !!}

                                @if ($errors->first('remarks') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('remarks') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                        class="icon-backward2"></i></b>Go Back</a>
            <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
            class="icon-database-insert"></i></b>{{ $btnType }}</button>
        </div>
    </div>
</div>

<script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
{{-- <script src="{{ asset('admin/validation/overtimeRequest.js') }}"></script> --}}

<script>
    // var businessTripId = "{{ $id ?? '' }}";

    $(document).ready(function() {
        $('#employee_id, #startTime, #endTime').on('change', function () {
            let start_time = $('#startTime').val()
            let end_time = $('#endTime').val()
            getTimeDifference(start_time, end_time)
        })

        function getTimeDifference(start_time, end_time){
            if(start_time && end_time){
                // Convert time to Date objects
                let start_date = new Date("1970-01-01T" + start_time + "Z");
                let end_date = new Date("1970-01-01T" + end_time + "Z");

                // Calculate difference in minutes
                let differenceInMs = end_date - start_date;
                let differenceInMinutes = Math.floor(differenceInMs / 1000 / 60);

                $('#otTime').val(differenceInMinutes)
                let employee_id = $('#employee_id').val()

                $.ajax({
                    type: 'GET',
                    url: '/admin/overtime-request/check-min-Ot-time',
                    data: {
                        employee_id: employee_id,
                        difference_in_mins: differenceInMinutes
                    },
                    success: function(msg) {
                        console.log(msg);
                        if(msg != ''){
                            $('#noticeList').show();
                            $('#noticeList').addClass('col-lg-12 mt-2 text-danger')
                            $('#noticeList').html(msg)

                            $('#startTime').val(null)
                            $('#endTime').val(null)
                            $('#otTime').val(null)
                        } else {
                            $('#noticeList').hide();
                        }
                    }
                });
            }
        }
    })
</script>
