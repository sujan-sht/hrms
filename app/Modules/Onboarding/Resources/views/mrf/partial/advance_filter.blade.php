<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => $route, 'method' => 'get']) !!}
        <div class="row">
            <div class="col-md-2">
                <label class="d-block font-weight-semibold">Organization</label>
                <div class="input-group">
                    {!! Form::select('organization', $organizationList, request('organization') ?? null, [
                        'placeholder' => 'Select Organization',
                        'class' => 'form-control select-search organization-filter2',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-2">
                <label class="d-block font-weight-semibold">Sub-Function</label>
                <div class="input-group">
                    {!! Form::select('department', $departmentList, request('department') ?? null, [
                        'placeholder' => 'Select Sub-Function',
                        'class' => 'form-control select-search department-filter',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-2">
                <label class="d-block font-weight-semibold">Designation</label>
                <div class="input-group">
                    {!! Form::select('designation', $designationList, request('designation') ?? null, [
                        'placeholder' => 'Select Designation',
                        'class' => 'form-control select-search designation-filter',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-2">
                <label class="d-block font-weight-semibold">Status</label>
                <div class="input-group">
                    @php
                        $statusList[3] = 'Publish';
                    @endphp
                    {!! Form::select('status', $statusList, request('status') ?? null, [
                        'placeholder' => 'Select Status',
                        'class' => 'form-control select-search',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
            <a href="{{ request()->url() }}" class="btn bg-secondary text-white"><i
                    class="icons icon-reset mr-1"></i>Reset</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>
