@section('script')
    <!-- Theme JS files -->
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <!-- /theme JS files -->
    <script src="{{ asset('admin/validation/setting.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/editor_ckeditor_default.js') }}"></script>
    <script src="{{ asset('admin/validation/setting.js') }}"></script>

    <script>
        $(document).ready(function() {
            var selectedValue;
            var selectedValueOrganization;
            $('input[name="sync_organization"]').on('change', function() {
                selectedValueOrganization = $(this).val();
                var hostName = $('#sync_host_name').val().trim();

                if (hostName === '') {
                    alert('Please Fill First Sync Domain Name.');
                    return;
                }

                $('#confirmationModal').modal('show');
            });

            $('#confirmButton').on('click', function() {
                $('#setting_submit').submit();
                $('#confirmationModal').modal('hide');

                if (selectedValueOrganization == 1) {
                    $.ajax({
                        url: "{{ route('v1.organization.getAll') }}",
                        method: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            syncOrganization: selectedValueOrganization,
                        },
                        success: function(response) {
                            toastr.success('Organization Data sync successfully.', 'Success');
                        },
                        error: function(xhr) {
                            toastr.error('An error occurred while Organization Data sync.');
                        }
                    });
                }
            });

            $('#confirmationModal').on('hidden.bs.modal', function() {
                $('input[name="sync_organization"]').prop('checked', false);
                $('input[name="sync_organization"][value="' + (selectedValue === '1' ? '0' : '1') + '"]')
                    .prop('checked', true);
            });

            $('input[name="sync_employee"]').on('change', function() {
                selectedValue = $(this).val();
                var hostName = $('#sync_host_name').val().trim();

                if (hostName === '') {
                    alert('Please Fill First Sync Domain Name.');
                    return;
                }

                $('#confirmationModal').modal('show');
            });

            $('#confirmButton').on('click', function() {
                $('#setting_submit').submit();
                $('#confirmationModal').modal('hide');

                if (selectedValue == 1) {
                    $.ajax({
                        url: "{{ route('v1.employee.getAll') }}",
                        method: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            syncOrganization: selectedValue,
                        },
                        success: function(response) {
                            toastr.success('Employee Data sync successfully.', 'Success');
                        },
                        error: function(xhr) {
                            toastr.error('An error occurred while Employee Data sync.');
                        }
                    });
                }
            });

            $('#confirmationModal').on('hidden.bs.modal', function() {
                $('input[name="sync_employee"]').prop('checked', false);
                $('input[name="sync_employee"][value="' + (selectedValue === '1' ? '0' : '1') + '"]').prop(
                    'checked', true);
            });

        });
    </script>
@endsection


