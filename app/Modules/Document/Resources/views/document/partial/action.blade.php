<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="form-group row">
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Title : <span class="text-danger">*</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('title', null, [
                                        'rows' => 5,
                                        'placeholder' => 'Write title here..',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                @if ($errors->has('title'))
                                    <div class="error text-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Document Type : <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <input type="radio" name="type" value="personal"
                                        {{ @$documentModel->type == 'personal' ? 'checked' : '' }}>&nbsp;Personal
                                    &nbsp;
                                    <input type="radio" name="type" value="official"
                                        {{ @$documentModel->type == 'official' ? 'checked' : '' }}>&nbsp;Official


                                    @if ($errors->has('type'))
                                        <div class="error text-danger">{{ $errors->first('type') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12 mb-3">
                            <div class="row">
                                <label class="col-form-label col-lg-2">Description : <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::textarea('description', null, [
                                            'rows' => 5,
                                            'placeholder' => 'Write description here..',
                                            'class' => 'form-control',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('description'))
                                        <div class="error text-danger">{{ $errors->first('description') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (auth()->user()->user_type == 'employee' || auth()->user()->user_type == 'supervisor')
                            <div class="col-lg-12 mb-3">
                                <div class="row">
                                    <label class="col-form-label col-lg-2">Share With Employee : </label>
                                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text"><i class="icon-user-plus"></i></span>
                                            </span>
                                            @php $selectedEmployees = $isEdit && !empty($documentEmployees) ? ($documentEmployees) : null; @endphp
                                            {!! Form::select('employees[]', $employeeList, $selectedEmployees, [
                                                'class' => 'form-control multiselect-select-all',
                                                'multiple' => 'multiple',
                                            ]) !!}
                                        </div>
                                        @if ($errors->has('employees'))
                                            <div class="error text-danger">{{ $errors->first('employees') }}</div>
                                        @endif

                                        {!! Form::hidden('method_type', 2, []) !!}
                                    </div>
                                </div>
                            </div>
                        @elseif (auth()->user()->user_type == 'hr' ||
                                auth()->user()->user_type == 'division_hr' ||
                                auth()->user()->user_type == 'admin' ||
                                auth()->user()->user_type == 'super_admin')
                            <div class="col-lg-12 mb-3">
                                <div class="row">
                                    <label class="col-form-label col-lg-2">Choose Method :<span class="text-danger">
                                            *</span></label>
                                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            <div class="p-1 rounded">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    {{ Form::radio('method_type', 1, false, ['class' => 'custom-control-input chooseMethod', 'id' => 'radio1']) }}
                                                    <label class="custom-control-label"
                                                        for="radio1">{{ 'Department' }}</label>
                                                </div>

                                                <div class="custom-control custom-radio custom-control-inline">
                                                    {{ Form::radio('method_type', 2, false, ['class' => 'custom-control-input chooseMethod', 'id' => 'radio2']) }}
                                                    <label class="custom-control-label"
                                                        for="radio2">{{ 'Employee' }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($errors->has('method_type'))
                                            <div class="error text-danger">{{ $errors->first('method_type') }}
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-12 mb-3 departmentDiv" style="display:none;">
                                <div class="row">
                                    <label class="col-form-label col-lg-2">Sub-Function : <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::select('departmentIds[]', $departmentList, null, [
                                                'class' => 'form-control multiselect-select-all departments',
                                                'multiple' => 'multiple',
                                            ]) !!}
                                        </div>
                                        @if ($errors->has('departmentIds'))
                                            <div class="error text-danger">{{ $errors->first('departmentIds') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 employeeDiv" style="display:none;">
                                <div class="row mb-3">
                                    <label class="col-form-label col-lg-3">Organization : <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::select('organization_id', $organizationList, null, [
                                                'class' => 'form-control select-search organization-filter',
                                                'placeholder' => 'Select Organization',
                                            ]) !!}

                                            {{-- {!! Form::select('organization_id', $organizationList, null, ['class' => 'form-control select-search chooseOrganization', 'placeholder'=>'Select Organization']) !!} --}}
                                        </div>
                                        <span class="errorOrganization"></span>

                                        @if ($errors->has('organization_id'))
                                            <div class="error text-danger">{{ $errors->first('organization_id') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-form-label col-lg-3">Employee : <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::select('employeeIds[]', $employeeList, null, [
                                                'class' => 'form-control multiselect-select-all-filtering',
                                                'multiple',
                                            ]) !!}

                                            {{-- {!! Form::select('employeeIds[]', [], null, ['class' => 'form-control fetchEmployees', 'multiple' => 'multiple']) !!} --}}
                                        </div>
                                        @if ($errors->has('employeeIds'))
                                            <div class="error text-danger">{{ $errors->first('employeeIds') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Other Details</legend>
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <div class="row">
                                <label class="col-form-label col-lg-3">Status : <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('status', $statusList, null, ['class' => 'form-control select-search']) !!}
                                    </div>
                                    @if ($errors->has('status'))
                                        <div class="error text-danger">{{ $errors->first('status') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (!$isEdit)
                            <div class="col-lg-12 mb-3">
                                <div class="row">
                                    <label class="col-form-label col-lg-3">Attachment : <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="file" name="attachments[]" class="form-control h-auto"
                                            accept=".jpg, .png, .doc, .pdf" multiple>
                                    </div>
                                    @if ($errors->has('attachments'))
                                        <div class="error text-danger">{{ $errors->first('attachments') }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif
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


    @section('script')
        <script src="{{ asset('admin/validation/document.js') }}"></script>

        <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
        <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
        <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
        <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
        <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
        <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>

        <script>
            $(document).ready(function() {

                $('.chooseMethod').on('change', function() {
                    var methodType = $(this).val();
                    if (methodType == 1) {
                        $('.departmentDiv').show();
                        $('.employeeDiv').hide();
                    } else if (methodType == 2) {
                        $('.departmentDiv').hide();
                        $('.employeeDiv').show();
                    }
                });

                // $('.chooseOrganization').on('change', function () {
                //     var organizationId = $(this).val();
                //     $.ajax({
                //         type: 'GET',
                //         url: '/admin/organization/get-employees',
                //         data: {
                //             organization_id : organizationId
                //         },
                //         success: function(data) {
                //             var list = JSON.parse(data);
                //             var options = '';

                //             // options += "<option value=''>Select Employee</option>";
                //             $.each(list, function(id, value){
                //                 options += "<option value='" + id + "'>" + value + "</option>";
                //             });

                //             $('.fetchEmployees').html(options);
                //             $('.fetchEmployees').select2();

                //             // $('.fetchEmployees').multiselect({
                //             //     includeSelectAllOption: true,
                //             // });
                //         }
                //     });
                // });


                // $('#organizationId').on('change', function () {
                //     $('.chooseMethod').trigger('change');
                // });

                // var organizationId = $('#organizationId').val();
                // if(organizationId == ''){
                //     $('#organizationId').css('border-color', 'red');
                //     $('.errorOrganization').html(
                //         '<i class="icon-thumbs-down3 mr-1"></i> Please choose organization first.'
                //     );
                //     $('.errorOrganization').removeClass('text-success');
                //     $('.errorOrganization').addClass('text-danger');
                //     $('#organizationId').focus();
                //     // $("#requestType").val(null).trigger("change");

                //     event.preventDefault();
                // }else {
                //     $('#organizationId').css('border-color', 'green');
                //     $('.errorOrganization').html('');
                //     $('.errorOrganization').removeClass('text-danger');
                //     $('.errorOrganization').addClass('text-success');
                // }
            });
        </script>
    @endSection
