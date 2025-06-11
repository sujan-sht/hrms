@if ($isEdit && count($pollModel->options) > 0)
    @foreach ($pollModel->options as $option)
        <div class="form-group row items">
            <div class="col-lg-12">
                <div class="row">
                    <label class="col-form-label col-lg-2">Options : <span class="text-danger">*</span></label>
                    <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('options['.$option->id.']', $option->option, ['placeholder' => 'Enter Option', 'class' => 'form-control']) !!}
                        </div>
                        @if ($errors->has('options'))
                            <div class="error text-danger">{{ $errors->first('options') }}</div>
                        @endif
                    </div>
                    {{-- <div class="col-lg-1">
                        <button id="remove-btn" class="btn btn-danger" onclick="$(this).parents('.items').remove()">
                            <i class="icon-minus-circle2"></i>&nbsp;&nbsp;REMOVE
                        </button>
                    </div> --}}
                </div>
            </div>
        </div>
    @endforeach
    
    <div class="repeaterForm"></div>

    <div class="col-lg-1 addMore ">
        <span class="btn btn-outline-warning">
            <i class="icon-plus-circle2"></i>&nbsp;&nbsp;ADD
        </span>
    </div>
@else
    <div class="form-group row">
        <div class="col-lg-12">
            <div class="row">
                <label class="col-form-label col-lg-2">Option : <span class="text-danger">*</span></label>
                <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::text('options[]', '', ['placeholder' => 'Enter option..', 'class' => 'form-control']) !!}
                    </div>
                    @if ($errors->has('options'))
                        <div class="error text-danger">{{ $errors->first('options') }}</div>
                    @endif
                </div>
                <div class="col-lg-1 addMore ">
                    <span class="btn btn-outline-warning">
                        <i class="icon-plus-circle2"></i>&nbsp;&nbsp;ADD
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="repeaterForm"></div>
@endif
