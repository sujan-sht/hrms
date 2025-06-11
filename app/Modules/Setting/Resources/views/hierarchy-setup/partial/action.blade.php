<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="row">
                    <div class="col-lg-10 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Organization :<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    @if ($isEdit)
                                        {!! Form::select('organization_id', $organizationList, null, ['class' => 'form-control', 'disabled']) !!}
                                    @else
                                        {!! Form::select('organization_id', $organizationList, null, [
                                            'id' => 'organization_id',
                                            'class' => 'form-control select2',
                                            'placeholder' => 'Choose Organization',
                                        ]) !!}
                                        @if ($errors->has('organization_id'))
                                            <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sub-Function Start --}}
                <legend class="text-uppercase font-size-sm font-weight-bold">Department Detail</legend>
                @if ($isEdit)
                    @foreach ($hierarchySetupModel->getOrganizationDepartments as $getOrganizationDepartment)
                        <div class="row parent">
                            <div class="col-lg-10 mb-3">
                                <div class="row">
                                    <label class="col-lg-2 col-form-label">Sub-Function :</label>
                                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::text('department_name[]', $getOrganizationDepartment->department_name, [
                                                'placeholder' => 'Enter Sub-Function Name',
                                                'class' => 'form-control',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <a class="btn btn-danger rounded-pill remove-department">
                                    <i class="icon-minus-circle2 mr-1"></i>Remove
                                </a>
                            </div>
                        </div>
                    @endforeach
                    <div class="department-form-repeater"></div>
                    <div class="row">
                        <div class="col-lg-2 mb-3">
                            <a id="addMoreDepartment" class="btn btn-success rounded-pill">
                                <i class="icon-plus-circle2 mr-1"></i>Add More
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-lg-10 mb-3">
                            <div class="row">
                                <label class="col-lg-2 col-form-label">Sub-Function :</label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('department_name[]', null, [
                                            'placeholder' => 'Enter Sub-Function Name',
                                            'class' => 'form-control',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <a id="addMoreDepartment" class="btn btn-success rounded-pill">
                                <i class="icon-plus-circle2 mr-1"></i>Add More
                            </a>
                        </div>
                    </div>
                    <div class="department-form-repeater"></div>
                @endif
                {{-- Department End --}}

                {{-- Level Start --}}
                <legend class="text-uppercase font-size-sm font-weight-bold">Level Detail</legend>
                @if ($isEdit)
                    @foreach ($hierarchySetupModel->getOrganizationLevels as $getOrganizationLevel)
                        <div class="row parent">
                            <div class="col-lg-10 mb-3">
                                <div class="row">
                                    <label class="col-lg-2 col-form-label">Level :</label>
                                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::text('level_name[]', $getOrganizationLevel->level_name, [
                                                'placeholder' => 'Enter Level Name',
                                                'class' => 'form-control',
                                                'required',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <a class="btn btn-danger rounded-pill remove-level">
                                    <i class="icon-minus-circle2 mr-1"></i>Remove
                                </a>
                            </div>
                        </div>
                    @endforeach
                    <div class="level-form-repeater"></div>
                    <div class="row">
                        <div class="col-lg-2 mb-3">
                            <a id="addMoreLevel" class="btn btn-success rounded-pill">
                                <i class="icon-plus-circle2 mr-1"></i>Add More
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-lg-10 mb-3">
                            <div class="row">
                                <label class="col-lg-2 col-form-label">Level :</label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('level_name[]', null, ['placeholder' => 'Enter Level Name', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <a id="addMoreLevel" class="btn btn-success rounded-pill">
                                <i class="icon-plus-circle2 mr-1"></i>Add More
                            </a>
                        </div>
                    </div>
                    <div class="level-form-repeater"></div>
                @endif
                {{-- Level End --}}

                {{-- Designation Start --}}
                <legend class="text-uppercase font-size-sm font-weight-bold">Designation Detail</legend>
                @if ($isEdit)
                    @foreach ($hierarchySetupModel->getOrganizationDesignations as $getOrganizationDesignation)
                        <div class="row parent">
                            <div class="col-lg-10 mb-3">
                                <div class="row">
                                    <label class="col-lg-2 col-form-label">Designation :</label>
                                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::text('designation_name[]', $getOrganizationDesignation->designation_name, [
                                                'placeholder' => 'Enter Designation Name',
                                                'class' => 'form-control',
                                                'required',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <a class="btn btn-danger rounded-pill remove-designation">
                                    <i class="icon-minus-circle2 mr-1"></i>Remove
                                </a>
                            </div>
                        </div>
                    @endforeach
                    <div class="designation-form-repeater"></div>
                    <div class="row">
                        <div class="col-lg-2 mb-3">
                            <a id="addMoreDesignation" class="btn btn-success rounded-pill">
                                <i class="icon-plus-circle2 mr-1"></i>Add More
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-lg-10 mb-3">
                            <div class="row">
                                <label class="col-lg-2 col-form-label">Designation :</label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('designation_name[]', null, [
                                            'placeholder' => 'Enter Designation Name',
                                            'class' => 'form-control',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <a id="addMoreDesignation" class="btn btn-success rounded-pill">
                                <i class="icon-plus-circle2 mr-1"></i>Add More
                            </a>
                        </div>
                    </div>
                    <div class="designation-form-repeater"></div>
                @endif
                {{-- Designation End --}}
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btns btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <script src="{{ asset('admin/validation/hierarchySetup.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Department Start
            $('#addMoreDepartment').on('click', function() {
                var html = '<div class="row parent"><div class="col-lg-10 mb-3"><div class="row">';
                html += '<label class="col-lg-2 col-form-label">Sub-Function :</label>';
                html += '<div class="col-lg-10 form-group-feedback form-group-feedback-right">';
                html += '<div class="input-group">';
                html +=
                    '<input type="text" name="department_name[]" class="form-control" placeholder="Enter Department Name" required>';
                html += '</div>'
                html += '</div></div></div>';
                html += '<div class="col-lg-2 mb-3">';
                html += '<a class="btn btn-danger rounded-pill remove-department">';
                html += '<i class="icon-minus-circle2 mr-1"></i>Remove';
                html += '</a>';
                html += '</div</div>';
                $('.department-form-repeater').append(html);
            });
            // Department End

            // Level Start
            $('#addMoreLevel').on('click', function() {
                var html = '<div class="row parent"><div class="col-lg-10 mb-3"><div class="row">';
                html += '<label class="col-lg-2 col-form-label">Level :</label>';
                html += '<div class="col-lg-10 form-group-feedback form-group-feedback-right">';
                html += '<div class="input-group">';
                html +=
                    '<input type="text" name="level_name[]" class="form-control" placeholder="Enter Level Name" required>';
                html += '</div>'
                html += '</div></div></div>';
                html += '<div class="col-lg-2 mb-3">';
                html += '<a class="btn btn-danger rounded-pill remove-level">';
                html += '<i class="icon-minus-circle2 mr-1"></i>Remove';
                html += '</a>';
                html += '</div</div>';
                $('.level-form-repeater').append(html);
            });
            // Level End

            // Designation Start
            $('#addMoreDesignation').on('click', function() {
                var html = '<div class="row parent"><div class="col-lg-10 mb-3"><div class="row">';
                html += '<label class="col-lg-2 col-form-label">Designation :</label>';
                html += '<div class="col-lg-10 form-group-feedback form-group-feedback-right">';
                html += '<div class="input-group">';
                html +=
                    '<input type="text" name="designation_name[]" class="form-control" placeholder="Enter Designation Name" required>';
                html += '</div>'
                html += '</div></div></div>';
                html += '<div class="col-lg-2 mb-3">';
                html += '<a class="btn btn-danger rounded-pill remove-designation">';
                html += '<i class="icon-minus-circle2 mr-1"></i>Remove';
                html += '</a>';
                html += '</div</div>';
                $('.designation-form-repeater').append(html);
            });
            // Designation End

            $(document).on('click', '.remove-department, .remove-designation, .remove-level', function() {
                $(this).closest('.parent').remove();
            });
        });
    </script>
@endSection
