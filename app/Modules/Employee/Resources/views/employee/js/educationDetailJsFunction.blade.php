<script>
    $(document).on('click', '.educationDetail', function() {
        //render Table of Education Detail
        rerenderEducationDetail()
    })

    ////Save Education Detail and re-render
    $(document).on('submit', '.submitEducationDetail', function(e) {
        e.preventDefault()
        createEducationDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    $(document).on('click', '.createmode', function() {
        $('.createEducationDetail').show()
        $('.editEducationDetail').hide()
    })

    //Save Education Detail and re-render
    $(document).on('submit', '.updateEducationDetail', function(e) {
        e.preventDefault()
        updateEducationDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    function createEducationDetail() {
        var formData = new FormData();
        var equivalent_certificates = $('#equivalent_certificates')[0].files;
        var degree_certificates = $('#degree_certificates')[0].files;

        var is_foreign_board_file = $('#is_foreign_board_file')[0].files[0];
        if (is_foreign_board_file != undefined && is_foreign_board_file != null) {
            formData.append("is_foreign_board_file", is_foreign_board_file);
        }

        let selectedValue = $('input[name="is_foreign_board"]:checked').val();
        formData.append("is_foreign_board", selectedValue);




        for (var i = 0; i < equivalent_certificates.length; i++) {
            formData.append("equivalent_certificates[]", equivalent_certificates[i], equivalent_certificates[i][
                'name'
            ]);
        }
        for (var i = 0; i < degree_certificates.length; i++) {
            formData.append("degree_certificates[]", degree_certificates[i], degree_certificates[i][
                'name'
            ]);
        }
        formData.append("type_of_institution", $('#type_of_institution').val());
        formData.append("institution_name", $('#institution_name').val());
        formData.append("affiliated_to", $('#affiliated_to').val());
        formData.append('attended_to', $('#attended_to').val());
        formData.append("attended_from", $('#attended_from').val());
        formData.append("course_name", $('#course_name').val());
        formData.append("score", $('#score').val());
        formData.append("specialization", $('#specialization').val());
        formData.append("division", $('#division').val());
        formData.append("faculty", $('#faculty').val());
        formData.append("university_name", $('#university_name').val());
        formData.append("major_subject", $('#major_subject').val());
        formData.append("attended_to", $('#attended_to').val());
        formData.append("passed_year", $('#passed_year').val());
        formData.append("level", $('#level').val());
        formData.append("note", $('#note').val());
        formData.append("employee_id", "{{ $employeeModel->id }}");
        formData.append("_token", "{{ csrf_token() }}");

        var that = $('.submitEducationDetail');
        $.ajax({
            type: 'POST',
            url: "{{ route('educationDetail.save') }}",
            data: formData,
            processData: false,
            contentType: false,
            success: function(resp) {

                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderEducationDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitEducationDetail').trigger("reset");
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function updateEducationDetail() {
        var formData = new FormData();
        var equivalent_certificates = $('#edit_equivalent_certificates')[0].files;
        var degree_certificates = $('#edit_degree_certificates')[0].files;
        for (var i = 0; i < equivalent_certificates.length; i++) {
            formData.append("equivalent_certificates[]", equivalent_certificates[i], equivalent_certificates[i][
                'name'
            ]);
        }
        for (var i = 0; i < degree_certificates.length; i++) {
            formData.append("degree_certificates[]", degree_certificates[i], degree_certificates[i][
                'name'
            ]);
        }
        var is_foreign_board_file = $('#edit_is_foreign_board_file')[0].files[0];
        if (is_foreign_board_file != undefined && is_foreign_board_file != null) {
            formData.append("is_foreign_board_file", is_foreign_board_file);
        }

        let selectedValue = $('input[name="edit_is_foreign_board"]:checked').val();
        formData.append("is_foreign_board", selectedValue);

        formData.append("type_of_institution", $('#edit_type_of_institution').val());
        formData.append("institution_name", $('#edit_institution_name').val());
        formData.append("affiliated_to", $('#edit_affiliated_to').val());
        formData.append('attended_to', $('#edit_attended_to').val());
        formData.append("attended_from", $('#edit_attended_from').val());
        formData.append("course_name", $('#edit_course_name').val());
        formData.append("score", $('#edit_score').val());
        formData.append("specialization", $('#edit_specialization').val());
        formData.append("division", $('#edit_division').val());
        formData.append("faculty", $('#edit_faculty').val());
        formData.append("university_name", $('#edit_university_name').val());
        formData.append("major_subject", $('#edit_major_subject').val());
        formData.append("attended_to", $('#edit_attended_to').val());
        formData.append("passed_year", $('#edit_passed_year').val());
        formData.append("level", $('#edit_level').val());
        formData.append("note", $('#edit_note').val());
        formData.append("id", $('.educationDetailId').val());
        formData.append("_token", "{{ csrf_token() }}");

        var that = $('.updateEducationDetail');


        $.ajax({
            type: 'POST',
            url: "{{ route('educationDetail.update') }}",
            data: formData,
            processData: false, // Prevent jQuery from processing data
            contentType: false, // Prevent jQuery from setting content-type header
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderEducationDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.editEducationDetail').hide()
                    $('.createEducationDetail').show()
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function editEducation(educationDetailId) {

        console.log(educationDetailId, 'edit education detail')

        $.ajax({
            type: 'GET',
            url: "{{ route('educationDetail.edit') }}",
            data: {
                id: educationDetailId,
            },
            success: function(resp) {
                console.log(resp);
                if (resp.status) {
                    var data = resp.data;


                    $('.createEducationDetail').hide()
                    $('.editEducationDetail').show()
                    $('.editEducationDetail #edit_type_of_institution').val(data.type_of_institution)
                    $('.editEducationDetail #edit_institution_name').val(data.institution_name)
                    $('.editEducationDetail #edit_affiliated_to').val(data.affiliated_to)
                    $('.editEducationDetail #edit_attended_from').val(data.attended_from)
                    $('.editEducationDetail #edit_attended_to').val(data.attended_to)
                    $('.editEducationDetail #edit_passed_year').val(data.passed_year)
                    $('.editEducationDetail #edit_course_name').val(data.course_name)
                    $('.editEducationDetail #edit_score').val(data.score)
                    $('.editEducationDetail #edit_division').val(data.division)
                    $('.editEducationDetail #edit_specialization').val(data.specialization)
                    $('.editEducationDetail #edit_faculty').val(data.faculty)
                    $('.editEducationDetail #edit_university_name').val(data.university_name)
                    $('.editEducationDetail #edit_major_subject').val(data.major_subject)
                    $('.editEducationDetail #edit_level').val(data.level)
                    $('.editEducationDetail #edit_note').val(data.note)
                    $('.editEducationDetail .educationDetailId').val(data.id)

                    if (data.is_foreign_board == 1) {
                        $("#edit_is_foreign_board[value='1']").prop("checked", true);
                        $('input[name="is_foreign_board_file"]').show(); // Show file input
                    } else {
                        $("#edit_is_foreign_board[value='0']").prop("checked", true);
                        $('input[name="is_foreign_board_file"]').hide(); // Hide file input
                    }

                }
            }
        });
    }

    function deleteEducationDetail(id) {

        let formData = {
            id,
            "_token": "{{ csrf_token() }}"
        };
        $.ajax({
            type: 'DELETE',
            url: "{{ route('educationDetail.delete') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderEducationDetail();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function rerenderEducationDetail() {
        $.ajax({
            type: 'GET',
            url: "{{ route('educationDetail.appendAll') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.educationTable').empty();
                $('.educationTable').append(resp);
            }
        });
    }
</script>
