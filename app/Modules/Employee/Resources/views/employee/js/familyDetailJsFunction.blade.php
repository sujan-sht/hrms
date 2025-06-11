<script>
    $('.edit_district_id').select2();

    $(document).on('click', '.familyDetail', function() {
        //render Table of Family Detail
        rerenderFamilyDetail()
    })

    $(document).on('click', '.createmode', function() {
        $('.createFamilyDetail').show()
        $('.editFamilyDetail').hide()
    })

    ////Save Family Detail and re-render
    $(document).on('submit', '.submitFamilyDetail', function(e) {
        e.preventDefault()
        var that = $(this);
        toggleCreateBtn(that)
        createFamilyDetail()
    })

    //Save Family Detail and re-render
    $(document).on('submit', '.updateFamilyDetail', function(e) {
        e.preventDefault()
        var that = $(this);
        toggleCreateBtn(that)
        udpateFamilyDetail()
    })

    function createFamilyDetail() {

        let name = $('#name').val();
        let relation = $('#relation').val();
        let dob = $('#dob').val();
        let contact = $('#contact_no').val();
        let is_emergency_contact = $('#is_emergency_contact').val();
        let is_dependent = $('#is_dependent').val();
        let employee_same = $('#employee_same:checked').val();
        let is_nominee_detail = $("#is_nominee_detail:checked").val();
        let employee_different = $('#employee_different:checked').val();

        let address = $("#address").val();
        let province_id = $("#province_id").val();
        let district_id = $("#district_id").val();
        let municipality = $("#municipality").val();
        let ward_no = $("#ward_no").val();


        let late_status = $('input[name="late_status"]:checked').val();
        let include_in_medical_insurance = $('#include_in_medical_insurance').val();
        let employee_id = "{{ $employeeModel->id }}";


        let formData = {
            name,
            relation,
            employee_id,
            dob,
            contact,
            is_emergency_contact,
            is_dependent,
            employee_same,
            address,
            province_id,
            district_id,
            municipality,
            ward_no,
            employee_different,
            is_nominee_detail,
            late_status,
            include_in_medical_insurance,
            "_token": "{{ csrf_token() }}"
        };





        var that = $('.submitFamilyDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('familyDetail.save') }}",
            data: formData,

            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderFamilyDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitFamilyDetail').get(0).reset();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function udpateFamilyDetail() {

        let id = $('.memberId').val();
        let dob = $('#editdob').val();
        let name = $("#editname").val();
        let relation = $("#editrelation").val();
        let contact = $("#editcontact_no").val();
        let is_emergency_contact = $('#edit_is_emergency_contact:checked').val();
        let is_dependent = $('#edit_is_dependent:checked').val();
        let employee_same = $('#edit_employee_same:checked').val();
        let is_nominee_detail = $("#edit_is_nominee_detail:checked").val();
        let employee_different = $('#edit_employee_different').val();
        let address = $("#edit_address").val();
        let province_id = $("#edit_province_id").val();
        let district_id = $("#edit_district_id").val();
        let municipality = $("#edit_municipality").val();
        let ward_no = $("#edit_ward_no").val();
        let late_status = $('#edit_late_status:checked').val();
        let include_in_medical_insurance = $('#edit_include_in_medical_insurance:checked').val();
        let employee_id = "{{ $employeeModel->id }}";

        let formData = {
            id,
            dob,
            name,
            is_emergency_contact,
            is_dependent,
            employee_same,
            is_nominee_detail,
            employee_different,
            address,
            province_id,
            district_id,
            municipality,
            ward_no,
            late_status,
            include_in_medical_insurance,
            employee_id,
            relation,
            contact,
            "_token": "{{ csrf_token() }}"
        };

        var that = $('.updateFamilyDetail');




        $.ajax({
            type: 'POST',
            url: "{{ route('familyDetail.update') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderFamilyDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.editFamilyDetail').hide()
                    $('.createFamilyDetail').show()
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function editMember(data) {
        $('.createFamilyDetail').hide()
        $('.editFamilyDetail').show()
        $('.editFamilyDetail #editname').val(data.name)
        $('.editFamilyDetail #editrelation').val(data.relation).trigger('change');
        $('.editFamilyDetail #editcontact_no').val(data.contact)
        $('.editFamilyDetail .memberId').val(data.id)
        $('.editFamilyDetail #editdob').val(data.dob)


        if (data.is_nominee_detail == 1) {
            $("#edit_is_nominee_detail[value='1']").prop("checked", true);
        } else {
            $("#edit_is_nominee_detail[value='0']").prop("checked", true);
        }

        if (data.is_emergency_contact == 1) {
            $("#edit_is_emergency_contact[value='1']").prop("checked", true);
        } else {
            $("#edit_is_emergency_contact[value='0']").prop("checked", true);
        }

        if (data.is_dependent == 1) {
            $("#edit_is_dependent[value='1']").prop("checked", true);
        } else {
            $("#edit_is_dependent[value='0']").prop("checked", true);
        }

        if (data.include_in_medical_insurance == '1') {
            $("#edit_include_in_medical_insurance[value='1']").prop("checked", true);
        } else {
            $("#edit_include_in_medical_insurance[value='0']").prop("checked", true);
        }


        if (data.late_status == '1') {
            $(".editFamilyDetail #edit_late_status[value='1']").prop("checked", true);
        } else {
            $(".editFamilyDetail #edit_late_status[value='0']").prop("checked", true);
        }


        if (data.same_as_employee != null) {
            $('.editFamilyDetail #edit_employee_same').attr('checked', true);
            $('.editFamilyDetail #edit_family_address').css('display', 'none');
        } else {
            $('.editFamilyDetail #edit_family_address').css('display', 'block');
            $('.editFamilyDetail #edit_employee_different').attr('checked', true);
            $('.editFamilyDetail #edit_province_id').val(data.province_id);

            console.log(data.province_id, 'hello')
            if (data.province_id) {
                // Set selected option
                $('#edit_province_id').val(data.province_id);

                // If you are using Select2 plugin (which is common with 'select-search')
                // Then you may need to trigger an update:
                $('#edit_province_id').trigger('change');
            }

            if (data.district_id) {
                // Set selected option
                $('#edit_district_id').val(data.district_id);

                // If you are using Select2 plugin (which is common with 'select-search')
                // Then you may need to trigger an update:
                $('#edit_district_id').trigger('change');
            }

            if (data.province_id != null) {
                $(".editFamilyDetail #districtDiv").css('display', 'block');
                var provinceId = $(".editFamilyDetail #edit_province_id").val();
                if (provinceId !== '') {
                    $.ajax({
                        url: '{{ route('event.get-districts') }}',
                        method: 'GET',
                        data: {
                            province_id: provinceId
                        },
                        success: function(response) {
                            var $districtSelect = $('.editFamilyDetail #edit_district_id');
                            $districtSelect.empty();
                            $districtSelect.append('<option value="">Select a District</option>');
                            $.each(response, function(key, district) {
                                $districtSelect.append(
                                    '<option value="' + key + '">' + district +
                                    '</option>'
                                );
                            });
                            if (data.district_id) {
                                $districtSelect.val(data.district_id);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred while fetching districts:", error);
                        }
                    });
                }
            }

            $('.editFamilyDetail #edit_municipality').val(data.municipality);
            $('.editFamilyDetail #edit_ward_no').val(data.ward_no);
            $('.editFamilyDetail #edit_address').val(data.family_address);
        }
    }

    function deleteFamilyDetail(id) {

        let formData = {
            id,
            "_token": "{{ csrf_token() }}"
        };
        $.ajax({
            type: 'DELETE',
            url: "{{ route('familyDetail.delete') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderFamilyDetail();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function rerenderFamilyDetail() {
        $.ajax({
            type: 'GET',
            url: "{{ route('familyDetail.appendAll') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.familyTable').empty();
                $('.familyTable').append(resp);
            }
        });
    }
</script>
