$(document).ready(function(){
    $('#trainingFormSubmit').validate({
        rules: {
            'division_id' : 'required',
            'type' : 'required',
            'title' : 'required',
            'from_date' : 'required',
            'to_date' : 'required',
            'no_of_days' : 'required',
            'location' : 'required',
            'facilitator' : 'required',
            'facilitator_name' : 'required',
            'month[]' : 'required',
            'planned_budget' : 'required',
            // 'actual_expense_incurred' : 'required',
            'no_of_participants' : 'required',
            'no_of_mandays' : 'required',
            'no_of_employee' : 'required',
            'status' : 'required',
            'training_for' : 'required',
            'functional_type' : 'required',
            'fiscal_year_id' : 'required',
            'department_id' : 'required',

        },
        messages: {
            'division_id' : 'Please Select Division',
            'type' : 'Please Select Type',
            'title' : 'Please Enter Title',
            'from_date' : 'Please Choose Start Date',
            'to_date' : 'Please Choose End Date',
            'no_of_days' : 'Please Enter No of Days',
            'location' : 'Please Select Location',
            'facilitator' : 'Please Select Facilitator',
            'facilitator_name' : 'Please enter Facilitator Name',
            'month[]' : 'Please Select Month',
            'planned_budget' : 'Please Enter Planned Budget',
            // 'actual_expense_incurred' : 'Please Enter Expense Incurred',
            'no_of_participants' : 'Please enter No of Participants',
            'no_of_mandays' : 'Please Enter No of Mandays',
            'no_of_employee' : 'Please Enter No of Employees',
            'status' : 'Please Enter Status',
            'training_for' : 'Please select Training For',
            'functional_type' : 'Please select Functional Type',
            'fiscal_year_id' : 'Please select Fiscal year',
            'department_id' : 'Please select Department',

        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // console.log(element);
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
});
