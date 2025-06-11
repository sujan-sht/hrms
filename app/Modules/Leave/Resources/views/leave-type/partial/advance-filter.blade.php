<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ $route }}" method="GET">
            <div class="row">
                @if (Auth::user()->user_type == 'super_admin' ||
                        Auth::user()->user_type == 'hr' ||
                        Auth::user()->user_type == 'division_hr')
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

                    <div class="col-md-3 mb-2">
                        <label class="form-label">Organization</label>
                        {!! Form::select('organization_id', $organizationList, $value = request('organization_id') ?: null, [
                            // 'placeholder' => 'Select Organization',
                            'class' => 'form-control select-search organization-filter2',
                        ]) !!}
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Unit</label>
                        @php
                            if (isset($_GET['branch_id'])) {
                                $branchValue = $_GET['branch_id'];
                            } else {
                                $branchValue = null;
                            }
                        @endphp
                        {!! Form::select('branch_id', $branchList, $value = $branchValue, [
                            'placeholder' => 'Select Unit',
                            'class' => 'form-control select2 branch-filter',
                        ]) !!}
                    </div>
                @endif
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ request()->url() . '?leave_year_id=' . getCurrentLeaveYearId() }}"
                    class="btn bg-secondary text-white">
                    <i class="icons icon-reset mr-1"></i>Reset
                </a>
            </div>
        </form>

    </div>
</div>
