$(document).ready(function () {
    $(".validatePreviousJobDetail").validate({
        rules: {
            "company_name": "required",
            "address": "required",
            "from_date": "required",
            "to_date": "required",
            "job_title": "required",
            "designation_on_joining": "required",
            "designation_on_leaving": "required",
            "industry_type": "required",
            "break_in_career": "required",
            "reason_for_leaving": "required",
            "role_key": "required",
        },
        messages: {
            "company_name": "Enter Company Name",
            "address": "Enter Address",
            "from_date": "Choose Date",
            "to_date": "Choose Date",
            "job_title": "Enter Functional Title",
            "designation_on_joining": "Enter Designation On Joining",
            "designation_on_leaving": "Enter Designation On Leaving",
            "industry_type": "Enter Industry Type",
            "break_in_career": "Enter Break In Career",
            "reason_for_leaving": "Enter Reason For Leaving",
            "role_key": "Enter Role Key",
        },
        errorElement: "em",
        errorPlacement: function (error, element) {  console.log(element)
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
        success: function (label, element) { console.log(element);
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
});

