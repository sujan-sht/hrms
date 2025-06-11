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
                                        if (count($department->organizations) > 0) {
                                            foreach ($department->organizations as $model) {
                                                $organizationValues[] = $model->organization_id;
                                            }
                                        } else {
                                            $organizationValues = null;
                                        }
                                    } else {
                                        $organizationValues = null;
                                    }
                                @endphp
                                {!! Form::select('organization_ids[]', $organizationList, $organizationValues, [
                                    'class' => 'form-control multiselect-select-all-filtering',
                                    'multiple',
                                ]) !!}
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
                                <label class="form-label">Function <span class="text-danger">*</span></label>
                            </div>
                            @php
                                $functionList = App\Modules\Setting\Entities\Functional::pluck('title', 'id');
                            @endphp
                            <div class="col-md-12">
                                {!! Form::select('function_id', $functionList, @$department->function_id, [
                                    'class' => 'form-control select-search select2',
                                    'id' => 'function_id',
                                    'placeholder' => 'Select Function',
                                    'required',
                                ]) !!}
                                @if ($errors->first('function_id') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('function_id') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
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

                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Short Code</label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('short_code', request()->get('short_code') ? request()->get('short_code') : null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter code..',
                                ]) !!}

                                @if ($errors->first('short_code') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('short_code') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Display Short Code</label>
                            </div>
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right mt-2">
                                <div class="form-check input-group form-check-inline">
                                    <input type="checkbox" name="display_short_code" id='levelShortCode'
                                        class='form-check-input'
                                        {{ isset($level->display_short_code) && $level->display_short_code == 1 ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Category</label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::select('category', $categoryList, $value = null, [
                                    'id' => 'category',
                                    'class' => 'form-control',
                                    'placeholder' => 'Select Category',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
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
<script src="{{ asset('admin/validation/department.js') }}"></script>
