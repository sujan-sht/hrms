<script type="text/javascript" src="{{ asset('admin/nepalidatepicker/nepali.datepicker.v2.2.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('admin/nepalidatepicker/nepali.datepicker.v2.2.min.css')}}" />

    <fieldset class="mb-3">
        
        <legend class="text-uppercase font-size-sm font-weight-bold">Basic Details</legend>

        <!-- Select Dates -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-form-label col-lg-4">Title:<span class="text-danger">*</span></label>
                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-quill4"></i></span>
                            </span>
                            {!! Form::text('title', null, ['placeholder'=>'Enter Title','class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-form-label col-lg-4">Select Request<span class="text-danger">*</span></label>
                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                            <span class="input-group-prepend" style="width: 100%">
                                <span class="input-group-text"><i class="icon-user"></i></span>
                                {!! Form::select('adv_request_id',$request_adv,  null, ['placeholder'=>'Select Advance Request','class'=>'form-control select-search']) !!}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-form-label col-lg-4">Travel Date From:<span class="text-danger">*</span></label>
                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-calendar2"></i></span>
                            </span>
                            {!! Form::text('nep_from_date', null, ['id'=>'nep_from_date', 'placeholder'=>'Enter From Date', 'class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-form-label col-lg-4">Travel Date To:<span class="text-danger">*</span></label>
                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-calendar2"></i></span>
                            </span>
                            {!! Form::text('nep_to_date', null, ['id'=>'nep_to_date', 'placeholder'=>'Enter To Date', 'class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <legend class="text-uppercase font-size-sm font-weight-bold">Claim Details</legend>

        @if($is_edit && count($tada->tadaDetails) > 0)
            @foreach ($tada->tadaDetails as $tadaDetail)
                <div class="row items">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4">Claim Type:<span class="text-danger">*</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend" style="width: 100%">
                                        <span class="input-group-text"><i class="icon-color-sampler"></i></span>
                                        {!! Form::select('type_id[]', $tadaTypes,  $tadaDetail->type_id, ['placeholder'=>'Select Claim Type','class'=>'form-control select-search select2']) !!}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4">Amount:</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text">Rs.</span>
                                    </span>
                                    {!! Form::text('amount[]', $tadaDetail->amount, ['placeholder'=>'Amount', 'class'=>'form-control numeric']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4">Remarks:</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('remark[]', $tadaDetail->remark, ['placeholder'=>'Write remarks here..', 'class'=>'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button id="remove-btn" class="btn btn-danger" onclick="$(this).parents('.items').remove()">
                            <i class="icon-minus-circle2"></i>&nbsp;&nbsp;REMOVE
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="row items">
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-4">Claim Type:<span class="text-danger">*</span></label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend" style="width: 100%">
                                    <span class="input-group-text"><i class="icon-color-sampler"></i></span>
                                    {!! Form::select('type_id[]', $tadaTypes,  null, ['placeholder'=>'Select Claim Type','class'=>'form-control select-search select2']) !!}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-4">Amount:</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text">Rs.</span>
                                </span>
                                {!! Form::text('amount[]', null, ['placeholder'=>'Amount', 'class'=>'form-control numeric']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-4">Remarks:</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('remark[]', null, ['placeholder'=>'Write remarks here..', 'class'=>'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="repeaterForm"></div>

        <span class="btn bg-teal-400 addMore"><i class="icon-plus-circle2"></i>&nbsp;&nbsp;ADD</span>

        <legend class="text-uppercase font-size-sm font-weight-bold"></legend>
        {{-- @if(!empty($tadaTypes))
        @foreach($tadaTypes as $id => $type)
        <div class="form-group row">
            <label class="col-form-label col-lg-3">{{$type}}:<span class="text-danger">*</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text">Rs.</span>
                    </span>
                    @php $amount = $is_edit ? (\App\Modules\Tada\Entities\Tada::tadaAmountByType($tada->id, $id) ? \App\Modules\Tada\Entities\Tada::tadaAmountByType($tada->id, $id)->amount : null) : null ; @endphp
                    {!! Form::hidden('type_id[]', $id) !!}
                    {!! Form::number('amount[]', $amount, ['placeholder'=>'Enter Amount','class'=>'form-control', 'min' => 0]) !!}
                </div>
            </div>
        </div>
        @endforeach
        @endif --}}

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Upload CSV/XLSX:<span class="text-danger">*</span></label>
            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-images3"></i></span>
                    </span>
                    {!! Form::file('excel_file', ['id'=>'excel_file','class'=>'form-control' , 'accept'=>'.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel']) !!}
                </div>
            </div>            
        </div>

        @if($is_edit)
        <div class="form-group row">
            <label class="col-form-label col-lg-2"></label>
            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                <a href="{{asset('uploads/tada/excels').'/'.$tada->excel_file}}" target='_blank'><p>{{$tada->excel_file}}</p> </a>
            </div>
        </div>
        @endif

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Upload Bills: <span class="text-danger">*</span></label>
            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-images3"></i></span>
                    </span>
                    {!! Form::file('bills[]', ['id'=>'bill','class'=>'form-control', 'multiple']) !!}
                </div>
            </div>            
        </div>

        @if($is_edit && !empty($tada->bills))
        @foreach($tada->bills as $key => $value)
        <div class="form-group row">
            <label class="col-form-label col-lg-3"></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <a href="javascript:;" class="removeBillImage" data-id="{{$value->id}}">
                <span class="close">
                      <i class="icon-cross2"></i>
                </span>
                </a>
                <a href="{{asset('uploads/tada/bills').'/'.$value->image_src}}" target='_blank'><p>{{$value->image_src}}</p> </a>
               
            </div>
        </div>
        @endforeach
        @endif

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Remarks:</label>
            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {{-- <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-pencil5"></i></span>
                    </span> --}}
                    {!! Form::textarea('remarks', null, ['placeholder'=>'Write remarks here..','class'=>'form-control']) !!}
                </div>
            </div>
        </div>

        <!-- <div class="form-group row">
            <label class="col-form-label col-lg-2">Status:</label>
            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-dots"></i></span>
                    </span>
                    {!! Form::select('status',['pending'=>'Pending', 'approved'=>'Approved', 'rejected'=> 'Reject'], null, ['id'=>'status','class'=>'form-control']) !!}
                </div>
            </div>
        </div> -->
   

    </fieldset>

    <div class="text-right">
        <button type="submit" class="btn bg-teal-400"><i class="icon-database-insert"></i>&nbsp;&nbsp;{{ $btnType }}</button>
    </div>

<script>
<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
$(document).ready(function () {

    $('.select2').select2();

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

    $('.removeBillImage').on('click', function() {
        var image_id = $(this).attr('data-id');
        var self = $(this);
        $.ajax({
            type: "GET",
            url: '{{url("admin/tada/bill-image/delete")}}/'+image_id,
            success: function (dt) {
               self.parent().parent().remove();
            }
        });// Ajax
    });

    $(".addMore").click(function() {
        $.ajax({
            url: "<?php echo route('tada.getRepeaterForm') ?>",
            method: 'POST',
            data: {
                _token:"{{ csrf_token() }}"
            },
            success: function(data) {
                $(".repeaterForm").append(data.result);
                $(".select-search").select2();
                $('.numeric').keyup(function() {
                    if (this.value.match(/[^0-9.]/g)) {
                        this.value = this.value.replace(/[^0-9.]/g, '');
                    }
                });
                $('.select2').select2();
            }
        });
    });

});
</script>
