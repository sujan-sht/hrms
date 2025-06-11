@extends('admin::layout')

@section('title')
    Former Employee
@endsection

@section('breadcrum')
    <a class="breadcrumb-item active">List of Former Employee</a>
@endsection


@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


    @include('employee::employee.partial.directory-advance-filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Former Employee</h6>
                All the Former Employees Information will be listed below.
            </div>
            <div class="mt-1 mr-2">
                <a href="{{ route('employee.directoryReportExport', request()->all()) }}" class="btn btn-success"><i
                        class="icon-file-excel"></i> Export</a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped mb-0" id="former-employee-table-list">
                            <thead>
                                <tr class="text-white">
                                    <th>S.N</th>
                                    <th>Code</th>
                                    <th>Full Name</th>
                                    <th>Unit</th>
                                    <th @if ($displayAll && !in_array('address', $select_columns)) @hide @endif>Address</th>
                                    @if (auth()->user()->user_type != 'employee' && auth()->user()->user_type != 'supervisor')
                                        <th @if ($displayAll && !in_array('mobile', $select_columns)) @hide @endif>Mobile</th>
                                    @else
                                        <th @if ($displayAll && !in_array('official_email', $select_columns)) @hide @endif>Personal Email</th>
                                    @endif
                                    <th @if ($select_columns && in_array('phone', $select_columns)) @else @hide @endif>CUG No.</th>

                                    {{-- <th @if ($displayAll && !in_array('email', $select_columns)) @hide @endif>Personal Email</th> --}}

                                    @if (auth()->user()->user_type == 'admin' ||
                                            auth()->user()->user_type == 'super_admin' ||
                                            auth()->user()->user_type == 'hr')
                                        <th @if ($displayAll && !in_array('official_email', $select_columns)) @hide @endif>Personal Email</th>
                                        <th @if ($displayAll && !in_array('dob', $select_columns)) @hide @endif>D.O.B</th>
                                        <th @if ($displayAll && !in_array('level', $select_columns)) @hide @endif>Grade</th>
                                        <th @if ($displayAll && !in_array('join_date', $select_columns)) @hide @endif>Join Date</th>
                                    @endif


                                    <th @if ($displayAll && !in_array('group', $select_columns)) @hide @endif>Blood Group</th>
                                    <th @if ($displayAll && !in_array('designation', $select_columns)) @hide @endif>Designation</th>
                                    <th>Archived Date</th>
                                    <th>Archived Reason</th>
                                    <th>Status</th>

                                    @if (auth()->user()->user_type != 'employee')
                                        @if ($menuRoles->assignedRoles('employee.view') || $menuRoles->assignedRoles('employee.resetPassword'))
                                            <th style="width: 130px;">Action</th>
                                        @endif
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($employeeModels->total() > 0)
                                    @foreach ($employeeModels as $key => $employeeModel)
                                        <tr>

                                            <td>
                                                {{ $employeeModels->firstItem() + $key }}
                                            </td>
                                            <td>{{ $employeeModel->employee_code }}</td>
                                            <td>
                                                <div class="media">
                                                    <div class="mr-3">
                                                        <a href="#">
                                                            <img src="{{ $employeeModel->getImage() }}"
                                                                class="rounded-circle" width="40" height="40"
                                                                alt="">
                                                        </a>
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="media-title font-weight-semibold">
                                                            {{ $employeeModel->getFullName() }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ optional($employeeModel->branchModel)->name }}</td>
                                            <td @if ($displayAll && !in_array('address', $select_columns)) @hide @endif>
                                                {{ $employeeModel->permanentaddress }}
                                            </td>
                                            @if (auth()->user()->user_type != 'employee' && auth()->user()->user_type != 'supervisor')
                                                <td @if ($displayAll && !in_array('mobile', $select_columns)) @hide @endif>
                                                    {{ $employeeModel->mobile }}
                                                </td>
                                            @else
                                                <td @if ($displayAll && !in_array('official_email', $select_columns)) @hide @endif>
                                                    {{ $employeeModel->official_email ?? '-' }}
                                                </td>
                                            @endif
                                            <td @if ($select_columns && in_array('phone', $select_columns)) @else @hide @endif>
                                                {{ $employeeModel->phone }}
                                            </td>

                                            @if (auth()->user()->user_type == 'admin' ||
                                                    auth()->user()->user_type == 'super_admin' ||
                                                    auth()->user()->user_type == 'hr')
                                                <td @if ($displayAll && !in_array('official_email', $select_columns)) @hide @endif>
                                                    {{ $employeeModel->official_email ?? '-' }}
                                                </td>
                                                <td @if ($displayAll && !in_array('dob', $select_columns)) @hide @endif>
                                                    {{ setting('calendar_type') == 'BS' ? $employeeModel->nep_dob : date('M d, Y', strtotime($employeeModel->dob)) }}
                                                </td>
                                                <td @if ($displayAll && !in_array('level', $select_columns)) @hide @endif>
                                                    {{ optional($employeeModel->level)->title ?? '-' }}
                                                </td>

                                                <td @if ($displayAll && !in_array('join_date', $select_columns)) @hide @endif>
                                                    {{ setting('calendar_type') == 'BS' ? $employeeModel->nepali_join_date : date('M d, Y', strtotime($employeeModel->join_date)) }}
                                                </td>
                                            @endif
                                            <td @if ($displayAll && !in_array('group', $select_columns)) @hide @endif>
                                                {{ optional($employeeModel->getBloodGroup)->dropvalue ?? '-' }}
                                            </td>

                                            <td @if ($displayAll && !in_array('designation', $select_columns)) @hide @endif>
                                                {{ optional($employeeModel->designation)->title ?? '-' }}
                                            </td>
                                            <td>
                                                {{ setting('calendar_type') == 'BS' ? $employeeModel->nep_archived_date : date('M d, Y', strtotime($employeeModel->archived_date)) }}
                                            </td>
                                            <td>
                                                {{ $employeeModel->archive_reason }}
                                            </td>
                                            @php
                                                $archiveType = App\Modules\Employee\Enum\ArvhiveStatusEnum::getNameByValue(
                                                    $employeeModel->archived_type,
                                                );
                                            @endphp
                                            <td>{{ $archiveType }}</td>
                                            @php
                                                $insuranceDetail = optional($employeeModel->insuranceDetail);
                                            @endphp
                                            @if (auth()->user()->user_type != 'employee')
                                                <td class="d-flex">
                                                    @if ($menuRoles->assignedRoles('employee.view'))
                                                        <a href="{{ route('employee.view', $employeeModel->id) }}"
                                                            class="btn btn-outline-info btn-icon mr-1" data-popup="tooltip"
                                                            data-placement="top" data-original-title="View">
                                                            <i class="icon-eye"></i>
                                                        </a>
                                                    @endif
                                                    <a data-toggle="modal" data-target="#modal_theme_warning_status"
                                                        class="btn btn-outline-secondary btn-icon border-1"
                                                        data-popup="tooltip" data-placement="bottom"
                                                        data-employee-id="{{ $employeeModel->id }}"
                                                        data-original-title="Move To Active"><i
                                                            class="icon-thumbs-up3"></i></a>

                                                    @if ($menuRoles->assignedRoles('employee.resetDevice') && isset(optional($employeeModel->getUser)->imei))
                                                        <a href="{{ route('employee.resetDevice', $employeeModel->id) }}"
                                                            class="btn btn-outline-primary btn-icon mr-1"
                                                            data-popup="tooltip" data-placement="top"
                                                            data-original-title="Reset Device Data">
                                                            <i class="icon-reset"></i>
                                                        </a>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Warning modal -->
    <div id="modal_theme_warning_status" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h6 class="modal-title text-white">Are you sure to onboard ?</h6>
                    <a href="" class="text-white" style="font-size: 0.9rem;" data-dismiss="modal">X</a>
                </div>
                <script src="{{ asset('admin/global/js/plugins/pickers/pickadate/picker.js') }}"></script>
                <script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>

                <div class="modal-body">
                    <form action="{{ route('employee.update.status') }}" method="post">
                        @csrf
                        <input type="hidden" name="employee_id" id="employee_id">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Reason for activation:</label>
                            <div class="col-lg-9">
                                <select name="archived_type" id="archived_type" required class="form-control select2"
                                    required>
                                    <option value="">---Select Reason---</option>
                                    @php
                                        $list = [
                                            'rejoin' => 'Rejoin',
                                            'other' => 'Other',
                                        ];
                                    @endphp

                                    @foreach ($list as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Remarks:</label>
                            <div class="col-lg-9">
                                {!! Form::textarea('archive_reason', $value = null, [
                                    'id' => 'archive_reason',
                                    'placeholder' => 'Enter Reason for activation',
                                    'class' => 'form-control',
                                    'required' => 'required',
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Rejoining Date:</label>
                            <div class="col-lg-9">
                                <x-utilities.date-picker :date="null" mode="both" default="nep"
                                    nepDateAttribute="nep_archived_date" engDateAttribute="archived_date" />
                                {{-- @if (setting('calendar_type') == 'BS')
                                    {!! Form::text('archived_date', $value = null, [
                                        'id' => 'archive_date',
                                        'placeholder' => 'Rejoining Date',
                                        'class' => 'form-control nepali-calendar',
                                        'id' => 'offboarding-date',
                                    ]) !!}
                                @else
                                    {!! Form::date('archived_date', $value = null, [
                                        'id' => 'archive_date',
                                        'placeholder' => 'Rejoining Date',
                                        'class' => 'form-control daterange-single',
                                    ]) !!}
                                @endif --}}
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn bg-success text-white">Submit</button>
                            <button type="button" class="btn bg-danger text-white" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- /warning modal -->

    <div class="row">
        <div class="col-12">
            <ul class="pagination pagination-rounded justify-content-end mb-3">
                {{-- @if ($employeeModels->total() != 0)
                    {{ $employeeModels->appends(request()->all())->links() }}
                @endif --}}
            </ul>
        </div>
    </div>

    <div class="modal modal-form fade" id="modal_theme_notice" tabindex="-1" role="dialog"
        aria-labelledby="noticeLabel" aria-hidden="true">
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
                <div class="modal-header bg-warning text-white">
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
                            <label class="col-form-label col-lg-3">Type : <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
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
                        <button type="submit" class="btn bg-success text-white">Save Changes</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/pages/form-advanced.init.js') }}"></script>
    <script>
        $(document).ready(function() {

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

            let searchTimeout;

            $('#filterFullName').on('input', function() {
                let query = $(this).val();
                clearTimeout(searchTimeout);
                if (query.length > 1) {
                    searchTimeout = setTimeout(function() {
                        $.ajax({
                            url: "{{ route('search.employee.fullname') }}",
                            method: 'GET',
                            data: {
                                query: query
                            },
                            success: function(response) {
                                let results = '';

                                if (response.length > 0) {
                                    response.forEach(employee => {
                                        results += `
                                            <div class="mt-3">
                                                <li class="suggestion-item">
                                                    <p>${employee.fullname}</p>
                                                    <div class="dropdown-divider"></div>
                                                </li>
                                            </div>
                                        `;
                                    });
                                } else {
                                    results += `
                                        <li>
                                            <a href="{{ route('employee.index') }}" class="dropdown-item">
                                                Employee List
                                            </a>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                    `;
                                }

                                $('#search-employee-results').html(results).show();
                            },
                            error: function(xhr, status, error) {
                                console.error('Search error:', error);
                            }
                        });
                    }, 300); // 300ms delay
                } else {
                    $('#search-employee-results').hide();
                }
            });

            $(document).on('click', '.suggestion-item', function() {
                const selectedName = $(this).find('p').text();
                $('#filterFullName').val(selectedName);
                $('#search-employee-results').hide();
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#filterFullName, #search-employee-results').length) {
                    $('#search-employee-results').hide();
                }
            });
        });
    </script>



    <script>
        $(document).ready(function() {
            $('#former-employee-table-list').dataTable({
                "iDisplayLength": 25
            });

            $('#former-employee-table-list_length label').contents().filter(function() {
                return this.nodeType === 3;
            }).each(function() {
                this.nodeValue = this.nodeValue.replace('Show', 'Result Per Page').replace('entries', '');
            });


            // Remove the text "Search" from the label
            $('#former-employee-table-list_filter label').contents().filter(function() {
                return this.nodeType === 3; // Text node
            }).remove();

            // Remove margin from .dataTables_filter
            $('.dataTables_filter').css('margin', '0');


            // Add placeholder "Search" to the input element
            $('#former-employee-table-list_filter input').attr('placeholder', 'Search...');
        });
    </script>
    <script type="text/javascript">
        $('document').ready(function() {

            $(document).on('change', '.sortBy', function() {
                var value = $(this).val();
                var search_form = $('#directorySearchForm').serialize() + '&sortBy=' + value;
                var url = window.location.origin + "" + window.location.pathname + '?' + search_form;
                window.location = url;
            });


            $(document).on('click', '.updateUser', function() {
                $('.username').val($(this).data('username'))
                $('.id').val($(this).data('id'))

                $('.check_available, .create_user_access').on('click', function(event) {
                    var username = $('#username').val();
                    var user_exist = $('.user_exist').val();
                    var userid = $('.id').val();

                    if (username == '') {
                        $('#username').focus();
                        $('#username').css('border-color', 'red');
                        $('.error_username').html(
                            '<i class="icon-thumbs-down3 mr-1"></i> Please Set Username.');
                        $('.error_username').addClass('text-danger');
                        event.preventDefault();
                        return false;
                    }

                    $.ajax({
                        type: 'GET',
                        url: 'checkAvailabilityOthers',
                        data: {
                            username: username,
                            userid: userid
                        },
                        async: false,
                        success: function(data) {

                            if (data == 1) {
                                $('#username').css('border-color', 'red');
                                $('.error_username').html(
                                    '<i class="icon-thumbs-down3 mr-1"></i> Username Already Exists.'
                                );
                                $('.error_username').removeClass('text-success');
                                $('.error_username').addClass('text-danger');
                                $('.user_exist').val('1');
                                $('#username').focus();
                                event.preventDefault();
                            } else {
                                $('#username').css('border-color', 'green');
                                $('.error_username').html(
                                    '<i class="icon-thumbs-up3 mr-1"></i>User Available.'
                                );
                                $('.error_username').removeClass('text-danger');
                                $('.error_username').addClass('text-success');
                                $('.user_exist').val('0');
                            }

                        }
                    });
                });
            })

            $(document).on('change', '#see_password', function() {
                if (this.checked) {
                    $('.password').attr('type', 'text')
                } else {
                    $('.password').attr('type', 'password')
                }
            })

            //multiple leaves update status
            $('#checkAll').checkAll();
            $('.checkItem').on('click', function() {
                var anyChecked = $('.checkItem:checked').length > 0;
                $('.bulkUpdateStatusDiv').toggleClass('d-none', !anyChecked);
            });

            $('.checkAll').on('click', function() {
                var anyChecked = $('.checkAll:checked').length > 0;
                $('.bulkUpdateStatusDiv').toggleClass('d-none', !anyChecked);
            });

            $(document).on("click", '.bulkUpdateStatus', function() {
                // $("#bulkUpdateStatus").html('');
                var request_ids = $("input[name='employee_ids[]']:checked").map(function() {
                    return $(this).val();
                }).get();
                var request_ids_string = JSON.stringify(request_ids);

                $('#employeeIds').val(request_ids_string)
                $('#bulkUpdateStatus').modal('show')
            });
            //
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $(document).on('click', '[data-target="#modal_theme_warning_status"]', function() {

                // $('.daterange-single').daterangepicker({
                //     parentEl: '#modal_theme_warning_status',
                //     singleDatePicker: true,
                //     showDropdowns: true,
                //     autoUpdateInput: false,
                //     locale: {
                //         format: 'YYYY-MM-DD'
                //     }
                // });
                modal = $('#modal_theme_warning_status');
                modal.find('form')[0].reset();

                var employeeId = $(this).data('employee-id');
                $('#modal_theme_warning_status').find('#employee_id').val(employeeId);
            });
        })
    </script>
    <script>
        $(document).ready(function() {
            $("#toggleButton").click(function() {
                const $moreFields = $(".moreFieldsAdd");
                const $toggleButton = $(this);

                if ($moreFields.is(":hidden")) {
                    $moreFields.slideDown();
                    $toggleButton.html("<i class='icon-arrow-up16'></i> Show Less Filters");
                } else {
                    $moreFields.slideUp();
                    $toggleButton.html("<i class='icon-arrow-down16'></i> Show More Filters");
                }
            });
        })
    </script>
@endsection
