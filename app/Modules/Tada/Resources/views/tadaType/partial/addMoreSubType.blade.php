<div class="row items">
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Title:</label>
            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('sub_type_title[]', null, ['placeholder' => 'Enter title', 'class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-1">
        <button id="remove-btn" class="btn btn-danger"
            onclick="$(this).parents('.items').remove()">
            <i class="icon-minus-circle2"></i>
        </button>
    </div>
</div>
