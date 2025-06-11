    <fieldset class="mb-3">
        <legend class="text-uppercase font-size-sm font-weight-bold"></legend>

        <div class="form-group row">
            <label class="col-form-label col-lg-3">Title:<span class="text-danger">*</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-pencil5"></i></span>
                    </span>
                    {!! Form::text('title', null, ['placeholder'=>'Enter Title','class'=>'form-control']) !!}
                </div>
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Status:</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-database-check"></i></span>
                    </span>
                    {!! Form::select('status',[ '1'=>'Active','0'=>'In-Active'], null, ['id'=>'status','class'=>'form-control']) !!}
                </div>
            </div>
        </div>

    </fieldset>


    <div class="text-right">
        <button type="submit" class="btn bg-teal-400">{{ $btnType }} <i class="icon-database-insert"></i></button>
    </div>