<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to change the synchronization setting?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmButton">Confirm</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card bd-card">
            <div class="card-body">
                @if ($is_edit)
                    {!! Form::model($setting, [
                        'method' => 'PUT',
                        'route' => ['setting.update', $setting->id],
                        'class' => 'form-horizontal',
                        'id' => 'setting_submit',
                        'role' => 'form',
                        'files' => true,
                    ]) !!}
                @else
                    {!! Form::open([
                        'route' => 'setting.store',
                        'id' => 'setting_submit',
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'files' => true,
                    ]) !!}
                @endif

                <fieldset class="mb-1">

                    <legend class="text-uppercase font-size-sm font-weight-bold">Basic Company Information</legend>

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Company Name: <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-office"></i></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::text('company_name', $setting->company_name, [
                                                'id' => 'company_name',
                                                'placeholder' => 'Enter Company Name',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @else
                                            {!! Form::text('company_name', $value = null, [
                                                'id' => 'company_name',
                                                'placeholder' => 'Enter Company Name',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('company_name') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4"> Company PAN / VAT No. </label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-cash3"></i></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::text('pan_vat_no', $setting->pan_vat_no, [
                                                'id' => 'pan_vat_no',
                                                'placeholder' => 'Enter Company PAN/VAT No',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @else
                                            {!! Form::text('pan_vat_no', $value = null, [
                                                'id' => 'pan_vat_no',
                                                'placeholder' => 'Enter Company PAN/VAT No',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('pan_vat_no') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Company Website:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-sphere3"></i></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::text('website', $setting->website, [
                                                'id' => 'website',
                                                'placeholder' => 'Enter Company Website',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @else
                                            {!! Form::text('website', $value = null, [
                                                'id' => 'website',
                                                'placeholder' => 'Enter Company Website',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('website') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4"> Company Info:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><b>©</b></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::text('company_info', $setting->company_info, [
                                                'id' => 'company_info',
                                                'placeholder' => 'Enter Company Copyright',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @else
                                            {!! Form::text('company_info', $value = null, [
                                                'id' => 'company_info',
                                                'placeholder' => 'Enter Company Info',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('company_info') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Company Logo:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-image2"></i></span>
                                        </span>
                                        {!! Form::file('company_logo', ['id' => 'company_logo', 'class' => 'form-control']) !!}
                                        <span class="text-danger">{{ $errors->first('company_logo') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="row">
                                <label class="col-form-label col-lg-2"></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        @if ($is_edit and $setting->company_logo !== null)
                                            <img id="bannerImage"
                                                src="{{ asset('uploads/setting/' . $setting->company_logo) }}"
                                                alt="your image" class="preview-image"
                                                style="height: 100px;width: auto;" />
                                        @else
                                            <img id="bannerImage" src="{{ asset('admin/image.png') }}" alt="your image"
                                                class="preview-image" style="height: 100px; width: auto;" />
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </fieldset>

                <fieldset class="mb-1">

                    <legend class="text-uppercase font-size-sm font-weight-bold">Company Detail Information</legend>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Contact No 1:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-phone"></i></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::text('contact_no1', $setting->contact_no1, [
                                                'id' => 'contact_no1',
                                                'placeholder' => 'Enter contact number',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @else
                                            {!! Form::text('contact_no1', $value = null, [
                                                'id' => 'contact_no1',
                                                'placeholder' => 'Enter contact number',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('contact_no1') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Contact No 2</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-phone"></i></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::text('contact_no2', $setting->contact_no2, [
                                                'id' => 'contact_no2',
                                                'placeholder' => 'Enter contact number 2',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @else
                                            {!! Form::text('contact_no2', $value = null, [
                                                'id' => 'contact_no2',
                                                'placeholder' => 'Enter contact number 2',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('contact_no2') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Address 1:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-location4"></i></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::text('address1', $setting->address1, [
                                                'id' => 'address1',
                                                'placeholder' => 'Enter Address 1',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @else
                                            {!! Form::text('address1', $value = null, [
                                                'id' => 'address1',
                                                'placeholder' => 'Enter Address 1',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('address1') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Address 2:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-location4"></i></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::text('address2', $setting->address2, [
                                                'id' => 'address2',
                                                'placeholder' => 'Enter Address 2',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @else
                                            {!! Form::text('address2', $value = null, [
                                                'id' => 'address2',
                                                'placeholder' => 'Enter Address 2',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('address2') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Fax:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-envelop"></i></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::text('fax', $setting->fax, ['id' => 'fax', 'placeholder' => 'Enter Fax', 'class' => 'form-control']) !!}
                                        @else
                                            {!! Form::text('fax', $value = null, ['id' => 'fax', 'placeholder' => 'Enter Fax', 'class' => 'form-control']) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('fax') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Post Box:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-mailbox"></i></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::text('post_box', $setting->post_box, [
                                                'id' => 'post_box',
                                                'placeholder' => 'Enter Post Box',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @else
                                            {!! Form::text('post_box', $value = null, [
                                                'id' => 'post_box',
                                                'placeholder' => 'Enter Post Box',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('post_box') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Company Email:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-envelop5"></i></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::text('company_email', $setting->company_email, [
                                                'id' => 'company_email',
                                                'placeholder' => 'Enter Company Email',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @else
                                            {!! Form::text('company_email', $value = null, [
                                                'id' => 'company_email',
                                                'placeholder' => 'Enter Company Email',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('company_email') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Enable Email: <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-envelop5"></i></span>
                                        </span>
                                        {!! Form::select('enable_mail', $statusList, $value = null, [
                                            'id' => 'enable_mail',
                                            'placeholder' => 'Choose',
                                            'class' => 'form-control',
                                        ]) !!}

                                        <span class="text-danger">{{ $errors->first('enable_mail') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Calendar Type: <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-envelop5"></i></span>
                                        </span>
                                        {!! Form::select('calendar_type', ['AD' => 'AD', 'BS' => 'BS'], $value = null, [
                                            'id' => 'calendar_type',
                                            'placeholder' => 'Choose Calendar Type',
                                            'class' => 'form-control',
                                        ]) !!}

                                        <span class="text-danger">{{ $errors->first('calendar_type') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Leave Deduction From Biometric: <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-envelop5"></i></span>
                                        </span>
                                        {!! Form::select('leave_deduction_from_biometric', $statusList, $value = null, [
                                            'id' => 'leave_deduction_from_biometric',
                                            'placeholder' => 'Choose',
                                            'class' => 'form-control',
                                        ]) !!}

                                        <span
                                            class="text-danger">{{ $errors->first('leave_deduction_from_biometric') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-form-label col-lg-2">Google Map:</label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-map4"></i></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::textarea('google_map', $setting->google_map, [
                                                'rows' => 2,
                                                'id' => 'google_map',
                                                'placeholder' => 'Enter Google Map',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @else
                                            {!! Form::textarea('google_map', $value = null, [
                                                'rows' => 2,
                                                'id' => 'google_map',
                                                'placeholder' => 'Enter Google Map',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('google_map') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Web Attendance: <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-envelop5"></i></span>
                                        </span>
                                        {!! Form::select('web_attendance', [10 => 'Disable', 11 => 'Enable'], $value = null, [
                                            'id' => 'web_attendance',
                                            'placeholder' => 'Choose Option',
                                            'class' => 'form-control',
                                        ]) !!}

                                        <span class="text-danger">{{ $errors->first('web_attendance') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Force App Update: <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-mobile"></i></span>
                                        </span>
                                        {!! Form::select('force_app_update', [10 => 'Disable', 11 => 'Enable'], $value = null, [
                                            'id' => 'force_app_update',
                                            'placeholder' => 'Choose Option',
                                            'class' => 'form-control',
                                        ]) !!}

                                        <span class="text-danger">{{ $errors->first('force_app_update') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Google Play Store Version:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-mobile"></i></span>
                                        </span>
                                        {!! Form::text('play_store_app_version', $value = null, [
                                            'id' => 'play_store_app_version',
                                            'placeholder' => 'Enter Google Play Store Version',
                                            'class' => 'form-control',
                                        ]) !!}

                                        <span
                                            class="text-danger">{{ $errors->first('play_store_app_version') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Apple Store Version:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-mobile"></i></span>
                                        </span>
                                        {!! Form::text('apple_store_app_version', $value = null, [
                                            'id' => 'apple_store_app_version',
                                            'placeholder' => 'Enter Apple Store Version',
                                            'class' => 'form-control',
                                        ]) !!}

                                        <span
                                            class="text-danger">{{ $errors->first('apple_store_app_version') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Real Time APP Attendance: <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-mobile"></i></span>
                                        </span>
                                        {!! Form::select('real_time_app_atd', [10 => 'Disable', 11 => 'Enable'], $value = null, [
                                            // 'id' => 'force_app_update',
                                            'placeholder' => 'Choose Option',
                                            'class' => 'form-control',
                                        ]) !!}

                                        <span class="text-danger">{{ $errors->first('real_time_app_atd') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Enable Two Step Substitute Leave: <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-envelop5"></i></span>
                                        </span>
                                        {!! Form::select('two_step_substitute_leave', $statusList, $value = null, [
                                            'id' => 'two_step_substitute_leave',
                                            'placeholder' => 'Choose',
                                            'class' => 'form-control',
                                        ]) !!}

                                        <span
                                            class="text-danger">{{ $errors->first('two_step_substitute_leave') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Enable Attendance Lock: <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-lock5"></i></span>
                                        </span>
                                        {!! Form::select('attendance_lock', $statusList, $value = null, [
                                            'id' => 'attendance_lock',
                                            'placeholder' => 'Choose Option',
                                            'class' => 'form-control',
                                        ]) !!}

                                        <span class="text-danger">{{ $errors->first('attendance_lock') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                            <div class="col-md-12">
                                <div class="row">
                                    <label class="col-form-label col-lg-2">App Update Description:</label>
                                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::textarea('app_update_description', $value = null, [
                                                'id' => 'editor-full',
                                                'placeholder' => 'Enter App Update Description',
                                                'class' => 'form-control',
                                                'rows' => '3',
                                                'cols' => '12',
                                            ]) !!}

                                            <span
                                                class="text-danger">{{ $errors->first('app_update_description') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                </fieldset>

                <fieldset class="mb-1">

                    <legend class="text-uppercase font-size-sm font-weight-bold">Social Media Information</legend>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Facebook URL:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-facebook2"></i></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::text('facebook_link', $setting->facebook_link, [
                                                'id' => 'facebook_link',
                                                'placeholder' => 'Enter Facebook URL',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @else
                                            {!! Form::text('facebook_link', $value = null, [
                                                'id' => 'facebook_link',
                                                'placeholder' => 'Enter Facebook URL',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('facebook_link') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">LinkedIn URL:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-linkedin"></i></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::text('linkin_link', $setting->linkin_link, [
                                                'id' => 'linkin_link',
                                                'placeholder' => 'Enter LinkedIn URL',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @else
                                            {!! Form::text('linkin_link', $value = null, [
                                                'id' => 'linkin_link',
                                                'placeholder' => 'Enter LinkedIn URL',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('linkin_link') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Twitter URL:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-twitter"></i></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::text('twitter_link', $setting->twitter_link, [
                                                'id' => 'twitter_link',
                                                'placeholder' => 'Enter Twitter URL',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @else
                                            {!! Form::text('twitter_link', $value = null, [
                                                'id' => 'twitter_link',
                                                'placeholder' => 'Enter Twitter URL',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('twitter_link') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Youtube URL:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-play"></i></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::text('youtube_link', $setting->youtube_link, [
                                                'id' => 'youtube_link',
                                                'placeholder' => 'Enter Youtube URL',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @else
                                            {!! Form::text('youtube_link', $value = null, [
                                                'id' => 'youtube_link',
                                                'placeholder' => 'Enter Youtube URL',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('youtube_link') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Instagram URL:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-instagram"></i></span>
                                        </span>
                                        @if ($is_edit)
                                            {!! Form::text('instagram_link', $setting->instagram_link, [
                                                'id' => 'instagram_link',
                                                'placeholder' => 'Enter Instagram URL',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @else
                                            {!! Form::text('instagram_link', $value = null, [
                                                'id' => 'instagram_link',
                                                'placeholder' => 'Enter Instagram URL',
                                                'class' => 'form-control',
                                            ]) !!}
                                        @endif
                                        <span class="text-danger">{{ $errors->first('instagram_link') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <legend class="text-uppercase font-size-sm font-weight-bold">Sync Setting </legend>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Sync Domain Name</label>
                        <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-sphere3"></i></span>
                                </span>
                                @if ($is_edit)
                                    {!! Form::text('sync_host_name', $setting->sync_host_name, [
                                        'id' => 'sync_host_name',
                                        'placeholder' => 'Eg:- https://bidhee.com/',
                                        'class' => 'form-control',
                                    ]) !!}
                                @else
                                    {!! Form::text('sync_host_name', $value = null, [
                                        'id' => 'sync_host_name',
                                        'placeholder' => 'https://bidhee.com/',
                                        'class' => 'form-control',
                                    ]) !!}
                                @endif
                                <span class="text-danger">{{ $errors->first('sync_host_name') }}</span>
                            </div>
                        </div>
                    </div>

                    @if (isset($setting->flag_organization) && $setting->flag_organization == 1)
                        <div style="margin-left: 25px;">
                            <legend class="text-uppercase font-size-sm font-weight-bold">Sync Organization Setting
                            </legend>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Allow Sync Organization Data:</label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            {!! Form::radio('sync_organization', '1', isset($setting) && $setting->sync_organization, [
                                                'class' => 'form-check-input-styled',
                                                'data-fouc',
                                            ]) !!}
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            {!! Form::radio('sync_organization', '0', isset($setting) && !$setting->sync_organization, [
                                                'class' => 'form-check-input-styled',
                                                'data-fouc',
                                            ]) !!}
                                            No
                                        </label>
                                    </div>
                                    <span class="text-danger">{{ $errors->first('sync_organization') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (isset($setting->flag_employee) && $setting->flag_employee == 1)
                        <div style="margin-left: 25px;">
                            <legend class="text-uppercase font-size-sm font-weight-bold">Employee Sync Setting
                            </legend>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Allow Sync Employee Data:</label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            {!! Form::radio('sync_employee', '1', isset($setting) && $setting->sync_employee, [
                                                'class' => 'form-check-input-styled',
                                                'data-fouc',
                                            ]) !!}
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            {!! Form::radio('sync_employee', '0', isset($setting) && !$setting->sync_employee, [
                                                'class' => 'form-check-input-styled',
                                                'data-fouc',
                                            ]) !!}
                                            No
                                        </label>
                                    </div>

                                    <span class="text-danger">{{ $errors->first('sync_employee') }}</span>

                                </div>
                            </div>
                        </div>
                    @endif


                </fieldset>

                <div class="text-right">
                    <button type="submit" class="ml-2 text-white btn bg-pink btn-labeled btn-labeled-left"><b><i
                                class="icon-database-insert"></i></b>{{ $btnType }} Changes</button>
                </div>

                {!! Form::close() !!}

            </div>
        </div>


    </div>
</div>
