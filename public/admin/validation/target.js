$(document).ready(function(){
    $('#targetFormSubmit').validate({
        rules: {
            'kra_id' : 'required',
            'kpi_id' : 'required',
            'fiscal_year_id' : 'required',
            'title' : 'required',
            'frequency' : 'required',
            'weightage' : 'required',
            'no_of_quarter' : 'required',
        },
        messages: {
            'kra_id' : 'Please select KRA',
            'kpi_id' : 'Please select KPA',
            'fiscal_year_id' : 'Please select Fiscal Year',
            'title' : 'Please enter title',
            'frequency' : 'Please select frequency',
            'weightage' : 'Please enter weightage',
            'no_of_quarter' : 'Please enter No of Quarter',
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

    //fetch kpi
    $('#kraId').on('change', function (){
        let kra_id = $(this).val();
        $.ajax({
            type: 'GET',
            url: '/admin/target/fetchKPIs',
            data: {
                kra_id : kra_id
            },
            success: function(data) {
                let kpi = JSON.parse(data);
                let kpi_data = '';

                        kpi_data += "<select name='kpi_id' id='kpiId' class='form-control' required>";
                        kpi_data += "<option value=''>Select KPI</option>";
                        $.each(kpi, function(kpi_id, title){
                            kpi_data += "<option value ='"+kpi_id+"'>"+title+"</option>";
                        });
                        kpi_data += "</select>";
                $('.append_kpi_data').html(kpi_data);
                $('#kpiId').select2();
            }
        });
    });
    //

    //check if sum of weightage greater than 100
    // $('#weightageId').on('keyup', function(){
    //     var weightage_val = $(this).val();
    //     $.ajax({
    //         url: '',
    //         type: '',
    //         data: {

    //         },
    //         success: function (data) {
    //             console.log(data);
    //         }
    //     });
    // });
});
