<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        {!! Form::open(['route'=>'tadaRequest.index','method'=>'GET','class'=>'form-horizontal', 'id'=> 'tada_filter', 'role'=>'form']) !!}
        <div class="row">
            @if (Auth::user()->user_type=='super_admin' || Auth::user()->user_type=='admin' || Auth::user()->user_type=='hr')
                <div class="col-md-3">
                    <label class="form-label">Organization</label>
                    {!! Form::select('organization_id', $organizationList, $value = request('organization_id') ? : null, ['placeholder'=>'Select Organization', 'class'=>'form-control select2 organization-filter organization-filter2']) !!}
                </div>
                <div class="col-md-3">
                    <label class="form-label">Branch</label>
                    {!! Form::select('branch_id', $branchList, $value = request('branch_id') ? : null, ['placeholder'=>'Select Branch', 'class'=>'form-control select2 branch-filter']) !!}
                </div>
                <div class="col-md-3">
                    <label class="form-label">Employee</label>
                    {!! Form::select('emp_id', $employeeList, $value = request('emp_id') ? : null, ['placeholder'=>'Select Employee', 'class'=>'form-control select2 employee-filter']) !!}
                </div>
            @elseif (Auth::user()->user_type == 'supervisor')
                <div class="col-md-3">
                    <label class="form-label">Employee</label>
                    {!! Form::select('emp_id', $employeeList, $value = request('emp_id') ? : null, ['placeholder'=>'Select Employee', 'class'=>'form-control select2']) !!}
                </div>
            @endif

            {{-- <div class="col-md-3">
                <div class="form-group mb-0">
                    <label class="d-block font-weight-semibold">Requested Date:</label>
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-calendar"></i></span>
                        </span>
                        {!! Form::text('requested_date', request('requested_date') ?? null, ['placeholder'=>'e.g: YYYY-MM-DD', 'class'=>'form-control daterange-single']) !!}
                    </div>
                </div>
            </div> --}}

            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label class="d-block font-weight-semibold">Status:</label>
                    <div class="input-group">
                        {{-- @php $selected_search_value = isset($selected_search_value) && !empty($selected_search_value) ? $selected_search_value : ''; @endphp
                        {!! Form::select('status', $statusList, $value = null, [ 'class' => 'form-control', 'placeholder' => 'Select Status', 'required']) !!} --}}
                        {!! Form::select('status', $statusList, request('status') ?? null, ['class'=>'form-control select2', 'placeholder'=>'Select Status']) !!}

                    </div>
                </div>
            </div>
            {{-- <div class="col-md-3 mt-2">
                <div class="form-group mb-0">
                    <label class="d-block font-weight-semibold">Title:</label>
                    <div class="input-group">
                        {!! Form::text('title', request('title') ?? null, ['class'=>'form-control', 'placeholder'=>'Search by title']) !!}
                    </div>
                </div>
            </div> --}}
        </div>

        <div class="d-flex justify-content-end mt-2">
            <button class="btn bg-yellow mr-2" type="submit">
                <i class="icon-filter3 mr-1"></i>Filter
            </button>
            <a href="{{ route('tada.index') }}" class="btn bg-secondary text-white">
                <i class="icons icon-reset mr-1"></i>Reset
            </a>
        </div>
        {!! Form::close() !!}
    </div>
</div>


<script>
    $(document).ready(function () {
        $('.select2').select2();
    })
</script>
