<script>
    $(document).on('click', '.emergencyDetail', function() {
        //render Table of Emergency Detail
        rerenderEmergencyDetail()
    })

    ////Save Emergency Detail and re-render
    $(document).on('submit', '.submitEmergencyDetail', function(e) {
        e.preventDefault()
        createEmergencyDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    $(document).on('click', '.createmode', function() {
        $('.createEmergencyDetail').show()
        $('.editEmergencyDetail').hide()
    })

    //Save Emergency Detail and re-render
    $(document).on('submit', '.updateEmergencyDetail', function(e) {
        e.preventDefault()
        udpateEmergencyDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    function createEmergencyDetail() {
        let name = $('.submitEmergencyDetail #name').val();
        let phone1 = $('.submitEmergencyDetail #phone').val();
        let phone2 = $('.submitEmergencyDetail #phone1').val();
        let relation = $('.submitEmergencyDetail #relation').val();
        let address = $('.submitEmergencyDetail #address').val();
        let note = $('.submitEmergencyDetail #note').val();
        let employee_id = "{{ $employeeModel->id }}";

        let formData = {
            name,
            phone1,
            phone2,
            address,
            note,
            relation,
            employee_id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.submitEmergencyDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('emergencyDetail.save') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderEmergencyDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitEmergencyDetail').trigger("reset");
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function udpateEmergencyDetail() {
        let name = $('.editEmergencyDetail #editname').val();
        let phone1 = $('.editEmergencyDetail #editphone').val();
        let phone2 = $('.editEmergencyDetail #editphone1').val();
        let relation = $('.editEmergencyDetail #editrelation').val();
        let address = $('.editEmergencyDetail #editaddress').val();
        let note = $('.editEmergencyDetail #editnote').val();
        let id = $('.emergencyId').val();

        let formData = {
            name,
            phone1,
            phone2,
            relation,
            address,
            note,
            id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.editEmergencyDetail');

        $.ajax({
            type: 'POST',
            url: "{{ route('emergencyDetail.update') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderEmergencyDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.editEmergencyDetail').hide()
                    $('.createEmergencyDetail').show()
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function editEmergency(data) {
        $('.createEmergencyDetail').hide()
        $('.editEmergencyDetail').show()
        $('.editEmergencyDetail #editname').val(data.name)
        $('.editEmergencyDetail #editphone').val(data.phone1)
        $('.editEmergencyDetail #editphone1').val(data.phone2)
        $('.editEmergencyDetail #editaddress').val(data.address)
        $('.editEmergencyDetail #editrelation').val(data.relation).trigger('change')
        $('.editEmergencyDetail #editnote').val(data.note)
        $('.editEmergencyDetail .emergencyId').val(data.id)
    }

    function deleteEmergencyDetail(id) {

        let formData = {
            id,
            "_token": "{{ csrf_token() }}"
        };
        $.ajax({
            type: 'DELETE',
            url: "{{ route('emergencyDetail.delete') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderEmergencyDetail();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function rerenderEmergencyDetail() {
        $.ajax({
            type: 'GET',
            url: "{{ route('emergencyDetail.appendAll') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.emergencyDetailtable').empty();
                $('.emergencyDetailtable').append(resp);
            }
        });
    }
</script>
