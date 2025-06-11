<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ $route }}" method="GET">
            <div class="row">
                <div class="col-md-3 mb-2">
                    @php
                        $leaveYearId = getCurrentLeaveYearId();

                        if (request('leave_year_id')) {
                            $leaveYearId = request('leave_year_id');
                        }
                    @endphp
                    <label class="form-label">Leave Year</label>
                    {!! Form::select('leave_year_id', $leaveYearList, $value = $leaveYearId ?: null, [
                        'placeholder' => 'Select Leave Year',
                        'class' => 'form-control select-search',
                        'required',
                    ]) !!}
                </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ request()->url().'?leave_year_id='.getCurrentLeaveYearId() }}" class="btn bg-secondary text-white">
                    <i class="icons icon-reset mr-1"></i>Reset
                </a>
            </div>
        </form>

    </div>
</div>
