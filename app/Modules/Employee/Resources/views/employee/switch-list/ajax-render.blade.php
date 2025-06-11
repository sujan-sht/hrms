@if ($tableViewEmployees->isNotEmpty())
    @foreach ($tableViewEmployees as $key => $employeeModel)
        <tr>
            @if (
                ((!$employeeModel['user'] && $menuRoles->assignedRoles('employee.bulkUserStatusActive')) ||
                    auth()->user()->user_type == 'admin' ||
                    auth()->user()->user_type == 'super_admin' ||
                    auth()->user()->user_type == 'hr') &&
                !is_null($employeeModel['user'])
                    ? !$employeeModel['user']['active']
                    : true)
                <td class="checkAllContainer">{!! Form::checkbox('employee_ids[]', $employeeModel['id'], false, ['class' => 'checkItem']) !!}</td>
            @else
                <td></td>
            @endif
            <td>{{ $key + 1 }}</td>
            <td>{{ $employeeModel['employee_code'] }}</td>
            <td>{{ $employeeModel['biometric_id'] }}</td>
            <td>
                <a href="{{ route('employee.view', $employeeModel['id']) }}" target="_blank">
                    {{ $employeeModel['full_name'] }}</a>
            </td>
            <td>
                {{ $employeeModel['manager']['full_name'] ?? '' }}

            </td>
            <td>{{ optional($employeeModel['branch_model'])['name'] }}</td>
            <td @if ($displayAll && !in_array('address', $select_columns)) @hide @endif>
                {{ $employeeModel['permanentaddress'] }}
            </td>
            @if (auth()->user()->user_type != 'employee' && auth()->user()->user_type != 'supervisor')
                <td @if ($displayAll && !in_array('mobile', $select_columns)) @hide @endif>
                    {{ $employeeModel['mobile'] }}
                </td>
            @else
                <td @if ($displayAll && !in_array('official_email', $select_columns)) @hide @endif>
                    {{ $employeeModel['official_email'] ?? '-' }}
                </td>
            @endif
            <td @if ($displayAll && !in_array('phone', $select_columns)) @hide @endif>
                {{ $employeeModel['phone'] }}
            </td>

            @if (auth()->user()->user_type == 'admin' ||
                    auth()->user()->user_type == 'super_admin' ||
                    auth()->user()->user_type == 'hr')
                <td @if ($displayAll && !in_array('official_email', $select_columns)) @hide @endif>
                    {{ $employeeModel['official_email'] ?? '-' }}
                </td>
                <td @if ($displayAll && !in_array('dob', $select_columns)) @hide @endif>
                    {{ date_converter()->eng_to_nep_convert($employeeModel['dob']) }}
                </td>
                <td @if ($displayAll && !in_array('dob', $select_columns)) @hide @endif>
                    {{ date('M d, Y', strtotime($employeeModel['dob'])) }}
                </td>
                <td @if ($displayAll && !in_array('level', $select_columns)) @hide @endif>
                    {{ optional($employeeModel['level'])['title'] ?? '-' }}
                </td>

                <td @if ($displayAll && !in_array('join_date', $select_columns)) @hide @endif>
                    {{ $employeeModel['nepali_join_date'] }}
                </td>
                <td @if ($displayAll && !in_array('join_date', $select_columns)) @hide @endif>
                    {{ date('M d, Y', strtotime($employeeModel['join_date'])) }}
                </td>
            @endif
            <td @if ($displayAll && !in_array('group', $select_columns)) @hide @endif>
                {{ optional($employeeModel['get_blood_group'])['dropvalue'] ?? '-' }}
            </td>
            <td @if ($displayAll && !in_array('designation', $select_columns)) @hide @endif>
                {{ optional($employeeModel['designation'])['title'] ?? '-' }}
            </td>
            @if (auth()->user()->user_type != 'employee')
                <td class="d-flex">
                    @if ($menuRoles->assignedRoles('employee.view'))
                        <a href="{{ route('employee.view', $employeeModel['id']) }}"
                            class="btn btn-outline-info btn-icon mr-1" data-popup="tooltip" data-placement="top"
                            data-original-title="View">
                            <i class="icon-eye"></i>
                        </a>
                    @endif
                    @if ($menuRoles->assignedRoles('employee.edit'))
                        <a href="{{ route('employee.edit', $employeeModel['id']) }}"
                            class="btn btn-outline-primary btn-icon mr-1" data-popup="tooltip" data-placement="top"
                            data-original-title="Edit">
                            <i class="icon-pencil7"></i>
                        </a>
                    @endif
                    @if ($menuRoles->assignedRoles('employee.resetPassword') && isset($employeeModel['get_user']))
                        <a data-username="{{ optional($employeeModel['get_user'])['username'] }}"
                            data-id="{{ optional($employeeModel['get_user'])['id'] }}" href="javascript:void(0);"
                            data-toggle="modal" data-target="#modal_theme_notice"
                            class="btn btn-outline-warning btn-icon mr-1 centerPopup updateUser"
                            link="{{ route('employee.resetPassword', ['id' => $employeeModel['id']]) }}"
                            mtitle="Reset Employee Password" data-popup="tooltip" data-placement="top"
                            data-original-title="Reset">
                            <i class="icon-user-lock"></i>
                        </a>
                    @endif

                    @if ($menuRoles->assignedRoles('employee.resetDevice') && isset(optional($employeeModel['get_user'])['imei']))
                        <a href="{{ route('employee.resetDevice', $employeeModel['id']) }}"
                            class="btn btn-outline-primary btn-icon mr-1" data-popup="tooltip" data-placement="top"
                            data-original-title="Reset Device Data">
                            <i class="icon-reset"></i>
                        </a>
                    @endif

                    @if ($employeeModel['status'] == '1')
                        <a data-toggle="modal" data-target="#modal_theme_warning_status_list"
                            class="btn btn-outline-warning  btn-icon border-1 status_employee mr-1"
                            employment_id="{{ $employeeModel['id'] }}" data-popup="tooltip" data-placement="bottom"
                            data-original-title="Offboard"><i class="icon-basket"></i></a>
                    @else
                        <a data-toggle="modal" data-target="#modal_theme_warning_status_list"
                            class="btn btn-outline-warning btn-icon border-1 status_employee mr-1"
                            employee_id="{{ $employeeModel['id'] }}" data-popup="tooltip" data-placement="bottom"
                            data-original-title="In-Active Employer"><i class="icon-basket"></i></a>
                    @endif

                    @if ($employeeModel['is_user_access'] == '1')
                        <a class="btn btn-outline-secondary border-2 btn-icon  remove_user_access_table" link=""
                            data-popup="tooltip" data-placement="bottom" data-original-title="User access granted"><i
                                class="icon-user-check"></i></a>
                    @else
                        <a data-toggle="modal" data-target="#modal_theme_success_table"
                            class="ml-1 btn btn-outline-secondary border-2 btn-icon  employer_user_access_table"
                            emp_id="{{ $employeeModel['id'] }}"
                            email="{{ $employeeModel['official_email'] ?? $employeeModel['personal_email'] }}"
                            employee_id="{{ $employeeModel['employee_id'] }}" data-popup="tooltip"
                            data-placement="bottom" data-original-title="User Access"><i class="icon-user-plus"></i></a>
                    @endif
                </td>
            @endif
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="9">No record found.</td>
    </tr>
