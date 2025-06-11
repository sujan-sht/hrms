{{-- <script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script> --}}
<script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                {{-- @php
                    $created_by = \Illuminate\Support\Facades\Auth::user()->id;
                @endphp
                <input type="hidden" name="created_by" value="{{ $created_by }}"> --}}

                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Title:<span class="text-danger">*</span></label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-pencil"></i></span>
                            </span>

                            {!! Form::text('title', $value = null, [
                                'id' => 'title',
                                'placeholder' => 'Enter Title',
                                'class' => 'form-control',
                            ]) !!}

                        </div>
                        @if ($errors->has('title'))
                            <span class="text-danger">{{ $errors->first('title') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Description :</label>

                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-pencil"></i></span>
                            </span>

                            {!! Form::textarea('description', null, [
                                'id' => 'description',
                                'placeholder' => 'Enter Description',
                                'class' => 'form-control',
                            ]) !!}

                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Link:<span class="text-danger"></span></label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-pencil"></i></span>
                            </span>

                            {!! Form::text('link', $value = null, [
                                'id' => 'link',
                                'placeholder' => 'Enter link',
                                'class' => 'form-control',
                            ]) !!}

                        </div>
                        @if ($errors->has('link'))
                            <span class="text-danger">{{ $errors->first('link') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Image: </label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-pencil"></i></span>
                            </span>

                            {!! Form::file('image', ['id' => 'attachment', 'class' => 'form-control']) !!}

                        </div>
                    </div>

                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Attachment: </label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-pencil"></i></span>
                            </span>

                            {{-- {!! Form::file('file', ['id' => 'attachment', 'class' => 'form-control']) !!} --}}
                            <input type="file" name="file[]" id="attachment" class="form-control" multiple>


                        </div>
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

                            {{-- @php
                                if ($is_edit) {
                                    if (count($notice->departments) > 0) {
                                        foreach ($notice->departments as $model) {
                                            $departmentValues[] = $model->department_id;
                                        }
                                    } else {
                                        $departmentValues = null;
                                    }
                                } else {
                                    $departmentValues = null;
                                }
                            @endphp --}}
                            {!! Form::select('departmentArray[]', $departmentList, isset($department_id) ? $department_id : '', [
                                'id' => 'departmentId',
                                'class' => 'form-control multiselect-select-all-filtering',
                                'multiple' => 'multiple',
                            ]) !!}
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Employee :</label>

                    <div class="col-lg-10">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-pencil"></i></span>
                            </span>

                            {!! Form::select(
                                'employeeId[]',
                                isset($employeeList) ? $employeeList : [],
                                isset($employee_id) ? $employee_id : '',
                                [
                                    'id' => 'employeeId',
                                    'class' => 'form-control multiselect-select-all-filtering',
                                    'multiple' => 'multiple',
                                ],
                            ) !!}

                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Type: <span class="text-danger">*</span></label>

                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {{-- <div class="form-check form-check-inline">
                                {!! Form::radio('type', '1', isset($is_edit) && $notice->type == 1 ? true : false, [
                                    'id' => 'cr_l_i_s',
                                    'class' => 'form-check-input check_type',
                                ]) !!}
                                {{ Form::label('cr_l_i_s', 'Post Now', ['class' => 'form-check-label']) }}

                            </div> --}}

                            {{-- <div class="form-check form-check-inline">
                                {!! Form::radio('type', '2',isset($is_edit) && $notice->type == 2 ? true:false, ['id' => 'cr_l_i_u', 'class' => 'form-check-input check_type']) !!}
                                {{ Form::label('cr_l_i_u', 'Schedule', ['class' => 'form-check-label']) }}
                            </div> --}}

                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input check_type" name="type" id="PostNow"
                                    value="1"
                                    {{ isset($is_edit) && $is_edit == true && $notice->type == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="PostNow">Post Now</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input check_type" name="type" id="Schedule"
                                    value="2"
                                    {{ isset($is_edit) && $is_edit == true && $notice->type == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="Schedule">Schedule</label>
                            </div>

                        </div>
                        @if ($errors->has('type'))
                            <span class="text-danger">{{ $errors->first('type') }}</span>
                        @endif
                    </div>

                </div>


                <div class="form-group row schedule-row d-none">
                    <label class="col-form-label col-lg-2">Notice Date:<span class="text-danger">*</span></label>
                    @if (setting('calendar_type') == 'BS')
                        <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('notice_date_nepali', $value = null, [
                                    'placeholder' => 'Please Choose Date',
                                    'class' => 'form-control nepali-calendar',
                                ]) !!}
                            </div>
                            @if ($errors->has('notice_date_nepali'))
                                <span class="text-danger">{{ $errors->first('notice_date_nepali') }}</span>
                            @endif
                        </div>
                    @else
                        <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('notice_date', $value = null, [
                                    'placeholder' => 'Please Choose Date',
                                    'class' => 'form-control notice_date_picker',
                                ]) !!}
                            </div>
                            @if ($errors->has('notice_date'))
                                <span class="text-danger">{{ $errors->first('notice_date') }}</span>
                            @endif
                        </div>
                    @endif

                    <label class="col-form-label col-lg-1">Notice Time:<span class="text-danger">*</span></label>
                    <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-watch2"></i></span>
                            </span>
                            {!! Form::text('notice_time', null, ['class' => 'form-control', 'id' => 'start-timepicker']) !!}
                        </div>
                        @if ($errors->has('notice_time'))
                            <span class="text-danger">{{ $errors->first('notice_time') }}</span>
                        @endif
                    </div>
                </div>





            </div>
        </div>
    </div>
</div>

<div class="text-right">
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

<script>
    $(document).ready(function() {
        $('#organizationId').on('change', function() {
            var organization_id = $('#organizationId').val();
            var department_id = $('#departmentId').val();
            var branch_id = $('#branchId').val();

            $.ajax({
                url: "{{ url('admin/notice/getOrganizationBranch') }}", // Add this route in your web.php
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
                url: "{{ url('admin/notice/getOrganizationEmployee') }}",
                method: 'GET',
                data: {
                    organization_id: organization_id,
                    department_id: department_id,
                    branch_id: branch_id
                },
                success: function(data) {
                    $('#employeeId').empty();
                    $.each(data, function(id, name) {
                        $('#employeeId').append(new Option(name, id));
                    });
                    $('#employeeId').multiselect('rebuild');
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
                url: "{{ url('admin/notice/getOrganizationEmployee') }}",
                method: 'GET',
                data: {
                    organization_id: organization_id,
                    branch_id: branch_id,
                    department_id: department_id
                },
                success: function(data) {
                    $('#employeeId').empty();
                    $.each(data, function(id, name) {
                        $('#employeeId').append(new Option(name, id));
                    });
                    $('#employeeId').multiselect('rebuild');
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
                url: "{{ url('admin/notice/getOrganizationEmployee') }}",
                method: 'GET',
                data: {
                    organization_id: organization_id,
                    department_id: department_id,
                    branch_id: branch_id
                },
                success: function(data) {
                    $('#employeeId').empty();
                    $.each(data, function(id, name) {
                        $('#employeeId').append(new Option(name, id));
                    });
                    $('#employeeId').multiselect('rebuild');
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + error.message);
                }
            });
        });

    });

    $('.notice_date_picker').daterangepicker({
        parentEl: '.content-inner',
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false,
        minDate: new Date(),
        locale: {
            format: 'YYYY-MM-DD'
        }

    });
    $('.notice_date_picker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
    });

    $('.notice_date_picker').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
</script>
