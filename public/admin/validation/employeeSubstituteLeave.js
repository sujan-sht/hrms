$(document).ready(function () {
    $("#employeeSubstituteLeaveFormSubmit").validate({
        rules: {
            employee_id: "required",
            date: "required",
            leave_kind: "required",
            remark: "required",
        },
        messages: {
            employee_id: "Employee is required",
            date: "Date is required",
            leave_kind: "Leave Category is required",
            remark: "Remark is required",
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // console.log(element);
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents(".col-lg-9").addClass("form-group-feedback");

            if (
                element.prop("type") === "checkbox" ||
                element.prop("type") === "radio"
            ) {
                error.insertAfter(element.parents(".input-group"));
                // console.log(element.parents());
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
            // console.log(element);
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
            $(element).addClass("border-danger").removeClass("border-success");

            if (element.type === "radio") {
                $(element)
                    .parents('.form-group-feedback')
                    .addClass("text-danger")
                    .removeClass("text-success");
            } else {
                $(element)
                    .parent()
                    .parent()
                    .addClass("text-danger")
                    .removeClass("text-success");
            }

            $(element)
                .next("div .form-control-feedback")
                .find("i")
                .addClass("icon-cross2 text-danger")
                .removeClass("icon-checkmark4 text-success");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element)
                .parent()
                .find("span .input-group-text")
                .addClass("alpha-success text-success border-success")
                .removeClass("alpha-danger text-danger border-danger ");
            $(element).addClass("border-success").removeClass("border-danger");
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
});
