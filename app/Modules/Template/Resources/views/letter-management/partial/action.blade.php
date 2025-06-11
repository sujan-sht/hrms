<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="form-group row">

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Employee :<span class="text-danger"> *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                   
                                    {!! Form::select('employee_id', $employeeList, isset($letter) ? ($letter->employee_id ?? '') : '', ['placeholder' => 'Choose Employee', 'class' => 'form-control select-search']) !!}
                                </div>
                                @if ($errors->has('title'))
                                    <div class="error text-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Type :<span class="text-danger"> *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    
                                    {!! Form::select('type', $typeList, isset($letter) ? ($letter->getRawOriginal('type') ?? '') : '', ['placeholder' => 'Choose option', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('type'))
                                    <div class="error text-danger">{{ $errors->first('type') }}</div>
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
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>
