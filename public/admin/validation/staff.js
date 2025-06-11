$(document).ready(function () {
    $("#staff_submit").validate({
        rules: {
            "client_id": "required",
            "branch_id": "required",
            "first_name": "required",
            "last_name": "required",
            "join_date_nep": "required",
            "dob": "required",
            "mobile_no_1": "required",
            "working_hr": "required",
            "ot_working_hr_rate": "required",
            "bank_name": "required",
            "account_name": "required",
            "account_no": "required",
            "basic_salary": "required",
            "permanentprovince": "required",
            "permanentdistrict": "required",
            "permanentmunicipality_vdc": "required",
            "permanentaddress": "required"
        },
        messages: {
            "client_id": "Select Client Name",
            "branch_id": "Select Branch",
            "first_name": "Enter First Name",
            "last_name": "Enter Last Name",
            "join_date_nep": "Set Join Date",
            "dob": "Set DOB",
            "mobile_no_1": "Enter Mobile No.",
            "working_hr": "Enter Working Hr",
            "ot_working_hr_rate": "Enter OT Working Hr Rate",
            "bank_name": "Enter Bank Name",
            "account_name": "Enter Account Name",
            "account_no": "Enter Account No.",
            "basic_salary": "Enter Basic Salary",
            "permanentprovince": "Select Province",
            "permanentdistrict": "Select District",
            "permanentmunicipality_vdc": "Enter Municipality/VDC",
            "permanentaddress": "Enter Address"
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

