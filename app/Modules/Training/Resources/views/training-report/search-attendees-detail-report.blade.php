<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => ['training-attendees-detail-report'], 'method' => 'get']) !!}
        <div class="row">
            <div class="col-md-3">
                <label class="d-block font-weight-semibold">Training:</label>
                <div class="input-group">
                    @php
                        if (isset($_GET['training_id'])) {
                            $trainingValue = $_GET['training_id'];
                        } else {
                            $trainingValue = null;
                        }
                    @endphp
                    {!! Form::select('training_id', $trainingList, $value = $trainingValue, [
                        'placeholder' => 'Select Training',
                        'class' => 'form-control select-search',
                    ]) !!}
                </div>
            </div>

            @if (setting('calendar_type') == 'BS')
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="d-block font-weight-semibold">From Date:</label>
                            <div class="input-group">
                                {!! Form::text('from_nep_date', $value = request('from_nep_date') ?: null, [
                                    'placeholder' => 'e.g : YYYY-MM-DD',
                                    'class' => 'form-control nepali-calendar',
                                    'autocomplete' => 'on',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="d-block font-weight-semibold">To Date:</label>
                            <div class="input-group">
                                {!! Form::text('to_nep_date', $value = request('to_nep_date') ?: null, [
                                    'placeholder' => 'e.g : YYYY-MM-DD',
                                    'class' => 'form-control nepali-calendar',
                                    'autocomplete' => 'on',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-3">
                        <div class="fmb-3">
                            <label class="d-block font-weight-semibold">Date Range:</label>
                            <div class="input-group">
                                {!! Form::text('date_range', $value = request('date_range') ?: null, [
                            'placeholder' => 'e.g : YYYY-MM-DD to YYYY-MM-DD',
                            'class' => 'form-control dateRange',
                            'autocomplete' => 'on',
                        ]) !!}
                            </div>
                        </div>
                    </div>
                @endif
        </div>
        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
            <a href="{{ route('training-attendees-detail-report') }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.dateRange').daterangepicker({
            parentEl: '.content-inner',
            autoUpdateInput: false,
            showDropdowns: true,
            // minDate: minDate,
            // maxDate: maxDate,
            locale: {
                format: 'YYYY-MM-DD'
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                'YYYY-MM-DD'));
        }).on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
</script>


@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js')}}"></script>
@endSection

