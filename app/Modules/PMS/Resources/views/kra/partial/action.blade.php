<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">KRA Details</legend>
                <div class="form-group row">
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Title :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('title', null, ['rows' => 5, 'placeholder' => 'Write title here..', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('title'))
                                    <div class="error text-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Organization :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('division_id', $organizationList, null, [
                                        'id' => 'divisionId',
                                        'placeholder' => 'Select Organization',
                                        'class' => 'form-control select-search organization-filter2',
                                    ]) !!}
                                </div>
                                @if ($errors->has('division_id'))
                                    <div class="error text-danger">{{ $errors->first('division_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Sub-Function :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('department_id', $departmentList, null, [
                                        'id' => 'departmentId',
                                        'placeholder' => 'Select Sub-Function',
                                        'class' => 'form-control select-search department-filter',
                                    ]) !!}
                                </div>
                                @if ($errors->has('department_id'))
                                    <div class="error text-danger">{{ $errors->first('department_id') }}</div>
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
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="{{ asset('admin/validation/kra.js') }}"></script>
@endSection
