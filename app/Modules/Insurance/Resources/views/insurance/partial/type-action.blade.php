<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Title<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::select(
                                    'title',
                                    [
                                        '1' => 'Life Insurance',
                                        '2' => 'Accident Insurance',
                                        '3' => 'Medical Insurance',
                                    ],
                                    null,
                                    [
                                        'id' => 'title',
                                        'class' => 'form-control select-search',
                                        'placeholder' => 'Select Insurance Type',
                                        'required',
                                    ],
                                ) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                        class="icon-backward2"></i></b>Go Back</a>
            <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                        class="icon-database-insert"></i></b>{{ $btnType }}</button>
        </div>
    </div>

</div>

<script></script>
