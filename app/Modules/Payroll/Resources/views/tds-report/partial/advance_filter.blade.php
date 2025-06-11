<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form>
            <div class="row">
                {{-- <div class="col-md-3">
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
                </div> --}}
                @if (Auth::user()->user_type == 'super_admin' ||
                        Auth::user()->user_type == 'admin' ||
                        Auth::user()->user_type == 'hr' ||
                        Auth::user()->user_type == 'supervisor')
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="example-email" class="form-label">Organization <span class="text-danger">*</span></label>
                            @php
                                if (isset($_GET['organization_id'])) {
                                    $orgValue = $_GET['organization_id'];
                                } else {
                                    $orgValue = null;
                                }
                            @endphp
                            {!! Form::select('organization_id', $organizationList, $value = $orgValue, [
                                // 'placeholder' => 'Select Organization',
                                'id' => 'organization',
                                'class' => 'form-control organization_id',
                                'required',
                            ]) !!}
                        </div>
                    </div>


                    <div class="col-md-3 year" style="display: none;">
                        <div class="mb-3">
                            <label for="example-email" class="form-label">Year</label>
                            @php
                                if (isset($_GET['year']) &&  $_GET['year']) {
                                    $year = $_GET['year'];
                                } 
                                elseif(isset($_GET['eng_year']) && $_GET['eng_year']) {
                                    $year = $_GET['eng_year'];
                                }
                                else {
                                    $year = null;
                                }
                            @endphp
                            <div class="input-group engDiv" style="display: none;">
                                {!! Form::select('eng_year', $yearList, $value = $year, [
                                    'placeholder' => 'Select Year',
                                    'id' => 'engYear',
                                    'class' => 'form-control',
                                    'required',
                                ]) !!}
                            </div>
                            <div class="input-group nepDiv" style="display: none;">
                                {!! Form::select('year', $nepaliYearList, $value = $year, [
                                    'id' => 'nepYear',
                                    'placeholder' => 'Select Year',
                                    'class' => 'form-control',
                                    'required',
                                ]) !!}
                            </div>
                            {{-- {!! Form::select('year', $nepaliYearList, $value = $year, [
                                'placeholder' => 'Select Year',
                                'class' => 'form-control select2','required',
                            ]) !!} --}}
                        </div>
                    </div>

                    <div class="col-md-3 month" style="display: none;">
                        <div class="mb-3">
                            <label for="example-email" class="form-label">Month</label>
                            @php
                                if (isset($_GET['month']) && $_GET['month']) {
                                    $month = $_GET['month'];
                                }
                                elseif (isset($_GET['eng_month']) && $_GET['eng_month']) {
                                    $month = $_GET['eng_month'];
                                }
                                 else {
                                    $month = null;
                                }
                            @endphp
                            {{-- {!! Form::select('month', $nepaliMonthList, $value = $month, [
                                'placeholder' => 'Select Month',
                                'class' => 'form-control select2','required',
                            ]) !!} --}}
                            <div class="input-group engDiv" style="display: none;">
                                {!! Form::select('eng_month', $monthList, $value = $month, [
                                    'placeholder' => 'Select Month',
                                    'id' => 'engMonth',
                                    'class' => 'form-control',
                                    'required',
                                ]) !!}
                            </div>
                            <div class="input-group nepDiv" style="display: none;">
                                {!! Form::select('month', $nepaliMonthList, $value = $month, [
                                    'placeholder' => 'Select Month',
                                    'id' => 'nepMonth',
                                    'class' => 'form-control select2',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icons icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ request()->url() }}" class="btn bg-secondary text-white">
                    <i class="icons icon-reset mr-1"></i>Reset
                </a>
            </div>
        </form>

    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
        calendarTypeFilter();
        $('#organization').on('change', function() {
            calendarTypeFilter();
        });
    });
</script>
<script>
    function calendarTypeFilter() {
        var organizationId = $('.organization_id').val();
        $.ajax({
            type: 'GET',
            url: '/admin/payroll-setting/get-calendar-type',
            data: {
                organization_id: organizationId
            },
            success: function(data) {
                // console.log(data);
                var list = JSON.parse(data);
                if (list.calendar_type == 'nep') {
                    $('.engDiv').hide();
                    $('.nepDiv').show();
                    $('.year').show();
                    $('.month').show();
                    $('#engYear').val('');
                    $('#engMonth').val('');
                } else {
                    $('.year').show();
                    $('.month').show();
                    $('.engDiv').show();
                    $('.nepDiv').hide();
                    $('#nepYear').val('');
                    $('#nepMonth').val('');
                }

            }
        });
    }
</script>
