<div class="row">
    <div class="col-md-12 mb-3">
        <div class="row">

            <div class="col-lg-11 form-group-feedback form-group-feedback-right">
                <!-- Radio Button Section -->
                <div class="mb-3 d-flex">
                    <div class="form-check mr-2">
                        <input type="radio" class="form-check-input" name="option" id="uploadOption" value="upload"
                            {{ isset($employees->resume) ? 'checked' : '' }}>
                        <label class="form-check-label" for="uploadOption">Upload Document</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="option" id="textOption" value="text"
                            {{ isset($employees->job_description) ? 'checked' : '' }}>
                        <label class="form-check-label" for="textOption">Job Description</label>
                    </div>
                </div>

                <!-- File Upload Section -->
                <div class="mb-3" id="fileUploadSection" style="display: none;">
                    {{-- <label for="fileInput" class="form-label">Upload Document</label> --}}
                    <input type="file" class="form-control" name="resume" id="fileInput" accept=".pdf, .doc, .docx">
                    <div class="form-text">Allowed formats: PDF, DOC, DOCX</div>
                </div>

                <div class="input-group" id="textInputSection" style="display: none;">
                    {!! Form::textarea('job_description', $value = null, [
                        'placeholder' => 'Enter Job Description.....',
                        'class' => 'form-control basicTinymce1',
                        'id' => 'editor-full',
                        'rows' => '4',
                        'cols' => '8',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Event listener for radio button changes
        var value = "{{ @$employees->resume }}";
        if (value) {
            $('#fileUploadSection').show();
        } else {
            $('#fileUploadSection').hide();
        }

        $('input[name="option"]').on('change', function() {


            if ($(this).val() === 'upload') {
                $('#fileUploadSection').show();
                $('#textInputSection').hide();
                $('#textInput').val('');
            } else if ($(this).val() === 'text') {
                $('#fileUploadSection').hide();
                $('#textInputSection').show();
                $('#fileInput').val('');
            }
        });

        // Optional: Validate file type on input change
        $('#fileInput').on('change', function() {
            const allowedExtensions = ['pdf', 'doc', 'docx'];
            const file = this.files[0];
            if (file) {
                console.log(file);
                const fileExtension = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(fileExtension)) {
                    alert('Invalid file type. Please upload a PDF, DOC, or DOCX file.');
                    this.value = ''; // Clear the input
                }
            }
        });
    });
</script>
