<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-1 d-flex justify-content-center">
                    {{-- <div class="col-lg-2"> --}}
                    <div class="col-lg-8">
                        {{-- <div class="row">
                            <div class="col-lg-2">
                                <label for="search" class="col-form-label">Search:</label>
                            </div>
                            <div class="col-lg-10">
                                <form method="GET" action="{{ route('employee.index') }}">
                                    <input type="text" name="search" class="form-control w-50" id="search_any"
                                        placeholder="Search by name..." value="{{ request()->get('search') ?? null }}">
                                    <input type="hidden" name="switch_view" value="list-view">
                                    <button type="submit" style="display:none;"></button>
                                </form>
                            </div>
                        </div> --}}
                    </div>
                    <div class="col-lg-4">
                        <div class="row d-flex justify-content-end">
                            {{-- <label class="col-form-label col-lg-3">Result Per Page:</label>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select(
                                        'sortBy',
                                        [10 => 10, 20 => 20, 50 => 50, 100 => 100],
                                        request()->get('sortBy') ? request()->get('sortBy') : 20,
                                        [
                                            'class' => 'form-control sortBy',
                                            'placeholder' => 'Select',
                                        ],
                                    ) !!}

                                </div>
                            </div> --}}
                            <div>
                                <span class="bulkUpdateStatusDiv d-none mt-2">
                                    <a data-toggle="modal" data-target="#bulkUpdateStatus"
                                        class="btn btn-outline-warning btn-icon bulkUpdateStatus mb-2 mr-3 mt-2"
                                        data-popup="tooltip" data-placement="top" data-original-title="Status">
                                        <i class="icon-flag3"></i>
                                    </a>
                                </span>
                                <a href="{{ route('employee.directoryReportExport', request()->all()) }}"
                                    class="btn btn-success btn-sm" data-popup="tooltip" data-placement="bottom"
                                    data-original-title="Export"><i class="icon-file-excel"></i> Export</a>

                                <a href="#" class="btn btn-secondary btn-sm" data-toggle="modal"
                                    data-target="#modal_default_import" data-popup="tooltip" data-placement="bottom"
                                    data-original-title="Import"><i class="icon-file-excel"></i>
                                    Import</a>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap table-striped mb-0" id="employee-table-list">
                        <thead>
                            <tr class="text-white">
                                @if (
                                    $menuRoles->assignedRoles('employee.bulkUserStatusActive') ||
                                        auth()->user()->user_type == 'admin' ||
                                        auth()->user()->user_type == 'super_admin' ||
                                        auth()->user()->user_type == 'hr')
                                    <th class="checkAllContainer">
                                        <span>User Access</span>
                                    </th>
                                @else
                                    <th></th>
                                @endif
                                <th> S.N. </th>
                                <th> Code </th>
                                <th> Biometric ID </th>
                                <th> Full Name </th>
                                <th> Manager </th>
                                <th> Unit </th>
                                <th @if ($displayAll && !in_array('address', $select_columns)) @hide @endif>Address</th>
                                @if (auth()->user()->user_type != 'employee' && auth()->user()->user_type != 'supervisor')
                                    <th @if ($displayAll && !in_array('mobile', $select_columns)) @hide @endif>Mobile</th>
                                @else
                                    <th @if ($displayAll && !in_array('official_email', $select_columns)) @hide @endif>Official Email</th>
                                @endif
                                <th @if ($displayAll && !in_array('phone', $select_columns)) @hide @endif>CUG No.</th>

                                {{-- <th @if ($displayAll && !in_array('email', $select_columns)) @hide @endif>Personal Email</th> --}}

                                @if (auth()->user()->user_type == 'admin' ||
                                        auth()->user()->user_type == 'super_admin' ||
                                        auth()->user()->user_type == 'hr')
                                    <th @if ($displayAll && !in_array('official_email', $select_columns)) @hide @endif>Official Email</th>
                                    <th @if ($displayAll && !in_array('dob', $select_columns)) @hide @endif>D.O.B(B.S)</th>
                                    <th @if ($displayAll && !in_array('dob', $select_columns)) @hide @endif>D.O.B(A.D)</th>
                                    <th @if ($displayAll && !in_array('level', $select_columns)) @hide @endif>Grade</th>
                                    <th @if ($displayAll && !in_array('join_date', $select_columns)) @hide @endif>Join Date(B.S)</th>
                                    <th @if ($displayAll && !in_array('join_date', $select_columns)) @hide @endif>Join Date(A.D)</th>
                                @endif


                                <th @if ($displayAll && !in_array('group', $select_columns)) @hide @endif>Blood Group</th>
                                <th @if ($displayAll && !in_array('designation', $select_columns)) @hide @endif>Designation</th>
                                {{-- <th>Status</th> --}}
                                {{-- <th @if ($displayAll && !in_array('gpa_enable', $select_columns)) @hide @endif>GPA</th>
                                <th @if ($displayAll && !in_array('gmi_enable', $select_columns)) @hide @endif>GMI</th> --}}
                                @if (auth()->user()->user_type != 'employee')
                                    @if ($menuRoles->assignedRoles('employee.view') || $menuRoles->assignedRoles('employee.resetPassword'))
                                        <th style="width: 130px;">Action</th>
                                    @endif
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @include('employee::employee.switch-list.ajax-render')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <ul class="pagination pagination-rounded justify-content-end mb-3">
            {{-- @if ($tableViewEmployees->total() != 0)
                {{ $tableViewEmployees->appends(request()->all() + ['switch_view' => 'list-view'])->links() }}
            @endif --}}
        </ul>
    </div>
