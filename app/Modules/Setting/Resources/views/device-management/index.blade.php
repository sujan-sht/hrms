@extends('admin::layout')
@section('title') Biometric Device Information @stop
@section('breadcrum')
    <a href="{{ route('deviceManagement.index') }}" class="breadcrumb-item">Biometric Device Information</a>
    <a class="breadcrumb-item active"> Add Device </a>
@endsection

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Biometric Devices</h6>
                All the Biometric Device Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('deviceManagement.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('deviceManagement.create') }}" class="btn btn-success rounded-pill">Add Biometric
                        Device</a>
                </div>
            @endif

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive ">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-light btn-slate">
                            <th>#</th>
                            <th>Organization</th>
                            <th>IP Address</th>
                            <th>Port</th>
                            <th>Device Id</th>
                            <th>Communication Password</th>
                            <th>Location</th>
                            <th>Status</th>
                            @if ($menuRoles->assignedRoles('deviceManagement.edit') || $menuRoles->assignedRoles('deviceManagement.delete'))
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($deviceModels->count() > 0)
                            @foreach ($deviceModels as $key => $type)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ optional($type->organization)->name }}</td>
                                    <td>{{ $type->ip_address }}</td>
                                    <td>{{ $type->port }}</td>
                                    <td>{{ $type->device_id }}</td>
                                    <td>{{ $type->communication_password }}</td>
                                    <td>{{ $type->location }}</td>
                                    <td class="text-teal">
                                        <span data-popup="tooltip"
                                            data-original-title="{{ $type->status ? 'Active' : 'In-Active' }}"
                                            class="badge badge-icon {{ $type->status ? 'badge-success border-success' : 'badge-danger border-danger' }} border-2 rounded-round">
                                            {{ $type->status ? 'Active' : 'Inactive' }}</i>
                                        </span>
                                    </td>
                                    @if ($menuRoles->assignedRoles('deviceManagement.edit') || $menuRoles->assignedRoles('deviceManagement.delete'))
                                        <td>
                                            <a data-toggle="modal" data-target="#updateStatus"
                                                class="btn btn-outline-warning btn-icon updateStatus mx-1"
                                                data-logs="{{ $type->logs }}" data-popup="tooltip" data-placement="top"
                                                data-original-title="Log Status">
                                                <i class="icon-flag3"></i>
                                            </a>

                                            @if ($menuRoles->assignedRoles('deviceManagement.edit'))
                                                <a class="btn btn-outline-primary btn-icon mx-1"
                                                    href="{{ route('deviceManagement.edit', $type->id) }}"
                                                    data-popup="tooltip" data-placement="bottom"
                                                    data-original-title="Edit"><i class="icon-pencil7"></i></a>
                                            @endif
                                            @if ($menuRoles->assignedRoles('deviceManagement.delete'))
                                                <a class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                                    link="{{ route('deviceManagement.delete', $type->id) }}"
                                                    data-placement="bottom" data-popup="tooltip"
                                                    data-original-title="Delete"><i class="icon-trash-alt"></i></a>
                                            @endif



                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>No Device Data Found!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{-- <span style="margin: 5px;float: right;">
                    @if ($deviceModels->count() != 0)
                        {{ $deviceModels->links() }}
                    @endif
                </span> --}}
            </div>
        </div>
    </div>

    <!-- Warning modal -->
    <div id="modal_theme_warning" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h6 class="modal-title">Are you sure to Delete a Device?</h6>
                </div>

                <div class="modal-body">
                    <a class="btn btn-success get_link" href="">Yes</a> &nbsp; | &nbsp;
                    <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /warning modal -->

    <div id="updateStatus" class="modal fade show" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attendance Log Detail</h5>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="appendLog">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('document').ready(function() {
            $('.delete_tada_type').on('click', function() {
                var link = $(this).attr('link');
                $('.get_link').attr('href', link);
            });

            $('.updateStatus').on('click', function(e) {
                dataArray = $(this).data('logs')
                $('#appendLog').empty();
                $.each(dataArray, function(index, item) {
                    $('#appendLog').append(
                        $('<tr>').append(
                            $('<td>').text(item.date),
                            $('<td>').text(item.total)
                        )
                    );
                });
            });

        });
    </script>

@endsection
