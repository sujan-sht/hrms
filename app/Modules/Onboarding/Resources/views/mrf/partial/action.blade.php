<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="row">
                    @if (isset($organizationId))
                        {!! Form::hidden('organization_id', $organizationId, []) !!}
                    @else
                        <div class="col-lg-6 mb-3">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Organization :<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('organization_id', $organizationList, null, [
                                            'class' => 'form-control select-search organization_id organization-filter2',
                                            'placeholder' => 'Select Organization',
                                            'onchange' => 'filterAll()',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('organization_id'))
                                        <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Reference Number :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('reference_number', null, ['placeholder'=>'e.g: 54321','class'=>'form-control numeric']) !!}
                                </div>
                                @if ($errors->has('reference_number'))
                                    <div class="error text-danger">{{ $errors->first('reference_number') }}</div>
                                @endif
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Title :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('title', null, ['placeholder' => 'e.g: Vacancy for Developer', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('title'))
                                    <div class="error text-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Last Submission Date :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    @php
                                        $dateData = null;
                                        if (setting('calendar_type') == 'BS') {
                                            $classData = 'form-control nepali-calendar';
                                            if ($isEdit) {
                                                $dateData = date_converter()->eng_to_nep_convert($mrfModel['end_date']);
                                            }
                                        } else {
                                            $classData = 'form-control daterange-single';
                                            if ($isEdit) {
                                                $dateData = $mrfModel['end_date'];
                                            }
                                        }
                                    @endphp
                                    {!! Form::text('end_date', $dateData, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => $classData,
                                        'autocomplete' => 'off',
                                        'readonly',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Division :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('division', $divisionList, null, [
                                        'placeholder' => 'Select Division',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Sub-Function :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('department', $departmentList, null, [
                                        'placeholder' => 'Select Sub-Function',
                                        'class' => 'form-control select-search department_id department-filter',
                                        'onchange' => 'filterAll()',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Designation :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('designation', $designationList, null, [
                                        'placeholder' => 'Select Designation',
                                        'class' => 'form-control select-search designation_id designation-filter',
                                        'onchange' => 'filterAll()',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">MRF Type :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('type', $mrfTypeList, null, ['class' => 'form-control select-search']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Job Description:</label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::textarea('description', null, [
                                        'placeholder' => 'Write here..',
                                        'class' => 'form-control basicTinymce1',
                                        'id' => 'editor-full',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Job Specification:</label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                {!! Form::textarea('specification', null, [
                                    'placeholder' => 'Write here..',
                                    'class' => 'form-control basicTinymce1',
                                    'id' => 'editor-full',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Other Detail</legend>
                <div class="row">

                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Position :</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('position', null, ['placeholder' => 'e.g: Manager', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Minimum Age :</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('age', null, ['placeholder' => 'e.g: 25', 'class' => 'form-control numeric']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Salary :</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('salary', null, ['placeholder' => 'e.g: 12000', 'class' => 'form-control numeric']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Experience (Years):</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('experience', null, ['placeholder' => 'e.g: 2', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Require Two Wheeler License?</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('two_wheeler_status', [10 => 'No', 11 => 'Yes'], null, [
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Require Four Wheeler License?</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('four_wheeler_status', [10 => 'No', 11 => 'Yes'], null, [
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">

                            <label class="col-form-label col-lg-8">Reporting To : </label>
                            <div class="col-lg-4">

                                <div class="input-group">

                                    {!! Form::select('reporting_to', $authEmployeeList, null, [
                                        'class' => 'form-control select-search employee-filter',
                                        'placeholder' => 'Select Employee',
                                    ]) !!}

                                </div>
                                {{-- <i class="ph-spinner report-spinner spinner"></i> --}}
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">ABP Head Count : </label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('head_count', [10 => 'No', 11 => 'Yes'], null, ['class' => 'form-control select-search']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Position Fullfillment Date: </label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    @php
                                        $dateData1 = null;
                                        if (setting('calendar_type') == 'BS') {
                                            $clData = 'form-control nepali-calendar';
                                            if ($isEdit && $mrfModel['fullfillment_date']) {
                                                $dateData1 = date_converter()->eng_to_nep_convert(
                                                    $mrfModel['fullfillment_date'],
                                                );
                                            }
                                        } else {
                                            $clData = 'form-control daterange-single';
                                            if ($isEdit && $mrfModel['fullfillment_date']) {
                                                $dateData1 = $mrfModel['fullfillment_date'];
                                            }
                                        }
                                    @endphp

                                    {!! Form::text('fullfillment_date', $dateData1, [
                                        'placeholder' => 'YYYY-MM-DD',
                                        'class' => $clData,
                                        'autocomplete' => 'off',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::hidden('prepared_by', auth()->user()->emp_id, []) !!}
        {!! Form::hidden('status', 1, []) !!}
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

<script>
    const filterAll = () => {
        organizationId = $(".organization_id").val();
        department_id = $(".department_id").val();
        // designation_id = $(".designation_id").val();

        $.ajax({
            type: 'GET',
            url: '/admin/organization/get-employees',
            data: {
                organization_id: organizationId,
                department_id: department_id,
                // designation_id: designation_id
            },
            success: function(data) {
                var list = JSON.parse(data);
                appendEmployee(list)
            }
        });

    }

    const appendEmployee = (list) => {
        var options = '';
        options += "<option value=''>Select Employee</option>";
        $.each(list, function(id, value) {
            options += "<option value='" + id + "'  >" + value + "</option>";
        });

        $('.employee-filter').html(options);
        $('.employee-filter').select2();
    }
</script>

@section('script')
    <!-- validation js -->
    <script src="{{ asset('admin/validation/mrf.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/editor_ckeditor_default.js') }}"></script>
    {{-- <script src="https://cdn.tiny.cloud/1/cjrqkjizx7e1ld0p8kcygaj4cvzc6drni6o4xl298c5hl9l1/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script> --}}
    <script>
        $(document).ready(function() {
            // tinymce.init({
            //     selector: 'textarea.basicTinymce',
            //     height: '245'
            // });




        });
    </script>
@endSection
