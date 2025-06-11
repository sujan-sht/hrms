<div>
    @if ($isEdit && isset($shiftModel) && isset($shiftModel->shiftSeasons))
        @foreach ($shiftModel->shiftSeasons as $key => $season)
            <div class="row">
                <legend class="text-uppercase font-size-sm font-weight-bold">Season {{ $key+1 }}</legend>
                <!-- Start Date Input -->
                <div class="col-lg-3 mb-3">
                    <label class="col-form-label">Start Date :<span class="text-danger">*</span></label>
                    <div class="form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('date_from['. ($key+1).']', $season->date_from ?? null, [
                                'id' => 'startDate' . ($key+1),
                                'placeholder' => 'e.g: YYYY-MM-DD',
                                'class' => 'form-control daterange-single',
                                // 'class' => 'form-control daterange-single',
                                'autocomplete' => 'off',
                                'required'
                            ]) !!}
                        </div>
                    </div>
                </div>

                <!-- End Date Input -->
                <div class="col-lg-3 mb-3">
                    <label class="col-form-label">End Date :<span class="text-danger">*</span></label>
                    <div class="form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('date_to['. ($key+1).']', $season->date_to ?? null, [
                                'id' => 'endDate' . ($key+1),
                                'placeholder' => 'e.g: YYYY-MM-DD',
                                'class' => 'form-control daterange-single',
                                // 'class' => 'form-control daterange-single',
                                'autocomplete' => 'off',
                                'required'
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 mb-3">
                    <label class="col-form-label">Is Multi Day Shift?</label>
                    <div class="form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::select('is_multi_day_shift['. ($key+1).']', ['0' => 'No', '1' => 'Yes'], isset($season->is_multi_day_shift) ? $season->is_multi_day_shift : '', [
                                'id' => 'isMultiDayShift' . ($key+1),
                                'class' => 'form-control',
                                'data-toggle' => 'select2',
                            ]) !!}
                        </div>
                    </div>

                </div>

                <!-- Add/Remove Button -->
                <div class="col-lg-3 mb-3">
                    <div class="row justify-content-end">
                        @if ($key+1 == 1)
                            <a class="btn btn-success rounded-pill addMore">
                                <i class="icon-plus-circle2 mr-1"></i>Add More
                            </a>
                        @else
                            <a class="btn btn-danger rounded-pill remove-btn">
                                <i class="icon-minus-circle2 mr-1"></i>Remove
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @php
                $numberIncr = $key+1;
            @endphp
            <div class="row">
                <div class="col-lg-12">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Day-wise Shift Times</legend>
                    @if (isset($season->seasonShiftDayWise) && count($season->seasonShiftDayWise) > 0)
                        @foreach ($season->seasonShiftDayWise as $shiftDayWise)
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label class="form-label">Day</label>
                                <div class="input-group">
                                    {!! Form::text("season_day[$shiftDayWise->day][$numberIncr]", $shiftDayWise->day, ['class' => 'form-control', 'readonly']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Checkin Start Time <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    {!! Form::time("season_checkin_start_time[$shiftDayWise->day][$numberIncr]", isset($shiftDayWise->checkin_start_time) ? $shiftDayWise->checkin_start_time : '00:00', ['class' => 'form-control', 'required']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    {!! Form::time(
                                        "season_start_time[$shiftDayWise->day][$numberIncr]",
                                        isset($shiftDayWise->start_time) ? $shiftDayWise->start_time : '00:00',
                                        ['class' => 'form-control start-time', 'required']
                                    ) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">End Time <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    {!! Form::time(
                                        "season_end_time[$shiftDayWise->day][$numberIncr]",
                                        isset($shiftDayWise->end_time) ? $shiftDayWise->end_time : '00:00',
                                        ['class' => 'form-control end-time', 'required']
                                    ) !!}
                                </div>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Total Hrs</label>
                                <div class="input-group">
                                    {!! Form::text('season_total_hrs['. $numberIncr.']', null, ['class' => 'form-control total-hrs', 'readonly']) !!}
                                </div>
                            </div>
                        </div>
                        @endforeach

                    @endif
                </div>

            </div>
        @endforeach
    @else
    <div class="row">
        <legend class="text-uppercase font-size-sm font-weight-bold">Season {{ $numberIncr }}</legend>
        <!-- Start Date Input -->
        <div class="col-lg-3 mb-3">
            <label class="col-form-label">Start Date :<span class="text-danger">*</span></label>
            <div class="form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('date_from['. $numberIncr.']', null, [
                        'id' => 'startDate' . $numberIncr,
                        'placeholder' => 'e.g: YYYY-MM-DD',
                        // 'class' => 'form-control daterange-single',
                        'class' => 'form-control daterange-single',
                        'autocomplete' => 'off',
                    ]) !!}
                </div>
            </div>
        </div>

        <!-- End Date Input -->
        <div class="col-lg-3 mb-3">
            <label class="col-form-label">End Date :<span class="text-danger">*</span></label>
            <div class="form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('date_to['. $numberIncr.']', null, [
                        'id' => 'endDate' . $numberIncr,
                        'placeholder' => 'e.g: YYYY-MM-DD',
                        // 'class' => 'form-control daterange-single',
                        'class' => 'form-control daterange-single',
                        'autocomplete' => 'off',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="col-lg-3 mb-3">
            <label class="col-form-label">Is Multi Day Shift?</label>
            <div class="form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('is_multi_day_shift['. $numberIncr.']', ['0' => 'No', '1' => 'Yes'], isset($season->is_multi_day_shift) ? $season->is_multi_day_shift : '', [
                        'id' => 'isMultiDayShift' . $numberIncr,
                        'class' => 'form-control',
                        'data-toggle' => 'select2',
                    ]) !!}
                </div>
            </div>

        </div>
        <!-- Add/Remove Button -->
        <div class="col-lg-3 mb-3">
            <div class="row justify-content-end">
                @if ($numberIncr == 1)
                    <a class="btn btn-success rounded-pill addMore">
                        <i class="icon-plus-circle2 mr-1"></i>Add More
                    </a>
                @else
                    <a class="btn btn-danger rounded-pill remove-btn">
                        <i class="icon-minus-circle2 mr-1"></i>Remove
                    </a>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <legend class="text-uppercase font-size-sm font-weight-bold">Day-wise Shift Times</legend>
            @if (isset($shiftModel->shiftDayWise) && count($shiftModel->shiftDayWise) > 0)
                @foreach ($shiftModel->shiftDayWise as $key => $shiftDayWise)
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label class="form-label">Day</label>
                            <div class="input-group">
                                {!! Form::text("season_day['. $numberIncr.']", $shiftDayWise->day, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Checkin Start Time <span class="text-danger">*</span></label>
                            <div class="input-group">
                                {!! Form::time("season_checkin_start_time[$shiftDayWise->day]", isset($shiftDayWise->checkin_start_time) ? $shiftDayWise->checkin_start_time : '00:00', ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Start Time <span class="text-danger">*</span></label>
                            <div class="input-group">
                                {!! Form::time(
                                    "season_start_time['. $numberIncr.']",
                                    isset($shiftDayWise->start_time) ? $shiftDayWise->start_time : '00:00',
                                    ['class' => 'form-control start-time', 'required'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">End Time <span class="text-danger">*</span></label>
                            <div class="input-group">
                                {!! Form::time(
                                    "season_end_time['. $numberIncr.']",
                                    isset($shiftDayWise->end_time) ? $shiftDayWise->end_time : '00:00',
                                    ['class' => 'form-control end-time', 'required'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Total Hrs</label>
                            <div class="input-group">
                                {!! Form::text('season_total_hrs['. $numberIncr.']', null, ['class' => 'form-control total-hrs', 'readonly']) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                @foreach ($daysOfWeek as $day => $fullName)
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label class="form-label">Day</label>
                            <div class="input-group">
                                {!! Form::text("season_day[$day][$numberIncr]", $day, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Checkin Start Time <span class="text-danger">*</span></label>
                            <div class="input-group">
                                {!! Form::time("season_checkin_start_time[$day][$numberIncr]", '08:00', ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Start Time <span class="text-danger">*</span></label>
                            <div class="input-group">
                                {!! Form::time("season_start_time[$day][$numberIncr]", '10:00', ['class' => 'form-control start-time', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">End Time <span class="text-danger">*</span></label>
                            <div class="input-group">
                                {!! Form::time("season_end_time[$day][$numberIncr]", '17:00', ['class' => 'form-control end-time', 'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-1">
                            <label class="form-label">Total Hrs</label>
                            <div class="input-group">
                                {!! Form::text("season_total_hrs[$numberIncr]", null, ['class' => 'form-control total-hrs', 'readonly']) !!}
                            </div>
                        </div>

                    </div>
                @endforeach
            @endif
        </div>

    </div>
    @endif

</div>
<script>
    $(document).ready(function() {

        engDatePicker('daterange-single');

        function engDatePicker(element) {
            $('.' + element).daterangepicker({
                parentEl: '.content-inner',
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        }

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
    });
</script>
