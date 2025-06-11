<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Title<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::select('title', $titleList, isset($shiftModel) ? $shiftModel->title : '', [
                                    'id' => 'shiftTitle',
                                    'class' => 'form-control',
                                    'data-toggle' => 'select2',
                                ]) !!}
                                @if ($errors->first('title') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('title') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="customTitle" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Custom Title<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::text('custom_title', isset($shiftModel) ? $shiftModel->custom_title : '', [
                                        'placeholder' => 'Custom Title',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Start Time</label>
                            </div>
                            <div class="col-md-12">
                                @php
                                    if (isset($shiftModel)) {
                                        $startTimeValue = $shiftModel->start_time;
                                    } else {
                                        $startTimeValue = '10:00';
                                    }
                                @endphp
                                <div class="input-group">
                                    {!! Form::time('start_time', $value = $startTimeValue, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">End Time</label>
                            </div>
                            <div class="col-md-12">
                                @php
                                    if (isset($shiftModel)) {
                                        $endTimeValue = $shiftModel->end_time;
                                    } else {
                                        $endTimeValue = '17:00';
                                    }
                                @endphp
                                <div class="input-group">
                                    {!! Form::time('end_time', $value = $endTimeValue, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <legend class="text-uppercase font-size-sm font-weight-bold">Day-wise Shift Times</legend>
                @if (isset($shiftModel->shiftDayWise) && count($shiftModel->shiftDayWise) > 0)
                    @foreach ($shiftModel->shiftDayWise as $key => $shiftDayWise)
                        {{-- @include('shift::shift.partial.clone', [
                            'btnType' => 'Edit',
                            'count' => $key,
                            'shiftDayWise' => $shiftDayWise,
                        ]) --}}

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Day</label>
                                <div class="input-group">
                                    {!! Form::text("day[$shiftDayWise->day]", $shiftDayWise->day, ['class' => 'form-control', 'readonly']) !!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    {!! Form::time(
                                        "start_time[$shiftDayWise->day]",
                                        isset($shiftDayWise->start_time) ? $shiftDayWise->start_time : '00:00',
                                        ['class' => 'form-control start-time', 'required'],
                                    ) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">End Time <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    {!! Form::time(
                                        "end_time[$shiftDayWise->day]",
                                        isset($shiftDayWise->end_time) ? $shiftDayWise->end_time : '00:00',
                                        ['class' => 'form-control end-time', 'required'],
                                    ) !!}
                                </div>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Total Hrs</label>
                                <div class="input-group">
                                    {!! Form::text('total_hrs', null, ['class' => 'form-control total-hrs', 'readonly']) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- @include('shift::shift.partial.clone', [
                        'btnType' => 'Create',
                        'count' => 0,
                    ]) --}}

                    @foreach ($daysOfWeek as $day => $fullName)
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Day</label>
                                <div class="input-group">
                                    {!! Form::text("day[$day]", $day, ['class' => 'form-control', 'readonly']) !!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    {!! Form::time("start_time[$day]", '10:00', ['class' => 'form-control start-time', 'required']) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">End Time <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    {!! Form::time("end_time[$day]", '17:00', ['class' => 'form-control end-time', 'required']) !!}
                                </div>
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">Total Hrs</label>
                                <div class="input-group">
                                    {!! Form::text('total_hrs', null, ['class' => 'form-control total-hrs', 'readonly']) !!}
                                </div>
                            </div>

                        </div>
                    @endforeach
                @endif

                {{-- @php
                    $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    $defaultStartTime = '10:00';
                    $defaultEndTime = '17:00';
                @endphp
 
                @foreach ($daysOfWeek as $day)
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Day</label>
                            <div class="input-group">
                                {!! Form::text("day[$day]", $day, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Start Time</label>
                            <div class="input-group">
                                {!! Form::time("start_time[$day]", isset($shiftModel->start_time[$day]) ? $shiftModel->start_time[$day] : $defaultStartTime, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">End Time</label>
                            <div class="input-group">
                                {!! Form::time("end_time[$day]", isset($shiftModel->end_time[$day]) ? $shiftModel->end_time[$day] : $defaultEndTime, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                    </div>
                @endforeach --}}

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

<script>
    $(function() {
        $('#shiftTitle').on('change', function() {
            var title = $(this).val();
            if (title == 'Custom') {
                $('#customTitle').show();
            } else {
                $('#customTitle').hide();
            }
        });

        $('#shiftTitle').trigger('change');
    });

    function calculateTotalHours(startTime, endTime) {
        const start = new Date('1970-01-01T' + startTime + 'Z');
        const end = new Date('1970-01-01T' + endTime + 'Z');
        if (end < start) {
            end.setDate(end.getDate() + 1);
        }
        const diff = end - start;
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

        const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
        return `${hours}.${formattedMinutes}`;
    }

    $('.start-time, .end-time').on('change', function() {
        const row = $(this).closest('.row');
        const startTime = row.find('.start-time').val();
        const endTime = row.find('.end-time').val();
        const totalHours = calculateTotalHours(startTime, endTime);
        row.find('.total-hrs').val(totalHours);
    });

    $('.start-time, .end-time').trigger('change');
</script>
