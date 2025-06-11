@extends('admin::layout')
@section('title') Leave @endSection
@section('breadcrum')
<a href="{{ route('leave.index') }}" class="breadcrumb-item">Leaves</a>
<a class="breadcrumb-item active">Apply Leave</a>
@stop

@section('content')

{!! Form::open([
    'route' => 'leave.store',
    'method' => 'POST',
    'class' => 'form-horizontal',
    'id' => 'leaveFormSubmit',
    'role' => 'form',
    'files' => true,
]) !!}

@include('leave::leave.partial.action', ['btnType' => 'Save Record'])

{!! Form::close() !!}

@endSection

@push('custom_script')
<script>
    $(document).ready(function() {
        let empId = '{!! getEmpId() !!}';

        $('.organizationFilter').on('change', function(e, data) {
            filterEmployeeAndLeaveTypeByOrganization(data);
        });

        if (empId) {
            empModel = (jQuery.parseJSON(empId));
            $('.organizationFilter').val([empModel.organization_id]).trigger('change', [empModel]);
        } else {
            $('.organizationFilter').trigger('change');

        }

        function filterEmployeeAndLeaveTypeByOrganization(empArray) {
            var organizationId = $('.organizationFilter').val();
            var employeeId = $('.employee-filter').val();
            var leaveTypeId = $('.leave-type-filter').val();

            $.ajax({
                type: 'GET',
                url: '/admin/organization/get-employees',
                data: {
                    organization_id: organizationId
                },
                success: function(data) {
                    var list = JSON.parse(data);
                    var options = '';

                    options += "<option value=''>Select Employee</option>";
                    $.each(list, function(id, value) {
                        options += "<option value='" + id + "'  >" + value + "</option>";
                    });

                    $('.employee-filter').html(options);
                    $('.employee-filter').select2();

                    if (employeeId) {
                        $('.employee-filter').val(employeeId);
                    }

                    if (empArray) {
                        $('.employee-filter').val(empArray.id);
                    }
                    $('input[name="leave_kind"]').prop('checked', false);

                    $('.employee-filter').trigger('change');
                    $('#remainingLeaveDetail').empty();
                    $('#remainingLeaveDiv').hide();
                    $('#leaveType').html("<option selected=\"selected\">Choose Leave Type</option>")
                        .select2();
                }
            });
        }

    });


   $(document).on('change', '#employeeId', function () {
    var employeeId = $(this).val();

    if (employeeId) {
        $.ajax({
            url: '{{ route("getEmployeeApprovalFlow") }}',
            method: 'GET',
            data: {
                employee_id: employeeId
            },
            success: function (response) {
                $('#first_approver').text(response.first_approver || 'N/A');
                $('#second_approver').text(response.second_approver || 'N/A');
                $('#third_approver').text(response.third_approver || 'N/A');
                $('#last_approver').text(response.last_approver || 'N/A');

                // Show the approval flow section
                $('#approvalFlowWrapper').removeClass('d-none');
            },
            error: function () {
                // Hide section or reset values on error
                $('#approvalFlowWrapper').addClass('d-none');
                $('#first_approver, #second_approver, #third_approver, #last_approver').text('N/A');
            }
        });
    } else {
        $('#approvalFlowWrapper').addClass('d-none');
    }
});

</script>
<script>
    window.currentUser = {
        user_type: '{{ auth()->user()->user_type }}',
        employee_id: '{{ auth()->user()->emp_id }}'
    };

</script>

<script>

   $(document).ready(function () {
    if (window.currentUser.user_type === 'employee' && window.currentUser.employee_id) {
        $.ajax({
            url: '{{ route("getEmployeeApprovalFlow") }}',
            method: 'GET',
            data: {
                employee_id: window.currentUser.employee_id
            },
            success: function (response) {
                console.log("Success:", response);
                $('#first_approver').text(response.first_approver || 'N/A');
                $('#second_approver').text(response.second_approver || 'N/A');
                $('#third_approver').text(response.third_approver || 'N/A');
                $('#last_approver').text(response.last_approver || 'N/A');
                $('#approvalFlowWrapper').removeClass('d-none');
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
                $('#approvalFlowWrapper').addClass('d-none');
            }
        });
    }
});

</script>


@endpush
