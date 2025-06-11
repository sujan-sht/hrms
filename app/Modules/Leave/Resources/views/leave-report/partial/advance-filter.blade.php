<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ $route }}" method="GET">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="form-label">Leave Year</label>
                    {!! Form::select('leave_year_id', $leaveYearList, $value = request('leave_year_id') ?: null, [
                        'id'=>'leave_year_id',
                        'placeholder' => 'Select Leave Year',
                        'class' => 'form-control select-search',
                        'required'
                    ]) !!}
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Organization<span class="text-danger"> *</span></label>
                    {!! Form::select('organization_id', $organizationList, $value = request('organization_id') ? : null, ['placeholder'=>'Select Organization', 'class'=>'form-control select-search','required']) !!}
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Leave Type<span class="text-danger"> *</span></label>
                    {{-- {!! Form::select('leave_type_id', $leaveTypeList, $value = request('leave_type_id') ? : null, ['placeholder'=>'Select Leave Type', 'class'=>'form-control select-search','required']) !!} --}}
                    {!! Form::select('leave_type_id', [], $value = request('leave_type_id') ? : null, ['placeholder'=>'Select Leave Type', 'class'=>'form-control select-search','required','id'=>'leave_type_id']) !!}
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Employee</label>
                    {!! Form::select('employee_id[]', $employee_list, $value = request('employee_id') ? : null, ['class'=>'form-control multiselect-select-all-filtering','multiple']) !!}
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
