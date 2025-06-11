<script type="text/javascript" src="{{ asset('admin/nepalidatepicker/nepali.datepicker.v2.2.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('admin/nepalidatepicker/nepali.datepicker.v2.2.min.css') }}" />


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <fieldset class="mb-3">

                    <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                    <div class="row">
                        @if (Auth::user()->user_type =='employee')
                            {!! Form::hidden('employee_id', Auth::user()->emp_id) !!}
                        @else
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3">Employee:<span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            <span class="input-group-prepend" style="width: 100%">
                                                <span class="input-group-text"><i class="icon-user"></i></span>
                                                {!! Form::select('employee_id', $employees, $value = isset($tada) ? $tada->employee_id : null, [
                                                    'placeholder' => 'Select Employee',
                                                    'class' => 'form-control select-search',
                                                    'id' => 'employee_id',
                                                    'disabled' => $is_edit ? true : false,
                                                ]) !!}
                                            </span>
                                        </div>
                                        @if ($is_edit)
                                            <input type="hidden" name="employee_id" value="{{$tada->employee_id}}">
                                        @endif
                                        @if ($errors->has('employee_id'))
                                            <div class="error text-danger">Please Choose One Employee.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Request Date :<span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-calendar2"></i></span>
                                        </span>
                                        @if (setting('calendar_type') == 'BS')
                                            {!! Form::text('nep_request_date', null, [
                                                // 'id' => 'nep_from_date',
                                                'placeholder' => 'Enter Request Date',
                                                'class' => 'form-control nepali-calendar','required'
                                            ]) !!}
                                        @else
                                            {!! Form::text('eng_request_date', null, [
                                                // 'id' => 'nep_from_date',
                                                'placeholder' => 'Enter Request Date',
                                                'class' => 'form-control daterange-single','required'
                                            ]) !!}
                                        @endif
                                    </div>
                                    {{-- @if ($errors->has('nep_request_date'))
                                        <div class="error text-danger">Choose request Date.</div>
                                    @endif --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Title:<span class="text-danger">*</span></label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-quill4"></i></span>
                                        </span>
                                        {!! Form::text('title', null, ['placeholder' => 'Enter Title', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Request Code:<span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend" style="width: 100%">
                                            {!! Form::text('request_code', null, ['placeholder' => 'Enter Request Code', 'class' => 'form-control']) !!}
                                        </span>
                                    </div>
                                    @if ($errors->has('request_code'))
                                        <div class="error text-danger">Enter Request Code.</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                       
                    </div> --}}
                 
                    <legend class="text-uppercase font-size-sm font-weight-bold">Request Detail</legend>

                    @if ($is_edit && count($tada->tadaDetails) > 0)
                        @foreach ($tada->tadaDetails as $tadaDetail)
                            <div class="row items">
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3">Request Type:</label>
                                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                <span class="input-group-prepend" style="width: 100%">
                                                    <span class="input-group-text"><i
                                                            class="icon-color-sampler"></i></span>
                                                    {!! Form::select('type_id[]', $tadaTypes, $tadaDetail->type_id, [
                                                        'placeholder' => 'Select Request Type',
                                                        'class' => 'form-control select-search requestType',
                                                    ]) !!}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3">Sub Type:</label>
                                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                <span class="input-group-prepend" style="width: 100%">
                                                    <span class="input-group-text"><i
                                                            class="icon-color-sampler"></i></span>
                                                    {!! Form::select('sub_type_id[]', $tadaSubTypes, $tadaDetail->sub_type_id, [
                                                        'placeholder' => 'Select Sub Type',
                                                        'class' => 'form-control select-search subTypeFilter',
                                                    ]) !!}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3">Quantity:</label>
                                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {{-- <span class="input-group-prepend">
                                                    <span class="input-group-text">Rs.</span>
                                                </span> --}}
                                                {!! Form::text('amount[]', $tadaDetail->amount, [
                                                    'placeholder' => 'Quantity',
                                                    'class' => 'form-control numeric',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3">Message:</label>
                                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::text('remark[]', $tadaDetail->remark, [
                                                    'placeholder' => 'Write message here..',
                                                    'class' => 'form-control',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button id="remove-btn" class="btn btn-danger"
                                        onclick="$(this).parents('.items').remove()">
                                        <i class="icon-minus-circle2"></i>&nbsp;&nbsp;REMOVE
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row items">
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3">Request Type:</label>
                                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            <span class="input-group-prepend" style="width: 100%">
                                                <span class="input-group-text"><i class="icon-color-sampler"></i></span>
                                                {!! Form::select('type_id[]', $tadaTypes, null, [
                                                    'placeholder' => 'Select Request Type',
                                                    'class' => 'form-control select-search requestType',
                                                ]) !!}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3">Sub Type:</label>
                                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            <span class="input-group-prepend" style="width: 100%">
                                                <span class="input-group-text"><i
                                                        class="icon-color-sampler"></i></span>
                                                {!! Form::select('sub_type_id[]', $tadaSubTypes, null, [
                                                    'placeholder' => 'Select Sub Type',
                                                    'class' => 'form-control select-search subTypeFilter',
                                                ]) !!}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3">Quantity:</label>
                                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {{-- <span class="input-group-prepend">
                                                <span class="input-group-text">Rs.</span>
                                            </span> --}}
                                            {!! Form::text('amount[]', null, ['placeholder' => 'Quantity', 'class' => 'form-control numeric']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3">Message:</label>
                                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::text('remark[]', null, ['placeholder' => 'Write message here..', 'class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="repeaterForm"></div>

                    <span class="btn btn-outline-warning mx-1 addMore"><i
                            class="icon-plus-circle2"></i>&nbsp;&nbsp;ADD</span>

                    <legend class="text-uppercase font-size-sm font-weight-bold mt-4">Other Details</legend>
                    {{-- @if (!empty($tadaTypes))
                        @foreach ($tadaTypes as $id => $type)
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">{{$type}}:<span class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text">Rs.</span>
                                    </span>
                                    @php $amount = $is_edit ? (\App\Modules\Tada\Entities\TadaRequest::tadaAmountByType($tada->id, $id) ? \App\Modules\Tada\Entities\TadaRequest::tadaAmountByType($tada->id, $id)->amount : null) : null ; @endphp
                                    {!! Form::hidden('type_id[]', $id) !!}
                                    {!! Form::number('amount[]', $amount, ['placeholder'=>'Enter Amount','class'=>'form-control', 'min' => 0]) !!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif --}}
                                {{-- <div class="col-md-12"> --}}

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Remarks:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {{-- <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-pencil5"></i></span>
                        </span> --}}
                                    {!! Form::textarea('remarks', null, [
                                        'placeholder' => 'Write remarks here..',
                                        'class' => 'form-control',
                                        'rows' => '5',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        @if (auth()->user()->user_type == 'supervisor' || auth()->user()->user_type == 'employee')
                        {!! Form::hidden('status', 1) !!}

                        @else
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Status:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-dots"></i></span>
                                    </span>
                                    {!! Form::select('status', $statusList, null, [
                                        'id' => 'status',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                {{-- <div class="form-group row">
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="form-check input-group form-check-inline">
                                    <input type="checkbox" name="is_agree" value = 1 class='form-check-input' {{ (isset($tada->is_agree) && $tada->is_agree == 1) ? 'checked' : ''}}>
                                    <label class="col-form-label col-lg-11">I confirm that the submitted documents are accurate, and I accept full responsibility for their contents. <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                </fieldset>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

<script>
    $(document).ready(function() {
        $(".addMore").click(function() {
            $.ajax({
                url: "<?php echo route('tadaRequest.getRepeaterForm'); ?>",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    $(".repeaterForm").append(data.result);
                    $(".select-search").select2();
                    $('.numeric').keyup(function() {
                        if (this.value.match(/[^0-9.]/g)) {
                            this.value = this.value.replace(/[^0-9.]/g, '');
                        }
                    });
                }
            });
        });

        $('.requestType').on('change', function() {
            var type_id = $(this).val()
            var that =  $(this)
            $.ajax({
                url: "<?php echo route('tadaType.getSubTypeList'); ?>",
                method: 'POST',
                data:{
                    _token: "{{ csrf_token() }}",
                    type_id: type_id
                },
                success: function (data) {
                    var list = JSON.parse(data)
                    var options = ''

                    options += "<option value=''>Select Sub Type</option>"
                    $.each(list, function(id, value){
                        options += "<option value='" + id + "'>" + value + "</option>"
                    })

                    var subType = that.closest('.items').find('.subTypeFilter')
                    subType.html(options)
                    subType.select2({
                        placeholder: "Select Sub Type"
                    })
                }
            })
        })

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
                url: '{{ url('admin/tada/bill-image/delete') }}/' + image_id,
                success: function(dt) {
                    self.parent().parent().remove();
                }
            }); // Ajax
        });


    });
</script>
