<script>
    $(document).on('click', '.researchAndPublicationDetail', function() {
        //render Table of ResearchAndPublication Detail
        rerenderResearchAndPublicationDetail()
    })

    ////Save ResearchAndPublication Detail and re-render
    $(document).on('submit', '.submitResearchAndPublicationDetail', function(e) {
        e.preventDefault()
        createResearchAndPublicationDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    $(document).on('click', '.createmode', function() {
        $('.createResearchAndPublicationDetail').show()
        $('.editResearchAndPublicationDetail').hide()
    })

    //Save ResearchAndPublication Detail and re-render
    $(document).on('submit', '.updateResearchAndPublicationDetail', function(e) {
        e.preventDefault()
        udpateResearchAndPublicationDetail()
        var that = $(this);
        toggleCreateBtn(that)
    })

    function createResearchAndPublicationDetail() {
        let research_title = $('#research_title').val();
        let note = $('#note1').val();
        let employee_id = "{{ $employeeModel->id }}";

        let formData = {
            research_title,
            note,
            employee_id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.submitResearchAndPublicationDetail');


        $.ajax({
            type: 'POST',
            url: "{{ route('researchAndPublicationDetail.save') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderResearchAndPublicationDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.submitResearchAndPublicationDetail').trigger("reset");
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function udpateResearchAndPublicationDetail() {
        let research_title = $('#edit_research_title').val();
        let note = $('#edit_note1').val();
        let id = $('.researchAndPublicationDetailId').val();

        let formData = {
            research_title,
            note,
            id,
            "_token": "{{ csrf_token() }}"
        };
        var that = $('.updateResearchAndPublicationDetail');


        $.ajax({
            type: 'POST',
            url: "{{ route('researchAndPublicationDetail.update') }}",
            data: formData,
            success: function(resp) {
                if (resp.status = 1) {
                    toastr.success(resp.message);
                    rerenderResearchAndPublicationDetail();
                    that.find('button[type=submit]').attr('disabled', false).find('.spinner').remove();
                    $('.editResearchAndPublicationDetail').hide()
                    $('.createResearchAndPublicationDetail').show()
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function editResearchAndPublication(data) {
        // console.log(data)
        $('.createResearchAndPublicationDetail').hide()
        $('.editResearchAndPublicationDetail').show()

        $('.updateResearchAndPublicationDetail').trigger("reset");

        $('.editResearchAndPublicationDetail #edit_research_title').val(data.research_title)

        if (data.note != null) {
            tinymce.get("edit_note1").setContent(data.note);
        }
        // $('.editResearchAndPublicationDetail #edit_note1').val(data.note)
        $('.editResearchAndPublicationDetail .researchAndPublicationDetailId').val(data.id)
    }

    function deleteResearchAndPublicationDetail(id) {

        let formData = {
            id,
            "_token": "{{ csrf_token() }}"
        };
        $.ajax({
            type: 'DELETE',
            url: "{{ route('researchAndPublicationDetail.delete') }}",
            data: formData,
            success: function(resp) {
                if (resp.status == 1) {
                    toastr.success(resp.message);
                    rerenderResearchAndPublicationDetail();
                    return
                }
                toastr.error(resp.message);
                return
            }
        });
    }

    function rerenderResearchAndPublicationDetail() {
        $.ajax({
            type: 'GET',
            url: "{{ route('researchAndPublicationDetail.appendAll') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.researchAndPublicationTable').empty();
                $('.researchAndPublicationTable').append(resp);
            }
        });
    }
</script>
