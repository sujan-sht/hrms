<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'training.index', 'method' => 'get']) !!}
        <div class="row">
            <div class="col-md-3">
                <label class="d-block font-weight-semibold">Fiscal Year:</label>
                <div class="input-group">
                    {!! Form::select('fiscal_year_id', $fiscalYearList, request('fiscal_year_id') ?? null, ['class'=>'form-control', 'placeholder'=>'Select Fiscal Year']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <label class="d-block font-weight-semibold">Organization:</label>
                <div class="input-group">
                    {!! Form::select('division_id', $organizationList, request('division_id') ?? null, ['class'=>'form-control select2','placeholder'=>'Select Organization']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <label class="d-block font-weight-semibold">Type:</label>
                <div class="input-group">
                    {!! Form::select('type', ['functional'=>'Functional', 'behavioural'=>'Behavioural'], request('type') ?? null, ['class'=>'form-control select2','placeholder'=>'Select Type']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <label class="d-block font-weight-semibold">Location:</label>
                <div class="input-group">
                    {!! Form::select('location', ['physical'=>'Physical', 'virtual'=>'Virtual'], request('location') ?? null, ['class'=>'form-control select2','placeholder'=>'Select Location']) !!}
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <label class="d-block font-weight-semibold">Facilitator:</label>
                <div class="input-group">
                    {!! Form::select('facilitator', ['internal'=>'Internal', 'external'=>'External'], request('facilitator') ?? null, ['class'=>'form-control select2','placeholder'=>'Select Facilitator']) !!}
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <label class="d-block font-weight-semibold">Month:</label>
                <div class="input-group">
                    {!! Form::select('month', $monthList, request('month') ?? null, ['class'=>'form-control select2','placeholder'=>'Select Month']) !!}
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
            <a href="{{ route('training.index')  }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $('.select2').select2();
</script>



