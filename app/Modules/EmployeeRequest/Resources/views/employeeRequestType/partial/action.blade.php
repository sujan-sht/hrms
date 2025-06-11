    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <fieldset class="mb-3">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

                        <div class="form-group row">
                            <div class="col-lg-6 mb-3">
                                <div class="row">
                                    <label class="col-form-label col-lg-2">Title:<span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text"><i class="icon-pencil5"></i></span>
                                            </span>
                                            {!! Form::text('title', null, ['placeholder' => 'Enter Title', 'class' => 'form-control']) !!}
                                        </div>
                                        @if ($errors->has('title'))
                                            <div class="error text-danger">{{ $errors->first('title') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div class="row">
                                    <label class="col-form-label col-lg-2">Status:</label>
                                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text"><i
                                                        class="icon-database-check"></i></span>
                                            </span>
                                            {!! Form::select('status', ['1' => 'Active', '0' => 'In-Active'], null, [
                                                'id' => 'status',
                                                'class' => 'form-control',
                                            ]) !!}
                                        </div>
                                        @if ($errors->has('status'))
                                            <div class="error text-danger">{{ $errors->first('status') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                    class="icon-database-insert"></i></b>{{ $btnType }}</button>
    </div>
