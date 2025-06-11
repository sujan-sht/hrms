<script>
    $(document).on('click', '.assetDetail', function() {
        //render Table of Asset Detail
        rerenderAssetDetail()
    })


    ////Save Asset Detail and re-render
    $(document).on('submit', '.submitAssetDetail', function(e) {
        e.preventDefault()
        createAssetDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    $(document).on('click', '.createmode', function() {
        $('.createAssetDetail').show()
        $('.editAssetDetail').hide()
    })

    //Save Asset Detail and re-render
    $(document).on('submit', '.updateAssetDetail', function(e) {
        e.preventDefault()
        udpateAssetDetail();
        var that = $(this);
        toggleCreateBtn(that)
    })

    function createAssetDetail() {
        let asset_type = $('#assetType').val();
        let asset_detail = $('#assetDetail').val();
        let given_date = $('#givenDate').val();
        let return_date = $('#returnDate').val();
        let employee_id = "{{ $employeeModel->id }}";

        let formData = {
            asset_type,
            asset_detail,
            given_date,
            return_date,
            employee_id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.submitAssetDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('assetDetail.save') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderAssetDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitAssetDetail').trigger("reset");
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function udpateAssetDetail() {
        let asset_type = $('#editAssetType').val();
        let asset_detail = $('#editAssetDetail').val();
        let given_date = $('#editGivenDate').val();
        let return_date = $('#editReturnDate').val();
        let id = $('.assetDetailId').val();

        let formData = {
            asset_type,
            asset_detail,
            given_date,
            return_date,
            id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.updateAssetDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('assetDetail.update') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderAssetDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.editAssetDetail').hide()
                    $('.createAssetDetail').show()
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function editAsset(data) {
        var that = $(this);
        $('.createAssetDetail').hide()
        $('.editAssetDetail').show()
        $('.editAssetDetail #editAssetType').val(data.asset_type).trigger('change');
        $('.editAssetDetail #editAssetDetail').val(data.asset_detail)
        $('.editAssetDetail #editGivenDate').val(data.given_date)
        $('.editAssetDetail #editReturnDate').val(data.return_date)
        $('.editAssetDetail .assetDetailId').val(data.id)
    }



    function deleteAssetDetail(id) {

        let formData = {
            id,
            "_token": "{{ csrf_token() }}"
        };
        $.ajax({
            type: 'DELETE',
            url: "{{ route('assetDetail.delete') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderAssetDetail();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function rerenderAssetDetail() {
        $.ajax({
            type: 'GET',
            url: "{{ route('assetDetail.appendAll') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.assetTable').empty();
                $('.assetTable').append(resp);
            }
        });
    }
</script>
