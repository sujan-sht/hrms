<div class="row items">
    <div class="col-lg-4 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-3">Employee :<span class="text-danger"> *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('employee_id[]', $employee, null, ['placeholder' => 'Choose Employee', 'class' => 'employee form-control']) !!}
                </div>
                @if ($errors->has('employee_id'))
                    <div class="error text-danger">{{ $errors->first('employee_id') }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-3 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-3">Name :<span class="text-danger">
                    *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('int_name[]', null, [
                        'placeholder' => 'Enter Name',
                        'class' => 'internal_name form-control',
                    ]) !!}
                </div>
                @if ($errors->has('int_name'))
                    <div class="error text-danger">{{ $errors->first('int_name') }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-3 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-3">Email :<span class="text-danger">
                    *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('int_email[]', null, [
                        'placeholder' => 'Email Address',
                        'class' => 'internal_email form-control',
                    ]) !!}
                </div>
                @if ($errors->has('int_email'))
                    <div class="error text-danger">{{ $errors->first('email') }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-2 mb-3">
        <a id="remove"  onclick="$(this).parents('.items').remove()" class="btn btn-danger rounded-pill">
            <i class="icons icon-minus-circle2 mr-1"></i>Remove
        </a>
    </div>
</div>
