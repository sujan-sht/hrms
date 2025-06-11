@forelse($empAttendanceLogs as $key => $attendanceLog)
    @php
        $coordinates = [];
        $atdCoordinates = array('lat'=>$attendanceLog->lat, 'long'=>$attendanceLog->long);
        if ($attendanceLog->inout_mode == 0) {
            $atdCoordinates += array('color' => 'green', 'type' => 'Check in');
        }elseif ($attendanceLog->inout_mode == 1) {
            $atdCoordinates += array('color' => 'red', 'type' => 'Check out');
        }
        $coordinates[0] = $atdCoordinates;
    @endphp
    <tr>
        <td width="5%">{{ ++$key }}</td>
        <td>{{ $attendanceLog->date }}</td>
        <td>{{ $attendanceLog->time ? date('h:i A', strtotime($attendanceLog->time)) : '' }}</td>
        <td>{{ $attendanceLog->inout_mode == 0 ? 'Check In' : 'Check Out' }}</td>
        <td>{{ Str::ucfirst($attendanceLog->punch_from) }}</td>
        <td>
            @if (!empty($coordinates))
                <i class="icon-location3 getLocation" style="color: blue"
                    data-location="{{ json_encode($coordinates) }}"></i>
            @endif
        </td>
    </tr>
@empty
    <tr>    
        <td colspan="5">No Attendance Logs Found !!!</td>
    </tr>
@endforelse
