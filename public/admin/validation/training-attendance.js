$(document).ready(function(){
    $('#trainingAttendanceFormSubmit').validate({
        rules: {
            'employee_id' : 'required',
            'contact_no' : 'required',
            'email' : 'required',
            'remarks' : 'required',
            'feedback' : 'required',
            'marks_obtained' : 'required'
        },
        messages: {
            'employee_id' : 'Employee name is required',
            'contact_no' : 'Contact number is required',
            'email' : 'Email is required',
            'remarks' : 'Remarks is required',
            'feedback' : 'Feedback is required',
            'marks_obtained' : 'Marks Obtained is required',
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

    //Fetch Participant Data
    $('#isParticipantId').on('change', function () {
        var is_participant = $(this).val();
        if(is_participant == '1'){
            $('.show_participant').show();
            $('.employeeDiv').hide();

            $('#participant_data').on('change', function () {
                var participant_id = $(this).val();
                emp_id =$(this).find(":selected").attr('data-employee')
                $('#employeeId').val(emp_id);
                console.log(emp_id);
                fetchParticipantData(emp_id);
            });
        }else{
            $('.show_participant').hide();
            $('.employeeDiv').show();
            $('#participant_name').val('');
            $('#contact_no').val('');
            $('#email').val('');
            $('#remarks').val('');
        }
    });

    function fetchParticipantData(participant_id) {
        var training_id = $('#training').val();
        $.ajax({
            type: 'GET',
            url: "/training/"+{training_id}+"/training-attendance/ParticipantData",
            data: {
                participant_id: participant_id
            },
            success:function (data) {
                var participant_details = JSON.parse(data);
                // $('#participant_name').val(participant_details.participant_name);
                $('#contact_no').val(participant_details.contact_no);
                $('#email').val(participant_details.email);
                $('#remarks').val(participant_details.remarks);
            }
        });
    }

    $('#employee_id').on('change', function () {
        var employee_id = $(this).val();
        // $('#participant_data').val($(this).find(":selected").attr('data-employee'));
        fetchParticipantData(employee_id);
    });
    //
});
