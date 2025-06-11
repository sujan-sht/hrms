@extends('admin::layout')

@section('title')Employee @stop

@section('breadcrum')
    <a href="{{ route('employee.index') }}" class="breadcrumb-item">Employees</a>
    <a class="breadcrumb-item active">Edit</a>
@stop

@section('script')
    <!-- Theme JS files -->
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>

    <!-- /theme JS files -->
    <script src="{{ asset('admin/validation/employee.js') }}"></script>
@stop
@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@section('content')

    {!! Form::model($employees, [
        'method' => 'PUT',
        'route' => ['employee.update', $employees->id],
        'class' => 'form-horizontal',
        'id' => 'employee_submit',
        'role' => 'form',
        'files' => true,
    ]) !!}

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline text-light bg-secondary">
                    <h5 class="card-title">Employee Details</h5>
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>

                <div class="card-body">
                    @include('employee::employee.empForm.basicEmployeeDetail')
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                @php
                    $checked = false;
                    if ($employees->country != null) {
                        $checked = true;
                    }
                @endphp
                <div class="card-header header-elements-inline text-light bg-secondary">
                    <h5 class="card-title">Permanent Address</h5>
                    <input type="checkbox" id="permanent_address" {{ $checked ? 'checked' : '' }}
                        value="{{ $checked ? 1 : 0 }}" onchange='change(this);'
                        style=" transform: scale(1.5);margin-left: auto; display:block" class="permanent_address"><span
                        class="ml-2">Outside Country<span><br>
                </div>

                <div class="card-body">
                    @include('employee::employee.empForm.permanent_address')
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-elements-inline text-light bg-secondary">
                    <h5 class="card-title">Temporary Address</h5>
                    <input type="checkbox" id="temporary_address" value="1" onchange='handleChange(this);'
                        style=" transform: scale(1.5);margin-left: auto"><br>
                </div>

                <div class="card-body">

                    @include('employee::employee.empForm.temporary_address')

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-elements-inline text-light bg-secondary">
                    <h5 class="card-title">Manpower - Darbandi</h5>
                    <div class="header-elements">

                    </div>
                </div>

                <div class="card-body">
                    @include('employee::employee.empForm.darbandi')
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-elements-inline text-light bg-secondary">
                    <h5 class="card-title">Employee Approval Flow</h5>
                    <div class="header-elements">

                    </div>
                </div>

                <div class="card-body">
                    @include('employee::employee.empForm.approvalFlow')
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="accordion-styled">
                <div class="card-group-control card-group-control-right" id="accordion-control-right">
                    <div class="card">
                        <div class="card-header text-light bg-secondary">
                            <h6 class="card-title">
                                <a data-toggle="collapse" class="text-white" href="#accordion-styled-group1"
                                    aria-expanded="false"> Employee Documents (NOTE: The upload document should be of
                                    250*300 pixels)</a>
                            </h6>
                        </div>

                        <div id="accordion-styled-group1" class="collapse show" data-parent="#accordion-styled"
                            style="">
                            <div class="card-body">
                                @include('employee::employee.empForm.document')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="accordion-styled">
                <div class="card-group-control card-group-control-right">
                    <div class="card">
                        <div class="card-header text-light bg-secondary">
                            <h6 class="card-title">
                                <a data-toggle="collapse" class="text-white" href="#payrollRelatedDetail"
                                    aria-expanded="false"> Employee Payroll Related Details</a>
                            </h6>
                        </div>
                        <div id="payrollRelatedDetail" class="collapse show">
                            <div class="card-body">
                                @include('employee::employee.empForm.payrollRelatedDetail')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="accordion-styled">
                <div class="card-group-control card-group-control-right">
                    <div class="card">
                        <div class="card-header text-light bg-secondary">
                            <h6 class="card-title">
                                <a data-toggle="collapse" class="text-white" href="#otDetail" aria-expanded="false">
                                    Employee OT Details</a>
                            </h6>
                        </div>
                        <div id="otDetail" class="collapse show">
                            <div class="card-body">
                                @include('employee::employee.empForm.otDetail')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="accordion-styled">
                <div class="card-group-control card-group-control-right">
                    <div class="card">
                        <div class="card-header text-light bg-secondary">
                            <h6 class="card-title">
                                <a data-toggle="collapse" class="text-white" href="#jobDescription"
                                    aria-expanded="false">
                                    Job Description</a>
                            </h6>
                        </div>
                        <div id="jobDescription" class="collapse">
                            <div class="card-body">
                                @include('employee::employee.empForm.jobDescription')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($menuRoles->assignedRoles('employee.showLeaveDetail'))
        @if (count($employeeLeaveDetails) > 0)
            <div class="row">
                <div class="col-md-12">
                    <div class="card-group-control card-group-control-right">
                        <div class="card">
                            <div class="card-header bg-secondary">
                                <h5 class="card-title">
                                    <a data-toggle="collapse" class="text-white collapsed" href="#employeeLeaveDetail"
                                        aria-expanded="false">Employee Leave Detail</a>
                                </h5>
                            </div>
                            <div id="employeeLeaveDetail" class="collapse">
                                <div class="card-body">
                                    @include('employee::employee.empForm.employee_leave_detail')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif



    {{-- @if ($user != 'true') --}}
    @if (auth()->user()->user_type == 'super_admin' ||
            auth()->user()->user_type == 'admin' ||
            auth()->user()->user_type == 'hr')
        <div class="row">
            <div class="col-md-12">
                <div id="accordion-styled">
                    <div class="card-group-control card-group-control-right">
                        <div class="card">
                            <div class="card-header bg-secondary">
                                <h5 class="card-title"> <a data-toggle="collapse" class="text-white collapsed"
                                        href="#assignRole" aria-expanded="false">Employee Role</a></h5>
                            </div>
                            <div id="assignRole" class="collapse">
                                <div class="card-body">
                                    @include('employee::employee.empForm.assignRole')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="text-right">
        <button type="submit" class="ml-2 btn text-light bg-pink btn-labeled btn-labeled-left"><b><i
                    class="icon-database-insert"></i></b>Update</button>
    </div>

    <!-- /form inputs -->
    {!! Form::close() !!}

    <script>
        function handleChange(checkbox) {

            if (checkbox.checked == true) {
                const p_address = $('#permanentaddress').val();
                const p_district = $('#permanentdistrict').val();
                const p_municipality = $('#permanentmunicipality_vdc').val();
                const p_ward = $('#permanentward').val();
                const p_state = $('#permanentprovince').val();

                $('#temporaryaddress').val(p_address).prop('readonly', true);
                $('#temporarymunicipality_vdc').val(p_municipality).prop('readonly', true);
                $('#temporaryward').val(p_ward).prop('readonly', true);
                $('#temporaryprovince').val(parseInt(p_state)).prop('readonly', true).change();
                $.ajax({
                    type: 'GET',
                    url: '/admin/employee/getdistrict/' + p_state,
                    success: function(resp) {
                        $('#temporarydistrict').empty();
                        $('#temporarydistrict').html(resp);
                        $('#temporarydistrict').val(p_district).prop('readonly', true)
                    }
                });
            } else {
                $('#temporaryaddress').val('').prop('readonly', false);
                $('#temporarydistrict').val('').prop('readonly', false);
                $('#temporarymunicipality_vdc').val('').prop('readonly', false);
                $('#temporaryprovince').val('').prop('readonly', false);
                $('#temporaryward').val('').prop('readonly', false);

            }
        }

        function change(checkbox) {
            if (checkbox.checked == true) {
                $('.state').hide();
                $('.district').hide();
                $('.minicipality').hide();
                $('.ward').hide();
                $('.country').show();

            } else {
                $('.state').show();
                $('.district').show();
                $('.minicipality').show();
                $('.ward').show();
                $('.country').hide();
            }
        }

        //fetch department's first and last approval
        $('#department_id').on('change', function() {
            let department_id = $(this).val();
            fetchApproval(department_id);
        });

        function fetchApproval(department_id) {
            $.ajax({
                type: 'GET',
                url: '/admin/approvalFlow/fetchDepartmentApprovals',
                data: {
                    department_id: department_id
                },
                success: function(resp) {
                    let data = JSON.parse(resp);
                    $('#first_approval_user_id').val(data.first_approval_user_id);
                    $('#last_approval_user_id').val(data.last_approval_user_id);

                    $('#first_approval_user_id').val(data.first_approval_user_id).trigger('change');
                    $('#last_approval_user_id').val(data.last_approval_user_id).trigger('change');
                }
            });
        }

        $(document).ready(function() {
            var checkbox = $('.permanent_address').val();
            if (checkbox == '1') {
                $('.state').hide();
                $('.district').hide();
                $('.minicipality').hide();
                $('.ward').hide();
                $('.country').show();

            } else {
                $('.state').show();
                $('.district').show();
                $('.minicipality').show();
                $('.ward').show();
                $('.country').hide();
            }

        });


        $('.branch-filter').on('change', function() {
            var branchId = $(this).val();
            var selectedUnitId = "{{ @$employees->getUnit->id }}" ?? null;
            $.ajax({
                url: "{{ route('filter-branch-unit') }}",
                type: "get",
                data: {
                    branchId: branchId
                },
                success: function(response) {
                    console.log(response);

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
        $('.enableEdit').on('click', function(e) {
            e.preventDefault();
            $('.editBox').attr('readonly', false);
            $('.editBox').show();
            $('#edit_leave').val(1);
        })
        $('.branch-filter').change();
    </script>
@stop
