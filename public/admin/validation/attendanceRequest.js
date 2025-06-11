$(document).ready(function(){
    $('#attendanceFormSubmit').validate({
        rules: {
            'employee_id[]': {
                required: true,
                minlength: 1
            },
            'start_date': 'required',
            'end_date': 'required',
            'type': 'required',
            'time': 'required',
            'detail': 'required',
            'kind': 'required'
        },
        messages: {
            'employee_id[]': {
                required: 'At least one employee is required',
                minlength: 'Please select at least one employee'
            },
            'start_date': 'Date is required',
            'end_date': 'Date is required',
            'type': 'Type is required',
            'time': 'Time is required',
            'detail': 'Detail is required',
            'kind': 'Kind is required',
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            error.addClass("help-block");
            element.parents(".col-lg-9").addClass("form-group-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element.parent());
            }

            if (!element.parent().parent().next("div")[0]) {
                $("<div class='form-control-feedback'><i class='icon-cross2 text-danger'></i></div>").insertAfter(element);
            }
        },
        success: function (label, element) {
            if (!$(element).next("div")[0]) {
                $("<div class='form-control-feedback'><i class='icon-checkmark4 text-success'></i></div>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parent().find('span .input-group-text').addClass("alpha-danger text-danger border-danger").removeClass("alpha-success text-success border-success");
            $(element).addClass("border-danger").removeClass("border-success");
            $(element).parent().parent().addClass("text-danger").removeClass("text-success");
            $(element).next('div .form-control-feedback').find('i').addClass("icon-cross2 text-danger").removeClass("icon-checkmark4 text-success");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parent().find('span .input-group-text').addClass("alpha-success text-success border-success").removeClass("alpha-danger text-danger border-danger");
            $(element).addClass("border-success").removeClass("border-danger");
            $(element).parent().parent().addClass("text-success").removeClass("border-danger");
            $(element).next('div .form-control-feedback').find('i').addClass("icon-checkmark4 text-success").removeClass("icon-cross2 text-danger");
        }
    });
});
