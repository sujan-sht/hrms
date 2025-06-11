<script>
    $(document).on('click', '.insuranceDetail', function() {
        //render Table of Insurance Detail
        rerenderInsuranceDetail()
    })

    ////Save Insurance Detail and re-render
    $(document).on('submit', '.submitInsuranceDetail', function(e) {
        e.preventDefault()
        createInsuranceDetail()
        var that = $(this);
        toggleCreateBtn(that)

    })

    $(document).on('click', '.createmode', function() {
        $('.createInsuranceDetail').show()
        $('.editInsuranceDetail').hide()
        $('.viewInsuranceDetail').hide()
    })

    //Save Insurance Detail and re-render
    $(document).on('submit', '.updateInsuranceDetail', function(e) {
        e.preventDefault()
        udpateInsuranceDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    function createInsuranceDetail() {
        let company_name = $('#company_name1').val();
        let gpa_enable = $('#gpa_enable').val();
        let gpa_sum_assured = $('#gpa_sum_assured').val();
        let medical_coverage = $('#medical_coverage').val();
        let individual = $('#individual').val();
        let spouse = $('#spouse').val();
        let kid_one = $('#kid_one').val();
        let kid_two = $('#kid_two').val();
        let mom = $('#mom').val();
        let dad = $('#dad').val();
        let gmi_enable = $('#gmi_enable').val();
        let individual_or_fam = $('#individual_or_fam').val();
        let gmi_sum_assured = $('#gmi_sum_assured').val();
        let hospitality_in_perc = $('#hospitality_in_perc').val();
        let hospitality_in_amt = $('#hospitality_in_amt').val();
        let domesticality_in_perc = $('#domesticality_in_perc').val();
        let domesticality_in_amt = $('#domesticality_in_amt').val();
        let employee_id = "{{ $employeeModel->id }}";

        let formData = {
            company_name,
            gpa_enable,
            gpa_sum_assured,
            medical_coverage,
            individual,
            spouse,
            kid_one,
            kid_two,
            mom,
            dad,
            gmi_enable,
            individual_or_fam,
            gmi_sum_assured,
            hospitality_in_perc,
            hospitality_in_amt,
            domesticality_in_perc,
            domesticality_in_amt,
            employee_id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.submitInsuranceDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('insuranceDetail.save') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderInsuranceDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitInsuranceDetail').trigger("reset");
                    $('.createInsurance').css('display', 'none')
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function viewInsurance(data) {
        $('.createInsuranceDetail').hide()
        $('.editInsuranceDetail').hide()
        $('.viewInsuranceDetail').show()
        $('.viewInsuranceDetail #editt_company_name1').val(data.company_name)
        $('.viewInsuranceDetail #editt_gpa_enable').val(data.gpa_enable).trigger('change')
        $('.viewInsuranceDetail #editt_gpa_sum_assured').val(data.gpa_sum_assured)
        $('.viewInsuranceDetail #editt_medical_coverage').val(data.medical_coverage)
        $('.viewInsuranceDetail #editt_individual').val(data.individual)
        $('.viewInsuranceDetail #editt_spouse').val(data.spouse)

        $('.viewInsuranceDetail #editt_kid_one').val(data.kid_one)
        $('.viewInsuranceDetail #editt_kid_two').val(data.kid_two)
        $('.viewInsuranceDetail #editt_mom').val(data.mom)
        $('.viewInsuranceDetail #editt_dad').val(data.dad)
        $('.viewInsuranceDetail #editt_gmi_enable').val(data.gmi_enable).trigger('change')
        $('.viewInsuranceDetail #editt_individual_or_fam').val(data.individual_or_fam).trigger('change')
        $('.viewInsuranceDetail #editt_gmi_sum_assured').val(data.gmi_sum_assured)
        $('.viewInsuranceDetail #editt_hospitality_in_perc').val(data.hospitality_in_perc)
        $('.viewInsuranceDetail #editt_hospitality_in_amt').val(data.hospitality_in_amt)
        $('.viewInsuranceDetail #editt_domesticality_in_perc').val(data.domesticality_in_perc)
        $('.viewInsuranceDetail #editt_domesticality_in_amt').val(data.domesticality_in_amt)

        if (data.spouse === 1) {
            $('.viewInsuranceDetail #editt_spouse').prop('checked', true);
        } else {
            $('.viewInsuranceDetail #editt_spouse').prop('checked', false);
        }
        if (data.kid_one === 1) {
            $('.viewInsuranceDetail #editt_kid_one').prop('checked', true);
        } else {
            $('.viewInsuranceDetail #editt_kid_one').prop('checked', false);
        }
        if (data.kid_two === 1) {
            $('.viewInsuranceDetail #editt_kid_two').prop('checked', true);
        } else {
            $('.viewInsuranceDetail #editt_kid_two').prop('checked', false);
        }
        if (data.mom === 1) {
            $('.viewInsuranceDetail #editt_mom').prop('checked', true);
        } else {
            $('.viewInsuranceDetail #editt_mom').prop('checked', false);
        }
        if (data.dad === 1) {
            $('.viewInsuranceDetail #editt_dad').prop('checked', true);
        } else {
            $('.viewInsuranceDetail #editt_dad').prop('checked', false);
        }
        // $('.viewInsuranceDetail .insuranceDetailId').val(data.id)
    }

    function udpateInsuranceDetail() {
        let company_name = $('#edit_company_name1').val();
        let gpa_enable = $('#edit_gpa_enable').val();
        let gpa_sum_assured = $('#edit_gpa_sum_assured').val();
        let medical_coverage = $('#edit_medical_coverage').val();
        let individual = $('#edit_individual').val();
        let spouse = $('#edit_spouse').val();
        let kid_one = $('#edit_kid_one').val();
        let kid_two = $('#edit_kid_two').val();
        let mom = $('#edit_mom').val();
        let dad = $('#edit_dad').val();
        let gmi_enable = $('#edit_gmi_enable').val();
        let individual_or_fam = $('#edit_individual_or_fam').val();
        let gmi_sum_assured = $('#edit_gmi_sum_assured').val();
        let hospitality_in_perc = $('#edit_hospitality_in_perc').val();
        let hospitality_in_amt = $('#edit_hospitality_in_amt').val();
        let domesticality_in_perc = $('#edit_domesticality_in_perc').val();
        let domesticality_in_amt = $('#edit_domesticality_in_amt').val();
        let id = $('.insuranceDetailId').val();

        let formData = {
            company_name,
            gpa_enable,
            gpa_sum_assured,
            medical_coverage,
            individual,
            spouse,
            kid_one,
            kid_two,
            mom,
            dad,
            gmi_enable,
            individual_or_fam,
            gmi_sum_assured,
            hospitality_in_perc,
            hospitality_in_amt,
            domesticality_in_perc,
            domesticality_in_amt,
            id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.updateInsuranceDetail');


        $.ajax({
            type: 'POST',
            url: "{{ route('insuranceDetail.update') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderInsuranceDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.editInsuranceDetail').hide()
                    $('.createInsuranceDetail').show()
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function editInsurance(data) {
        $('.createInsuranceDetail').hide()
        $('.editInsuranceDetail').show()
        $('.viewInsuranceDetail').hide()
        $('.editInsuranceDetail #edit_company_name1').val(data.company_name)
        $('.editInsuranceDetail #edit_gpa_enable').val(data.gpa_enable).trigger('change')
        $('.editInsuranceDetail #edit_gpa_sum_assured').val(data.gpa_sum_assured)
        $('.editInsuranceDetail #edit_medical_coverage').val(data.medical_coverage)
        $('.editInsuranceDetail #edit_individual').val(data.individual)
        $('.editInsuranceDetail #edit_spouse').val(data.spouse)

        $('.editInsuranceDetail #edit_kid_one').val(data.kid_one)
        $('.editInsuranceDetail #edit_kid_two').val(data.kid_two)
        $('.editInsuranceDetail #edit_mom').val(data.mom)
        $('.editInsuranceDetail #edit_dad').val(data.dad)
        $('.editInsuranceDetail #edit_gmi_enable').val(data.gmi_enable).trigger('change')
        $('.editInsuranceDetail #edit_individual_or_fam').val(data.individual_or_fam).trigger('change')
        $('.editInsuranceDetail #edit_gmi_sum_assured').val(data.gmi_sum_assured)
        $('.editInsuranceDetail #edit_hospitality_in_perc').val(data.hospitality_in_perc)
        $('.editInsuranceDetail #edit_hospitality_in_amt').val(data.hospitality_in_amt)
        $('.editInsuranceDetail #edit_domesticality_in_perc').val(data.domesticality_in_perc)
        $('.editInsuranceDetail #edit_domesticality_in_amt').val(data.domesticality_in_amt)

        $('.editInsuranceDetail .insuranceDetailId').val(data.id)

        if (data.spouse === 1) {
            $('.editInsuranceDetail #edit_spouse').prop('checked', true);
        } else {
            $('.editInsuranceDetail #edit_spouse').prop('checked', false);
        }
        if (data.kid_one === 1) {
            $('.editInsuranceDetail #edit_kid_one').prop('checked', true);
        } else {
            $('.editInsuranceDetail #edit_kid_one').prop('checked', false);
        }
        if (data.kid_two === 1) {
            $('.editInsuranceDetail #edit_kid_two').prop('checked', true);
        } else {
            $('.editInsuranceDetail #edit_kid_two').prop('checked', false);
        }
        if (data.mom === 1) {
            $('.editInsuranceDetail #edit_mom').prop('checked', true);
        } else {
            $('.editInsuranceDetail #edit_mom').prop('checked', false);
        }
        if (data.dad === 1) {
            $('.editInsuranceDetail #edit_dad').prop('checked', true);
        } else {
            $('.editInsuranceDetail #edit_dad').prop('checked', false);
        }
    }

    function deleteInsuranceDetail(id) {

        let formData = {
            id,
            "_token": "{{ csrf_token() }}"
        };
        $.ajax({
            type: 'DELETE',
            url: "{{ route('insuranceDetail.delete') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderInsuranceDetail();
                    $('.createInsurance').css('display', 'block')
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function rerenderInsuranceDetail() {
        $.ajax({
            type: 'GET',
            url: "{{ route('insuranceDetail.appendAll') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.insuranceTable').empty();
                $('.insuranceTable').append(resp);
            }
        });
    }

    $(document).ready(function () {
        $('#individual_or_fam').on('change', function () {
            var option = $(this).val()
            if(option == 'family'){
                $('.familyDiv').css('display', 'block')
            }else{
                $('.familyDiv').css('display', 'none')
            }
        })
        $('#edit_individual_or_fam').on('change', function () {
            var option = $(this).val()
            if(option == 'family'){
                $('.editFamilyDiv').css('display', 'block')
            }else{
                $('.editFamilyDiv').css('display', 'none')
            }
        })

        $('#editt_individual_or_fam').on('change', function () {
            var option = $(this).val()
            if(option == 'family'){
                $('.edittFamilyDiv').css('display', 'block')
            }else{
                $('.edittFamilyDiv').css('display', 'none')
            }
        })

        $('#spouse').click(function () {
            giveCheckedVal('spouse')
        });

        $('#kid_one').click(function () {
            giveCheckedVal('kid_one')
        });

        $('#kid_two').click(function () {
            giveCheckedVal('kid_two')
        });

        $('#mom').click(function () {
            giveCheckedVal('mom')
        });

        $('#dad').click(function () {
            giveCheckedVal('dad')
        });

        $('#edit_spouse').click(function () {
            giveCheckedVal('edit_spouse')
        });

        $('#edit_kid_one').click(function () {
            giveCheckedVal('edit_kid_one')
        });

        $('#edit_kid_two').click(function () {
            giveCheckedVal('edit_kid_two')
        });

        $('#edit_mom').click(function () {
            giveCheckedVal('edit_mom')
        });

        $('#edit_dad').click(function () {
            giveCheckedVal('edit_dad')
        });
        function giveCheckedVal(id) {
            if($('#'+id).prop("checked")){
                $('#'+id).val(1)
            }else{
                $('#'+id).val(0)
            }
        }
        $('#hospitality_in_perc').keyup(function () {
            update_amount('gmi_sum_assured', 'hospitality_in_amt', 'hospitality_in_perc')
        })

        $('#domesticality_in_perc').keyup(function () {
            update_amount('gmi_sum_assured', 'domesticality_in_amt', 'domesticality_in_perc')
        })

        $('#edit_hospitality_in_perc').keyup(function () {
            update_amount('edit_gmi_sum_assured', 'edit_hospitality_in_amt', 'edit_hospitality_in_perc')
        })

        $('#edit_domesticality_in_perc').keyup(function () {
            update_amount('edit_gmi_sum_assured', 'edit_domesticality_in_amt', 'edit_domesticality_in_perc')
        })

        function update_amount(sum, amt, edit) {
            var sum_amount = $('#'+sum).val()
            var perc = $('#'+edit).val()
            var dom_amount = (perc / 100)*sum_amount
            $('#'+amt).val(dom_amount)
        }

        $('#gmi_sum_assured').keyup(function () {
            $('#hospitality_in_perc').val('')
            $('#hospitality_in_amt').val('')
            $('#domesticality_in_perc').val('')
            $('#domesticality_in_amt').val('')
        })

        $('#edit_gmi_sum_assured').keyup(function () {
            $('#edit_hospitality_in_perc').val('')
            $('#edit_hospitality_in_amt').val('')
            $('#edit_domesticality_in_perc').val('')
            $('#edit_domesticality_in_amt').val('')
        })
    })

</script>
