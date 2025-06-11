<div class="card">
    <div class="card-body">

        <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-4">Date :<span class="text-danger"> *</span></label>
                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            @php
                                $dateData = null;
                                if(setting('calendar_type') == 'BS'){
                                    $classData = 'form-control nepali-calendar';
                                    if($isEdit){
                                        $dateData = date_converter()->eng_to_nep_convert($worklog['date']);
                                    }
                                }else{
                                    $classData = 'form-control daterange-single';
                                    if($isEdit){
                                        $dateData = $worklog['date'];
                                    }
                                }
                            @endphp
                            {!! Form::text('date', $value = $dateData, [
                                'placeholder' => 'Please Choose Date',
                                'id' => 'date',
                                'class' => $classData,
                                'required',
                            ]) !!}
                        </div>
                        @if ($errors->has('date'))
                            <span class="text-danger">{{ $errors->first('date') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            {{-- @if (auth()->user()->user_type == 'admin' ||
                    auth()->user()->user_type == 'super_admin' ||
                    auth()->user()->user_type == 'hr') --}}
                <div class="col-md-6 mb-3">
                    <span class="btn btn-outline-warning mx-1 addMore"><i
                            class="icon-plus-circle2"></i>&nbsp;&nbsp;ADD</span>
                </div>
            {{-- @endif --}}

        </div>
    </div>
</div>

{{-- @dd($isEdit) --}}
@if ($isEdit == true)
    @foreach ($worklog->workLogDetail as $key => $item)
        @include('worklog::worklog.partial.clone-log', ['item' => $item, 'key' => $key])
    @endforeach
@else
    <div class="card clone-div">
        <div class="card-body">
            <legend class="text-uppercase font-size-sm font-weight-bold">Log Detail</legend>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Title :<span class="text-danger"> *</span></label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">

                                {!! Form::text('multi[0][title]', $value = null, [
                                    'id' => 'title',
                                    'placeholder' => 'Enter Title',
                                    'class' => 'form-control title',
                                ]) !!}

                            </div>
                            @if ($errors->has('title'))
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-3">Time (In Hours):<span class="text-danger"> *</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('multi[0][hours]', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Time',
                                    'id' => 'hours',
                                ]) !!}
                            </div>
                            @if ($errors->has('hours'))
                                <span class="text-danger">{{ $errors->first('hours') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Status :<span class="text-danger"> *</span></label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">

                                {!! Form::select('multi[0][status]', $statusList, $value = null, [
                                    'id' => 'status',
                                    'placeholder' => 'Choose Status',
                                    'class' => 'form-control',
                                ]) !!}

                            </div>
                            @if ($errors->has('status'))
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-3">Priority :</label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('multi[0][priority]', $value = null, [
                                    'placeholder' => 'Enter Priority',
                                    'id' => 'priority',
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                            @if ($errors->has('priority'))
                                <span class="text-danger">{{ $errors->first('priority') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- <div class="col-md-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Assigned To :</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('multi[0][assigned_to]', $value = null, [
                                    'placeholder' => 'Enter Assigned To',
                                    'id' => 'assigned_to',
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                            @if ($errors->has('assigned_to'))
                                <span class="text-danger">{{ $errors->first('assigned_to') }}</span>
                            @endif
                        </div>
                    </div>
                </div> --}}
                <div class="col-md-6 mb-3">
                    <div class="row">
                        @if (auth()->user()->user_type == 'admin' ||
                                auth()->user()->user_type == 'super_admin' ||
                                auth()->user()->user_type == 'hr')
                            <label class="col-form-label col-lg-4">Assigned To : <span class="text-danger">*</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">

                                    {!! Form::select('multi[0][employee_id]', $employees, $value = null, [
                                        'id' => 'employee_id',
                                        'placeholder' => 'Choose Employee',
                                        'class' => 'form-control',
                                        'required'
                                    ]) !!}

                                </div>
                                @if ($errors->has('employee_id'))
                                    <span class="text-danger">{{ $errors->first('employee_id') }}</span>
                                @endif
                            </div>
                        {{-- @else
                            {!! Form::hidden('multi[0][employee_id]', auth()->user()->emp_id) !!} --}}
                        @endif
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <label class="col-form-label col-lg-2">Description:</label>
                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('multi[0][detail]', null, [
                                    'id' => 'detail',
                                    'placeholder' => 'Enter description',
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="append-clone"></div>


<div class="text-center">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

<script>
    $('.select-search1').select2();


    $('.addMore').on('click', function() {
        length = $('.clone-div').length + 1;
        var clone = $('.clone-div:first');
        var appendClone = clone.clone(true, true).appendTo(".append-clone");

        // $(".clone-div:last").find(".select-search").each(function(index) {
        //     console.log(index,$(this));
        //     if ($(this).data('select2')) {
        //         $(this).select2('destroy');
        //         // $(this).select2();
        //     }
        // })
        $('#worklog_submit').validate();


        $(".clone-div:last").find(":input").each(function(index) {
            name = $(this).attr('name')
            replace = name.replace(/0/g, length);
            rep_name = $(this).attr('name', replace);
            id = $(this).attr('id') + '-' + length;
            $(this).attr('id', id);
            // console.log(id);
        })

        ary = [
            'title-' + length,
            'status-' + length,
            // 'hours-' + length
        ];

        addRules(ary)
        appendClone.find(".card-body").prepend(
            '<button type="button" class="btn btn-outline-danger mx-1 btn-remove float-right" ><i class="icon-trash"></i>&nbsp;&nbsp;Remove</button>'
        );
        appendClone.find(':input').val('');
    })

    $(document).on('click', '.btn-remove', function() {
        var parent = $(this).parent().parent();
        parent.remove();
    })

    function addRules(array) {
        array.forEach(function(key) {
            $('#worklog_submit #' + key).rules("add", {
                required: true,
                messages: {
                    required: 'This field is required'
                }
            });
        })

    }
</script>
