$(document).ready(function () {
    $("#start_km_id, #end_km_id").keyup(function () {
        var start_km = $('#start_km_id').val();
        var end_km = $('#end_km_id').val();
        var km_travelled = end_km - start_km;
        $('#km_travelled_id').val(km_travelled);
    });


    $("#fuelConsumption_submit").validate({
        rules: {
            starting_place_id: "required",
            destination_place_id: "required",
            start_km_id: "required",
            end_km_id: "required",
            km_travelled_id: "required",
            vehicle_no_id: "required",
            purpose_id: "required",
            parking_cost_id: "required",
        },
        messages: {
            starting_place_id: "Enter starting place",
            destination_place_id: "Enter destination place",
            start_km_id: "Enter start km",
            end_km_id: "Enter end km",
            km_travelled_id: "Enter km travelled",
            vehicle_no_id: "Enter vehicle number",
            purpose_id: "Enter purpose",
            parking_cost_id: "Enter parking cost",
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
});
