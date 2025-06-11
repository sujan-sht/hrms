<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open([
            'method' => 'GET',
            'route' => ['worklog.index'],
            'class' => 'form-horizontal',
            'role' => 'form',
        ]) !!}
        <div class="row">
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

            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Title:</label>
                    <div class="input-group">

                        @php $title = isset(request()->title) ? request()->title : null;
                        @endphp
                        {!! Form::text('title', $value = $title, [
                            'id' => 'title',
                            'placeholder' => 'Enter Title',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>
            </div>

            @if(auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'hr')
            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Employee:</label>
                    <div class="input-group">

                        @php $employee_id = isset(request()->employee_id) ? request()->employee_id : null;
                        @endphp
                        {!! Form::select('employee_id', $employees ,$value = $employee_id, [
                            'id' => 'employee_id',
                            'placeholder' => 'Choose Employee',
                            'class' => 'form-control select2',
                        ]) !!}
                    </div>
                </div>
            </div>
            @endif

            {{-- <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Project:</label>
                    <div class="input-group">

                        @php $project_id = isset(request()->project_id) ? request()->project_id : null;
                        @endphp
                        {!! Form::select('project_id', $projects ,$value = $project_id, [
                            'id' => 'project_id',
                            'placeholder' => 'Choose Project',
                            'class' => 'form-control select2',
                        ]) !!}
                    </div>
                </div>
            </div> --}}


            <div class="col-md-3">
                <div class="form-group mb-0 pt-1  pr-3">
                    <label class="d-block font-weight-semibold">Status:</label>
                    <div class="input-group">

                        @php $status = isset(request()->status) ? request()->status : null;
                        @endphp
                        {!! Form::select('status', $statusList ,$value = $status, [
                            'id' => 'status',
                            'placeholder' => 'Choose Status',
                            'class' => 'form-control select2',
                        ]) !!}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-end mb-3 mr-3">
        <button class="btn bg-yellow mr-1" type="submit">
            <i class="icons icon-filter3 mr-1"></i>Filter
        </button>

        <a href="{{ request()->url() }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a>
    </div>



    {!! Form::close() !!}
</div>
<script>
    $('.select2').select2();
</script>
