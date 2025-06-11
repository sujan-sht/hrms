<div class="form-group row">
    <div class="col-12 col-md-2"><label for="request_type">Request Type <span class="text-danger">*</span></label>
    </div>
    <div class="col-12 col-md-7">
        {!! Form::select('request_type',['ci'=>'Check-in Late Arrival','co'=>'Check-out Early Departure','do'=>'Day-off Request','mci'=>'Missed Check-In','mco'=>'Missed Check-Out', 'ot'=>'Over-Time Request'], $value = null,
            ['placeholder'=>'Select Request Type','class'=>'form-control select2']) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-12 col-md-2"><label for="request_type">Attendance Date <span class="text-danger">*</span></label>
    </div>
    <div class="col-12 col-md-7">
        {!! Form::text('att_date', $value = null, ['id' => 'att_date', 'placeholder'=>'Select Attendance Date','class'=>'form-control attrequest-date daterange-single']) !!}
    </div>
</div>

<div class="form-group row half_day_div"  style="display:none;">
    <div class="col-12 col-md-2"><label for="time_spend">Approximate Return Time<span class="text-danger">*</span></label>
    </div>
    <div class="col-12 col-md-7">
    {!! Form::text('return_time', null, ['class' => 'form-control', 'id' => 'start-timepicker','placeholder'=>'Select Time']) !!}
    </div>
</div>

<div class="form-group row miss_ci_co_div"  style="display:none;">
    <div class="col-12 col-md-2"><label for="time_spend">Select Time<span class="text-danger">*</span></label>
    </div>
    <div class="col-12 col-md-7">
    {!! Form::text('check_in_out_time', null, ['class' => 'form-control', 'id' => 'start-timepicker_1','placeholder'=>'Select Time']) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-12 col-md-2"><label for="description">Reason <span class="text-danger">*</span></label>
    </div>
    <div class="col-12 col-md-7">
    {!! Form::textarea('request_reason', $value = null, ['id'=>'request_reason','placeholder'=>'Enter Reason For Request','class'=>'form-control']) !!}
    </div>
</div>

<div class="form-group row field_work_div"  style="display:none;">
    <div class="col-12 col-md-2"><label for="time_spend">Time Spend<span class="text-danger">*</span></label>
    </div>
    <div class="col-12 col-md-7">
    {!! Form::select('time_spend',['fd'=>'Full Day','hd'=>'Half Day'], $value = null, ['placeholder'=>'Select Request Type','class'=>'form-control']) !!}
    </div>
</div>
<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        $('#start-timepicker').clockTimePicker();
        $('#start-timepicker_1').clockTimePicker();
        $('select[name=request_type]').on('change', function () {
            var selected_requst_type = $('select[name=request_type]').val();
            fieldwork(selected_requst_type);
        });
        $('select[name=time_spend]').on('change', function () {
            var selected_half_day = $('select[name=time_spend]').val();
            halfday(selected_half_day);
        });

        $('.attrequest-date').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
            maxDate: new Date()

        });
    });

    function fieldwork(selected_requst_type)
    {

        if (selected_requst_type === 'fw') {
            $('.field_work_div').show();
            $('.miss_ci_co_div').hide();
            $('.half_day_div').hide();
        }else if(selected_requst_type === 'mci' || selected_requst_type === 'mco'  || selected_requst_type === 'ci' || selected_requst_type === 'co' ){
            $('.miss_ci_co_div').show();
            $('.field_work_div').hide();
            $('.half_day_div').hide();
        }
        else {
            $('.miss_ci_co_div').hide();
            $('.half_day_div').hide();
            $('.field_work_div').hide();
        }
    }
    function halfday(selected_half_day)
    {
        if (selected_half_day === 'hd') {
            $('.half_day_div').show();
        } else {
            $('.half_day_div').hide();
        }
    }
</script>
