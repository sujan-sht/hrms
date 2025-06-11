<script>

    $(document).on('click', '.promotionTab', function() {
        renderView();
    })

    // $(document).on('click', '.createmode', function() {
    //     $('.createAssetDetail').show()
    //     $('.editAssetDetail').hide()
    // })

    // Save create form and render ciew
    // $(document).on('submit', '.submitCreateForm', function(e) {
    //     e.preventDefault();
    //     createData();
    //     toggleCreateBtn($(this));
    // })

    // Save update form and render view
    // $(document).on('submit', '.submitUpdateForm', function(e) {
    //     e.preventDefault();
    //     udpateData();
    //     toggleCreateBtn($(this));
    // })

    function renderView() {
        $.ajax({
            type: "GET",
            url: "{{ route('employeePromotion.list') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.employeePromotionTable').empty();
                $('.employeePromotionTable').append(resp);
            }
        });
    }

    // function createData()
    // {
    //     let formData = {
    //         "_token": "{{ csrf_token() }}",
    //         "employee_id" : $('#employeeId').val(),
    //         "transfer_date" : $('#transferDate').val(),
    //         "from_org_id" : $('#fromOrganizationId').val(),
    //         "to_org_id" : $('#toOrganizationId').val(),
    //         "remarks" : $('#remarks').val(),
    //         "status" : $('#status').val()
    //     };
    //     var that = $('.submitCreateForm');

    //     $.ajax({
    //         type: "POST",
    //         url: "{{ route('employeeTransfer.create') }}",
    //         data: formData,
    //         success: function(resp) {
    //             if (resp.status = 1) {
    //                 renderView();
    //                 that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
    //                 $('.submitCreateForm').trigger("reset");
    //                 toastr.success(resp.message);
    //             } else {
    //                 toastr.error(resp.message);
    //             }
    //         }
    //     });
    // }

    // function fetchData(data) {
    //     var context = $(this);
    //     $('.createAssetDetail').hide();
    //     $('.editAssetDetail').show();
    //     $('.editAssetDetail #primaryId').val(data.id);
    //     $('.editAssetDetail #transferDate').val(data.transfer_date);
    //     $('.editAssetDetail #toOrganizationId').val(data.to_org_id).trigger('change');
    //     $('.editAssetDetail #remarks').val(data.remarks);
    //     $('.editAssetDetail #status').val(data.status).trigger('change');
    // }

    // function udpateData() {
    //     let formData = {
    //         "_token": "{{ csrf_token() }}",
    //         "id" : $('#primaryId').val(),
    //         "employee_id" : $('#employeeId').val(),
    //         "transfer_date" : $('.editAssetDetail #transferDate').val(),
    //         "from_org_id" : $('#fromOrganizationId').val(),
    //         "to_org_id" : $('.editAssetDetail #toOrganizationId').val(),
    //         "remarks" : $('.editAssetDetail #remarks').val(),
    //         "status" : $('.editAssetDetail #status').val()
    //     };
    //     var that = $('.submitUpdateForm');

    //     $.ajax({
    //         type: "POST",
    //         url: "{{ route('employeeTransfer.update') }}",
    //         data: formData,
    //         success: function(resp) {
    //             if (resp.status = 1) {
    //                 renderView();
    //                 that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
    //                 $('.editAssetDetail').hide()
    //                 $('.createAssetDetail').show()
    //                 toastr.success(resp.message);
    //             } else {
    //                 toastr.error(resp.message);
    //             }
    //         }
    //     });
    // }

    // function deleteData(id) {
    //     let formData = {
    //         "_token": "{{ csrf_token() }}",
    //         id,
    //     };
    //     $.ajax({
    //         type: 'DELETE',
    //         url: "{{ route('employeeTransfer.delete') }}",
    //         data: formData,
    //         success: function(resp) {
    //             if (resp.status == 1) {
    //                 renderView();
    //                 toastr.success(resp.message);
    //             } else {
    //                 toastr.error(resp.message);
    //             }
    //         }
    //     });
    // }

</script>
