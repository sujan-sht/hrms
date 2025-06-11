@if ($isEdit && count($tadaType->tadaSubTypes) > 0)
    @foreach ($tadaType->tadaSubTypes as $tadaSubType)
        <div class="row items">
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Title:</label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('sub_type_title[]', $tadaSubType->sub_type_title, ['placeholder' => 'Enter Title', 'class' => 'form-control']) !!}
                        </div>
                        @if ($errors->has('sub_type_title'))
                            <div class="error text-danger">{{ $errors->first('sub_type_title') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <button id="remove-btn" class="btn btn-danger" onclick="$(this).parents('.items').remove()">
                    <i class="icon-minus-circle2"></i>
                </button>
            </div>
        </div>
    @endforeach
@else
    <div class="row items">
        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-form-label col-lg-2">Title:</label>
                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::text('sub_type_title[]', '', ['placeholder' => 'Enter Title', 'class' => 'form-control']) !!}
                    </div>
                    @if ($errors->has('sub_type_title'))
                        <div class="error text-danger">{{ $errors->first('sub_type_title') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

<div class="repeaterForm"></div>

<span class="btn btn-outline-warning mx-1 addMore "><i class="icon-plus-circle2"></i>&nbsp;&nbsp;ADD</span>
