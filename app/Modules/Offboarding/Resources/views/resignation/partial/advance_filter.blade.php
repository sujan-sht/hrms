<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open(['route'=>'resignation.index', 'method'=>'get']) !!}

            <div class="row">
                <div class="col-md-3">
                    <label class="d-block font-weight-semibold">Employee</label>
                    <div class="input-group">
                        {!! Form::select('employee_id', $employeeList, request('employee_id') ?? null, ['placeholder'=>'Select Employee', 'class'=>'form-control select-search']) !!}
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-2">
                <label class="d-block font-weight-semibold">Apply Date</label>
                <div class="input-group">
                    {!! Form::text('date', request('date') ?? null, ['placeholder'=>'e.g: YYYY-MM-DD', 'class'=>'form-control daterange-single']) !!}
                </div>
            </div> -->
            <div class="d-flex justify-content-end mt-2">
                <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
                <a href="{{ request()->url() }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a>
            </div>
        {!! Form::close() !!}
    </div>
</div>
