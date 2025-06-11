<div class="card">
    <div class="card-body">
        <legend class="text-uppercase font-size-sm font-weight-bold border-bottom">Notes</legend>
        @if(isset($dayOffs) && $dayOffs != '')
            <label id="basic-error" class="validation-invalid-label" for="basic">Day Off found on {{ $dayOffs }}</label>
        @endif
        @if(isset($holidays))
            <label id="basic-error" class="validation-invalid-label" for="basic">Holiday found on {{ $holidays }}</label>
        @endif
        @if(isset($previousLeaves))
            <label id="basic-error" class="validation-invalid-label" for="basic">Previous Leave found on {{ $previousLeaves }}</label>
        @endif
        @if(isset($preInformMessage))
            <label id="basic-error" class="validation-invalid-label" for="basic">{{ $preInformMessage }}</label>
        @endif
        @if(isset($maxLeaveMessage))
            <label id="basic-error" class="validation-invalid-label" for="basic">{{ $maxLeaveMessage }}</label>
        @endif
        @if(isset($sandwitchMessage))
            <label id="basic-error" class="validation-invalid-label" for="basic">{{ $sandwitchMessage }}</label>
        @endif
        @if(isset($finalMessage))
            <label id="basic-error" class="validation-valid-label" for="basic">{{ $finalMessage }}</label>
        @endif
    </div>
</div>