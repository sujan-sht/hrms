@php
    $defaultStartTime = '10:00';
    $defaultEndTime = '17:00';
@endphp

@foreach($daysOfWeek as $day => $fullName)
    <div class="row mb-3">
        <div class="col-md-3">
            <label class="form-label">Day</label>
            <div class="input-group">
                {!! Form::text("day[$day]", $fullName, ['class' => 'form-control', 'readonly']) !!}
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
@endforeach