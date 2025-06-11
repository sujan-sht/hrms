<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

                <div class="row">

                    <div class="col-md-6">
                        <div id="title">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Title<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::text('title', isset($shiftModel) ? $shiftModel->title : '', [
                                        'placeholder' => 'Title',
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

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
                </div>

                <br>
                <legend class="text-uppercase font-size-sm font-weight-bold">Grace Period (In minutes)</legend>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Check In <span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('grace_period_checkin', $value = null, [
                                    'placeholder' => 'e.g: 10',
                                    'class' => 'form-control numeric',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Check Out <span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('grace_period_checkout', $value = null, [
                                    'placeholder' => 'e.g: 10',
                                    'class' => 'form-control numeric',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Check In (for Penalty)</label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('grace_period_checkin_for_penalty', $value = null, [
                                    'placeholder' => 'e.g: 10',
                                    'class' => 'form-control numeric',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Check Out (for Penalty)</label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('grace_period_checkout_for_penalty', $value = null, [
                                    'placeholder' => 'e.g: 10',
                                    'class' => 'form-control numeric',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <legend class="text-uppercase font-size-sm font-weight-bold">leave Benchmark Time</legend>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">For First Half</label>
                            </div>
                            <div class="col-md-12">
                                @php
                                    if (isset($shiftGroupModel->leave_benchmark_time_for_first_half)) {
                                        $first_half_time = date('H:i', strtotime($shiftGroupModel->leave_benchmark_time_for_first_half));
                                    } else {
                                        $first_half_time = date('H:i', strtotime('13:00:00'));
                                    }
                                @endphp
                                <div class="input-group">
                                    {!! Form::time('leave_benchmark_time_for_first_half', $value = $first_half_time, ['class' => 'form-control']) !!}
                                    <!-- <span class="input-group-text"><i class="icon icon-watch2"></i></span> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">For Second Half</label>
                            </div>
                            <div class="col-md-12">
                                @php
                                    if (isset($shiftGroupModel->leave_benchmark_time_for_second_half)) {
                                        $second_half_time = date('H:i', strtotime($shiftGroupModel->leave_benchmark_time_for_second_half));
                                    } else {
                                        $second_half_time = date('H:i', strtotime('14:00:00'));
                                    }
                                @endphp
                                <div class="input-group">
                                    {!! Form::time('leave_benchmark_time_for_second_half', $value = $second_half_time, ['class' => 'form-control']) !!}
                                    <!-- <span class="input-group-text"><i class="icon icon-watch2"></i></span> -->
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
</script>
