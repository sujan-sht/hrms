
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                
                <div class="form-group row">
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Category:<span class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('category', null, ['placeholder' => 'Enter Category', 'class' => 'form-control','required']) !!}
                                </div>
                                @if ($errors->has('category'))
                                    <div class="error text-danger">{{ $errors->first('category') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Daily Wage:<span class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('daily_wage', null, ['placeholder' => 'Enter Daily Wage', 'class' => 'form-control','required']) !!}
                                </div>
                                @if ($errors->has('daily_wage'))
                                    <div class="error text-danger">{{ $errors->first('daily_wage') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

