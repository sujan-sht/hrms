<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('payroll.departmentwiseReport', $payrollModel->id) }}" method="GET">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="form-label">Sub-Function</label>
                    {!! Form::select('department_id', $departmentList, $value = request('department_id') ?: null, [
                        'placeholder' => 'Select Sub-Function',
                        'class' => 'form-control select-search',
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
