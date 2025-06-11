<script>
    $(document).on('click', '.carrierMobilityTab', function() {
        renderCarrierMobilityView();
    })

    function renderCarrierMobilityView() {
        $.ajax({
            type: "GET",
            url: "{{ route('employeeCarrierMobility.list') }}",
            data: {
                emp_id: "{{ $employeeModel->id }}",
            },
            success: function(resp) {
                $('.employeeCarrierMobilityTable').empty();
                $('.employeeCarrierMobilityTable').append(resp);
            }
        });
    }
</script>
