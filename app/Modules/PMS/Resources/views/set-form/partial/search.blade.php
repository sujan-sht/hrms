<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'set-form.index', 'method' => 'get']) !!}
        <div class="row">
            @if (Auth::user()->user_type == 'employee')
                {!! Form::hidden('emp_id', $value = optional(auth()->user()->userEmployer)->id, []) !!}
            @elseif(Auth::user()->user_type == 'super_admin' || Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'hr')
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Organization:</label>
                        <div class="input-group">
                            @php $selected_org_id = isset(request()->org_id) ? request()->org_id : null ; @endphp
                            {!! Form::select('org_id', $organizationList, $selected_org_id, [
                                'class' => 'form-control select2 organization-filter organization-filter2',
                                'placeholder' => 'Select Organization',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Branch:</label>
                        <div class="input-group">
                            @php $selected_branch_id = isset(request()->branch_id) ? request()->branch_id : null ; @endphp
                            {!! Form::select('branch_id', $branchList, $selected_branch_id, [
                                'class' => 'form-control branch-filter',
                                'placeholder' => 'Select Branch',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Employee:</label>
                        <div class="input-group">
                            @php $selected_emp_id = isset(request()->emp_id) ? request()->emp_id : null ; @endphp
                            {!! Form::select('emp_id', $employeeList, $selected_emp_id, [
                                'class' => 'form-control employee-filter',
                                'placeholder' => 'Select Employee',
                            ]) !!}
                        </div>
                    </div>
                </div>
            @elseif(Auth::user()->user_type == 'supervisor')
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Employee:</label>
                        <div class="input-group">
                            @php $selected_emp_id = isset(request()->emp_id) ? request()->emp_id : null ; @endphp
                            {!! Form::select('emp_id', $employeeList, $selected_emp_id, [
                                'class' => 'form-control select2',
                                'placeholder' => 'Select Employee',
                            ]) !!}
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Select Status:</label>
                    <div class="input-group">
                        @php $selected_status_id = isset(request()->status) ? request()->status : null ; @endphp
                        {!! Form::select('status', $statusList, $selected_status_id, [
                            'class' => 'form-control select2',
                            'placeholder' => 'Select Status',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
            <a href="{{ route('set-form.index') }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.select2').select2()
    })
</script>
