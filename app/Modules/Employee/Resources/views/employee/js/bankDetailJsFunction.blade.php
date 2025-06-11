<script>
    $(document).on('click', '.bankDetail', function() {
        //render Table of Bank Detail
        rerenderBankDetail()
    })

    ////Save Bank Detail and re-render
    $(document).on('submit', '.submitBankDetail', function(e) {
        e.preventDefault()
        createBankDetail()
        var that = $(this);
        toggleCreateBtn(that)

    })

    $(document).on('click', '.createmode', function() {
        $('.createBankDetail').show()
        $('.editBankDetail').hide()
    })

    //Save Bank Detail and re-render
    $(document).on('submit', '.updateBankDetail', function(e) {
        e.preventDefault()
        udpateBankDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    function createBankDetail() {
        let bank_name = $('#bank_name').val();
        let bank_code = $('#bank_code').val();
        let bank_address = $('#bank_address').val();
        let bank_branch = $('#bank_branch').val();
        let account_type = $('#account_type').val();
        let account_number = $('#account_number').val();
        let employee_id = "{{ $employeeModel->id }}";

        let formData = {
            bank_name,
            bank_code,
            bank_address,
            bank_branch,
            account_type,
            account_number,
            employee_id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.submitBankDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('bankDetail.save') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderBankDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitBankDetail').trigger("reset");
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function udpateBankDetail() {
        let bank_name = $('#edit_bank_name').val();
        let bank_code = $('#edit_bank_code').val();
        let bank_address = $('#edit_bank_address').val();
        let bank_branch = $('#edit_bank_branch').val();
        let account_type = $('#edit_account_type1').val();
        let account_number = $('#edit_account_number').val();
        let id = $('.bankDetailId').val();

        let formData = {
            bank_name,
            bank_code,
            bank_address,
            bank_branch,
            account_type,
            account_number,
            id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.updateBankDetail');


        $.ajax({
            type: 'POST',
            url: "{{ route('bankDetail.update') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderBankDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.editBankDetail').hide()
                    $('.createBankDetail').show()
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function editBank(data) {

        $('.createBankDetail').hide()
        $('.editBankDetail').show()
        $('.editBankDetail #edit_bank_name').val(data.bank_name).trigger('change');
        $('.editBankDetail #edit_bank_code').val(data.bank_code)
        $('.editBankDetail #edit_bank_address').val(data.bank_address)
        $('.editBankDetail #edit_bank_branch').val(data.bank_branch)
        $('.editBankDetail #edit_account_type1').val(data.account_type)
        $('.editBankDetail #edit_account_number').val(data.account_number)
        $('.editBankDetail .bankDetailId').val(data.id)
    }

    function deleteBankDetail(id) {

        let formData = {
            id,
            "_token": "{{ csrf_token() }}"
        };
        $.ajax({
            type: 'DELETE',
            url: "{{ route('bankDetail.delete') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderBankDetail();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function rerenderBankDetail() {
        $.ajax({
            type: 'GET',
            url: "{{ route('bankDetail.appendAll') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.bankTable').empty();
                $('.bankTable').append(resp);
            }
        });
    }
</script>
