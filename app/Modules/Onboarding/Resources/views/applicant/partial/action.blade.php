<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">MRF :<span class="text-danger"> *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                @if (isset($mrfId))
                                    {!! Form::hidden('manpower_requisition_form_id', $mrfId, []) !!}
                                    <div class="input-group">
                                        {!! Form::select('manpower_requisition_form_id', $mrfList, $mrfId, [
                                            'placeholder' => 'Select MRF',
                                            'class' => 'form-control select-search',
                                            'disabled',
                                        ]) !!}
                                    </div>
                                @else
                                    <div class="input-group">
                                        {!! Form::select('manpower_requisition_form_id', $mrfList, null, [
                                            'placeholder' => 'Select MRF',
                                            'class' => 'form-control select-search',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('manpower_requisition_form_id'))
                                        <div class="error text-danger">
                                            {{ $errors->first('manpower_requisition_form_id') }}</div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">First Name :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('first_name', null, ['placeholder' => 'e.g: Ram', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('first_name'))
                                    <div class="error text-danger">{{ $errors->first('first_name') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Middle Name :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('middle_name', null, ['placeholder' => 'e.g: Prashad', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('middle_name'))
                                    <div class="error text-danger">{{ $errors->first('middle_name') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Last Name :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('last_name', null, ['placeholder' => 'e.g: Shrestha', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('last_name'))
                                    <div class="error text-danger">{{ $errors->first('last_name') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Permanent Address :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('address', null, ['placeholder' => 'e.g: New Baneshwor', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">City/District :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('city', null, ['placeholder' => 'e.g: Kathmandu', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Province/State :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('province', null, ['placeholder' => 'e.g: Bagmati', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Mobile :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('mobile', null, ['placeholder' => 'e.g: 987654321', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('mobile'))
                                    <div class="error text-danger">{{ $errors->first('mobile') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Email :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('email', null, ['placeholder' => 'e.g: example@gmail.com', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('email'))
                                    <div class="error text-danger">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Gender :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('gender', $genderList, null, ['class' => 'form-control select-search']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Source :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('source', $sourceList, null, ['class' => 'form-control select-search']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Years of Experience :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('experience', null, ['placeholder' => 'e.g: 5', 'class' => 'form-control numeric']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Expected Salary :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('expected_salary', null, ['placeholder' => 'e.g: 50000', 'class' => 'form-control numeric']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Skills:</label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                {!! Form::textarea('skills', null, [
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
                <legend class="text-uppercase font-size-sm font-weight-bold">Additional Detail</legend>
                <div class="row mb-2">
                    <label class="col-lg-12 col-form-label font-weight-semibold">Resume :</label>
                    <div class="col-lg-12">
                        <input type="file" name="resume" class="file-input-advanced"
                            accept="application/msword, text/plain, application/pdf, image/*" data-fouc>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-12 col-form-label font-weight-semibold">Cover Letter :</label>
                    <div class="col-lg-12">
                        <input type="file" name="cover_letter" class="file-input-advanced"
                            accept="application/msword, text/plain, application/pdf, image/*" data-fouc>
                    </div>
                </div>
                {!! Form::hidden('status', 1, []) !!}
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Professional Qualification Detail</legend>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Academic Qualification :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('academic_qualification', null, [
                                        'placeholder' => 'e.g: Bachelor in IT',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                @if ($errors->has('academic_qualification'))
                                    <div class="error text-danger">{{ $errors->first('academic_qualification') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Current Organization :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('current_organization', null, ['placeholder' => 'e.g: ABC Company', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('current_organization'))
                                    <div class="error text-danger">{{ $errors->first('current_organization') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Current Designation :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('current_designation', null, [
                                        'placeholder' => 'e.g: Software Engineer II',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                @if ($errors->has('current_designation'))
                                    <div class="error text-danger">{{ $errors->first('current_designation') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Reference Name :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('reference_name', null, ['placeholder' => 'e.g: Sharad KC', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('reference_name'))
                                    <div class="error text-danger">{{ $errors->first('reference_name') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Reference Position :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('reference_position', null, ['placeholder' => 'e.g: Project Manager', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('reference_position'))
                                    <div class="error text-danger">{{ $errors->first('reference_position') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Reference Number:</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('reference_contact_number', null, [
                                        'placeholder' => 'e.g: 9858968597',
                                        'class' => 'form-control numeric',
                                    ]) !!}
                                </div>
                                @if ($errors->has('reference_contact_number'))
                                    <div class="error text-danger">{{ $errors->first('reference_contact_number') }}
                                    </div>
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
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btns btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <!-- validation js -->
    <script src="{{ asset('admin/validation/applicant.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <!-- file uploader js -->
    <script src="{{ asset('admin/global/js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/uploader_bootstrap.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/editor_ckeditor_default.js') }}"></script>
    <!-- tiny mce js -->
    {{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> --}}
    <script>
        $(document).ready(function() {

            // tinymce.init({
            //     selector: 'textarea.basicTinymce',
            //     height: '265'
            // });

        });
    </script>
@endSection
