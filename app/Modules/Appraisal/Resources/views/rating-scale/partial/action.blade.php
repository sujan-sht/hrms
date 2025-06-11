<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="form-group row">

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Score :<span class="text-danger"> *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('score', null, ['placeholder' => 'Enter Score', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('score'))
                                    <div class="error text-danger">{{ $errors->first('score') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Indication :<span class="text-danger"> *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('indication', null, ['placeholder' => 'Enter Indication', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('indication'))
                                    <div class="error text-danger">{{ $errors->first('indication') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Explanation :<span class="text-danger"> *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('explanation', null, ['placeholder' => 'Enter Explanation', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('explanation'))
                                    <div class="error text-danger">{{ $errors->first('explanation') }}</div>
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
