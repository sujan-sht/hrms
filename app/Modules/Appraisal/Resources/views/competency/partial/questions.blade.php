@if ($is_edit && count($competency->questions) > 0)
    @foreach ($competency->questions as $question)
        <div class="row items">
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Competancy Description:</label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('questions[]', $question->question, ['placeholder' => 'Enter Competancy Description', 'class' => 'form-control']) !!}
                        </div>
                        @if ($errors->has('questions'))
                            <div class="error text-danger">{{ $errors->first('questions') }}</div>
                        @endif
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
        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-form-label col-lg-2">Competancy Description:</label>
                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::text('questions[]', '', ['placeholder' => 'Enter Competancy Description', 'class' => 'form-control']) !!}
                    </div>
                    @if ($errors->has('questions'))
                        <div class="error text-danger">{{ $errors->first('questions') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

<div class="repeaterForm"></div>

<span class="btn btn-outline-warning mx-1 addMore "><i class="icon-plus-circle2"></i>&nbsp;&nbsp;ADD</span>
