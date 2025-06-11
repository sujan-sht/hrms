<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('admin/validation/organization.js') }}"></script>

<fieldset class="mb-3">

    <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

    <div class="form-group row">
        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-3">Logo:</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-image2"></i></span>
                        </span>
                        <input class="form-control" name="image" type="file">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 ml-2" id="new_client_logo" style="margin-bottom: -10px;">
            @if ($isEdit and $organizationModel->image !== null)
                <img src="{{ $organizationModel->getImage() }}" alt="your image" class="preview-image"
                    style="height: 100px;width: 85px;" />
            @else
                <img src="{{ asset('admin/clientLogo.png') }}" alt="Client Logo" class="preview-image"
                    style="height: 66px; width: 85px;">
            @endif
        </div>
    </div>

    <div class="form-group row">
        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-3">Name:<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-vcard"></i></span>
                        </span>
                        {!! Form::text('name', null, ['id' => 'name', 'placeholder' => 'Enter Name', 'class' => 'form-control']) !!}
                    </div>
                    @if ($errors->has('name'))
                        <div class="error text-danger">{{ $errors->first('name') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-3">Contact:</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-notebook"></i></span>
                        </span>
                        {!! Form::text('contact', null, [
                            'id' => 'contact_person',
                            'placeholder' => 'Enter Contact',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-3">Code:<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-vcard"></i></span>
                        </span>
                        {!! Form::text('organisation_code', null, ['id' => 'organisation_code', 'placeholder' => 'Enter Organization code', 'class' => 'form-control','required']) !!}
                    </div>
                    @if ($errors->has('organisation_code'))
                        <div class="error text-danger">{{ $errors->first('organisation_code') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-3">Address:<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-location4"></i></span>
                        </span>
                        {!! Form::text('address', null, [
                            'id' => 'contact_no',
                            'placeholder' => 'Enter Address',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                    @if ($errors->has('address'))
                        <div class="error text-danger">{{ $errors->first('address') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-3">Mobile:</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-mobile"></i></span>
                        </span>
                        {!! Form::text('mobile', null, ['placeholder' => 'Enter Mobile', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-3">Email:</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-envelop4"></i></span>
                        </span>
                        {!! Form::text('email', null, ['placeholder' => 'Enter Email', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-3">Fax:</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-mailbox"></i></span>
                        </span>
                        {!! Form::text('fax', null, ['placeholder' => 'Enter Fax', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <legend class="text-uppercase font-size-sm font-weight-bold">Other Detail</legend>

    <div class="form-group row">
        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-3">Letter Head:</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-image2"></i></span>
                        </span>
                        <input class="form-control" name="letter_head" type="file">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 ml-2" id="new_client_logo" style="margin-bottom: -10px;">
            @if ($isEdit and $organizationModel->letter_head !== null)
                <img src="{{ $organizationModel->getLetterHeadImage() }}" alt="your image" class="preview-image"
                    style="height: 100px;width: 85px;" />
            @else
                <img src="{{ asset('admin/clientLogo.png') }}" alt="Client Logo" class="preview-image"
                    style="height: 66px; width: 85px;">
            @endif
        </div>
    </div>

    <div class="form-group row">
        <div class="col-lg-12">
            <div class="row">
                <label class="col-form-label col-lg-12">Vision :</label>
                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::textarea('vision', null, ['placeholder' => 'Enter Vision', 'class' => 'form-control', 'rows' => 4]) !!}
                    </div>
                    @if ($errors->has('vision'))
                        <div class="error text-danger">{{ $errors->first('vision') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-12">
            <div class="row">
                <label class="col-form-label col-lg-12">Mission :</label>
                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::textarea('mission', null, ['placeholder' => 'Enter Mission', 'class' => 'form-control', 'rows' => 4]) !!}
                    </div>
                    @if ($errors->has('mission'))
                        <div class="error text-danger">{{ $errors->first('mission') }}</div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <legend class="text-uppercase font-size-sm font-weight-bold">Code of conduct</legend>

    <div class="form-group row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::textarea('code_of_conduct', null, [
                            'placeholder' => 'Enter Code of Conduct',
                            'class' => 'form-control basicTinymce1',
                            'id' => 'editor-full',
                        ]) !!}
                    </div>
                    @if ($errors->has('code_of_conduct'))
                        <div class="error text-danger">{{ $errors->first('code_of_conduct') }}</div>
                    @endif
                </div>
            </div>
        </div>

    </div>


    <div class="text-right">
        <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i
                    class="icon-database-insert"></i></b>{{ $btnType }}</button>
    </div>

    @section('script')
        <script src="{{ asset('admin/global/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
        <script src="{{ asset('admin/global/js/demo_pages/editor_ckeditor_default.js') }}"></script>
        {{-- <script src="https://cdn.tiny.cloud/1/cjrqkjizx7e1ld0p8kcygaj4cvzc6drni6o4xl298c5hl9l1/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        $(document).ready(function() {
            tinymce.init({
                selector: 'textarea.basicTinymce',
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                height: '500',
                width: '100%'
            });
        });
    </script> --}}
    @endsection

    <script type="text/javascript">
        function getFile(filePath) {
            return filePath.substr(filePath.lastIndexOf('\\') + 1);
        }

        $(document).ready(function() {

            document.getElementById("client_logo").onchange = function() {
                var ext = client_logo.value.split('.')[1];

                var reader = new FileReader();
                reader.onload = function(e) {
                    // get loaded data and render thumbnail.
                    if (ext == "jpg" || ext == "jpeg" || ext == "bmp" || ext == "gif" || ext == "png") {
                        document.getElementById("client_logo_picture").src = e.target.result;
                    } else {
                        alert('Please choose image file');
                    }
                };

                // read the image file as a data URL.
                reader.readAsDataURL(this.files[0]);
            };

            $('#has_branch').on('change', function() {
                var has_branch = $(this).val();

                if (has_branch == '1') {
                    $('.has_branc_client').show();
                } else {
                    $('.has_branc_client').hide();
                }
            });

            $('.add_contact').on('click', function() {
                $.ajax({
                    type: 'GET',
                    url: '/admin/client/appendContact',
                    success: function(data) {
                        $('.appendcontact').last().append(data.options);
                        $('.select-search').select2();
                        $('.numeric').keyup(function() {
                            if (this.value.match(/[^0-9.]/g)) {
                                this.value = this.value.replace(/[^0-9.]/g, '');
                            }
                        });
                    }
                });
            });
        });
    </script>
