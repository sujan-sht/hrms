@extends('admin::layout')
@section('title') Payroll @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Arrear Adjustment</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
            style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
    </div>
</div>


<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Mass Increment</h6>
            All the Mass Increment Information will listed below.
        </div>
        <div class="mt-1">
            <a href="{{ route('employeeMassIncrement.create') }}" class="btn btn-danger rounded-pill"><i
                    class="icon-plus3"></i>Create New</a>
        </div>
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="btn-slate text-light">
                    <th>S.N</th>
                    <th>Organization Name</th>
                    <th>Employee Name</th>
                    <th>Created Date</th>
                    <th>Updated Date</th>
                    <th width="25%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employeeMassIncrements as $key => $data)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $data->organizationModel->name }}</td>
                        <td>{{ $data->employee->getFullName() }}</td>
                        <td>{{ $data->created_at }}</td>
                        <td>{{ $data->updated_at }}</td>
                        <td>
                            @if ($menuRoles->assignedRoles('massIncrement.edit'))
                                <a class="btn btn-outline-primary btn-icon mx-1"
                                    href="{{ route('employeeMassIncrement.edit', $data->id) }}" data-popup="tooltip"
                                    data-placement="top" data-original-title="Edit">
                                    <i class="icon-pencil7"></i>
                                </a>
                            @endif
                            @if ($menuRoles->assignedRoles('massIncrement.delete') && count($data->details->where('status',true)) <=0)
                                <a data-toggle="modal" data-target="#modal_theme_warning"
                                    class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                    link="{{ route('employeeMassIncrement.delete', $data->id) }}" data-popup="tooltip"
                                    data-original-title="Delete" data-placement="bottom">
                                    <i class="icon-trash-alt"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $employeeMassIncrements->appends(request()->all())->links() }}
        </span>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script>
    $(document).ready(function() {
        payrollCalendarType();
        $('#organization1').on('change', function() {
            payrollCalendarType();
        });
        $('.confirmDelete').on('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Your file has been deleted.',
                        icon: 'success',
                        showCancelButton: false,
                        showConfirmButton: false,
                    });
                    window.location.href = $(this).attr('link');
                }
            });
        });
    })
</script>

<script>
    function payrollCalendarType() {
        var organizationId = $('.organizationID').val();
        $.ajax({
            type: 'GET',
            url: '/admin/payroll-setting/get-calendar-type',
            data: {
                organization_id: organizationId
            },
            success: function(data) {
                var list = JSON.parse(data);
                if (list.calendar_type == 'nep') {
                    $('.engDiv').hide();
                    $('.nepDiv').show();
                    $('.calendar_type').show();
                    $('.year').show();
                    $('.month').show();
                    $('#nepYear').removeAttr("disabled");
                    $('#nepMonth').removeAttr("disabled");
                    $('#calendarType').removeAttr("disabled");
                    $('#engYear').val('');
                    $('#engMonth').val('');
                } else {
                    $('.calendar_type').show();
                    $('.year').show();
                    $('.month').show();
                    $('.engDiv').show();
                    $('.nepDiv').hide();
                    $('#nepYear').val('');
                    $('#nepMonth').val('');
                }

            }
        });
    }
</script>
@endSection
