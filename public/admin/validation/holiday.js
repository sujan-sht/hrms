$(document).ready(function () {
    $(".holidayForm").validate({
        rules: {
            // "title": "required",
            "holiday_days[0][day]": "required",
            // "event_time":"required"
            // "editcontact_no": {
            //     required: true,
            //     // minlength: 10,
            //     maxlength: 10,
            //     digits: true
            // },
        },
        messages: {
            // "title": "Enter title",
            "holiday_days[0][day]": "Enter Holiday Title",
            // "event_time": "Choose Event Time",

            // "editcontact_no":
            // {
            //     required: 'Contact Number is required',
            //     // minlength: 'Contact Number must be 10 digits',
            //     maxlength: 'Contact Number cannot be more than 10 digits',
            //     digits: 'Contact Number must be digits',
            // },
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
        },
        // checkForm: function() {
        //     this.prepareForm();
        //         for ( var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++ ) {
        //             if (this.findByName( elements[i].name ).length != undefined && this.findByName( elements[i].name ).length > 1) {
        //                 for (var cnt = 0; cnt < this.findByName( elements[i].name ).length; cnt++) {
        //                     this.check( this.findByName( elements[i].name )[cnt] );
        //                 }
        //             }
        //             else {
        //                 this.check( elements[i] );
        //             }
        //         }
        //         return this.valid();
        // },
    });

    // $("[name^=holiday_days]").each(function () {
    //     $(this).rules("add", {
    //         required: true,
    //         checkValue: true
    //     });
    // });
});

