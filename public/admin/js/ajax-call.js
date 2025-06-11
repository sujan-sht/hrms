class AJAXCall {

    constructor(formID, url) {
        this.formID = formID;
        this.url = url;
        this.setupCSRFToken();
    }

    ajaxSubmit() {
        console.log("================AJAX Submit===================");

        var formData = new FormData();
        $.each($(this.formID).serializeArray(), function (key, value) {
            formData.append(value.name, value.value);
        });
        $.ajax({
            url: this.url,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status) {
                    $('.icon-spinner').replaceWith('');
                    toastr.success(response.message, 'Success');
                } else {
                    toastr.error(response.message, 'Error');
                }
                $(this.formID).find('button[type=submit]')
                    .attr('disabled', false).find('.spinner').remove();
            },
            error: function (xhr) {
                var response = JSON.parse(xhr.responseText);
                toastr.error(response.message, 'Error');
                $(this.formID).find('button[type=submit]')
                    .attr('disabled', false).find('.spinner').remove();
            },
            complete: function () {
                $(this.formID)[0].reset();
                $(this.formID).find('button[type=submit]')
                    .attr('disabled', false).find('.spinner').remove();
            }
        });

        console.log("================AJAX Submit===================");
    }


    // setup CSRF token
    setupCSRFToken() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }


}
