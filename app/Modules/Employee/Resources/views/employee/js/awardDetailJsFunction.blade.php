<script>
    console.log('awardDetailJsFunction');

    // $('#date-award').nepaliDatePicker({
    //     closeOnDateSelect: true,
    //     ndpYear: true,
    //     ndpMonth: true,

    // });


    $(document).on('click', '.awardDetails', function() {
        rerenderAwardDetails()
    })


    ////Save Medical Detail and re-render
    // save
    $(document).on('submit', '.submitAwardDetail', function(e) {
        e.preventDefault()
        createAwardDetail()
    })

    //Save Family Detail and re-render
    $(document).on('submit', '.updateAwardDetail', function(e) {
        e.preventDefault()
        var that = $(this);
        toggleCreateBtn(that)
        updateAwardDetail()
    })



    $(document).on('click', '.createmode', function() {
        $('.createMedicalDetail').show()
        $('.editMedicalDetail').hide()
    })


    function updateAwardDetail() {
        var formData = new FormData();
        var attachment = $('#edit_attachment')[0].files;
        for (var i = 0; i < attachment.length; i++) {
            formData.append("attachment[]", attachment[i], attachment[i]['name']);
        }
        formData.append("title", $('#edit_title').val());
        formData.append("date", $('#edit_date').val());
        formData.append("id", $(".awardDetailId").val())
        formData.append("_token", " {{ csrf_token() }}")


        var that = $('.submitAwardDetail');
        $.ajax({
            type: 'POST',
            url: "{{ route('awardDetail.update') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderAwardDetails();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitAwardDetail').get(0).reset();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }


    function createAwardDetail() {
        var formData = new FormData();
        var attachment = $('#attachment')[0].files;
        for (var i = 0; i < attachment.length; i++) {
            formData.append("attachment[]", attachment[i], attachment[i]['name']);
        }
        formData.append("title", $('#title').val());
        formData.append("date", $('#date').val());
        formData.append("employee_id", "{{ $employeeModel->id }}");
        formData.append("_token", " {{ csrf_token() }}")


        var that = $('.submitAwardDetail');
        $.ajax({
            type: 'POST',
            url: "{{ route('awardDetail.save') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderAwardDetails();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitAwardDetail').get(0).reset();
                    return
                }
                toastr.error(resp.message);
                // return
            }
        });

    }

    function editMedical(data) {
        $('.createMedicalDetail').hide()
        $('.editMedicalDetail').show()

    }

    function deleteAwardDetail(id) {

        let formData = {
            id,
            "_token": "{{ csrf_token() }}"
        };
        $.ajax({
            type: 'DELETE',
            url: "{{ route('awardDetail.delete') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderAwardDetails();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }


    function rerenderAwardDetails() {
        $.ajax({
            type: 'GET',
            url: "{{ route('awardDetail.appendAll') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.awardTable').empty();
                $('.awardTable').append(resp);
            }
        });
    }
</script>
