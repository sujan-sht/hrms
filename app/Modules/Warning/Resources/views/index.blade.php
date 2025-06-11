@extends('admin::layout')
@section('title') Warning @stop

@section('breadcrum')
    <a href="{{ route('warning.index') }}" class="breadcrumb-item">Warning</a>
    <a class="breadcrumb-item active">List</a>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
@stop

@section('content')

    @include('warning::partial.search')
    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Warnings</h6>
                All the Warning Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('warning.create'))
                <div class="mt-1">
                    <a href="{{ route('warning.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                        Add</a>
                </div>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>#</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Reg. No</th>
                    <th>Ref. No</th>
                    <th>Employee</th>
                    @if (
                        $menuRoles->assignedRoles('warning.edit') ||
                            $menuRoles->assignedRoles('warning.delete') ||
                            $menuRoles->assignedRoles('warning.view'))
                        <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if ($warnings->total() != 0)


                    @foreach ($warnings as $key => $warning)
                        <tr>
                            <td>{{ $warnings->firstItem() + $key }}</td>
                            <td>{{ $warning->title }}</td>
                            <td>
                                @if (setting('calendar_type') == 'BS')
                                    {{ date_converter()->eng_to_nep_convert($warning->date) }}
                                @else
                                    {{ $warning->date }}
                                @endif
                            </td>
                            <td>
                                {{ $warning->reg_no }}
                            </td>
                            <td>{{ $warning->ref_no }}</td>
                            <td>
                                @php
                                    $employees = json_decode($warning->employee_id);
                                    $employeeNames = array_map(function ($employeeId) {
                                        return App\Modules\Employee\Entities\Employee::find($employeeId)->full_name;
                                    }, $employees);
                                    $employeeList = implode(', ', $employeeNames);
                                @endphp

                                {{ $employeeList }}
                            </td>
                            <td>

                                @if ($menuRoles->assignedRoles('warning.edit'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('warning.edit', $warning->id) }}" data-popup="tooltip"
                                        data-original-title="Edit" data-placement="bottom">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif

                                @if ($menuRoles->assignedRoles('warning.delete'))
                                    <a data-toggle="modal" data-target="#modal_theme_warning"
                                        class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                        link="{{ route('warning.delete', $warning->id) }}" data-popup="tooltip"
                                        data-original-title="Delete" data-placement="bottom">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('warning.view'))
                                    <a class="btn btn-outline-secondary btn-icon mx-1"
                                        href="{{ route('warning.view', $warning->id) }}" data-popup="tooltip"
                                        data-original-title="View Warning" data-placement="bottom">
                                        <i class="icon-eye"></i>
                                    </a>
                                @endif


                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="">No Warnings Found !!!</td>
                    </tr>
                @endif
            </tbody>

        </table>
        <span style="margin: 5px;float: right;">
            @if ($warnings->total() != 0)
                {{ $warnings->appends(request()->all())->links() }}
            @endif
        </span>
    </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#organizationId').on('change', function() {
                var organization_id = $('#organizationId').val();

                $.ajax({
                    url: "{{ url('admin/notice/getOrganizationEmployee') }}",
                    method: 'GET',
                    data: {
                        organization_id: [organization_id]
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
    </script>

@endsection
