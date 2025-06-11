<script type="text/javascript" src="{{ asset('admin/nepalidatepicker/nepali.datepicker.v2.2.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('admin/nepalidatepicker/nepali.datepicker.v2.2.min.css')}}" />

<div class="form-group row">
    <div class="col-12 col-md-2"><label for="request_title">Title <span>*</span></label>
    </div>
    <div class="col-12 col-md-7">
        {!! Form::text('title', null, ['placeholder'=>'Enter Title','class'=>'form-control']) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-12 col-md-2"><label for="request_title">Travel From Date <span>*</span></label>
    </div>
    <div class="col-12 col-md-7">
        {!! Form::text('nep_from_date', null, ['id'=>'nep_from_date', 
        'placeholder'=>'Enter From Date','class'=>'form-control']) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-12 col-md-2"><label for="request_title">Travel To date <span>*</span></label>
    </div>
    <div class="col-12 col-md-7">
        {!! Form::text('nep_to_date', null, ['id'=>'nep_to_date', 
        'placeholder'=>'Enter To Date','class'=>'form-control']) !!}
    </div>
</div>

@if(!empty($tadaTypes))
@foreach($tadaTypes as $id => $type)
<div class="form-group row">
    <div class="col-12 col-md-2"><label for="request_title">{{$type}} (Rs.) <span>*</span></label>
    </div>
    <div class="col-12 col-md-7">
        @php $amount = $is_edit ? (\App\Modules\Tada\Entities\Tada::tadaAmountByType($tada->id, $id) ?
        \App\Modules\Tada\Entities\Tada::tadaAmountByType($tada->id, $id)->amount : null) : null ; @endphp
        {!! Form::hidden('type_id[]', $id) !!}
        {!! Form::number('amount[]', $amount, ['placeholder'=>'Enter Amount','class'=>'form-control', 'min' => 0]) !!}
    </div>
</div>

@endforeach
@endif

<div class="form-group row">
    <div class="col-12 col-md-2"><label for="request_title">Upload CSV/XLSX <span>*</span></label>
    </div>
    <div class="col-12 col-md-7">
        {!! Form::file('excel_file', ['id'=>'excel_file','class'=>'form-control' , 'accept'=>'.csv,
        application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel']) !!}
    </div>
</div>

@if($is_edit)
<div class="form-group row">
    <div class="col-12 col-md-2"><label for=""></label>
    </div>
    <div class="col-12 col-md-7">
        <a href="{{asset('uploads/tada/excels').'/'.$tada->excel_file}}" target='_blank'>
            <p>{{$tada->excel_file}}</p>
        </a>
    </div>
</div>
@endif

<div class="form-group row">
    <div class="col-12 col-md-2"><label for="request_title">Upload Bills <span>*</span></label>
    </div>
    <div class="col-12 col-md-7">
        {!! Form::file('bills[]', ['id'=>'bill','class'=>'form-control', 'multiple']) !!}
    </div>
</div>

@if($is_edit && !empty($tada->bills))
@foreach($tada->bills as $key => $value)
<div class="form-group row">
    <div class="col-12 col-md-2"><label for=""></label>
    </div>
    <div class="col-12 col-md-7">
        <a href="javascript:;" class="removeBillImage" data-id="{{$value->id}}">
            <span class="close">
                <i class="icon-cross2"></i>
            </span>
        </a>
        <a href="{{asset('uploads/tada/bills').'/'.$value->image_src}}" target='_blank'>
            <p>{{$value->image_src}}</p>
        </a>

    </div>
</div>
@endforeach
@endif

<div class="form-group row">
    <div class="col-12 col-md-2"><label for="request_title">Remarks <span>*</span></label>
    </div>
    <div class="col-12 col-md-7">
        {!! Form::textarea('remarks', null, ['placeholder'=>'Enter Remarks','class'=>'form-control']) !!}
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#nep_from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 10
        });

        $('#nep_to_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 10
        });

        $('.removeBillImage').on('click', function () {
            var image_id = $(this).attr('data-id');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: '{{url("admin/tada/bill-image/delete")}}/' + image_id,
                success: function (dt) {
                    self.parent().parent().remove();
                }
            }); // Ajax
        })

    })

</script>
