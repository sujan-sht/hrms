<div id="previous_leave_detail_import" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title text-white ">Import Employee Previous Leave Details</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="bd-import">
                    <div class="row">
                        <div class="col-md-4">
                            <h4>Upload Previous Leave Data Sheet</h4>
                            <form method="POST" action="{{ route('leave.storePreviousLeaveDetails') }}"
                                accept-charset="UTF-8" class="form-horizontal" role="form"
                                enctype="multipart/form-data" id="previous-leave-detail">
                                @csrf
                                <div class="position-relative">
                                    <input type="file" class="form-control h-auto"
                                        name="upload_previous_leave_details">
                                </div>
                                <span class="form-text text-muted">Accepted formats: xls, xlsx</span>
                                <button type="submit"
                                    class="text-light btn bg-primary btn-labeled btn-labeled-left"><b><i
                                            class="icon-upload"></i></b>Upload</button>
                            </form>
                            <div class="mt-3 form-group row list-items-producds/employee_sample/sample_employee.xt alert alert-success"
                                style="border: dashed;border-radius: 25px;border-width: thin;padding: 7px;">
                                <p class="mt-1"><b>Note:</b> Please make sure that Before Uploading Employee Leave
                                    DataSheet, Please Make Sure, You have Correct and Accurate Employee Leave Data
                                    Format as Similar to Sample Employee Leave DataSheet. Data may not be uploaded if
                                    missed Any Required Data.</p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            {{-- <div class="card mb-0 text-light bg-secondary">
                                <div class="8ard-body text-center">
                                    <i class="icon-file-spreadsheet icon-2x border-3 rounded-round p-3 mb-3 mt-1"></i>
                                    <h6>Get Employee Previous Leave Details Sample Sheet</h6>
                                    <p>
                                        Before Uploading Employee Leave Data - Please make sure, You have correct Employee Leave Data Format as similar to Sample Employee Leave Data.
                                    </p>
                                     <a href="{{asset('samples/previousLeaveDetails.xlsx')}}" target="_blank" class="text-light btn bg-primary btn-labeled btn-labeled-left"><b><i class="icon-download4"></i></b>Download</a>

                                </div>
                            </div> --}}

                            <div class="card">
                                <div class="card-body">
                                    <h4>Create Importer</h4>
                                    <hr>

                                    {!! Form::open([
                                        'route' => 'leave.postImportFile',
                                        'method' => 'POST',
                                        'class' => 'form-horizontal importForm',
                                        'role' => 'form',
                                    ]) !!}
                                    <div class="form-group">
                                        <div class="row mb-1">
                                            <label class="col-form-label col-lg-3">Select Organization: <span
                                                    class="text-danger">*</span></label>

                                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                                {!! Form::select('organization_id', $organizationList, $value = request('organization_id') ?: null, [
                                                    'placeholder' => 'Select Organization',
                                                    'class' => 'form-control select-search organization-upload-filter',
                                                    'required',
                                                ]) !!}
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col-form-label col-lg-3">Select Leave Type: <span
                                                    class="text-danger">*</span></label>

                                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                                {!! Form::select('leave_type_id[]', [], $value = request('leave_type_id') ?: null, [
                                                    'class' => 'form-control leaveMultiSelect',
                                                    'id' => 'leave_type_id',
                                                    'multiple' => 'multiple',
                                                    'data-fouc',
                                                    'required',
                                                ]) !!}

                                            </div>
                                        </div>
                                    </div>


                                    <div class="d-flex justify-content-end mt-2">
                                        <button class="btn bg-success mr-2 text-white" type="submit">
                                            <i class="icon-download mr-1"></i>Create
                                        </button>

                                    </div>
                                    {!! Form::close() !!}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn bg-primary" data-dismiss="modal">Close</button>
            </div> --}}
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#previous-leave-detail').validate({
            rules: {
                upload_previous_leave_details: 'required'
            },
            messages: {
                upload_previous_leave_details: "Please Select A File."
            },
            errorElement: "em",
            errorPlacement: function(error, element) {
                console.log(element)
                // Add the `help-block` class to the error element
                error.addClass("help-block");

                // Add `has-feedback` class to the parent div.form-group
                // in order to add icons to inputs
                element.parents(".col-lg-9").addClass("form-group-feedback");

                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element.parent());
                }

                // Add the span element, if doesn't exists, and apply the icon classes to it.
                if (!element.parent().parent().next("div")[0]) {
                    $("<div class='form-control-feedback'><i class='icon-cross2 text-danger'></i></div>")
                        .insertAfter(element);
                }
            },
            success: function(label, element) {
                console.log(element);
                // Add the span element, if doesn't exists, and apply the icon classes to it.
                if (!$(element).next("div")[0]) {
                    $("<div class='form-control-feedback'><i class='icon-checkmark4 text-success'></i></div>")
                        .insertAfter($(element));
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).parent().find('span .input-group-text').addClass(
                    "alpha-danger text-danger border-danger ").removeClass(
                    "alpha-success text-success border-success");
                $(element).addClass("border-danger").removeClass("border-success");
                $(element).parent().parent().addClass("text-danger").removeClass("text-success");
                $(element).next('div .form-control-feedback').find('i').addClass(
                    "icon-cross2 text-danger").removeClass("icon-checkmark4 text-success");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parent().find('span .input-group-text').addClass(
                    "alpha-success text-success border-success").removeClass(
                    "alpha-danger text-danger border-danger ");
                $(element).addClass("border-success").removeClass("border-danger");
                $(element).parent().parent().addClass("text-success").removeClass("border-danger");
                $(element).next('div .form-control-feedback').find('i').addClass(
                    "icon-checkmark4 text-success").removeClass("icon-cross2 text-danger");
            }
        });

        $('.organization-upload-filter').on('change', function() {
            var organizationId = $('.organization-upload-filter').val();
            $('.leaveMultiSelect').empty();
            $('.leaveMultiSelect').multiselect('destroy');

            $.ajax({
                type: 'GET',
                url: '/admin/organization/get-leave-types',
                data: {
                    organization_id: organizationId,
                },
                success: function(data) {
                    var list = JSON.parse(data);
                    var options = '';

                    $.each(list, function(id, value) {
                        options += "<option value='" + id + "'>" + value +
                            "</option>";
                    });
                    $('.leaveMultiSelect').html(options);
                    $('.leaveMultiSelect').multiselect({
                        nonSelectedText: 'Select Leave Type'
                    });

                }
            });

        })

        customValidation('importForm');

        $('.importForm').on('submit', function(e) {
            if ($(this).valid()) {
                $(this).find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                $('#previous_leave_detail_import').modal('hide');
                // $('#importForm')[0].reset();
            }
        })



    });
</script>
