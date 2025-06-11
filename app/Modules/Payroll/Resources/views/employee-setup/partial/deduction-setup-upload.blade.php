<div id="modal_default_import" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-grey">
                <h5 class="modal-title font-weight-black ">Import Employee Deductions</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="bd-import">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Upload Deduction salary Data Sheet</h4>
                               <form method="POST" action="{{ route('payroll.uploadEmployeeDeduction') }}" accept-charset="UTF-8" class="form-horizontal" role="form" enctype="multipart/form-data" id="gross-salary-form">
                                    @csrf
                                    <div class="position-relative">
                                        <input type="file" class="form-control h-auto" name="upload_employee_deduction_setup">
                                    </div>
                                    <span class="form-text text-muted">Accepted formats: xls, xlsx</span>
                                    <button type="submit" class="text-light btn bg-primary btn-labeled btn-labeled-left"><b><i class="icon-upload"></i></b>Upload</button>
                                </form>
                                <div class="mt-3 form-group row list-items-producds/employee_sample/sample_employee.xt alert alert-success" style="border: dashed;border-radius: 25px;border-width: thin;padding: 7px;">
                                    <p class="mt-1"><b>Note:</b> Please make sure, You have correct Employee Deduction Data Format as similar to Sample Employee Deduction DataSheet. Data may not be uploaded if missed Any Required Data.</p>
                                </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-0 text-light bg-secondary">
                                <div class="card-body text-center">
                                    <i class="icon-file-spreadsheet icon-2x border-3 rounded-round p-3 mb-3 mt-1"></i>
                                    <h6>Get Employee Deduction Data Sample Sheet</h6>
                                    <p>
                                        Before Uploading Employee Deduction Data - Please make sure, You have correct Employee Deduction Data Format as similar to Sample Employee Deduction Data.
                                    </p>
                                     {{-- <a href="{{asset('samples/Employee_Deduction_Setup_Sample.xlsx')}}" target="_blank" class="text-light btn bg-primary btn-labeled btn-labeled-left"><b><i class="icon-download4"></i></b>Download</a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
//  $(document).ready(function () {
//     $('#employee-form').validate({
//         rules: {
//             upload_employee: 'required'
//         },
//         messages: {
//             upload_employee: "Please Select A File."
//         },
//         errorElement: "em",
//         errorPlacement: function (error, element) {  console.log(element)
//             // Add the `help-block` class to the error element
//             error.addClass("help-block");

//             // Add `has-feedback` class to the parent div.form-group
//             // in order to add icons to inputs
//             element.parents(".col-lg-9").addClass("form-group-feedback");

//             if (element.prop("type") === "checkbox") {
//                 error.insertAfter(element.parent("label"));
//             } else {
//                 error.insertAfter(element.parent());
//             }

//             // Add the span element, if doesn't exists, and apply the icon classes to it.
//             if (!element.parent().parent().next("div")[0]) {
//                 $("<div class='form-control-feedback'><i class='icon-cross2 text-danger'></i></div>").insertAfter(element);
//             }
//         },
//         success: function (label, element) { console.log(element);
//             // Add the span element, if doesn't exists, and apply the icon classes to it.
//             if (!$(element).next("div")[0]) {
//                 $("<div class='form-control-feedback'><i class='icon-checkmark4 text-success'></i></div>").insertAfter($(element));
//             }
//         },
//         highlight: function (element, errorClass, validClass) {
//             $(element).parent().find('span .input-group-text').addClass("alpha-danger text-danger border-danger ").removeClass("alpha-success text-success border-success");
//             $(element).addClass("border-danger").removeClass("border-success");
//             $(element).parent().parent().addClass("text-danger").removeClass("text-success");
//             $(element).next('div .form-control-feedback').find('i').addClass("icon-cross2 text-danger").removeClass("icon-checkmark4 text-success");
//         },
//         unhighlight: function (element, errorClass, validClass) {
//             $(element).parent().find('span .input-group-text').addClass("alpha-success text-success border-success").removeClass("alpha-danger text-danger border-danger ");
//             $(element).addClass("border-success").removeClass("border-danger");
//             $(element).parent().parent().addClass("text-success").removeClass("border-danger");
//             $(element).next('div .form-control-feedback').find('i').addClass("icon-checkmark4 text-success").removeClass("icon-cross2 text-danger");
//         }
//     });
// });
</script>
