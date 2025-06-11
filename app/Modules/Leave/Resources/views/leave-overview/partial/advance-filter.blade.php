<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ $route }}" method="GET">
            <div class="row">
                {{-- <div class="col-md-3 mb-2">
                    <label for="example-email" class="form-label">Date Range</label>
                    {!! Form::text('date_range', $value = request('date_range') ? : null, ['placeholder' => 'e.g : YYYY-MM-DD to YYYY-MM-DD','class' => 'form-control daterange-buttons','autocomplete' => 'on']) !!}
                </div> --}}

                {{-- @if (Auth::user()->user_type=='super_admin' || Auth::user()->user_type=='admin' || Auth::user()->user_type=='hr')
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Organization</label>
                        {!! Form::select('organization_id', $organizationList, $value = request('organization_id') ? : null, ['placeholder'=>'Select Organization', 'class'=>'form-control select2 organization-filter organization-filter2']) !!}
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Branch</label>
                        {!! Form::select('branch_id', $branchList, $value = request('branch_id') ? : null, ['placeholder'=>'Select Branch', 'class'=>'form-control select2 branch-filter']) !!}
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Employee</label>
                        {!! Form::select('employee_id', $employeeList, $value = request('employee_id') ? : null, ['placeholder'=>'Select Employee', 'class'=>'form-control select2 employee-filter']) !!}
                    </div>
                @elseif (Auth::user()->user_type == 'supervisor')
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Employee</label>
                        {!! Form::select('employee_id', $employeeList, $value = request('employee_id') ? : null, ['placeholder'=>'Select Employee', 'class'=>'form-control select2']) !!}
                    </div>
                @endif --}}

                <div class="col-md-4 mb-2">
                    <label class="form-label">Leave Type</label>
                    {!! Form::select('leave_type_id', $leaveTypeList, $value = request('leave_type_id') ? : array_key_first($leaveTypeList), ['placeholder'=>'Select Leave Type', 'class'=>'form-control select-search']) !!}
                </div>

                {{-- <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label class="d-block font-weight-semibold">From Date:</label>
                        <div class="input-group">
                            @php
                                if (setting('calendar_type') == 'BS'){
                                    $classData = 'form-control nepali-calendar';
                                }else{
                                    $classData = 'form-control daterange-single';
                                }
                            @endphp
                            {!! Form::text('from_date', request('from_date') ?? null, ['placeholder'=>'e.g: YYYY-MM-DD', 'class'=>$classData]) !!}
                        </div>
                    </div>
                </div>
    
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label class="d-block font-weight-semibold">To Date:</label>
                        <div class="input-group">
                            {!! Form::text('to_date', request('to_date') ?? null, ['placeholder'=>'e.g: YYYY-MM-DD', 'class'=>$classData]) !!}
                        </div>
                    </div>
                </div> --}}

                {{-- <div class="col-md-3 mb-2">
                    <label class="form-label">Leave Category</label>
                    {!! Form::select('leave_kind', $leaveKindList, $value = request('leave_kind') ? : null, ['placeholder'=>'Select Leave Category', 'class'=>'form-control select2']) !!}
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Status</label>
                    {!! Form::select('status', $statusList, $value = request('status') ? : null, ['placeholder'=>'Select Status', 'class'=>'form-control select2']) !!}
                </div> --}}
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
