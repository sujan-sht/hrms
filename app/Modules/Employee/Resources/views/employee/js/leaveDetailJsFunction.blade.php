<script>
    $(document).on('click', '.leaveDetail', function() {
        rerenderLeaveReport();
        rerenderLeaveRemaining();
    });

    function rerenderLeaveReport() {
        $.ajax({
            type: 'GET',
            url: "{{ route('leaveDetail.leaveReport') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.leaveReportTable').empty();
                $('.leaveReportTable').append(resp);
            }
        });
    }

    function rerenderLeaveRemaining() {
        $.ajax({
            type: 'GET',
            url: "{{ route('leaveDetail.leaveRemaining') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.leaveRemainingTable').empty();
                $('.leaveRemainingTable').append(resp);
            }
        });
    }
</script>
