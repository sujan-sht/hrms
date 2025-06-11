<script>
    $(document).on('click', '.skillDetail', function() {
        rerenderSkillDetails()
    })
    $(document).on('click', '.createmode', function() {
        $('.createMedicalDetail').show()
        $('.editMedicalDetail').hide()
    })

    // save
    $(document).on('submit', '.submitSkill', function(e) {
        e.preventDefault()
        createSkill()
    })

    $(document).on('submit', '.updateSkillDetail', function(e) {
        e.preventDefault()
        var that = $(this);
        toggleCreateBtn(that)
        updateSkillDetail()
    })

    function updateSkillDetail() {
        var formData = new FormData();
        formData.append("skill_name", $('#edit_skill_name').val());
        formData.append("employee_id", "{{ $employeeModel->id }}");
        let selectedValue = $('input[name="rating"]:checked').val();
        formData.append("id", $(".skillDetailId").val())
        formData.append("rating", selectedValue);
        formData.append("_token", " {{ csrf_token() }}")
        var that = $('.submitSkill');
        $.ajax({
            type: 'POST',
            url: "{{ route('skillDetail.update') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderSkillDetails();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitSkill').get(0).reset();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }



    function createSkill() {

        var formData = new FormData();
        formData.append("skill_name", $('#skill_name').val());
        formData.append("employee_id", "{{ $employeeModel->id }}");
        let selectedValue = $('input[name="save_rating"]:checked').val();
        formData.append("rating", selectedValue);
        formData.append("_token", " {{ csrf_token() }}")
        var that = $('.submitSkill');
        $.ajax({
            type: 'POST',
            url: "{{ route('skillDetail.save') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderSkillDetails();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitSkill').get(0).reset();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }


    function editMedical(data) {
        $('.createMedicalDetail').hide()
        $('.editMedicalDetail').show()

    }

    function deleteSkillDetail(id) {

        let formData = {
            id,
            "_token": "{{ csrf_token() }}"
        };
        $.ajax({
            type: 'DELETE',
            url: "{{ route('skillDetail.delete') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderSkillDetails();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }


    function rerenderSkillDetails() {
        $.ajax({
            type: 'GET',
            url: "{{ route('skillDetail.appendAll') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.skillTable').empty();
                $('.skillTable').append(resp);
            }
        });
    }
</script>