@endif



<!-- Warning modal -->
<div id="modal_theme_success_table" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h6 class="modal-title text-white">Create User Access</h6>

            </div>

            <div class="modal-body">

                {!! Form::open([
                    'route' => 'employee.createUser',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'role' => 'form',
                    'files' => true,
                ]) !!}

                <div class="form-group row">
                    <label class="col-form-label col-lg-3">User Name:</label>
                    <div class="col-lg-5">
                        {!! Form::text('username', $value = null, [
                            'id' => 'username',
                            'placeholder' => 'Enter User Name',
                            'class' => 'form-control',
                            'required' => 'required',
                            'title' => '1 characters minimum',
                        ]) !!}
                        {{ Form::hidden('user_exist', '0', ['class' => 'user_exist']) }}
                        <span class="error_username"></span>
                    </div>
                    <div class="col-lg-4">
                        <button type="button" class="check_available btn text-light bg-success">Check
                            Availability</button>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Email:</label>
                    <div class="col-lg-9">
                        {!! Form::email('email', $value = null, [
                            'id' => 'email',
                            'placeholder' => 'Enter Email',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Password:</label>
                    <div class="col-lg-9">
                        {!! Form::text('password', $value = null, [
                            'id' => 'password',
                            'placeholder' => 'Enter Password',
                            'class' => 'form-control',
                            'required' => 'required',
                            'pattern' => '.{8,}',
                            'title' => '8 characters minimum',
                        ]) !!}
                    </div>
                </div>

                <fieldset>
                    <legend class="text-uppercase font-size-sm font-weight-bold">Assign Role</legend>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <div class="row">
                                <label class="col-form-label col-lg-3">Select User Role :<span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('role_id', $roles, $value = null, [
                                            'id' => 'role_id',
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Role',
                                            'required' => 'required',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <div class="row">
                                <label class="col-form-label col-lg-3">Assigned Roles:</label>
                                <div class="col-lg-9 form-group-feedback">
                                    <div class="input-group">
                                        {!! Form::select('assigned_role_ids[]', $roles, null, [
                                            'class' => 'form-control multiselect-select-all-filtering',
                                            'multiple' => 'multiple',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </fieldset>


                {{ Form::hidden('employer_id', '', ['class' => 'employer_id']) }}

                <div class="text-center">
                    <button type="submit" class="create_user_access text-white  btn bg-success">Create User</button>
                    <button type="button" class="btn bg-danger text-white" data-dismiss="modal">Close</button>
                </div>

                {!! Form::close() !!}


            </div>

            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- warning modal -->

<!-- Warning modal -->
<div id="modal_theme_warning_status_list" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h6 class="modal-title text-white">Are you sure to offboarding ?</h6>
            </div>
            <script src="{{ asset('admin/global/js/plugins/pickers/pickadate/picker.js') }}"></script>
            <script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>

            <div class="modal-body">
                {!! Form::open([
                    'route' => 'employee.update.status.user',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'role' => 'form',
                    'files' => true,
                ]) !!}
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Offboarding Reason:</label>
                    <div class="col-lg-9">
                        <select name="archived_type" id="archived_type" required class="form-control select2"
                            required>
                            <option value="">---Select Reason---</option>
                            @foreach ($archiveStatus as $key => $status)
                                <option value="{{ $status }}">{{ $key }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Remarks:</label>
                    <div class="col-lg-9">
                        <input type="hidden" class="employment_id" name="employment_id">
                        {!! Form::textarea('archive_reason', $value = null, [
                            'id' => 'archive_reason',
                            'placeholder' => 'Enter Reason for Offboarding',
                            'class' => 'form-control',
                            'required' => 'required',
                        ]) !!}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Offboarding Date:</label>
                    <div class="col-lg-9">
                        <x-utilities.date-picker :date="@$employees->nep_archived_date" mode="both" default="nep"
                            nepDateAttribute="nep_archived_date" engDateAttribute="archived_date" />

                        {{-- @if (setting('calendar_type') == 'BS')
                            {!! Form::text('archived_date', $value = null, [
                                'id' => 'archive_date',
                                'placeholder' => 'Select Offboarding Date',
                                'class' => 'form-control',
                                'id' => 'offboarding-date-list',
                            ]) !!}
                        @else
                            {!! Form::text('archived_date', $value = null, [
                                'id' => 'archive_date',
                                'placeholder' => 'Select Offboarding Date',
                                'class' => 'form-control daterange-single',
                            ]) !!}
                        @endif --}}
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn bg-success text-white">Submit</button>
                    <button type="button" class="btn bg-danger text-white" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}
            </div>

            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- warning modal -->

{{-- <script>
    $('#offboarding-date-list').nepaliDatePicker({
        container: '#modal_theme_warning_status_list',
        closeOnDateSelect: true
    });
</script> --}}
<script>
    $('#modal_theme_warning_status_list').on('show.bs.modal', function(e) {
        $(this).find('form')[0].reset();
        $(this).find('.select2').val('').trigger('change');
    });
</script>
