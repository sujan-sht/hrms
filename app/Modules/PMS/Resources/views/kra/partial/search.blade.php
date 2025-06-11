<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'kra.index', 'method' => 'get']) !!}
        <div class="row">
            <div class="col-md-3">
                <label class="d-block font-weight-semibold">Organization:</label>
                <div class="input-group">
                    {!! Form::select('division_id', $organizationList, request('division_id') ?? null, [
                        'class' => 'form-control select2 organization-filter2',
                        'placeholder' => 'Select Organization',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <label class="d-block font-weight-semibold">Sub-Function:</label>
                <div class="input-group">
                    {!! Form::select('department_id', $departmentList, request('department_id') ?? null, [
                        'class' => 'form-control select2 department-filter',
                        'placeholder' => 'Select Sub-Function',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
            <a href="{{ route('kra.index') }}" class="btn bg-secondary text-white"><i
                    class="icons icon-reset mr-1"></i>Reset</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $('.select2').select2();
</script>
