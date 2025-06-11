@extends('admin::layout')
@section('title') Geo Fence @stop

@section('breadcrum')
    <a class="breadcrumb-item active">GeoFences</a>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@stop

@section('content')


    @php
        $colors = [
            'Rejected' => 'danger',
            'Pending' => 'warning',
            'Completed' => 'success',
            'Todo' => 'warning',
            'In Progress' => 'info',
            'Done' => 'success',
        ];
    @endphp

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of GeoFences</h6>
                All the GeoFences Information will be listed below. You can Create and Modify the data.
            </div>

            @if ($menuRoles->assignedRoles('geoFence.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('geoFence.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                        Add</a>
                </div>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th width="5%">S.N</th>
                    <th width="20%">Title</th>
                    <th width="15%">Longitude</th>
                    <th width="15%">Latitude</th>
                    <th width="20%">Radius</th>
                    @if ($menuRoles->assignedRoles('geoFence.edit') || $menuRoles->assignedRoles('geoFence.delete'))
                        <th width="15%" class="text-center">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody class="tbody">
                @if ($geofences->total() != 0)
                    @foreach ($geofences as $key => $geofence)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $geofence->title }}</td>
                            <td>{{ $geofence->latitude }}</td>
                            <td>{{ $geofence->longitude }}</td>
                            <td>{{ $geofence->radius }}</td>
                            <td>
                                @if ($menuRoles->assignedRoles('geoFence.allocationList'))
                                    <a class="btn btn-outline-info btn-icon mx-1"
                                        href="{{ route('geoFence.allocationList', $geofence->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Allocations">
                                        <i class="icon-task"></i>
                                    </a>
                                @endif

                                @if ($menuRoles->assignedRoles('geoFence.edit'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('geoFence.edit', $geofence->id) }}" data-popup="tooltip"
                                        data-original-title="Edit" data-placement="bottom">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('geoFence.delete'))
                                    @if (auth()->user()->user_type == 'admin' ||
                                            auth()->user()->user_type == 'super_admin' ||
                                            auth()->user()->user_type == 'hr')
                                        <a data-toggle="modal" data-target="#modal_theme_warning"
                                            class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                            link="{{ route('geoFence.delete', $geofence->id) }}" data-popup="tooltip"
                                            data-original-title="Delete" data-placement="bottom">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">No geofence Found !!!</td>
                    </tr>
                @endif
            </tbody>

        </table>
        <span style="margin: 5px;float: right;">
            @if ($geofences->total() != 0)
                {{ $geofences->links() }}
            @endif
        </span>
    </div>

    <script></script>

@endsection
