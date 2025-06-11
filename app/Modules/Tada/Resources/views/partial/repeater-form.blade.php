<div class="row items">
    <div class="col-md-4">
        <div class="form-group row">
            <label class="col-form-label col-lg-4">Claim Type:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend" style="width: 100%">
                        <span class="input-group-text"><i class="icon-color-sampler"></i></span>
                        {!! Form::select('type_id[]', $tadaTypes,  null, ['placeholder'=>'Select Claim Type','class'=>'form-control select-search']) !!}
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
    <div class="col-md-1">
        <button id="remove-btn" class="btn btn-danger" onclick="$(this).parents('.items').remove()">
            <i class="icon-minus-circle2"></i>&nbsp;&nbsp;REMOVE
        </button>
    </div>
</div>
