{!! Form::hidden('advance_id', $advanceModel->id, []) !!}

<div class="row">
    <div class="col-lg-12 mb-3">
        <div class="row items">
            <label class="col-form-label col-lg-4">Date :<span class="text-danger"> *</span></label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('date', null, ['placeholder'=>'YYYY-MM-DD', 'class'=>'form-control daterange-single', 'readonly', 'required']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mb-3">
        <div class="row items">
            <label class="col-form-label col-lg-4">Amount :<span class="text-danger"> *</span></label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('amount', null, ['placeholder' => 'Enter Amount', 'class' => 'form-control numeric', 'required']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mb-3">
        <div class="row items">
            <label class="col-form-label col-lg-4">Remark :<span class="text-danger"> *</span></label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::textarea('remark', null, ['rows'=>5, 'placeholder' => 'Enter remark..', 'class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-right">
    <button type="submit" class="btns btn btn-secondary btn-labeled btn-labeled-left"><b><i class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>