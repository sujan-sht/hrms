<div class="card">
    <div class="card-body">
        <legend class="text-uppercase font-size-sm font-weight-bold border-bottom">Notes</legend>
        @if(isset($previousAttendanceRequests))
            <label id="basic-error" class="validation-invalid-label" for="basic">Previous Attendance Request found on {{ $previousAttendanceRequests }}</label>
        @endif
        @if(isset($finalMessage))
            <label id="basic-error" class="validation-valid-label" for="basic">{{ $finalMessage }}</label>
        @endif
    </div>
</div>