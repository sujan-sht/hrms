<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Include Holiday <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::select('include_holiday', [10 => 'No', 11 => 'Yes'], null, ['class' => 'form-control select-search']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Include DayOff <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::select('include_dayoff', [10 => 'No', 11 => 'Yes'], null, ['class' => 'form-control select-search']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Display force leave from date of join ( in days) <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('days_limit_from_doj', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                        class="icon-backward2"></i></b>Go Back</a>
            <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
            class="icon-database-insert"></i></b>{{ $btnType }}</button>
        </div>
    </div>
</div>

@section('script')
    <script src="{{ asset('admin/validation/force-leave-setup.js') }}"></script>
@endSection


