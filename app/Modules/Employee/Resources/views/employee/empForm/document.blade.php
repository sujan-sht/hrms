<div class="form-group row">

    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-4">
                <span class="form-text text-muted">Profile Pic:</span>
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-image2"></i></span>
                    </span>
                    {!! Form::file('profilepic', ['id' => 'profile_pic', 'class' => 'form-control']) !!}

                </div>
            </div>

            <div class="col-md-4">
                <span class="form-text text-muted">Citizenship Pic:</span>
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-image2"></i></span>
                    </span>
                    {!! Form::file('citizenpic', ['id' => 'citizen_pic', 'class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-md-4">
                <span class="form-text text-muted">CV Uploads:</span>
                <div class="input-group">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-profile"></i></span>
                        </span>
                        {!! Form::file('documentpic', ['id' => 'document_pic', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 form-group-feedback form-group-feedback-right" id="new_profile_pic">
                @if ($is_edit and $employees->profile_pic !== null)
                    @php  $ext = pathinfo(asset($employees->file_full_path).'/'.$employees->profile_pic, PATHINFO_EXTENSION); @endphp
                    @if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'bmp' || $ext == 'gif' || $ext == 'png')
                        <a href="{{ asset($employees->file_full_path) }}/profile_pic/{{ $employees->profile_pic }}"
                            target="_blank">
                            <img id="profile_picture"
                                src="{{ asset($employees->file_full_path) }}/profile_pic/{{ $employees->profile_pic }}"
                                alt="your image" class="preview-image" style="height: 100px;width: 85px;" />
                        </a>
                        @if ($menuRoles->assignedRoles('employee.deleteProfilePic'))
                            <a href="javascript:void(0)" class="delete-image" data-id="{{ $employees->id }}">
                                <i class="icon icon-cross3 text-danger" style="top:-41px"></i>
                            </a>
                            <div class="append-image"></div>
                        @endif
                    @else
                        <a href="{{ asset($employees->file_full_path) }}/profile_pic/{{ $employees->profile_pic }}"
                            target="_blank"><i class="icon-file-pdf"></i> {{ $employees->profile_pic }}</a>
                    @endif
                @else
                    <img id="profile_picture" src="{{ asset('admin/default.png') }}" alt="your image"
                        class="preview-image" style="height: 100px; width: 85px;" />
                @endif
            </div>

            <div class="col-lg-4 form-group-feedback form-group-feedback-right" id="new_citizen_pic">
                @if ($is_edit and $employees->citizen_pic !== null)
                    @php  $ext = pathinfo(asset($employees->file_full_path).'/citizen/'.$employees->citizen_pic, PATHINFO_EXTENSION); @endphp
                    @if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'bmp' || $ext == 'gif' || $ext == 'png')
                        <a href="{{ asset($employees->file_full_path) }}/citizen/{{ $employees->citizen_pic }}"
                            target="_blank">
                            <img id="citizen_picture"
                                src="{{ asset($employees->file_full_path) }}/citizen/{{ $employees->citizen_pic }}"
                                alt="your image" class="preview-image" style="height: 100px;width: 85px;" />
                        </a>
                    @else
                        <a href="{{ asset($employees->file_full_path) }}/citizen/{{ $employees->citizen_pic }}"
                            target="_blank"><i class="icon-file-pdf"></i> {{ $employees->citizen_pic }}</a>
                    @endif
                @else
                    <img id="citizen_picture" src="{{ asset('admin/citizen.jpg') }}" alt="your image"
                        class="mt-1 preview-image" style="height: 100px; width: 150px;" />
                @endif
            </div>

            <div class="col-lg-4 form-group-feedback form-group-feedback-right" id="new_document_pic">
                @if ($is_edit and $employees->document_pic !== null)
                    @php  $ext = pathinfo(asset($employees->file_full_path).'/document/'.$employees->document_pic, PATHINFO_EXTENSION); @endphp
                    @if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'bmp' || $ext == 'gif' || $ext == 'png')
                        <a href="{{ asset($employees->file_full_path) }}/document/{{ $employees->document_pic }}"
                            target="_blank">
                            <img id="docs"
                                src="{{ asset($employees->file_full_path) }}/document/{{ $employees->document_pic }}"
                                alt="your image" class="preview-image" style="height: 100px;width: 85px;" />
                        </a>
                    @else
                        <a href="{{ asset($employees->file_full_path) }}/document/{{ $employees->document_pic }}"
                            target="_blank"><i class="icon-file-pdf"></i> {{ $employees->document_pic }}</a>
                    @endif
                @else
                    <img id="docs" src="{{ asset('admin/pdf.jpg') }}" alt="your image" class="preview-image"
                        style="height: 100px; width: 85px;" />
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <span class="form-text text-muted">Main Signature:</span>
                <div class="input-group">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-profile"></i></span>
                        </span>
                        {!! Form::file('signature', ['id' => 'signature_pic', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <span class="form-text text-muted">Initial Signature:</span>
                <div class="input-group">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-profile"></i></span>
                        </span>
                        {!! Form::file('initial_signature', [
                            'id' => 'initial_signature',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 form-group-feedback form-group-feedback-right" id="new_signature_pic">
                @if ($is_edit and $employees->signature !== null)
                    @php  $ext = pathinfo(asset($employees->file_full_path).'/signature/'.$employees->signature, PATHINFO_EXTENSION); @endphp
                    @if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'bmp' || $ext == 'gif' || $ext == 'png')
                        <a href="{{ asset($employees->file_full_path) }}/signature/{{ $employees->signature }}"
                            target="_blank">
                            <img id="signatureId"
                                src="{{ asset($employees->file_full_path) }}/signature/{{ $employees->signature }}"
                                alt="your image" class="preview-image" style="height: 100px;width: 85px;" />
                        </a>
                    @else
                        <a href="{{ asset($employees->file_full_path) }}/signature/{{ $employees->signature }}"
                            target="_blank"><i class="icon-file-pdf"></i> {{ $employees->signature }}</a>
                    @endif
                @else
                    <img id="signatureId" src="{{ asset('admin/signature.png') }}" alt="your image"
                        class="preview-image pt-3" style="height: 100px;" />
                @endif
            </div>

            <div class="col-lg-6 form-group-feedback form-group-feedback-right" id="initial_signature">
                @if ($is_edit and $employees->initial_signature !== null)
                    @php  $ext = pathinfo(asset($employees->file_full_path).'/signature/'.$employees->initial_signature, PATHINFO_EXTENSION); @endphp
                    @if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'bmp' || $ext == 'gif' || $ext == 'png')
                        <a href="{{ asset($employees->file_full_path) }}/signature/{{ $employees->initial_signature }}"
                            target="_blank">
                            <img id="initialSignatureId"
                                src="{{ asset($employees->file_full_path) }}/signature/{{ $employees->initial_signature }}"
                                alt="your image" class="preview-initial-image" style="height: 100px;width: 85px;" />
                        </a>
                    @else
                        <a href="{{ asset($employees->file_full_path) }}/signature/{{ $employees->initial_signature }}"
                            target="_blank"><i class="icon-file-pdf"></i> {{ $employees->initial_signature }}</a>
                    @endif
                @else
                    <img id="initialSignatureId" src="{{ asset('admin/signature.png') }}" alt="your image"
                        class="preview-image pt-3" style="height: 100px;" />
                @endif
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        document.getElementById("profile_pic").onchange = function() {
            var ext = profile_pic.value.split('.')[1];

            var reader = new FileReader();
            reader.onload = function(e) {
                // get loaded data and render thumbnail.
                if (ext == "jpg" || ext == "jpeg" || ext == "bmp" || ext == "gif" || ext == "png") {
                    document.getElementById("profile_picture").src = e.target.result;
                    console.log(e);
                } else {
                    alert('Please choose image file');
                }
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        };

        document.getElementById("citizen_pic").onchange = function() {
            var ext = citizen_pic.value.split('.')[1];

            var reader = new FileReader();
            reader.onload = function(e) {
                // get loaded data and render thumbnail.
                if (ext == "jpg" || ext == "jpeg" || ext == "bmp" || ext == "gif" || ext == "png") {
                    document.getElementById("citizen_picture").src = e.target.result;
                } else {
                    $("#new_citizen_pic").html('<a download="' + getFile(citizen_pic.value) +
                        '" href="' + e.target.result + '" target="_blank" title="download">' +
                        getFile(citizen_pic.value) + '</a>');
                }
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        };

        document.getElementById("document_pic").onchange = function() {
            var ext = document_pic.value.split('.')[1];

            var reader = new FileReader();
            reader.onload = function(e) {
                // get loaded data and render thumbnail.
                if (ext == "jpg" || ext == "jpeg" || ext == "bmp" || ext == "gif" || ext == "png") {
                    document.getElementById("docs").src = e.target.result;
                } else {
                    $("#new_document_pic").html('<a download="' + getFile(document_pic.value) +
                        '" href="' + e.target.result + '" target="_blank" title="download">' +
                        getFile(document_pic.value) + '</a>');
                }
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        };

        document.getElementById("signature_pic").onchange = function() {
            var ext = signature_pic.value.split('.')[1];


            var reader = new FileReader();
            reader.onload = function(e) {
                // get loaded data and render thumbnail.
                if (ext == "jpg" || ext == "jpeg" || ext == "bmp" || ext == "gif" || ext == "png") {
                    document.getElementById("signatureId").src = e.target.result;
                } else {
                    $("#new_signature_pic").html('<a download="' + getFile(signature_pic.value) +
                        '" href="' + e.target.result + '" target="_blank" title="download">' +
                        getFile(signature_pic.value) + '</a>');
                }
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        };


        document.getElementById("initial_signature").onchange = function() {
            var ext = initial_signature.value.split('.')[1];
            var reader = new FileReader();
            reader.onload = function(e) {
                // get loaded data and render thumbnail.
                if (ext == "jpg" || ext == "jpeg" || ext == "bmp" || ext == "gif" || ext == "png") {
                    document.getElementById("initialSignatureId").src = e.target.result;
                } else {
                    $("#initial_signature").html('<a download="' + getFile(initial_signature.value) +
                        '" href="' + e.target.result + '" target="_blank" title="download">' +
                        getFile(initial_signature.value) + '</a>');
                }
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        };

    })

    function getFile(filePath) {
        return filePath.substr(filePath.lastIndexOf('\\') + 1);
    }

    $(document).on('click', '.delete-image', function() {
        var imageId = $(this).data('id');
        if (confirm('Are you sure you want to delete this image?')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('employee.deleteProfilePic', ['id' => '__id__']) }}".replace('__id__',
                    imageId),
                type: 'DELETE',
                success: function(response) {

                    $('#profile_picture').remove();
                    $('.delete-image').remove();

                    $('.append-image').empty();

                    var newImage = $('<img>').attr({
                        'src': '{{ asset('admin/default.png') }}',
                        'alt': 'Custom Image'
                    }).css({
                        'width': '85px', // Adjust width as needed
                        'height': '100px' // Adjust height as needed
                    });
                    $('.append-image').append(newImage);

                    toastr.success("Profile Image Delete")
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
    });
</script>
