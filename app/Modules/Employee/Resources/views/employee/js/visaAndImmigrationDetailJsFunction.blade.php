<script>
    $(document).on('click', '.visaAndImmigrationDetail', function() {
        //render Table of VisaAndImmigration Detail
        rerenderVisaAndImmigrationDetail()
    })

    ////Save VisaAndImmigration Detail and re-render
    $(document).on('submit', '.submitVisaAndImmigrationDetail', function(e) {
        e.preventDefault()
        createVisaAndImmigrationDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    $(document).on('click', '.createmode', function() {
        $('.createVisaAndImmigrationDetail').show()
        $('.editVisaAndImmigrationDetail').hide()
    })

    //Save VisaAndImmigration Detail and re-render
    $(document).on('submit', '.updateVisaAndImmigrationDetail', function(e) {
        e.preventDefault()
        udpateVisaAndImmigrationDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    function createVisaAndImmigrationDetail() {
        let country = $('#country').val();
        let visa_type = $('#visa_type').val();
        let visa_expiry_date = $('#visa_expiry_date').val();
        let issued_date = $('#issued_date').val();
        let passport_number = $('#passport_number').val();
        let note = $('#remarks').val();
        let employee_id = "{{ $employeeModel->id }}";

        let formData = {
            country,
            visa_type,
            visa_expiry_date,
            issued_date,
            passport_number,
            note,
            employee_id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.submitVisaAndImmigrationDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('visaAndImmigrationDetail.save') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderVisaAndImmigrationDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitVisaAndImmigrationDetail').trigger("reset");
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function udpateVisaAndImmigrationDetail() {
        let country = $('#edit_country').val();
        let visa_type = $('#edit_visa_type').val();
        let visa_expiry_date = $('#edit_visa_expiry_date').val();
        let issued_date = $('#edit_issued_date').val();
        let passport_number = $('#edit_passport_number').val();
        let note = $('#edit_remarks').val();
        // let note =  tinymce.get("edit_note").getContent();
        let id = $('.visaAndImmigrationDetailId').val();

        let formData = {
            country,
            visa_type,
            visa_expiry_date,
            issued_date,
            passport_number,
            note,
            id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.updateVisaAndImmigrationDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('visaAndImmigrationDetail.update') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderVisaAndImmigrationDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.editVisaAndImmigrationDetail').hide()
                    $('.createVisaAndImmigrationDetail').show()
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function editVisaAndImmigration(data) {
        $('.createVisaAndImmigrationDetail').hide()
        $('.editVisaAndImmigrationDetail').show()
        $('.updateVisaAndImmigrationDetail').trigger("reset");

        $('.editVisaAndImmigrationDetail #edit_country').val(data.country).trigger('change');
        $('.editVisaAndImmigrationDetail #edit_visa_type').val(data.visa_type)
        $('.editVisaAndImmigrationDetail #edit_visa_expiry_date').val(data.visa_expiry_date)
        $('.editVisaAndImmigrationDetail #edit_issued_date').val(data.issued_date)
        $('.editVisaAndImmigrationDetail #edit_passport_number').val(data.passport_number)

        // if (data.note != null) {
        //     tinymce.get("edit_note_visa_immig").setContent(data.note);
        // }
        $('.editVisaAndImmigrationDetail #edit_remarks').val(data.note)
        $('.editVisaAndImmigrationDetail .visaAndImmigrationDetailId').val(data.id)
    }

    function deleteVisaAndImmigrationDetail(id) {

        let formData = {
            id,
            "_token": "{{ csrf_token() }}"
        };
        $.ajax({
            type: 'DELETE',
            url: "{{ route('visaAndImmigrationDetail.delete') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderVisaAndImmigrationDetail();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function rerenderVisaAndImmigrationDetail() {
        $.ajax({
            type: 'GET',
            url: "{{ route('visaAndImmigrationDetail.appendAll') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.visaAndImmigrationTable').empty();
                $('.visaAndImmigrationTable').append(resp);
            }
        });
    }
</script>
