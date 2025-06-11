@extends('admin::layout')

@section('title')Employee @stop

@section('breadcrum')
    <a class="breadcrumb-item active">Employees</a>
@endSection
@section('css')
    <style>
        .toggle-container {
            display: flex;
            border: 1px solid #ccc;
            border-radius: 20px;
            overflow: hidden;
            width: 100px;
            height: 40px;
            background-color: white;
        }

        .toggle-option {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            user-select: none;
        }

        .toggle-option.active {
            background-color: #d0e8ff;
            color: #0066cc;
        }

        .toggle-option:first-child {
            border-right: 1px solid #ccc;
        }

        .icon {
            font-size: 16px;
        }

        .dataTables_length {
            margin-right: 1.5rem;
        }



        .table-responsive {
            height: 876px !important;
        }


        #employee-table-list_filter {
            position: sticky;
            left: 0;
            z-index: 10;
            background-color: white;
            padding: 10px;
        }

        .suggestion-box {
            border: 1px solid #ddd;
            max-height: 400px;
            overflow-y: auto;
            display: none;
            list-style: none;
            padding: 0;
            margin: 0;
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .suggestion-box li {
            cursor: pointer;
            position: relative;
            /* Added for proper hover containment */
        }

        .suggestion-box li p {
            margin: 0;
            padding: 8px 12px;
        }

        .suggestion-item {
            cursor: pointer;
            display: block;
            /* Ensures the item takes full width */
        }

        .suggestion-item:hover {
            background-color: #ece6e6;
        }

        /* Remove any height gaps */
        .suggestion-item p {
            margin: 0;
            width: 100%;
            height: 100%;
        }

        .dropdown-divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 0;
        }

        .dropdown-item {
            display: block;
            padding: 8px 12px;
            text-decoration: none;
            color: #333;
        }
    </style>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    {{-- <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script> --}}
    <script src="{{ asset('admin/validation/uploadEmployee.js') }}"></script>
    <script src="{{ asset('admin/typeahead.bundle.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script>
        $(document).ready(function() {
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
        });
    </script>


    <script>
        $(document).ready(function() {
            var switch_view = "{{ request()->input('switch_view', 'list-view') }}"; // Default to 'list-view'
            if (switch_view == "list-view") {
                console.log('list view');
                $(".list-view").removeClass("d-none");
                $(".grid-view").addClass("d-none");
                $(".toggle-option").removeClass("active");
                $("#list-view").addClass("active");
                $(".list-view-only-columns").show();
                $("#bulk_active").removeClass('d-none');



            } else {
                $('.grid-import-export').removeClass('d-none');
                $("#bulk_active").addClass('d-none');
                $(".toggle-option").removeClass("active");
                $("#grid-view").addClass("active");
                $(".grid-view").removeClass("d-none");
                $(".list-view").addClass("d-none");
                $(".list-view-only-columns").hide();
            }
            $(".toggle-option").click(function() {
                $(".toggle-option").removeClass("active");
                $(this).addClass("active");
                if ($(this).attr("id") == "list-view") {
                    $(".grid-view").addClass("d-none");
                    $(".list-view").removeClass("d-none");
                    $("#switch_view").val("list-view");
                    $(".list-view-only-columns").show();
                    $("#bulk_active").removeClass('d-none');
                    $('.grid-import-export').addClass('d-none');

                } else if ($(this).attr("id") == "grid-view") {
                    $(".grid-view").removeClass("d-none");
                    $(".list-view").addClass("d-none");
                    $("#switch_view").val("grid-view");
                    $("#bulk_active").addClass('d-none');
                    $('.grid-import-export').removeClass('d-none');

                    $(".list-view-only-columns").hide();
                }

            });
        });
    </script>
