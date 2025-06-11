<script>
    $(document).on('click', '.demotionTab', function() {
        renderDemotionView();
    })

    function renderDemotionView() {
        $.ajax({
            type: "GET",
            url: "{{ route('employeeDemotion.list') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.employeeDemotionTable').empty();
                $('.employeeDemotionTable').append(resp);
            }
        });
    }
</script>
