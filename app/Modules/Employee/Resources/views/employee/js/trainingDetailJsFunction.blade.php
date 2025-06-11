<script>
    $(document).on('click', '.trainingDetail', function() {
        //render Table of training Detail
        rerenderTrainingDetail()
    })

    function rerenderTrainingDetail() {
        $.ajax({
            type: 'GET',
            url: "{{ route('trainingDetail.appendAll') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.trainingTable').empty();
                $('.trainingTable').append(resp);
            }
        });
    }

    //Save Training Detail and re-render
    $(document).on('submit', '.updateTrainingDetail', function(e) {
        e.preventDefault()
        udpateTrainingDetail();
        var that = $(this);
        toggleCreateBtn(that)
    })



    function udpateTrainingDetail() {
        let rating = $('#editTrainingRating').val();
        let id = $('.trainingDetailId').val();
        let formData = {
            rating,
            id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.updateTrainingDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('trainingDetail.update') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderTrainingDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.editTrainingDetail').hide()
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function editTraining(data) {
        var that = $(this);
        $('.editTrainingDetail').show()
        $('.editTrainingDetail #editTrainingTitle').val(data.training_info.title)
        $('.editTrainingDetail .trainingDetailId').val(data.id)
    }
</script>
