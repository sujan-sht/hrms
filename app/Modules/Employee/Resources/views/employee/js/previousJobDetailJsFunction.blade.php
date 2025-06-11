<script>
    $(document).on('click', '.previousJobDetail', function() {
        //render Table of Previous Job Detail
        rerenderPreviousJobDetail()
    })


    ////Save Previous Job Detail and re-render
    $(document).on('submit', '.submitPreviousJobDetail', function(e) {
        e.preventDefault()
        createPreviousJobDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    $(document).on('click', '.createmode', function() {
        $('.createPreviousJobDetail').show()
        $('.editPreviousJobDetail').hide()
    })

    //Save PreviousJob Detail and re-render
    $(document).on('submit', '.updatePreviousJobDetail', function(e) {
        e.preventDefault()
        udpatePreviousJobDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    function createPreviousJobDetail() {
        let company_name = $('#company_name').val();
        let address = $('#create_address').val();
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        let job_title = $('#job_title').val();
        let designation_on_joining = $('#designation_on_joining').val();
        let designation_on_leaving = $('#designation_on_leaving').val();
        let industry_type = $('#industry_type').val();
        let break_in_career = $('#break_in_career').val();
        let reason_for_leaving = $('#reason_for_leaving').val();
        let role_key = $('#role_key').val();
        let employee_id = "{{ $employeeModel->id }}";

        let formData = {
            company_name,
            address,
            from_date,
            to_date,
            job_title,
            designation_on_joining,
            designation_on_leaving,
            industry_type,
            break_in_career,
            reason_for_leaving,
            role_key,
            employee_id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.submitPreviousJobDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('previousJobDetail.save') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderPreviousJobDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitPreviousJobDetail').trigger("reset");
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function udpatePreviousJobDetail() {
        let company_name = $('.edit_company_name').val();
        let address = $('.edit_address').val();
        let from_date = $('.edit_from_date').val();
        let to_date = $('.edit_to_date').val();
        let job_title = $('.edit_job_title').val();
        let designation_on_joining = $('.edit_designation_on_joining').val();
        let designation_on_leaving = $('.edit_designation_on_leaving').val();
        let industry_type = $('.edit_industry_type').val();
        let break_in_career = $('.edit_break_in_career').val();
        let reason_for_leaving = $('.edit_reason_for_leaving').val();
        let role_key = $('.edit_role_key').val();
        let id = $('.previousJobDetailId').val();

        let formData = {
            company_name,
            address,
            from_date,
            to_date,
            job_title,
            designation_on_joining,
            designation_on_leaving,
            industry_type,
            break_in_career,
            reason_for_leaving,
            role_key,
            id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.updatePreviousJobDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('previousJobDetail.update') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderPreviousJobDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.editPreviousJobDetail').hide()
                    $('.createPreviousJobDetail').show()
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function editPreviousJob(data) {
        // console.log(data)
        $('.createPreviousJobDetail').hide()
        $('.editPreviousJobDetail').show()
        $('.editPreviousJobDetail #edit_company_name').val(data.company_name)
        $('.editPreviousJobDetail #edit_address').val(data.address)
        $('.editPreviousJobDetail #edit_from_date').val(data.from_date)
        $('.editPreviousJobDetail #edit_to_date').val(data.to_date)
        $('.editPreviousJobDetail #edit_job_title').val(data.job_title)
        $('.editPreviousJobDetail #edit_designation_on_joining').val(data.designation_on_joining)
        $('.editPreviousJobDetail #edit_designation_on_leaving').val(data.designation_on_leaving)
        $('.editPreviousJobDetail #edit_industry_type').val(data.industry_type)
        $('.editPreviousJobDetail #edit_break_in_career').val(data.break_in_career)
        $('.editPreviousJobDetail #edit_reason_for_leaving').val(data.reason_for_leaving)
        $('.editPreviousJobDetail #edit_role_key').val(data.role_key)
        $('.editPreviousJobDetail .previousJobDetailId').val(data.id)
    }

    function deletePreviousJobDetail(id) {

        let formData = {
            id,
            "_token": "{{ csrf_token() }}"
        };
        $.ajax({
            type: 'DELETE',
            url: "{{ route('previousJobDetail.delete') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderPreviousJobDetail();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function rerenderPreviousJobDetail() {
        $.ajax({
            type: 'GET',
            url: "{{ route('previousJobDetail.appendAll') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.previousJobTable').empty();
                $('.previousJobTable').append(resp);
            }
        });
    }
</script>
