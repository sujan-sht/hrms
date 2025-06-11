<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="row">

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('title', request()->get('title') ? request()->get('title') : null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter title..',
                                ]) !!}

                                @if ($errors->first('title') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('title') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Short Code</label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('code', request()->get('code') ? request()->get('code') : null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter code..',
                                ]) !!}

                                @if ($errors->first('code') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('code') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::textarea('description', null, [
                                    'rows' => 4,
                                    'placeholder' => 'Write description here..',
                                    'class' => 'form-control',
                                ]) !!}
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

<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
<script src="{{ asset('admin/validation/functional.js') }}"></script>
