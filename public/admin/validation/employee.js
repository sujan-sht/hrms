$(document).ready(function () {
    var checkUrl = window.location.origin + "/admin/employee";
    var pathUrl = window.location.pathname;

    splitUrl = pathUrl.split("/");


    id = "";
    if (splitUrl[3] == "edit") {
        id = splitUrl[4];
    }

    remoteEmployeeCodeUrl = checkUrl + "/check-employee-code" + "?id=" + id;
    remoteBiometricIdUrl = checkUrl + "/check-biometric-id" + "?id=" + id;
    remotePanCheckUrl = checkUrl + "/check-pan-unique" + "?id=" + id;


    $("#employee_submit").validate({
        rules: {
            employee_code: {
                required: true,
                remote: remoteEmployeeCodeUrl,
            },
            biometric_id: {
                remote: remoteBiometricIdUrl,
            }, pan_no: {
                remote: remotePanCheckUrl,
            },
            first_name: "required",
            last_name: "required",
            "dayoff[]": "required",
            join_date: "required",
            nepali_join_date: "required",
            dob: "required",
            nep_dob: "required",
            gender:"required",
            // phone: "required",
            permanentprovince: "required",
            permanentdistrict: "required",
            permanentmunicipality_vdc: "required",
            permanentward: "required",
            permanentaddress: "required",
            branch_id: "required",
            function_id: "required",
            department_id: "required",
            level_id: "required",
            designation_id: "required",
            job_title: "required",
            // last_approval_user_id: "required",
            // last_claim_approval_user_id: "required",
            // official_email: "required",
            // phone: {
            //     maxlength: 10,
            //     minlength: 10,
            // },
            personal_email: "email",
            // offboard_first_approval: 'required',
            // appraisal_first_approval: 'required',
            // advance_first_approval: 'required',
            // business_trip_last_approval: 'required',

        },
        messages: {
            employee_code: {
                required: "Enter Employee Code",
                remote: "Employee Code Already Taken",
            },
            biometric_id: {
                remote: "Biometric ID Already Taken",
            },
            pan_no: {
                remote: "Pan No Already Exists",
            },
            // "employee_code": "Enter Employee Code",
            first_name: "Enter First Name",
            last_name: "Enter Last Name",
            "dayoff[]": "Select DayOff",
            join_date: "Set Join Date",
            nepali_join_date: "Set Join Date",
            dob: "Set DOB",
            nep_dob: "Set DOB",
            gender: "Select Gender",
            // phone: "Enter CUG No.",
            permanentprovince: "Select Province",
            permanentdistrict: "Select District",
            permanentmunicipality_vdc: "Enter Municipality/VDC",
            permanentward: "Enter Ward",
            permanentaddress: "Enter Address",
            branch_id: "Select Branch",
            function_id: "Select Function",
            department_id: "Select Sub Function",
            level_id: "Select Level",
            designation_id: "Select Designation",
            job_title: "Enter functional title",
            // last_approval_user_id:
            //     "Select Last Approval User for Leave",
            // last_claim_approval_user_id:
            //     "Select Claim and Request Last Approval",
            // official_email: "Enter Official Email",
            // offboard_first_approval: 'Select Offboard First Approval',
            // appraisal_first_approval: 'Select Appraisal First Approval',
            // advance_first_approval: 'Select Advance First Approval',
            // business_trip_last_approval: 'required',
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
                $(
                    "<div class='form-control-feedback'><i class='icon-cross2 text-danger'></i></div>"
                ).insertAfter(element);
            }
        },
        success: function (label, element) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!$(element).next("div")[0]) {
                $(
                    "<div class='form-control-feedback'><i class='icon-checkmark4 text-success'></i></div>"
                ).insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element)
                .parent()
                .find("span .input-group-text")
                .addClass("alpha-danger text-danger border-danger ")
                .removeClass("alpha-success text-success border-success");
            // }
            $(element).addClass("border-danger").removeClass("border-success");
            $(element)
                .parent()
                .parent()
                .addClass("text-danger")
                .removeClass("text-success");
            $(element)
                .next("div .form-control-feedback")
                .find("i")
                .addClass("icon-cross2 text-danger")
                .removeClass("icon-checkmark4 text-success");
        },
        unhighlight: function (element, errorClass, validClass) {
            // console.log(element.name);
            // console.log(element.value);

            // if (element.name == "personal_email" &&  element.value != ''){
            $(element)
                .parent()
                .find("span .input-group-text")
                .addClass("alpha-success text-success border-success")
                .removeClass("alpha-danger text-danger border-danger ");
            $(element).addClass("border-success").removeClass("border-danger");
            // }
            $(element)
                .parent()
                .parent()
                .addClass("text-success")
                .removeClass("border-danger");
            $(element)
                .next("div .form-control-feedback")
                .find("i")
                .addClass("icon-checkmark4 text-success")
                .removeClass("icon-cross2 text-danger");
        },
    });

    $("#employee_submit").on("submit", function () {
        check_valid = $(this).valid();
        if (check_valid == true) {
            $(".btn-success").attr("disabled", true);
        }
    });
});
