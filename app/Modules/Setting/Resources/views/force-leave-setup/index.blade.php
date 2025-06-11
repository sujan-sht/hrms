@extends('admin::layout')

@section('title')
    Force Leave Setup List
@endsection

@section('breadcrum')
    <a class="breadcrumb-item active">Force Leave Setups </a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


    {{-- @include('setting::leave-encashment-setup.partial.filter') --}}

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Force Leave Setups</h6>
                All the Force Leave Setups Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('forceLeaveSetup.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('forceLeaveSetup.create') }}" class="btn btn-success rounded-pill">Create</a>
                </div>
            @endif
        </div>
    </div>
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Include Holiday</th>
                        <th>Include DayOff</th>
                        <th>Number of days from DOJ</th>
                        <th style="width: 12%;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($forceLeaveSetups->total() > 0)
                        @foreach ($forceLeaveSetups as $key => $forceLeaveSetup)
                            <tr>
                                <td>
                                    #{{ $forceLeaveSetups->firstItem() + $key }}
                                </td>
                                <td>{{ $forceLeaveSetup->include_holiday == 11 ? 'Yes' : 'No' }}</td>
                                <td>{{ $forceLeaveSetup->include_dayoff == 11 ? 'Yes' : 'No' }}</td>
                                <td>{{ $forceLeaveSetup->days_limit_from_doj }}</td>


                                <td class="text-center d-flex">
                                    @if ($menuRoles->assignedRoles('forceLeaveSetup.edit'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('forceLeaveSetup.edit', $forceLeaveSetup->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('forceLeaveSetup.delete'))
                                        <a class="btn btn-outline-danger btn-icon mr-1 confirmDelete"
                                            data-placement="bottom" data-popup="tooltip" data-original-title="Delete"
                                            link="{{ route('forceLeaveSetup.delete', $forceLeaveSetup->id) }}">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">No record found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $forceLeaveSetups->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
@endsection
