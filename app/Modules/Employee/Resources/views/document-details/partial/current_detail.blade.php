<div class="form-group row">
    <div class="col-md-12">
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Employee :</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-users2"></i></span>
                    </span>
                    {!! Form::text('employee_id', $value = optional($document->employeeModel)->full_name, [
                        'class' => 'form-control',
                        'readonly'
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-3">Country :</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('gender', $countryList, $value = $document->country, [
                        'class' => 'form-control',
                        'disabled'
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Type :</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-users2"></i></span>
                    </span>
                    {!! Form::text('visa_type', $value = $document->visa_type, [
                        'class' => 'form-control',
                        'readonly'
                    ]) !!}
                </div>
            </div>
        </div>
       
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Issued Date :</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-calendar"></i></span>
                    </span>
                    {!! Form::text('issued_date', $value = $document->issued_date, [
                        'class' => 'form-control',
                        'readonly'
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Expiry Date :</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-calendar"></i></span>
                    </span>
                    {!! Form::text('visa_expiry_date', $value = $document->visa_expiry_date, [
                        'class' => 'form-control',
                        'readonly'
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Document Number :</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-list3"></i></span>
                    </span>
                    {!! Form::text('passport_number', $value = $document->passport_number, [
                        'class' => 'form-control',
                        'readonly'
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
</div>
