<script>
    $(document).on('click', '.documentDetail', function() {
        //render Table of Document Detail
        rerenderDocumentDetail()
    })

    ////Save Document Detail and re-render
    $(document).on('submit', '.submitDocumentDetail', function(e) {
        e.preventDefault()
        createDocumentDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    $(document).on('click', '.createmode', function() {
        $('.createDocumentDetail').show()
        $('.editDocumentDetail').hide()
    })

    //Save Document Detail and re-render
    $(document).on('submit', '.updateDocumentDetail', function(e) {
        e.preventDefault()
        udpateDocumentDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    function createDocumentDetail() {
        let document_name = $('#document_name').val();
        let document_id_number = $('#id_number').val();
        let document_issued_date = $('#issued_date').val();
        let document_expiry_date = $('#expiry_date').val();
        let document_file = $('#file')[0].files[0];
        let employee_id = "{{ $employeeModel->id }}";
        let _token = "{{ csrf_token() }}";
        let formData = new FormData();
        formData.append('document_name', document_name);
        formData.append('id_number', document_id_number);
        formData.append('issued_date', document_issued_date);
        formData.append('expiry_date', document_expiry_date);
        formData.append('document_file', document_file);
        formData.append('employee_id', employee_id);
        formData.append('_token', _token);
        var that = $('.submitDocumentDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('documentDetail.save') }}",
            data: formData,
            processData: false,
            contentType: false,
            dataType:'json',
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderDocumentDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitDocumentDetail').trigger("reset");
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function udpateDocumentDetail() {
        let document_name = $('#edit_document_name').val();
        let document_id_number = $('#edit_id_number').val();
        let document_issued_date = $('#edit_issued_date').val();
        let document_expiry_date = $('#edit_expiry_date').val();
        let document_file = $('#edit_file')[0].files[0];
        let id = $('.documentDetailId').val();
        let _token = "{{ csrf_token() }}";

        let formData = new FormData();
        formData.append('document_name', document_name);
        formData.append('id_number', document_id_number);
        formData.append('issued_date', document_issued_date);
        formData.append('expiry_date', document_expiry_date);
        formData.append('document_file', document_file);
        formData.append('id', id);
        formData.append('_token', _token);

        var that = $('.updateDocumentDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('documentDetail.update') }}",
            data: formData,
            processData: false,
            contentType: false,
            dataType:'json',
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderDocumentDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.editDocumentDetail').hide()
                    $('.createDocumentDetail').show()
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function editDocument(data) {
        $('.createDocumentDetail').hide()
        $('.editDocumentDetail').show()
        $('.editDocumentDetail #edit_document_name').val(data.document_name)
        $('.editDocumentDetail #edit_id_number').val(data.id_number)
        $('.editDocumentDetail #edit_issued_date').val(data.issued_date)
        $('.editDocumentDetail #edit_expiry_date').val(data.expiry_date)
        $('.editDocumentDetail #edit_file').val(data.document_file)
        $('.editDocumentDetail .documentDetailId').val(data.id)
    }

    function deleteDocumentDetail(id) {

        let formData = {
            id,
            "_token": "{{ csrf_token() }}"
        };
        $.ajax({
            type: 'DELETE',
            url: "{{ route('documentDetail.delete') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderDocumentDetail();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function rerenderDocumentDetail() {
        $.ajax({
            type: 'GET',
            url: "{{ route('documentDetail.appendAll') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.documentTable').empty();
                $('.documentTable').append(resp);
            }
        });
    }
</script>
