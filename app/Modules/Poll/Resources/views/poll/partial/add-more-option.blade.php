<div class="form-group row items">
    <div class="col-lg-12">
        <div class="row">
            <label class="col-form-label col-lg-2">Option : <span class="text-danger">*</span></label>
            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('options[]', '', ['placeholder' => 'Enter option..', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-lg-1">
                <button id="remove-btn" class="btn btn-danger" onclick="$(this).parents('.items').remove()">
                    <i class="icon-minus-circle2"></i>&nbsp;&nbsp;REMOVE
                </button>
            </div>
        </div>
    </div>
</div>
