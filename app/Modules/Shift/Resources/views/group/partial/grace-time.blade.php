@if (isset($shift) && $shift->shiftSeasons->count() > 0)
    @foreach ($shift->shiftSeasons as $index => $season)
        {{-- @dd($season) --}}
        <legend class="text-uppercase font-size-sm font-weight-bold">
            Grace Period (In minutes) - Season {{ $index + 1 }}
        </legend>

        <input type="hidden" name="shift_season_id[]" id="shift_season_id" value="{{ $season->id }}">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Check In</label>
                    </div>
                    <div class="col-md-12">
                        {!! Form::text("ot_grace_period[$index]", null, [
                            'placeholder' => 'e.g: 10',
                            'class' => 'form-control numeric',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Check Out</label>
                    </div>
                    <div class="col-md-12">
                        {!! Form::text("grace_period_checkout[$index]", null, [
                            'placeholder' => 'e.g: 10',
                            'class' => 'form-control numeric',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Check In (for Penalty)</label>
                    </div>
                    <div class="col-md-12">
                        {!! Form::text('grace_period_checkin_for_penalty[]', null, [
                            'placeholder' => 'e.g: 10',
                            'class' => 'form-control numeric',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Check Out (for Penalty)</label>
                    </div>
                    <div class="col-md-12">
                        {!! Form::text('grace_period_checkout_for_penalty[]', null, [
                            'placeholder' => 'e.g: 10',
                            'class' => 'form-control numeric',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <legend class="text-uppercase font-size-sm font-weight-bold">Grace Period (In minutes)</legend>

    <input type="hidden" name="shift_season_id" id="shift_season_id" value="{{ @$shiftGroupModel->shift_season_id }}">
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <label class="form-label">Check In</label>
                </div>
                <div class="col-md-12">
                    {!! Form::text('ot_grace_period', @$shiftGroupModel->ot_grace_period, [
                        'placeholder' => 'e.g: 10',
                        'class' => 'form-control numeric',
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <label class="form-label">Check Out</label>
                </div>
                <div class="col-md-12">
                    {!! Form::text('grace_period_checkout', @$shiftGroupModel->grace_period_checkout, [
                        'placeholder' => 'e.g: 10',
                        'class' => 'form-control numeric',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <label class="form-label">Check In (for Penalty)</label>
                </div>
                <div class="col-md-12">
                    {!! Form::text('grace_period_checkin_for_penalty', @$shiftGroupModel->grace_period_checkin_for_penalty, [
                        'placeholder' => 'e.g: 10',
                        'class' => 'form-control numeric',
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <label class="form-label">Check Out (for Penalty)</label>
                </div>
                <div class="col-md-12">
                    {!! Form::text('grace_period_checkout_for_penalty', @$shiftGroupModel->grace_period_checkout_for_penalty, [
                        'placeholder' => 'e.g: 10',
                        'class' => 'form-control numeric',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
@endif
