$(document).ready(function () {
    $("#applicantFormSubmit").validate({
        rules: {
            "manpower_requisition_form_id": "required",
            "first_name": "required",
            "last_name": "required",
            "mobile": "required",
            // "academic_qualification":"required",
            // "current_organization": "required",
            // "current_designation": "required",
            // "reference_name": "required",
            // "reference_position": "required",
            // "reference_contact_number": "required"
        },
        messages: {
            "manpower_requisition_form_id": "Select MRF",
            "first_name": "Enter First Name",
            "last_name": "Enter Last Name",
            "mobile": "Enter Mobile Number",
            // "academic_qualification": "Enter Qualification",
            // "current_organization": "Enter Organization",
            // "current_designation": "Enter Designation",
            // "reference_name": "Enter Name",
            // "reference_position": "Enter Position",
            // "reference_contact_number": "Enter Contact Number"
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // console.log(element)
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
                $("<div class='form-control-feedback'><i class='icon-cross2 text-danger'></i></div>").insertAfter(element);
            }
        },
        success: function (label, element) {
            // console.log(element);
            // Add the span element, if doesn't exists, and apply the icon classes to it.
                if (!$(element).next("div")[0]) {
                $("<div class='form-control-feedback'><i class='icon-checkmark4 text-success'></i></div>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parent().find('span .input-group-text').addClass("alpha-danger text-danger border-danger ").removeClass("alpha-success text-success border-success");
            $(element).addClass("border-danger").removeClass("border-success");
            $(element).parent().parent().addClass("text-danger").removeClass("text-success");
            $(element).next('div .form-control-feedback').find('i').addClass("icon-cross2 text-danger").removeClass("icon-checkmark4 text-success");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parent().find('span .input-group-text').addClass("alpha-success text-success border-success").removeClass("alpha-danger text-danger border-danger ");
            $(element).addClass("border-success").removeClass("border-danger");
            $(element).parent().parent().addClass("text-success").removeClass("border-danger");
            $(element).next('div .form-control-feedback').find('i').addClass("icon-checkmark4 text-success").removeClass("icon-cross2 text-danger");
        }
    });


    $('#leaveFormSubmit').on('submit', function(){
        check_valid= $(this).valid();
        if(check_valid == true){
            $('.btn-success').attr('disabled', true);
        }
    });


});

