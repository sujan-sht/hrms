$(document).ready(function () {
    $("#approvalForm").validate({
        rules: {
            last_approval_user_id: "required",
            last_claim_approval_user_id: "required",
            official_email: "required",
            offboard_first_approval: "required",
            appraisal_first_approval: "required",
            advance_first_approval: "required",
        },
        messages: {
            last_approval_user_id:
                "Select Last Approval Employee for Leave <br> Note: Please Select First Approval first",
            last_claim_approval_user_id:
                "Select Claim and Request Last Approval",
            offboard_first_approval: "Select Offboard First Approval",
            appraisal_first_approval: "Select Appraisal First Approval",
            advance_first_approval: "Select Advance First Approval",
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
