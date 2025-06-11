$(document).ready(function () {
    $("#leaveTypeFormSubmit").validate({
        rules: {
            "organization_id": "required",
            "leave_year_id": "required",
            "name": "required",
            "number_of_days": "required",
            "departmentArray[]": "required",
            "levelArray[]": "required",
            "max_substitute_days":"required"
        },
        messages: {
            "organization_id": "Select Organization",
            "leave_year_id": "Select Leave Year",
            "name": "Enter Title",
            "number_of_days": "Enter Number of Days",
            "departmentArray[]": "Select Department",
            "levelArray[]": "Select Level",
            "max_substitute_days":"Enter Number of Days"
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
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
                $("<div class='form-control-feedback'><i class='icon-cross2 text-danger'></i></div>").insertAfter(element);
            }
        },
        success: function (label, element) {
            console.log(element);
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

    $(".validateUpdateMedicalDetail").validate({
        rules: {
            "medical_problem": "required",
        },
        messages: {
            "medical_problem": "Enter Medical Problem",
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
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
                $("<div class='form-control-feedback'><i class='icon-cross2 text-danger'></i></div>").insertAfter(element);
            }
        },
        success: function (label, element) {
            console.log(element);
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

    $('#leaveTypeFormSubmit').on('submit', function () {
        check_valid = $(this).valid();

        $('#departmentArray-error').remove();
        if ($('#departmentArray').val() == '') {
            $('#departmentArray').parents('.input-group').after('<em id="departmentArray-error" class="error help-block">Select Departments</em>');
            $('button[type=submit]').attr('disabled', false);
            $('button[type=submit]').find('.spinner').remove();
            return false;
        }

        $('#levelArray-error').remove();
        if ($('#levelArray').val() == '') {
            $('#levelArray').parents('.input-group').after('<em id="levelArray-error" class="error help-block">Select Levels</em>');
            $('button[type=submit]').attr('disabled', false);
            $('button[type=submit]').find('.spinner').remove();
            return false;
        }

        // Perform the desired act
        if (check_valid == true) {
            $('.btn-success').attr('disabled', true);
        }
    });

});