</div>

<div class="modal modal-form fade" id="modal_theme_notice" tabindex="-1" role="dialog" aria-labelledby="noticeLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h6 class="modal-title">Reset Credentials</h6>
                <div class="modal-events-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <div class="modal-body">
                <form action="{{ route('change-username') }}" autocomplete="off" method="POST">@csrf
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3" for="username">User Name</label>
                        <div class="col-lg-5">
                            {!! Form::text('username', null, [
                                'class' => 'form-control username',
                                'id' => 'username',
                                'placeholder' => 'Enter Username',
                                'required',
                            ]) !!}
                            {{ Form::hidden('user_exist', '0', ['class' => 'user_exist']) }}
                            <span class="error_username"></span>
                        </div>
                        <div class="col-lg-4">
                            <button type="button" class="check_available btn text-light bg-success">Check
                                Availability</button>
                        </div>
                    </div>
                    <input type="hidden" name="id" class="id">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Password</label>
                        <div class="col-lg-9">
                            <input type="password" name="password" class="form-control password" autocomplete="off">
                        </div>
                        <div id="password_error" style="color:red; font-size: smaller"></div>
                    </div>

                    <input type="checkbox" id="see_password"> &nbsp; View Password

                    <div class="text-center">
                        <button type="submit" class="create_user_access ml-2 btn btn-success">Update</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('admin::layouts.popup')

<!-- update status popup modal -->

<div id="bulkUpdateStatus" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">Update Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open([
                    'route' => 'employee.bulkUserStatusActive',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'role' => 'form',
                ]) !!}
                {!! Form::hidden('employee_multiple_id[]', null, ['id' => 'employeeIds']) !!}

                <div class="form-group">
                    <div class="row">
                        <label class="col-form-label col-lg-3">User Status : <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            {{-- @php
                                    unset($statusList[1], $statusList[5]);
                                @endphp --}}
                            {!! Form::select('type', $actionTypeList, null, [
                                'id' => 'requestStatus',
                                'class' => 'form-control select2',
                                'placeholder' => 'Select Type',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn bg-success text-white">Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<script>
    $('.password').on('input', function() {
        const password = $(this).val();
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/;
        if (regex.test(password)) {
            $(this).css('border-color', 'green');
            $('#password_error').text('');
            $('.create_user_access').prop('disabled', false);
        } else {
            $(this).css('border-color', 'red');
            $('#password_error').text(
                'Password must be at least 10 characters and include uppercase, lowercase, number, and special character.'
            );
            $('.create_user_access').prop('disabled', true);
        }
    });
</script>
