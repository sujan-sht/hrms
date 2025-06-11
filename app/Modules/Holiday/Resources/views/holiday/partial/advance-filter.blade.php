<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ $route }}" method="GET">
            <div class="row">
                <div class="col-md-3 mb-2">
                    @php
                        $fiscalYearId = getCurrentFiscalYearId();

                        if (request('fiscal_year_id')) {
                            $fiscalYearId = request('fiscal_year_id');
                        }
                    @endphp
                    <label class="form-label">Fiscal Year</label>
                    {!! Form::select('fiscal_year_id', $fiscalYearList, $value = $fiscalYearId ?: null, [
                        'placeholder' => 'Select Fiscal Year',
                        'class' => 'form-control select-search',
                        'required',
                    ]) !!}
                </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ request()->url().'?fiscal_year_id='.getCurrentFiscalYearId() }}" class="btn bg-secondary text-white">
                    <i class="icons icon-reset mr-1"></i>Reset
                </a>
            </div>
        </form>

    </div>
</div>
