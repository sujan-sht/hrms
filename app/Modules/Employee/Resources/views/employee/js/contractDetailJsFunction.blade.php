<script>
    $(document).on('click', '.contractDetail', function() {
        //render Table of Contract Detail
        rerenderContractDetail()
    })

    ////Save Contract Detail and re-render
    $(document).on('submit', '.submitContractDetail', function(e) {
        e.preventDefault()
        createContractDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    $(document).on('click', '.createmode', function() {
        $('.createContractDetail').show()
        $('.editContractDetail').hide()
    })

    //Save Contract Detail and re-render
    $(document).on('submit', '.updateContractDetail', function(e) {
        e.preventDefault()
        udpateContractDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    function createContractDetail() {
        let title = $('#title').val();
        let start_from = $('#start_from').val();
        let end_to = $('#end_to').val();
        let employee_id = "{{ $employeeModel->id }}";

        let formData = {
            title,
            start_from,
            end_to,
            employee_id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.submitContractDetail');


        $.ajax({
            type: 'POST',
            url: "{{ route('contractDetail.save') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderContractDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitContractDetail').trigger("reset");
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function udpateContractDetail() {

        let title = $('#edit_title').val();
        let start_from = $('#edit_start_from').val();
        let end_to = $('#edit_end_to').val();
        let id = $('.contractDetailId').val();

        let formData = {
            title,
            start_from,
            end_to,
            id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.updateContractDetail');


        $.ajax({
            type: 'POST',
            url: "{{ route('contractDetail.update') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderContractDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.editContractDetail').hide()
                    $('.createContractDetail').show()
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function editContract(data) {
        // console.log(data)
        $('.createContractDetail').hide()
        $('.editContractDetail').show()
        $('.editContractDetail #edit_title').val(data.title)
        $('.editContractDetail #edit_start_from').val(data.start_from)
        $('.editContractDetail #edit_end_to').val(data.end_to)
        $('.editContractDetail .contractDetailId').val(data.id)
    }

    function deleteContractDetail(id) {

        let formData = {
            id,
            "_token": "{{ csrf_token() }}"
        };
        $.ajax({
            type: 'DELETE',
            url: "{{ route('contractDetail.delete') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderContractDetail();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function rerenderContractDetail() {
        $.ajax({
            type: 'GET',
            url: "{{ route('contractDetail.appendAll') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.contractTable').empty();
                $('.contractTable').append(resp);
            }
        });
    }
</script>
