<script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js')}}"></script>
<script src="{{ asset('admin/global/js/demo_pages/picker_date.js')}}"></script>
<div class="form-group row">
    <div class="col-12 col-md-2"><label for="request_title">Request Title <span>*</span></label>
    </div>
    <div class="col-12 col-md-7">
        {!! Form::text('title', null, ['placeholder'=>'Enter Title','class'=>'form-control']) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-12 col-md-2"><label for="request_type">Request Type <span>*</span></label>
    </div>
    <div class="col-12 col-md-7">
        {!! Form::select('type_id', $requestTypes, null, ['placeholder'=>'Select Request Type','class'=>'form-control',
        'onchange'=>'yesnoCheck(this);']) !!}
    </div>
</div>

<div id="travel_block"
    style="display: {{$is_edit && isset($employeeRequest) && $employeeRequest->type_id == 2 ? 'block' : 'none' }};">
    <div class="form-group row">
        <div class="col-12 col-md-2"><label for="travel_date">Travel Date <span class="text-danger">*</span></label>
        </div>
        <div class="col-12 col-md-7">
            {!! Form::text('travel_date', null, ['id'=>'travel_date',
                'placeholder'=>'Select Travel Date','class'=>'form-control daterange-single']) !!}
        </div>

    </div>

    <div class="form-group row">
        <div class="col-12 col-md-2"><label for="market_visit_location">Market Visit Location:<span class="text-danger">*</span>
        </div>
        <div class="col-12 col-md-7">
            {!! Form::text('market_visit_location', null, ['id'=>'market_visit_location',
            'placeholder'=> 'Enter Market Visit Location', 'class'=>'form-control']) !!}
        </div>
    </div>

    <div class="form-group row">
        <div class="col-12 col-md-2"><label for="night_halt">Night Halt:<span class="text-danger">*</span></div>
        <div class="col-12 col-md-7">
            {!! Form::text('night_halt', null, ['id'=>'night_halt', 'placeholder'=> 'Enter Night Halt',
            'class'=>'form-control']) !!}
        </div>
    </div>

    <div class="form-group row">
        <div class="col-12 col-md-2"><label for="transport_cost">Transport Cost(Rs.)</div>
        <div class="col-12 col-md-7">
            {!! Form::text('transport_cost', null, ['id'=>'transport_cost', 'placeholder'=>'Enter Transport Cost',
            'class'=>'form-control numeric']) !!}
        </div>
    </div>

    <div class="form-group row">
        <div class="col-12 col-md-2"><label for="lodging">Lodging(Rs.)</div>
        <div class="col-12 col-md-7">
            {!! Form::text('lodging', null, ['id'=>'lodging','placeholder'=>'Enter PR Cost','class'=>'form-control numeric']) !!}
        </div>
    </div>

    <div class="form-group row">
        <div class="col-12 col-md-2"><label for="fooding">Fooding(Rs.)</div>
        <div class="col-12 col-md-7">
            {!! Form::text('fooding', null, ['id'=>'fooding','placeholder'=>'Enter PR Cost','class'=>'form-control numeric']) !!}
        </div>
    </div>

    <div class="form-group row">
        <div class="col-12 col-md-2"><label for="local_DA">Local DA(Rs.)</div>
        <div class="col-12 col-md-7">
            {!! Form::text('local_DA', null, ['id'=>'local_DA','placeholder'=>'Enter Transport Cost',
                'class'=>'form-control numeric']) !!}
        </div>
    </div>

    <div class="form-group row">
        <div class="col-12 col-md-2"><label for="DA">DA(Rs.)</div>
        <div class="col-12 col-md-7">
            {!! Form::text('DA', null, ['id'=>'transport_cost','placeholder'=>'Enter Transport Cost','class'=>'form-control numeric']) !!}
        </div>
    </div>

    <div class="form-group row">
        <div class="col-12 col-md-2"><label for="telephone">Telephone:</div>
        <div class="col-12 col-md-7">
            {!! Form::number('telephone', null, ['id'=>'telephone','placeholder'=>'Enter telephone','class'=>'form-control']) !!}
        </div>
    </div>

    <div class="form-group row">
        <div class="col-12 col-md-2"><label for="motor_cycle_expenses">MIS/Motor Cycle Expenses(Rs.)</div>
        <div class="col-12 col-md-7">
            {!! Form::text('motor_cycle_expenses', null, ['id'=>'motor_cycle_expenses',
                'placeholder'=>'Enter Motor Cycle Expenses','class'=>'form-control numeric']) !!}
        </div>
    </div>


    {{--<div class="form-group row">
        <div class="col-12 col-md-2"><label for="PR">PR:</div>
            <div class="col-12 col-md-7">
                    {!! Form::text('PR', null, ['id'=>'PR','placeholder'=>'Enter PR Cost','class'=>'form-control numeric']) !!}
            </div>
        </div>    --}}
</div>

<div id="ifYes"
    style="display: {{$is_edit && isset($employeeRequest) && $employeeRequest->type_id == 1 ? 'block' : 'none' }};">

{{--    <div class="form-group row">--}}
{{--        <div class="col-12 col-md-2"><label for="dropdown_id">Benefit Type:<span class="text-danger">*</span></label>--}}
{{--        </div>--}}
{{--        <div class="col-12 col-md-7">--}}
{{--            {!! Form::select('dropdown_id', $dropdown, null, ['id'=> 'dropdown_id', --}}
{{--                'placeholder'=>'Select Benefit Type','class'=>'form-control']) !!}--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="form-group row">
        <div class="col-12 col-md-2"><label for="pay_type">Pay Type:<span class="text-danger">*</span></label>
        </div>
        <div class="col-12 col-md-7">
            {!! Form::select('pay_type',[ '1'=>'Cash','2'=>'Cheque'], $value = null,
            ['id'=>'pay_type','class'=>'form-control', 'onchange'=>'show(this);']) !!}
        </div>
    </div>

    <div id="yes" style="display: none;">
        <div class="form-group row">
            <div class="col-12 col-md-2"><label for="bank_name">Bank Name:<span class="text-danger">*</span></label>
            </div>
            <div class="col-12 col-md-7">
                {!! Form::text('bank_name', null, ['placeholder'=>'Enter Bank Name','class'=>'form-control']) !!}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12 col-md-2"><label for="account_number">Account Number:<span
                        class="text-danger">*</span></label>
            </div>
            <div class="col-12 col-md-7">
                {!! Form::text('account_number', null, ['placeholder'=>'Enter Account Number',
                    'class'=>'form-control']) !!}
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-12 col-md-2"><label for="cost">Cost:<span class="text-danger">*</span></label>
        </div>
        <div class="col-12 col-md-7">
            {!! Form::text('cost', null, ['placeholder'=>'Enter Cost','class'=>'form-control']) !!}
        </div>
    </div>

</div>

<div id="show_bill"
    style="display:{{$is_edit && isset($employeeRequest) && ($employeeRequest->type_id == 2 || $employeeRequest->type_id == 1) ? 'block' : 'none' }}">
    <div class="form-group row">
        <div class="col-12 col-md-2"><label for="bill">Upload Bill <span class="text-danger">*</span></label>
        </div>
        <div class="col-12 col-md-7">
            {!! Form::file('bill', ['id'=>'bill','class'=>'form-control']) !!}
        </div>
    </div>
</div>



<div class="form-group row">
    <div class="col-12 col-md-2"><label for="description">Description</label>
    </div>
    <div class="col-12 col-md-7">
        {!! Form::textarea('description', null, ['class'=>'form-control']) !!}
    </div>
</div>
@if($is_edit)
<div class="form-group row">
    <div class="col-12 col-md-2"><label for="status">Status</label>
    </div>
    <div class="col-12 col-md-7">
        {!! Form::select('status',['0'=>'Pending','2'=>'Cancel'], null, ['id'=>'status','class'=>'form-control']) !!}
    </div>
</div>
@endif



<script type="text/javascript">
    @if(isset($employeeRequest) && $employeeRequest->type_id == 1)
    document.getElementById("ifYes").style.display = "block";
    @endif

    @if(isset($employeeRequest) && $employeeRequest->pay_type == 2)
    document.getElementById("yes").style.display = "block";
    @endif

    function yesnoCheck(that) {
        if (that.value == "1") {
            document.getElementById("ifYes").style.display = "block";
            document.getElementById("show_bill").style.display = "block";
            document.getElementById("travel_block").style.display = "none";
        } else if (that.value == "2") {
            document.getElementById("travel_block").style.display = "block";
            document.getElementById("show_bill").style.display = "block";
            document.getElementById("ifYes").style.display = "none";
        } else {
            document.getElementById("ifYes").style.display = "none";
            document.getElementById("travel_block").style.display = "none";
            document.getElementById("show_bill").style.display = "none";
        }
    }

    function show(that) {
        if (that.value == "2") {
            document.getElementById("yes").style.display = "block";
        } else {
            document.getElementById("yes").style.display = "none";
        }
    }

</script>
