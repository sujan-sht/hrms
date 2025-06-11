<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Organization <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-12">
                                @php
                                    if ($isEdit) {
                                        if (count($designation->organizations) > 0) {
                                            foreach ($designation->organizations as $model) {
                                                $organizationValues[] = $model->organization_id;
                                            }
                                        } else {
                                            $organizationValues = null;
                                        }
                                    } else {
                                        $organizationValues = null;
                                    }
                                @endphp
                                {!! Form::select('organization_ids[]', $organizationList, $organizationValues, ['class' => 'form-control multiselect-select-all-filtering', 'multiple']) !!}
                                @if ($errors->first('organization_ids') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('organization_ids') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('title', request()->get('title') ? request()->get('title') : null, ['class' => 'form-control', 'placeholder' => 'Enter title..']) !!}

                                @if ($errors->first('title') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('title') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Short Code</label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('short_code', request()->get('short_code') ? request()->get('short_code') : null, ['class' => 'form-control', 'placeholder' => 'Enter code..']) !!}

                                @if ($errors->first('short_code') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('short_code') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        {{-- <div class="form-group row">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="form-check input-group form-check-inline">
                                    <input type="checkbox" name="display_short_code" id='displayShortCode' class='form-check-input' {{ (isset($designation->display_short_code) && $designation->display_short_code == 1) ? 'checked' : ''}}>
                                    <label class="col-form-label col-lg-11">display_short_code </label>
                                </div>
                            </div>
                        </div> --}}

                        <div class="row">
                            {{-- <div class="col-md-12">
                                <label class="form-label">Display Short Code</label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::checkbox('display_short_code', request()->get('display_short_code') ? request()->get('display_short_code') : null, 1, ['class' => 'form-control']) !!}
                                <input type="checkbox" name="display_short_code" id='displayShortCode' class='form-check-input' {{ (isset($designation->display_short_code) && $designation->display_short_code == 1) ? 'checked' : ''}}>


                                @if ($errors->first('display_short_code') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('display_short_code') }}</li>
                                    </ul>
                                @endif
                            </div> --}}

                            <div class="col-md-12 mt-2">
                                <label class="form-label">Display Short Code</label>
                            </div>
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right mt-2">
                                <div class="form-check input-group form-check-inline">
                                    <input type="checkbox" name="display_short_code" id='displayShortCode' class='form-check-input' {{ (isset($designation->display_short_code) && $designation->display_short_code == 1) ? 'checked' : ''}}>
                                    {{-- <label class="col-form-label col-lg-11">Do not affect on payroll </label> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::textarea('description', request()->get('description') ? request()->get('description') : null, ['class' => 'form-control', 'placeholder' => 'Enter description..']) !!}

                                @if ($errors->first('description') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('description') }}</li>
                                    </ul>
                                @endif
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

    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    <script src="{{ asset('admin/validation/designation.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#displayShortCode').click(function () {
            if($(this).prop("checked")){
                $(this).val(1)
            }
        });
        })
    </script>

@endSection