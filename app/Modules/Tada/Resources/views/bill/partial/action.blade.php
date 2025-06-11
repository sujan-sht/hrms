    <fieldset class="mb-3">
        <legend class="text-uppercase font-size-sm font-weight-bold"></legend>

        <div class="form-group row">
            <label class="col-form-label col-lg-3">Title:<span class="text-danger">*</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('title', null, ['placeholder'=>'Enter Title','class'=>'form-control']) !!}
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-3">TADA:<span class="text-danger">*</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('tada_id', $tadas, request('tada') ?? null, ['placeholder'=>'Select TADA','class'=>'form-control']) !!}
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-3">Bill Type:<span class="text-danger">*</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('bill_type_id', $billTypes, null, ['placeholder'=>'Select Bill Type','class'=>'form-control']) !!}
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-3">Amount:</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('amount', null, ['placeholder'=>'Select Amount','class'=>'form-control numeric', 'min' => 0]) !!}
                </div>
            </div>
        </div>

        @if (!isset($tadaBill))
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Bill Image:</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::file('image_src', ['class'=>'form-control']) !!}
                </div>
                <span class="text-danger">{{ $errors->first('image_src') }}</span>
            </div>
        </div>
        @endif

        <div class="form-group row">
            <label class="col-form-label col-lg-3">Status:</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                {!! Form::select('status',[ '1'=>'Active','0'=>'In-Active'], null, ['id'=>'status','class'=>'form-control']) !!}
            </div>
        </div>
    </fieldset>


    <div class="text-right">
        <button type="submit" class="btn bg-teal-400">{{ $btnType }} <i class="icon-database-insert"></i></button>
    </div>


