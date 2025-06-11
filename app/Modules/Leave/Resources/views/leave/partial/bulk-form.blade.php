<table class="table table-striped">
    <thead class="text-white">
        <tr>
            <th>S.N</th>
            <th>Leave Type</th>
            <th>Remaining Leave</th>
            <th>Number of Days</th>
            <!-- <th>End Date</th> -->
        </tr>
    </thead>
    <tbody>
        @if (count($employeeLeaveList) > 0)
            @foreach ($employeeLeaveList as $key => $employeeLeave)
                <tr class="rowList">
                    <td>#{{ ++$key }}
                        {{-- {{ ($employeeLeave->leaveTypeModel->sandwitch_rule_status)}} --}}
                    </td>
                    <td>
                        <span class="leaveTypeName">{{ optional($employeeLeave->leaveTypeModel)->name }}</span>
                        <input type="hidden" name="leave_type_ids[]" value="{{ $employeeLeave->leave_type_id }}">
                    </td>
                    <td class="maxNumberOfDays">{{ $employeeLeave->leave_remaining }}</td>
                    <td>
                        {!! Form::number('number_of_days[]', null, ['placeholder' => 'e.g: 0', 'class' => 'form-control leaveDays']) !!}
                    </td>
                    <!-- <td>
                        {!! Form::text('end_date[]', null, [
                            'placeholder' => 'e.g: YYYY-MM-DD',
                            'class' => 'form-control endDate',
                            'readOnly',
                        ]) !!}
                    </td> -->
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4">No record found.</td>
            </tr>
        @endif
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $('.leaveDays').on('keyup', function() {
            var days = $(this).val();
            var maxDays = $(this).closest('.rowList').find('.maxNumberOfDays').html();
            var leaveTypeName = $(this).closest('.rowList').find('.leaveTypeName').html();
            if (parseInt(days) > parseInt(maxDays)) {
                alert('Maximum number for "' + leaveTypeName + '" is ' + maxDays +
                    ' days. So, Please choose other leave type for the remaining leave.');
                $(this).val(maxDays);
            }
            checkDayOff();

        });

        function checkDayOff() {
            var total_days = $('[name^="number_of_days[]"]').serializeArray();
            sum_day = 0;
            $.each(total_days, function(i, v) {
                if (v.value != "") {
                    sum_day += parseInt(v.value);
                }
            });

            $('.dayOff').empty();
            $.ajax({
                type: 'GET',
                url: "{{ route('leave.check.dayoff') }}",
                data: {
                    sum_day: sum_day,
                    start_date: $('[name^="start_date"]').val()
                },
                success: function(data) {
                    if (data !== 'undefined' && data !== '') {
                        $('<div/>', {
                            'class': 'card card-body',
                            html: '<h3>Day Off:</h3><p class="text-danger">' +
                                data +
                                '</p>',
                        }).appendTo($('.dayOff'));
                    }

                }
            });
        }

        function incrementDate(date_str, incrementor) {
            var parts = date_str.split("-");
            var dt = new Date(
                parseInt(parts[0], 10), // year
                parseInt(parts[1], 10) - 1, // month (starts with 0)
                parseInt(parts[2], 10) // date
            );
            dt.setTime(dt.getTime() + incrementor * 86400000);
            parts[0] = "" + dt.getFullYear();
            parts[1] = "" + (dt.getMonth() + 1);
            if (parts[1].length < 2) {
                parts[1] = "0" + parts[1];
            }
            parts[2] = "" + dt.getDate();
            if (parts[2].length < 2) {
                parts[2] = "0" + parts[2];
            }
            return parts.join("-");
        };

    });
</script>
