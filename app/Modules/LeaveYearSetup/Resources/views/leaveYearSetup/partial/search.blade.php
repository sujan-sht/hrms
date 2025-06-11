<script type="text/javascript">
    window.onload = function() {
        var startDateSearch = document.getElementById("nepali-datepicker-start-date-search");
        startDateSearch.nepaliDatePicker({
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 10
        });
        var endDateSearch = document.getElementById("nepali-datepicker-end-date-search");
        endDateSearch.nepaliDatePicker({
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 10
        });
    };
</script>

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'leaveYearSetup.index', 'method' => 'get']) !!}
        <div class="row">

            <div class="col-md-3">
                <label class="d-block font-weight-semibold">Start Date:</label>
                <div class="input-group">
                    {!! Form::text('start_date', request('start_date') ?? null, ['class'=>'form-control', 'id'=>'nepali-datepicker-start-date-search', 'placeholder'=>'Choose Start Date', 'readonly']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <label class="d-block font-weight-semibold">End Date:</label>
                <div class="input-group">
                    {!! Form::text('end_date', request('end_date') ?? null, ['class'=>'form-control', 'id'=>'nepali-datepicker-end-date-search', 'placeholder'=>'Choose End Date', 'readonly']) !!}
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
            <a href="{{ route('leaveYearSetup.index') }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>

{{-- <script>
    $('.select2').select2();
</script> --}}
