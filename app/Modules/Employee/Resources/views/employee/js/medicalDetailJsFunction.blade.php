<script>
    $(document).on('click', '.medicalDetail', function() {
        //render Table of Medical Detail
        rerenderMedicalDetail()
    })

    ////Save Medical Detail and re-render
    $(document).on('submit', '.submitMedicalDetail', function(e) {
        e.preventDefault()
        createMedicalDetail()

    })

    $(document).on('click', '.createmode', function() {
        $('.createMedicalDetail').show()
        $('.editMedicalDetail').hide()
    })

    //Save Medical Detail and re-render
    $(document).on('submit', '.updateMedicalDetail', function(e) {
        e.preventDefault()
        udpateMedicalDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    function createMedicalDetail() {
        var btn = $('.submitMedicalDetail');
        let medical_problem = $('#medical_problem').val();
        let details = $('#details').val();
        let insurance_company_name = $('#insurance_company_name').val();
        let medical_insurance_details = $('#medical_insurance_details').val();
        let employee_id = "{{ $employeeModel->id }}";

        let formData = {
            medical_problem,
            details,
            insurance_company_name,
            medical_insurance_details,
            employee_id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.submitMedicalDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('medicalDetail.save') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderMedicalDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitMedicalDetail').trigger("reset");
                    toggleCreateBtn(btn)
                    return
                }
                toastr.error(resp.message);
                return
            },
            error: function(request, status, error) {
                $('.medical_problem').html(request.responseJSON.errors.medical_problem);
                $('.medical_problem').focus();
            }
        });
    }

    function udpateMedicalDetail() {
        var btn = $('.updateMedicalDetail');
        let medical_problem = $('#edit_medical_problem').val();
        let details = $('#edit_details').val();
        let insurance_company_name = $('#edit_insurance_company_name').val();
        let medical_insurance_details = $('#edit_medical_insurance_details').val();
        let id = $('.medicalDetailId').val();

        let formData = {
            medical_problem,
            details,
            insurance_company_name,
            medical_insurance_details,
            id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.updateMedicalDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('medicalDetail.update') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderMedicalDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.editMedicalDetail').hide()
                    $('.createMedicalDetail').show()
                    toggleCreateBtn(btn)
                    return
                }
                toastr.error(resp.message);
                return
            },
            error: function(request, status, error) {
                $('.medical_problem').html(request.responseJSON.errors.medical_problem);
                $('.medical_problem').focus();
            }
        });
    }

    function editMedical(data) {
        $('.createMedicalDetail').hide()
        $('.editMedicalDetail').show()
        $('.updateMedicalDetail').trigger("reset");

        $('.editMedicalDetail #edit_medical_problem').text(data.medical_problem)
        $('.editMedicalDetail #edit_details').val(data.details)
        $('.editMedicalDetail #edit_medical_insurance_details').val(data.medical_insurance_details)

        // if (data.medical_problem != null) {
        //     tinymce.get("edit_medical_problem").setContent(data.medical_problem);
        // }
        // if (data.details != null) {
        //     tinymce.get("edit_details").setContent(data.details);
        // }

        // if (data.medical_insurance_details != null) {
        //     tinymce.get("edit_medical_insurance_details").setContent(data.medical_insurance_details);
        // }

        $('.editMedicalDetail #edit_insurance_company_name').val(data.insurance_company_name)
        $('.editMedicalDetail .medicalDetailId').val(data.id)
    }

    function deleteMedicalDetail(id) {

        let formData = {
            id,
            "_token": "{{ csrf_token() }}"
        };
        $.ajax({
            type: 'DELETE',
            url: "{{ route('medicalDetail.delete') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderMedicalDetail();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function rerenderMedicalDetail() {
        $.ajax({
            type: 'GET',
            url: "{{ route('medicalDetail.appendAll') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.medicalTable').empty();
                $('.medicalTable').append(resp);
            }
        });
    }
</script>
