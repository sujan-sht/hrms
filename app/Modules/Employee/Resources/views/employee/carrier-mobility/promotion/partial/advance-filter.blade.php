<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ $route }}" method="GET">
            <div class="row">
                <div class="col-md-3">
                    <label class="d-block font-weight-semibold">Select Organization:</label>
                    <div class="input-group">
                        @php $selected_org_id = isset(request()->org_id) ? request()->org_id : 1 ; @endphp

                        {!! Form::select('org_id', $organizationList, $selected_org_id, [
                            'class' => 'form-control select-search organization-filter',
                            'placeholder' => 'Select Organization',
                        ]) !!}
                    </div>
                </div>
                {{-- <div class="col-md-3">
                    <label class="d-block font-weight-semibold">Select Unit:</label>
                    <div class="input-group">
                        @php $selected_branch_id = isset(request()->branch_id) ? request()->branch_id : null ; @endphp
                        {!! Form::select('branch_id', $branchList, $selected_branch_id, [
                            'class' => 'form-control select-search branch-filter',
                            'placeholder' => 'Select Unit',
                        ]) !!}
                    </div>
                </div> --}}
                <div class="col-md-3">
                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                    {!! Form::select('employee_id', $employeeList, $value = request('employee_id') ?: null, [
                        'placeholder' => 'Select Employee',
                        'class' => 'form-control select-search employee-filter',
                        'required',
                    ]) !!}
                </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ request()->url() }}" class="btn bg-secondary text-white">
                    <i class="icons icon-reset mr-1"></i>Reset
                </a>
            </div>
        </form>

    </div>
</div>
