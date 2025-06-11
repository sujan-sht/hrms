<script>
    $(document).on('click', '.benefitDetail', function() {
        //render Table of Benefit Detail
        rerenderBenefitDetail()
    })

    ////Save Benefit Detail and re-render
    $(document).on('submit', '.submitBenefitDetail', function(e) {
        e.preventDefault()
        createBenefitDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    $(document).on('click', '.createmode', function() {
        $('.createBenefitDetail').show()
        $('.editBenefitDetail').hide()
    })

    //Save Benefit Detail and re-render
    $(document).on('submit', '.updateBenefitDetail', function(e) {
        e.preventDefault()
        udpateBenefitDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    function createBenefitDetail() {
        let benefit_type_id = $('#benefitType').val();
        let plan = $('#plan').val();
        let coverage = $('#coverage').val();
        let effective_date = $('#effectiveDate').val();
        let employee_contribution = $('#employeeContribution').val();
        let company_contribution = $('#companyContribution').val();
        let employee_id = "{{ $employeeModel->id }}";

        let formData = {
            benefit_type_id,
            plan,
            coverage,
            effective_date,
            employee_contribution,
            company_contribution,
            employee_id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.submitBenefitDetail');


        $.ajax({
            type: 'POST',
            url: "{{ route('benefitDetail.save') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderBenefitDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitBenefitDetail').trigger("reset");
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function udpateBenefitDetail() {
        let benefit_type_id = $('#editBenefitType').val();
        let plan = $('#editPlan').val();
        let coverage = $('#editCoverage').val();
        let effective_date = $('#editEffectiveDate').val();
        let employee_contribution = $('#editEmployeeContribution').val();
        let company_contribution = $('#editCompanyContribution').val();
        let id = $('.benefitDetailId').val();


        // console.log(name)

        let formData = {
            benefit_type_id,
            plan,
            coverage,
            effective_date,
            employee_contribution,
            company_contribution,
            id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.updateBenefitDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('benefitDetail.update') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderBenefitDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.editBenefitDetail').hide()
                    $('.createBenefitDetail').show()
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function editBenefit(data) {
        // console.log(data)
        $('.createBenefitDetail').hide()
        $('.editBenefitDetail').show()
        $('.editBenefitDetail #editBenefitType').val(data.benefit_type_id).trigger('change');
        $('.editBenefitDetail #editPlan').val(data.plan)
        $('.editBenefitDetail #editCoverage').val(data.coverage).trigger('change');
        $('.editBenefitDetail #editEffectiveDate').val(data.effective_date)
        $('.editBenefitDetail #editEmployeeContribution').val(data.employee_contribution)
        $('.editBenefitDetail #editCompanyContribution').val(data.company_contribution)
        $('.editBenefitDetail .benefitDetailId').val(data.id)
    }

    function deleteBenefitDetail(id) {

        let formData = {
            id,
            "_token": "{{ csrf_token() }}"
        };
        $.ajax({
            type: 'DELETE',
            url: "{{ route('benefitDetail.delete') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderBenefitDetail();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function rerenderBenefitDetail() {
        $.ajax({
            type: 'GET',
            url: "{{ route('benefitDetail.appendAll') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.benefitTable').empty();
                $('.benefitTable').append(resp);
            }
        });
    }
</script>
