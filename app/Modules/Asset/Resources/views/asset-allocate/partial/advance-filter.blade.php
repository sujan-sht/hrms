<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ $route }}" method="GET">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="form-label">Employee</label>
                    {!! Form::select('employee_id', $employees, $value = request('employee_id') ? : null, ['placeholder'=>'Select Employee', 'class'=>'form-control select2']) !!}
                </div>

                <div class="col-md-3 mb-2">
                    <label class="form-label">Asset</label>
                    {!! Form::select('asset_id', $assets, $value = request('asset_id') ? : null, ['placeholder'=>'Select Asset', 'class'=>'form-control select2']) !!}
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="example-email" class="form-label">Allocated Date</label>
                        @php
                            if (isset($_GET['allocated_date'])) {
                                $dateRangeValue = $_GET['allocated_date'];
                            } else {
                                $dateRangeValue = null;
                            }
                        @endphp
                        @if (setting('calendar_type') == 'BS')
                            <div class="col-md-3">
                                <label class="d-block font-weight-semibold">From Date:</label>
                                <div class="input-group">
                                    {!! Form::text('from_nep_date', $value = request('from_nep_date') ?: null, [
                                        'placeholder' => 'e.g : YYYY-MM-DD',
                                        'class' => 'form-control nepali-calendar from_nep_date',
                                        'autocomplete' => 'on',
                                    ]) !!}
                                </div>
                            </div>
        
                            <div class="col-md-3">
                                <label class="d-block font-weight-semibold">To Date:</label>
                                <div class="input-group">
                                    {!! Form::text('to_nep_date', $value = request('to_nep_date') ?: null, [
                                        'placeholder' => 'e.g : YYYY-MM-DD',
                                        'class' => 'form-control nepali-calendar to_nep_date',
                                        'autocomplete' => 'on',
                                    ]) !!}
                                </div>
                            </div>
                        @else
                            {!! Form::text('allocated_date', $value = $dateRangeValue, [
                                'placeholder' => 'e.g : YYYY-MM-DD to YYYY-MM-DD',
                                'class' => 'form-control daterange-buttons',
                                'autocomplete' => 'off',
                            ]) !!}
                        @endif

                    </div>
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
