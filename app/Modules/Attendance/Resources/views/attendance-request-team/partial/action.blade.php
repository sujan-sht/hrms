<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

                <div class="row mb-3">
                    {!! Form::hidden('calendar_type', $value='eng', []) !!}

                    {{-- @if (auth()->user()->user_type == 'admin' ||
                            auth()->user()->user_type == 'super_admin' ||
                            auth()->user()->user_type == 'hr') --}}
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Employee<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::select('employee_id', $employees, $value = null, [
                                        'id' => 'employee_id',
                                        'class' => 'form-control select-search employeeId',
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
                    {{-- @else
                        <input type="hidden" name="employee_id" value="{{ auth()->user()->emp_id }}" class="employeeId">
                    @endif --}}
                        {!! Form::hidden('calendar_type', $value='eng', ['id' => 'calendarType']) !!}
                    <div class="col-md-6">
                        <div id="customTitle">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Date<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::text('date', $value = null, [
                                        'placeholder' => 'Choose Date',
                                        'class' => 'form-control daterange-single date',
                                        'id' => 'maxDateId',
                                    ]) !!}
                                </div>
                                <span class="errorDate"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">

                    <div class="col-md-6">
                        <div id="customTitle">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Type<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::select('type', $type, $value = null, [
                                        'placeholder' => 'Choose Type',
                                        'class' => 'form-control select-search',
                                        'id' => 'requestType'
                                    ]) !!}
                                </div>
                                <span class="errorType"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 time">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Time<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-12">
                                @php
                                    if (isset($request)) {
                                        $time = date('H:i', strtotime($request->time));
                                    } else {
                                        $time = ' ';
                                    }
                                @endphp
                                <div class="input-group">
                                    {!! Form::time('time', $value = $time, ['class' => 'form-control timeVal']) !!}
                                    <!-- <span class="input-group-text"><i class="icon icon-watch2"></i></span> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 kind" style="display:none">
                        <div id="customTitle">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Kind<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::select('kind', $kind, $value = null, [
                                        'placeholder' => 'Choose Kind',
                                        'class' => 'form-control select-search',
                                        'id' => 'kind'
                                    ]) !!}
                                </div>
                                <span class="errorType"></span>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="row mb-3">

                    <div class="col-md-12">
                        <div id="customTitle">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Detail/Reason<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::textarea('detail', $value = null, [
                                        'placeholder' => 'Enter Reason',
                                        'class' => 'form-control',
                                        'rows' => 4,
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="text-center">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                        class="icon-backward2"></i></b>Go Back</a>
            <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                        class="icon-database-insert"></i></b>{{ $btnType }}</button>
        </div>
    </div>

</div>

<script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('admin/validation/attendanceRequest.js') }}"></script>

<script>
    $(document).ready(function() {
        var type = $('#requestType').val();
        if(type == '5' || type == '6' || type == '7') {
                $('.time').hide();
                $('.kind').show();
        } else {
            $('.time').show();
            $('.kind').hide();
            
        }
        
        $('#requestType').on('change', function() {
            var requestType = $(this).val();
            if(requestType == '5' || requestType == '6' || requestType == '7') {
                $('.time').hide();
                $('.kind').show();
            } else {
                $('.time').show();
                $('.kind').hide();
                
            }
        });
       

    });
</script>
