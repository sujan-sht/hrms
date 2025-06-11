<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'arrearAdjustment.index', 'method' => 'get']) !!}
        <div class="row">
            <div class="col-md-3">
                <label class="d-block font-weight-semibold">Organization</label>
                <div class="input-group">
                    {!! Form::select('organization_id', $organizationList, null, ['class' => 'form-control select-search organizationID']) !!}
                </div>
                {{-- <div class="input-group">
                    {!! Form::select('organization_id', $organizationList, request('organization_id') ?? null, [
                        'placeholder' => 'Select Organization',
                        'class' => 'form-control select-search organization-filter',
                    ]) !!}
                </div> --}}
            </div>
            <div class="col-md-2 year" style="display:none">
                <label class="d-block font-weight-semibold">Year</label>
                {{-- <div class="input-group">
                        {!! Form::select('year', $nepaliYearList, request('year') ?? null, ['placeholder'=>'Select Year', 'class'=>'form-control select-search']) !!}
                    </div> --}}
                @php
                    if (isset($_GET['year']) && $_GET['year']) {
                        $year = $_GET['year'];
                    }
                    elseif (isset($_GET['eng_year']) && $_GET['eng_year']) {
                        $year = $_GET['eng_year'];
                    } else {
                        $year = null;
                    }
                @endphp
                <div class="input-group engDiv" style="display: none;">
                    {!! Form::select('eng_year', $yearList, $value = $year, [
                        'id' => 'engYear',
                        'placeholder' => 'Select Year',
                        'class' => 'form-control select2',
                    ]) !!}
                </div>
                <div class="input-group nepDiv" style="display: none;">
                    {!! Form::select('year', $nepaliYearList, $value = $year, [
                        'id' => 'nepYear',
                        'placeholder' => 'Select Year',
                        'class' => 'form-control',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-2 month" style="display:none">
                <label class="d-block font-weight-semibold">Month</label>
                {{-- <div class="input-group">
                    {!! Form::select('month', $nepaliMonthList, request('month') ?? null, [
                        'placeholder' => 'Select Month',
                        'class' => 'form-control select-search',
                    ]) !!}
                </div> --}}
                @php
                    if (isset($_GET['month']) && $_GET['month']) {
                        $month = $_GET['month'];
                    } elseif (isset($_GET['eng_month']) && $_GET['eng_month']) {
                        $month = $_GET['eng_month'];
                    } else {
                        $month = null;
                    }
                @endphp
                <div class="input-group engDiv" style="display: none;">
                    {!! Form::select('eng_month', $monthList, $value = $month, [
                        'placeholder' => 'Select Month',
                        'id' => 'engMonth',
                        'class' => 'form-control',
                    ]) !!}
                </div>
                <div class="input-group nepDiv" style="display: none;">
                    {!! Form::select('month', $nepaliMonthList, $value = $month, [
                        'placeholder' => 'Select Month',
                        'id' => 'nepMonth',
                        'class' => 'form-control select2',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-3">
                <label class="d-block font-weight-semibold">Employee</label>
                <div class="input-group">
                    {!! Form::select('emp_id', @$employeeList, request('emp_id') ?? null, [
                        'placeholder' => 'Select Employee',
                        'class' => 'form-control select-search employee-filter',
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


