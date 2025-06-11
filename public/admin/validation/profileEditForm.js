$(document).ready(function () {
    $(".profileEditForm").validate({
        rules: {
            first_name: "required",
            last_name: "required",
            permanentaddress: "required",
            personal_email: { email: true },
            mobile: {
                required: true,
                minlength: 10,
                maxlength: 10,
                digits: true,
            },
            phone: {
                minlength: 10,
                maxlength: 10,
                digits: true,
            },
        },
        messages: {
            first_name: "Enter First Name",
            last_name: "Enter Last Name",
            permanentaddress: "Enter Permanent Address",
            personal_email: { email: "Enter valid email address" },
            mobile: {
                required: "Mobile Number is required",
                minlength: "Mobile Number must be 10 digits",
                maxlength: "Mobile Number cannot be more than 10 digits",
                digits: "Mobile Number must be digits",
            },
            phone: {
                minlength: "Phone Number must be 10 digits",
                maxlength: "Phone Number cannot be more than 10 digits",
                digits: "Phone Number must be digits",
            },
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            console.log(element);
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents(".col-lg-9").addClass("form-group-feedback");

            // if (element.prop("type") === "checkbox") {
            //     error.insertAfter(element.parent("label"));
            // } else {
            error.appendTo(element.parent());
            // }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!element.parent().parent().next("div")[0]) {
                $(
                    "<div class='form-control-feedback'><i class='icon-cross2 text-danger'></i></div>"
                ).insertAfter(element);
            }
        },
        success: function (label, element) {
            // console.log(element);
            // Add the span element, if doesn't exists, and apply the icon classes to it.<i class='icon-checkmark4 text-success'></i>
            if (!$(element).next("div")[0]) {
                $("<div class='form-control-feedback'></div>").insertAfter(
                    $(element)
                );
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element)
                .parent()
                .find("span .input-group-text")
                .addClass("alpha-danger text-danger border-danger")
                .removeClass("alpha-success text-success border-success");
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
                .removeClass("border-danger text-danger");
            $(element)
                .next("div .form-control-feedback")
                .find("i")
                .addClass("icon-checkmark4 text-success")
                .removeClass("icon-cross2 text-danger");
        },
    });

    // $('.profileEditForm input[type=text]').on('keyup', function(){
    //     if($(this).val() != null){
    //         $('.profileEditForm ').find('.bg-success').removeClass('d-none');
    //     }
    // });
});
