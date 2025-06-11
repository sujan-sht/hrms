<div class="row">
    <div class="col-lg-3">
        <div class="card sticky-top">
            <div class="card-header bg-transparent">
                <h6 class="card-title">
                    Advance Filter
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('organization.getLeaveReport') }}" id="leaveSearchForm" method="get">

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Leave Year <span class="text text-danger">*</span></label>
                            {!! Form::select('leave_year_id', $leaveYearList, $value = request('leave_year_id') ?: null, [
                                'class' => 'form-control select-search leave_year_id',
                                'id' => 'leave_year_id',
                                'required',
                            ]) !!}
                        </div>

                        <div class="col-md-12 mb-2">
                            <label for="example-email" class="form-label">Date Range</label>
                            {!! Form::text('date_range', $value = request('date_range') ?: null, [
                                'placeholder' => 'e.g : YYYY-MM-DD to YYYY-MM-DD',
                                'class' => 'form-control leaveDateRange',
                                'autocomplete' => 'on',
                            ]) !!}
                        </div>

                        <div class="col-md-12 mb-2">
                            <label class="form-label">Branch</label>
                            {!! Form::select('branch_id', $branchList, $value = request('branch_id') ?: null, [
                                'placeholder' => 'Select Branch',
                                'id' => 'branch_id',
                                'class' => 'form-control select2 branch-filter',
                            ]) !!}
                        </div>

                        <div class="col-md-12 mb-2">
                            <label class="form-label">Leave Type</label>
                            {!! Form::select('leave_type_id', $leaveTypeList, $value = request('leave_type_id') ?: null, [
                                'placeholder' => 'Select Leave Type',
                                'id' => 'leave_type_id',
                                'class' => 'form-control select2 leave-type-filter',
                            ]) !!}
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Leave Category</label>
                            {!! Form::select('leave_kind', $leaveKindList, $value = request('leave_kind') ?: null, [
                                'placeholder' => 'Select Leave Category',
                                'id' => 'leave_kind',
                                'class' => 'form-control select-search',
                            ]) !!}
                        </div>
                    </div>

                    <div class="d-flex justify-content-left mt-2">
                        <button class="btn bg-success mr-2 text-white" type="submit">
                            <i class="icon-filter3 mr-1"></i>Search
                        </button>
                        {{-- <a href="{{ request()->url() . '?leave_year_id=' . getCurrentLeaveYearId() }}"
                        class="btn bg-secondary text-white">
                        <i class="icons icon-reset mr-1"></i>Reset
                    </a> --}}
                    </div>
                </form>


            </div>
        </div>
    </div>
    <div class="col-lg-9">

        <section class="leave-detail">
            <!-- Leave Summary -->
            <div class="card">
                <div class="card-header header-elements-sm-inline">
                    <h6 class="card-title">
                        Leave Summary
                    </h6>
                    <div class="header-elements">
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">

                        @php
                            $icon = [1 => 'icon-pointer', 2 => 'icon-enter6', 3 => 'icon-exit', 4 => 'icon-trash'];
                            $color = [1 => 'info', 2 => 'secondary', 3 => 'teal', 4 => 'danger'];

                        @endphp
                        @foreach ($statusList as $statusKey => $statusValue)
                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-body">
                                    <div class="media">
                                        <div class="mr-3 align-self-center">
                                            <i
                                                class="{{ $icon[$statusKey] }} icon-3x text-{{ $color[$statusKey] }}"></i>
                                        </div>

                                        <div class="media-body text-right">
                                            <h3 class="font-weight-semibold mb-0">
                                                {{ isset($count_leave_status[$statusKey]) ? $count_leave_status[$statusKey] : 0 }}
                                            </h3>
                                            <span
                                                class="text-uppercase font-size-sm text-muted">{{ $statusValue }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @php
                            $leaveKindIcon = ['1' => 'icon-office', '2' => 'icon-city'];
                        @endphp
                        {{-- @foreach ($leaveKindList as $leaveKindListKey => $leaveKindListValue)
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="mr-3 align-self-center">
                                <i
                                    class="{{ $leaveKindIcon[$leaveKindListKey] }} icon-3x text-{{ $color[$leaveKindListKey] }}"></i>
                            </div>

                            <div class="media-body text-right">
                                <h3 class="font-weight-semibold mb-0">{{ $count_leave_kind[$leaveKindListKey] }}</h3>
                                <span class="text-uppercase font-size-sm text-muted">{{ $leaveKindListValue }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach --}}

                    </div>

                    <div class="row">


                        <div class="col-lg-9">
                            <div class="card">
                                {{-- <div class="card-header bg-transparent header-elements-inline">
                        <h6 class="card-title font-weight-semibold">
                            <i class="icon-exit2 mr-2"></i>
                            Leave Status
                        </h6>

                        <div class="header-elements">
                            <span class="text-muted">(93)</span>
                        </div>
                    </div> --}}
                                <div class="card-body">
                                    <div class="d-lg-flex">
                                        <ul
                                            class="nav nav-tabs nav-tabs-vertical flex-column mr-lg-3 wmin-lg-200 mb-lg-0 border-bottom-0">
                                            <li class="nav-itemonthListm"><a href="#vertical-left-tab1"
                                                    class="nav-link active" data-toggle="tab"><i
                                                        class="icon-menu7 mr-2"></i> Pending</a></li>
                                            <li class="nav-item"><a href="#vertical-left-tab2" class="nav-link "
                                                    data-toggle="tab"><i class="icon-mention mr-2"></i> Approved</a>
                                            </li>
                                            <li class="nav-item"><a href="#vertical-left-tab2" class="nav-link "
                                                    data-toggle="tab"><i class="icon-exit mr-2"></i> Forwaded</a>
                                            </li>
                                            <li class="nav-item"><a href="#vertical-left-tab2" class="nav-link "
                                                    data-toggle="tab"><i class="icon-trash mr-2"></i> Rejected</a>
                                            </li>
                                        </ul>

                                        <div class="tab-content flex-lg-fill">
                                            <div class="tab-pane fade active show" id="vertical-left-tab1">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr class="text-light btn-slate">
                                                            <th>S.N</th>
                                                            <th>Employee</th>
                                                            <th>Leave Date</th>
                                                            <th>Number of Days</th>
                                                            <th>Leave Type</th>
                                                            <th>Leave Category</th>
                                                            <th>Applied Date</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Sujan</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sujan</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sujan</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sujan</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sujan</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sujan</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                            <td>sad</td>
                                                        </tr>

                                                    </tbody>

                                                </table>
                                            </div>

                                            <div class="tab-pane fade" id="vertical-left-tab2">
                                                Food truck fixie locavore, accusamus mcsweeney's marfa nulla
                                                single-origin coffee squid laeggin.
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="card">
                                <div class="card-header header-elements-inline">
                                    <h6 class="card-title">Leave Type</h6>
                                </div>

                                <div class="table-responsive1">
                                    <table class="table table-striped table-hover text-nowrap">
                                        <tbody>
                                            @foreach ($count_leave_types as $leaveTypeKey => $leaveType)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="mr-3">
                                                                <a href="#"
                                                                    class="btn btn-primary rounded-pill btn-icon btn-sm">
                                                                    <span
                                                                        class="letter-icon">{{ substr($leaveTypeList[$leaveTypeKey], 0, 1) }}</span>
                                                                </a>
                                                            </div>
                                                            <div>
                                                                <a href="#"
                                                                    class="text-body font-weight-semibold letter-icon-title">{{ $leaveTypeList[$leaveTypeKey] }}</a>
                                                                {{-- <div class="text-muted font-size-sm">CL</div> --}}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{-- <span class="badge badge-success font-size-sm">Active</span> --}}
                                                    </td>
                                                    <td>

                                                        <span
                                                            class="badge badge-info badge-pill font-size-sm">{{ $leaveType }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Leave Summary -->

            <!-- Calendar -->
            {{-- <div class="card">
                <div class="card-header header-elements-sm-inline">
                    <h6 class="card-title">Leave Calendar</h6>
                    <div class="header-elements">

                        <div class="list-icons ml-3">
                            <a class="list-icons-item" data-action="reload"></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="collapse show">
                                    <div class="card-body">
                                        <form action="" id="calendarFilter">

                                            <div class="row">

                                                <div class="col-md-12 mb-2">
                                                    <label class="form-label">Branch</label>
                                                    {!! Form::select('branch_id', [], $value = request('branch_id') ?: null, [
                                                        'placeholder' => 'Select Branch',
                                                        'id' => 'branch_id',
                                                        'class' => 'form-control select2 branch-filter',
                                                    ]) !!}
                                                </div>

                                                <div class="col-md-12 mb-2">
                                                    <label class="form-label">Leave Type</label>
                                                    {!! Form::select('leave_type_id', [], $value = request('leave_type_id') ?: null, [
                                                        'placeholder' => 'Select Leave Type',
                                                        'id' => 'leave_type_id',
                                                        'class' => 'form-control select2 leave-type-filter',
                                                    ]) !!}
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label class="form-label">Leave Category</label>
                                                    {!! Form::select('leave_kind', [], $value = request('leave_kind') ?: null, [
                                                        'placeholder' => 'Select Leave Category',
                                                        'id' => 'leave_kind',
                                                        'class' => 'form-control select-search',
                                                    ]) !!}
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label class="form-label">Status</label>
                                                    {!! Form::select('status', [], $value = request('status') ?: null, [
                                                        'placeholder' => 'Select Status',
                                                        'id' => 'status',
                                                        'class' => 'form-control select-search',
                                                    ]) !!}
                                                </div>

                                            </div>

                                            <div class="mt-2 mb-2 float-right">
                                                <a href="{{ request()->url() . '?organization_id=' . request('organization_id') }}"
                                                    class="btn bg-secondary text-white">
                                                    <i class="icons icon-reset mr-1"></i>Reset
                                                </a>

                                                <button class="btn bg-yellow mr-2" type="submit">
                                                    <i class="icon-filter3 mr-1"></i>Filter
                                                </button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class='fullcalendar-basic'></div>

                        </div>
                    </div>
                </div>

            </div> --}}
            <!-- /Calendar -->

        </section>
    </div>

</div>




<script>
    // $(document).ready(function() {

    // dateRange = "{{ request('date_range') }}";

    $('#leave_year_id').on('change', function(e, data) {
        var id = $(this).val();
        // if (data && typeof data.checkDateRange !== 'undefined') {
        //     $('.leaveDateRange').val(dateRange);
        // } else {
        //     $('.leaveDateRange').val('');
        // }
        // $('.organization-filter').trigger('change');

        $.get('/leaveYearSetup/getLeaveYearById/' + id, function(response) {
                start_date = response.data.start_date_english;
                end_date = response.data.end_date_english;
                dateRangePicker(start_date, end_date)
            })
            .fail(function(xhr, status, error) {
                console.error(error);
            });

    });

    $('#leave_year_id').trigger('change', [{
        checkDateRange: true
    }]);

    function dateRangePicker(minDate, maxDate) {
        $('.leaveDateRange').daterangepicker({
            parentEl: '.content-inner',
            autoUpdateInput: false,
            showDropdowns: true,
            minDate: minDate,
            maxDate: maxDate,
            locale: {
                format: 'YYYY-MM-DD'
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                'YYYY-MM-DD'));
        }).on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    }

    $('.leaveDateRange').daterangepicker({
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

    // $('#leaveSearchForm').on('submit', function(event) {
    $('#leaveSearchForm').submit(function(event) {
        event.preventDefault();

        var formData1 = $(this).serializeArray();
        // var formData2 = $('#formFilter').serializeArray();

        // var mergedFormData = formData2.concat(formData1);
        $('#cover-spin').show();
        var organization_id = $('#organization_id').val();

        $.ajax({
            url: $(this).attr('action') +
                '?organization_id=' + organization_id,
            method: $(this).attr('method'),
            data: formData1,
            success: function(data) {
                // Handle the server data if needed.
                $('#leave-tab').html(data.view);
                $('#cover-spin').hide();


            },
            error: function(xhr, status, error) {
                // Handle errors if needed.
                console.error(error);
                $('#cover-spin').hide();

            }
        });


    })
    // })
</script>

@push('custom_script')
    <script>
        alert('asd')
    </script>
@endpush



<!-- Calendar -->
