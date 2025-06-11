<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'target.index', 'method' => 'get']) !!}
        <div class="row">
            <div class="col-md-3">
                <label class="d-block font-weight-semibold">Fiscal Year:</label>
                <div class="input-group">
                    {!! Form::select('fiscal_year_id', $fiscalYearList, request('fiscal_year_id') ?? null, ['class'=>'form-control', 'placeholder'=>'Select Fiscal Year']) !!}
                </div>
            </div>

            <div class="col-md-3">
                <label class="d-block font-weight-semibold">KRA:</label>
                <div class="input-group">
                    {!! Form::select('kra_id', $kraList, request('kra_id') ?? null, ['class'=>'form-control select2', 'placeholder'=>'Select KRA']) !!}
                </div>
            </div>

            <div class="col-md-3">
                <label class="d-block font-weight-semibold">KPI:</label>
                <div class="input-group">
                    {!! Form::select('kpi_id', $kpiList, request('kpi_id') ?? null, ['class'=>'form-control select2', 'placeholder'=>'Select KPI']) !!}
                </div>
            </div>

            <div class="col-md-3">
                <label class="d-block font-weight-semibold">Frequency:</label>
                <div class="input-group">
                    {!! Form::select('frequency', ['yearly'=>'Yearly', 'quarterly'=>'Quarterly', 'monthly' =>'Monthly', 'daily'=>'Daily'], request('frequency') ?? null, ['class'=>'form-control', 'placeholder'=>'Select Frequency']) !!}
                </div>
            </div>

            <div class="col-md-3 mt-2">
                <label class="d-block font-weight-semibold">Target:</label>
                <div class="input-group">
                    {!! Form::text('title', request('title') ?? null, ['class'=>'form-control', 'placeholder'=>'Enter Target Title']) !!}
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
            <a href="{{ route('target.index') }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $('.select2').select2();
</script>
