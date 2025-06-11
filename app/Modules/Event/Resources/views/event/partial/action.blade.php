<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Event Detail</legend>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Event Title:<span class="text-danger">*</span></label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {{-- <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-pencil"></i></span>
                            </span> --}}
                            {!! Form::text('title', $value = null, [
                                'id' => 'title',
                                'placeholder' => 'Enter Event Title',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        @if ($errors->has('title'))
                            <div class="error text-danger">{{ $errors->first('title') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Start Date:<span class="text-danger">*</span></label>

                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    @php
                                        $startDate = null;
                                        if (setting('calendar_type') == 'BS') {
                                            $clData = 'form-control nepali-calendar';
                                            if ($is_edit && $event['event_start_date']) {
                                                $startDate = date_converter()->eng_to_nep_convert(
                                                    $event['event_start_date'],
                                                );
                                            }
                                        } else {
                                            $clData = 'form-control daterange-single';
                                            if ($is_edit && $event['event_start_date']) {
                                                $startDate = $event['event_start_date'];
                                            }
                                        }
                                    @endphp
                                    {!! Form::text('event_start_date', $value = $startDate, [
                                        'id' => 'event_start_date',
                                        'class' => $clData,
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                    ]) !!}
                                </div>
                                @if ($errors->has('event_start_date'))
                                    <div class="error text-danger">{{ $errors->first('event_start_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <label class="col-form-label col-lg-4">End Date:</label>

                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    @php
                                        $endDate = null;
                                        if (setting('calendar_type') == 'BS') {
                                            $clData = 'form-control nepali-calendar';
                                            if ($is_edit && $event['event_end_date']) {
                                                $endDate = date_converter()->eng_to_nep_convert(
                                                    $event['event_end_date'],
                                                );
                                            }
                                        } else {
                                            $clData = 'form-control daterange-single';
                                            if ($is_edit && $event['event_end_date']) {
                                                $endDate = $event['event_end_date'];
                                            }
                                        }
                                    @endphp
                                    {!! Form::text('event_end_date', $value = $endDate, [
                                        'id' => 'event_end_date',
                                        'class' => $clData,
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                    ]) !!}
                                </div>
                                @if ($errors->has('event_end_date'))
                                    <div class="error text-danger">{{ $errors->first('event_end_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                {{-- <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Province:<span class="text-danger">*</span></label>

                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">

                                    {!! Form::select('province_id', $provinces, $value = null, [
                                        'id' => 'province_id',
                                        'class' => 'form-control select-search',
                                        'placeholder' => 'Select Province',
                                    ]) !!}
                                </div>
                                @if ($errors->has('province_id'))
                                    <div class="error text-danger">{{ $errors->first('province_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6" id="districtDiv" style="display: none;">
                        <div class="row">
                            <label class="col-form-label col-lg-4">District: <span class="text-danger">*</span> </label>

                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <select name="district_id[]" id="district_id"
                                        class="form-control multiselect-select-all-filtering districtSelect" multiple>
                                    </select>
                                </div>
                                @if ($errors->has('district_id'))
                                    <div class="error text-danger">{{ $errors->first('district_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div> --}}

                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Event Time:<span class="text-danger">*</span></label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {{-- <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-watch2"></i></span>
                            </span> --}}
                            {!! Form::text('event_time', null, [
                                'class' => 'form-control',
                                'id' => 'start-timepicker',
                                'placeholder' => 'e.g: HH-MM',
                            ]) !!}
                        </div>
                        @if ($errors->has('event_time'))
                            <div class="error text-danger">{{ $errors->first('event_time') }}</div>
                        @endif
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Description :</label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {{-- <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-pencil"></i></span>
                            </span> --}}
                            {!! Form::textarea('description', null, [
                                'id' => 'description',
                                'placeholder' => 'Enter Description',
                                'class' => 'form-control',
                                'rows' => 5,
                            ]) !!}
                        </div>
                        @if ($errors->has('description'))
                            <div class="error text-danger">{{ $errors->first('description') }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Organization :<span class="text-danger">*</span></label>


                    <div class="col-lg-10">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-pencil"></i></span>
                            </span>

                            {!! Form::select('organizationId[]', $organizationList, isset($organization_id) ? $organization_id : '', [
                                'id' => 'organizationId',
                                'class' => 'form-control multiselect-select-all-filtering',
                                'multiple' => 'multiple',
                            ]) !!}
                        </div>
                        @if ($errors->has('organizationId'))
                            <span class="text-danger">{{ $errors->first('organizationId') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Branch :</label>

                    <div class="col-lg-10">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-pencil"></i></span>
                            </span>

                            {!! Form::select('branchArray[]', isset($branchs) ? $branchs : [], isset($branch_id) ? $branch_id : '', [
                                'id' => 'branchId',
                                'class' => 'form-control multiselect-select-all-filtering',
                                'multiple' => 'multiple',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Sub-Functions :</label>

                    <div class="col-lg-10">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-pencil"></i></span>
                            </span>
                            {!! Form::select('departmentArray[]', $departmentList, isset($department_id) ? $department_id : '', [
                                'id' => 'departmentId',
                                'class' => 'form-control multiselect-select-all-filtering',
                                'multiple' => 'multiple',
                            ]) !!}
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Tag Employees:</label>
                    <div class="col-lg-10 ">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-user-plus"></i></span>
                            </span>
                            @php
                                $selected_users =
                                    $is_edit && !empty($event->tagged_employees)
                                        ? json_decode($event->tagged_employees)
                                        : null;
                            @endphp

                            {!! Form::select('tagged_users[]', $users, $selected_users, [
                                'id' => 'tagged_users',
                                'class' => 'form-control multiselect-select-all-filtering',
                                'multiple' => 'multiple',
                            ]) !!}

                            <span class="text-danger">{{ $errors->first('tagged_employees') }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Other Detail</legend>

                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Event Location:</label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {{-- <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-pencil"></i></span>
                            </span> --}}
                            {!! Form::text('location', $value = null, [
                                'id' => 'location',
                                'placeholder' => 'Enter Event Location',
                                'class' => 'form-control',
                            ]) !!}
                            <span class="text-danger">{{ $errors->first('location') }}</span>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Note :</label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {{-- <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-pencil"></i></span>
                            </span> --}}
                            {!! Form::textarea('note', null, [
                                'id' => 'note',
                                'placeholder' => 'Enter Note',
                                'class' => 'form-control',
                                'rows' => 5,
                            ]) !!}
                        </div>
                        @if ($errors->has('note'))
                            <div class="error text-danger">{{ $errors->first('note') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="text-center">
    @if (isset($goBack))
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                    class="icon-backward2"></i></b>Go Back</a>
    @endif

    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>


<script>
    $(document).ready(function() {
        $('#organizationId').on('change', function() {
            var organization_id = $('#organizationId').val();
            var department_id = $('#departmentId').val();
            var branch_id = $('#branchId').val();

            $.ajax({
                url: "{{ url('admin/event/getOrganizationBranch') }}",
                method: 'GET',
                data: {
                    organization_id: organization_id
                },
                success: function(data) {
                    $('#branchId').empty();
                    $.each(data, function(id, name) {
                        $('#branchId').append(new Option(name, id));
                    });
                    $('#branchId').multiselect('rebuild');
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + error.message);
                }
            });

            $.ajax({
                url: "{{ url('admin/event/getOrganizationEmployee') }}",
                method: 'GET',
                data: {
                    organization_id: organization_id,
                    department_id: department_id,
                    branch_id: branch_id
                },
                success: function(data) {
                    $('#tagged_users').empty();
                    $.each(data, function(id, name) {
                        $('#tagged_users').append(new Option(name, id));
                    });
                    $('#tagged_users').multiselect('rebuild');
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + error.message);
                }
            });
        });

        $('#branchId').on('change', function() {
            var organization_id = $('#organizationId').val();
            var branch_id = $('#branchId').val();
            var department_id = $('#departmentId').val();

            $.ajax({
                url: "{{ url('admin/event/getOrganizationEmployee') }}",
                method: 'GET',
                data: {
                    organization_id: organization_id,
                    branch_id: branch_id,
                    department_id: department_id
                },
                success: function(data) {
                    $('#tagged_users').empty();
                    $.each(data, function(id, name) {
                        $('#tagged_users').append(new Option(name, id));
                    });
                    $('#tagged_users').multiselect('rebuild');
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + error.message);
                }
            });
        });

        $('#departmentId').on('change', function() {
            var organization_id = $('#organizationId').val();
            var department_id = $('#departmentId').val();
            var branch_id = $('#branchId').val();


            $.ajax({
                url: "{{ url('admin/event/getOrganizationEmployee') }}",
                method: 'GET',
                data: {
                    organization_id: organization_id,
                    department_id: department_id,
                    branch_id: branch_id
                },
                success: function(data) {
                    $('#tagged_users').empty();
                    $.each(data, function(id, name) {
                        $('#tagged_users').append(new Option(name, id));
                    });
                    $('#tagged_users').multiselect('rebuild');
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + error.message);
                }
            });
        });

    });
</script>
{{--
<script>
    $(document).on('change', '#province_id', function() {
        $('#districtDiv').css('display', 'block');
        var provinceId = $(this).val();
        var $closestDistrictSelect = $(this).closest('.form-group').find('#district_id');
        if (provinceId !== '') {
            $.ajax({
                url: '{{ route('event.get-districts') }}',
                method: 'GET',
                data: {
                    province_id: provinceId
                },
                success: function(response) {
                    $closestDistrictSelect.empty();
                    $.each(response, function(key, district) {
                        $closestDistrictSelect.append($('<option>', {
                            value: key,
                            text: district
                        }));
                    });
                    $closestDistrictSelect.multiselect('rebuild');
                }
            });
        } else {
            $closestDistrictSelect.empty();
        }
    });
</script> --}}