@stop

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    @include('employee::employee.partial.directory-advance-filter')
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Employee</h6>
                All the Employee Information will be listed below. You can Create and Modify the data.
            </div>
            <div class="mr-2 d-none grid-import-export" style="margin-top: 0.5rem;">

                <a href="{{ route('employee.directoryReportExport', request()->all()) }}"
                    class="btn btn-outline-success btn-sm" data-popup="tooltip" data-placement="bottom"
                    data-original-title="Export"><i class="icon-file-excel"></i> Export</a>
            </div>
            <div class="d-none mr-2 grid-import-export" style="margin-top: 0.5rem;">
                <a href="#" class="btn btn-outline-secondary btn-sm" data-toggle="modal"
                    data-target="#modal_default_import" data-popup="tooltip" data-placement="bottom"
                    data-original-title="Import"><i class="icon-file-excel"></i>
                    Import</a>
            </div>
            <div class="mr-2" style="margin-top: 0.5rem;">
                <a href="{{ route('employee.create') }}" data-popup="tooltip" data-placement="bottom"
                    data-original-title="Add Employee" class="btn btn-success btn-sm    "><i class="icon-plus2"></i> Add
                    Employee</a>
            </div>
            <div class="mr-2" style="margin-top: 0.5rem;">
                <a href="{{ route('employee.downloadPdf', request()->all()) }}#" data-popup="tooltip"
                    data-placement="bottom" data-original-title="Download" class="btn btn-secondary btn-sm"><i
                        class="icon-file-download"></i>
                    Download</a>
            </div>
            <div class="toggle-container mt-1 mr-2">
                <div class="toggle-option" id="list-view" data-popup="tooltip" data-placement="bottom"
                    data-original-title="List view">
                    <span class="icon-list"></span>
                </div>
                <div class="toggle-option active" id="grid-view" data-popup="tooltip" data-placement="bottom"
                    data-original-title="Grid view">
                    <span class="icon-grid"></span>
                </div>
            </div>


            {{-- <div class="list-icons" style="margin-top: 0.9rem;">
                <div class="dropdown position-static">
                    <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"
                        data-popup="tooltip" data-placement="top" data-original-title="More">
                        <i class="icon-more2"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal_default_import">
                            <i class="icon-file-excel text-success"></i> Import
                        </a>
                        <a href="{{ route('employee.downloadSheet', request()->all()) }}#" class="dropdown-item">
                            <i class="icon-file-excel text-success"></i> Export
                        </a>
                        <a href="{{ route('employee.downloadPdf', request()->all()) }}#" class="dropdown-item">
                            <i class="icon-file-download text-warning"></i> Download
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('employee.indexArchive') }}" class="dropdown-item">
                            <i class="icon-archive text-danger"></i> Former Employee
                        </a>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>

    @include('employee::employee.partial.upload')

    {{-- Grid layout employee list --}}
    <div class="grid-view d-none">
        @include('employee::employee.switch-list.grid')
    </div>
    {{-- End Grid layout employee list --}}

    {{-- List layout employee list --}}
    <div class="list-view d-none">
        @include('employee::employee.switch-list.table')
    </div>
    {{-- End List layout employee list --}}




    <!-- Warning modal -->
    <div id="modal_parent_link" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h6 class="modal-title">Select Respective Sub-Function Head</h6>
                </div>

                <div class="modal-body">
                    {!! Form::open([
                        'route' => 'employee.updateType',
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'files' => true,
                    ]) !!}

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Select Parent Dept:</label>
                        <div class="col-lg-9">
                            <select name="parent_id" class="form-control select2 parent_id">
                                @php
                                    $HeadInfo = App\Modules\Employee\Entities\Employee::getHeadDept();
                                @endphp
                                @foreach ($HeadInfo as $key => $value)
                                    @php
                                        $user_type_explode = ucfirst(str_replace('_', ' ', $value['user_type']));
                                    @endphp
                                    <option value="{{ $value['id'] }}">
                                        {{ $value['first_name'] . ' :: ' . $user_type_explode }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    {{ Form::hidden('employerId', '', ['class' => 'employerId']) }}

                    <div class="text-center">
                        <button type="submit" class="btn bg-success">Link User</button>
                        <button type="button" class="btn bg-danger" data-dismiss="modal">Close</button>
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
    <div id="modal_theme_warning_status" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h6 class="modal-title text-white">Are you sure to offboard?</h6>
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
                            <select name="archived_type" id="archived_type" required class="form-control select2" required>
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
                                    'id' => 'offboarding-date',
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

    <!-- Warning modal -->
    <div id="modal_theme_success" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h6 class="modal-title text-white">Create User Access</h6>

                    {{-- <button type="button" class="btn bg-danger text-white" data-dismiss="modal">Close</button> --}}
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
                                            {{-- <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-user-tie"></i></span>
                                        </span> --}}
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
                        <button type="submit" class="create_user_access btn bg-success text-white">Create User</button>
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


    <script>
        $('#offboarding-date').nepaliDatePicker({
            container: '#modal_theme_warning_status',
            closeOnDateSelect: true
        });
    </script>

    <script type="text/javascript">
        $('document').ready(function() {
            $('.select-search').select2();
            $('.delete_employeen').on('click', function() {
                var link = $(this).attr('link');
                $('.get_link').attr('href', link);
            });

            $('.status_employee').on('click', function() {
                var employment_id = $(this).attr('employment_id');
                $('.employment_id').attr('value', employment_id);

            });

            $('.user_parent_link').on('click', function() {
                var empId = $(this).attr('empId');
                $('.employerId').val(empId);
                $('.parent_id').val($('#user_parent_id_' + empId).val());
            });

            $('.employer_user_access').on('click', function() {
                var emp_id = $(this).attr('emp_id');
                $('.employer_id').val(emp_id);
                var email = $(this).attr('email');
                $('#email').val(email);
                var employee_id = $(this).attr('employee_id');
                //$('#username').val(employee_id);
            });

            $('.employer_user_access_table').on('click', function() {
                var emp_id = $(this).attr('emp_id');
                $('.employer_id').val(emp_id);
                var email = $(this).attr('email');
                $('#email').val(email);
                var employee_id = $(this).attr('employee_id');
                //$('#username').val(employee_id);
            });


            $('.remove_user_access').on('click', function() {
                var link = $(this).attr('link');
                $('.get_link').attr('href', link);
            });

            $('.remove_user_access_table').on('click', function() {
                var link = $(this).attr('link');
                $('.get_link').attr('href', link);
            });


            $('.check_available, .create_user_access').on('click', function(event) {
                var username = $('#username').val();
                var user_exist = $('.user_exist').val();

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
                    url: 'employee/checkAvailability',
                    data: {
                        username: username
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
                                '<i class="icon-thumbs-up3 mr-1"></i>User Available.');
                            $('.error_username').removeClass('text-danger');
                            $('.error_username').addClass('text-success');
                            $('.user_exist').val('0');
                        }

                    }
                });
            });

        });
    </script>


    {{-- directory js --}}
    <script type="text/javascript">
        $('document').ready(function() {
            $('.branch-filter').on('change', function() {
                var branchId = $(this).val();
                var selectedUnitId = "{{ @request()->get('unit_id') }}" ?? null;
                $.ajax({
                    url: "{{ route('filter-branch-unit') }}",
                    type: "get",
                    data: {
                        branchId: branchId
                    },
                    success: function(response) {
                        var option = '';
                        option += '<option value="">Select Unit</option>';
                        $.each(response, function(index, value) {
                            option +=
                                `<option value="${index}" ${selectedUnitId==index ? 'selected':''}>${value}</option>`;
                        });
                        $('.unit-filter').html(option);
                    }
                });
            });
            $('.branch-filter').change();
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
    <!-- Select2 JS -->
    <script src="{{ asset('admin/assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/pages/form-advanced.init.js') }}"></script>


    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    <script>
        $('#modal_theme_warning_status').on('show.bs.modal', function(e) {
            $(this).find('form')[0].reset();
            $(this).find('.select2').val('').trigger('change');
        });
    </script>
    {{-- end directory js --}}


    {{-- <script>
        $(document).ready(function() {
            var table = $('#employee-table-list').DataTable({
                "pageLength": 25,
                "lengthMenu": [
                    [10, 25, 50, 100, 300, 500, 700],
                    [10, 25, 50, 100, 300, 500, 700]
                ]
            });
            $('#employee-table-list_length label').contents().filter(function() {
                return this.nodeType === 3;
            }).each(function() {
                this.nodeValue = this.nodeValue.replace('Show', 'Result Per Page').replace('entries', '');
            });

            var column = table.column('.checkAllContainer');
            column.visible(false);
            $("#active_user").change(function() {
                var column = table.column('.checkAllContainer');
                if ($(this).is(":checked")) {
                    $('.bulkUpdateStatusDiv').removeClass('d-none');
                    column.visible(true);
                } else {
                    $('.bulkUpdateStatusDiv').addClass('d-none');
                    column.visible(false);
                }
            });
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            // Remove the text "Search" from the label
            $('#employee-table-list_filter label').contents().filter(function() {
                return this.nodeType === 3; // Text node
            }).remove();

            // Remove margin from .dataTables_filter
            $('.dataTables_filter').css('margin', '0');


            // Add placeholder "Search" to the input element
            $('#employee-table-list_filter input').attr('placeholder', 'Search...');
        });
    </script>
@endsection
