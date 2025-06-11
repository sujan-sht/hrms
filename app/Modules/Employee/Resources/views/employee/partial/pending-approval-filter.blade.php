<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form id="pendingSearchForm">
            <div class="row">
                @if (setting('calendar_type') == 'BS')
                    <div class="col-md-3">
                        <label class="d-block font-weight-semibold">From Date:</label>
                        <div class="input-group">
                            {!! Form::text('from_nep_date', $value = request('from_nep_date') ?: null, [
                                'placeholder' => 'e.g : YYYY-MM-DD',
                                'class' => 'form-control nepali-calendar',
                                'autocomplete' => 'on',
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="d-block font-weight-semibold">To Date:</label>
                        <div class="input-group">
                            {!! Form::text('to_nep_date', $value = request('to_nep_date') ?: null, [
                                'placeholder' => 'e.g : YYYY-MM-DD',
                                'class' => 'form-control nepali-calendar',
                                'autocomplete' => 'on',
                            ]) !!}
                        </div>
                    </div>
                @else
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="example-email" class="form-label">Date Range</label>
                            @php
                                if (isset($_GET['date_range'])) {
                                    $dateRangeValue = $_GET['date_range'];
                                } else {
                                    $dateRangeValue = null;
                                }
                            @endphp
                            {!! Form::text('date_range', $value = $dateRangeValue, [
                                'placeholder' => 'e.g : YYYY-MM-DD to YYYY-MM-DD',
                                'class' => 'form-control daterange-buttons',
                                'autocomplete' => 'off',
                            ]) !!}
                        </div>
                    </div>
                @endif

                @if (Auth::user()->user_type != 'employee')
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Employee</label>
                        {!! Form::select('employee_id', $employeeList, $value = request('employee_id') ?: null, [
                            'placeholder' => 'Select Employee',
                            'class' => 'form-control select-search',
                        ]) !!}
                    </div>
                @endif


                <div class="col-md-3 mb-2">
                    <label class="form-label">Request Type</label>
                    {!! Form::select('type', $requestType, $value = request('type') ?: null, [
                        'placeholder' => 'Select Request Type',
                        'class' => 'form-control select-search',
                    ]) !!}
                </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icons icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ request()->url() }}" class="btn bg-secondary text-white"><i
                        class="icons icon-reset mr-1"></i>Reset</a>
            </div>
        </form>

    </div>
</div>

<script>
    $('.select-search').select2()
</script>
