@extends('admin::layout')
@section('title') Work Log @stop

@section('breadcrum')
    <a href="{{ route('worklog.index') }}" class="breadcrumb-item">Work Log</a>
    <a class="breadcrumb-item active">List</a>
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

    @include('worklog::worklog.partial.filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Work Logs</h6>
                All the Work Logs Information will be listed below. You can Create and Modify the data.
            </div>

            @if ($menuRoles->assignedRoles('worklog.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('worklog.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                        Add</a>
                </div>
            @endif
            <div class="list-icons mt-2">
                <div class="dropdown position-static">
                    <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-more2"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ route('exportWorklogReport', request()->all()) }}" class="dropdown-item">
                            <i class="icon-file-excel text-success"></i> Export
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th width="5%">S.N</th>
                    <th width="10%">Date</th>
                    <th width="70%" class="text-center">Detail</th>
                    @if ($menuRoles->assignedRoles('worklog.edit') || $menuRoles->assignedRoles('worklog.delete'))
                        <th width="15%" class="text-center">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody class="tbody">
                @if ($worklogs->total() != 0)
                    @foreach ($worklogs as $key => $worklog)
                        <tr>

                            <td>#{{ $worklogs->firstItem() + $key }}</td>
                            {{-- <td>{{ date('M d, Y', strtotime($worklog->date)) }}</td> --}}
                            <td>
                                @if (setting('calendar_type') == 'BS')
                                    {{ date_converter()->eng_to_nep_convert($worklog['date']) }}
                                @else
                                    {{ date('M d Y', strtotime($worklog['date'])) }}
                                @endif
                            </td>

                            <td>
                                <table class="table table-white table-bordered table-striped table-hover bg-teal"
                                    style="width: 80%">
                                    <tr class="text text-white text-center">
                                        @if (auth()->user()->user_type != 'employee')
                                            <td>Name</td>
                                        @endif
                                        <td>Title</td>
                                        <td>Hours</td>
                                        <td>Status</td>
                                        <td>Description</td>
                                    </tr>

                                    @foreach ($worklog->workLogDetail as $item)
                                        <tr class="table-{{ $colors[$item->getStatus() ?? 'Pending'] }}">
                                            @if (auth()->user()->user_type != 'employee')
                                                <td>
                                                    <div class="media">
                                                        <div class="mr-3">
                                                            <a href="#">
                                                                <img src="{{ optional($item->employee)->getImage() }}"
                                                                    class="rounded-circle" width="40" height="40"
                                                                    alt="">
                                                            </a>
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="media-title font-weight-semibold">
                                                                {{ optional($item->employee)->getFullName() }}
                                                            </div>
                                                            <span
                                                                class="text-muted">{{ optional($item->employee)->official_email ?? optional($item->employee)->personal_email }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->hours ?? 0 }}</td>

                                            <td class="badgeContainer">
                                                <span
                                                    class="badge badge-{{ $colors[$item->getStatus() ?? 'Pending'] }}">{{ $item->getStatus() }}</span>
                                            </td>
                                            <td>
                                                @if (Str::length($item->detail) > 20)
                                                    <div class="text-center description">
                                                        <a data-toggle="modal" data-target="#viewDescription"
                                                            data-popup="tooltip" data-placement="top"
                                                            data-original-title="View All" style="color: #2196f3;">
                                                            {{ Str::limit($item->detail, 20) }}
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="text-center">
                                                        {{ $item->detail }}
                                                    </div>
                                                @endif
                                                {!! Form::hidden('desc', $item->detail, ['class' => 'desc']) !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>

                            @if (
                                $menuRoles->assignedRoles('worklog.edit') ||
                                    $menuRoles->assignedRoles('worklog.delete') ||
                                    $menuRoles->assignedRoles('worklog.view'))
                                <td class="text-center">
                                    {{-- <a class="btn btn-outline-secondary btn-icon mx-1 updateStatusClick" data-toggle="modal"
                                        data-target="#updateStatus"
                                        link="{{ route('attendanceRequest.updateStatus', $item->id) }}"
                                        data-id="{{ $item->id }}" data-value="{{ $item->status }}"
                                        data-placement="bottom" data-popup="tooltip" data-original-title="Update Status">
                                        <i class="icon-flag3"></i>
                                    </a> --}}

                                    @if ($menuRoles->assignedRoles('worklog.view'))
                                        <a class="btn btn-outline-info btn-icon mx-1"
                                            href="{{ route('worklog.view', $worklog->id) }}" data-popup="tooltip"
                                            data-placement="bottom" data-original-title="view Details"><i
                                                class="icon-eye"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('worklog.edit'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('worklog.edit', $worklog->id) }}" data-popup="tooltip"
                                            data-original-title="Edit" data-placement="bottom">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('worklog.delete'))
                                        @if (auth()->user()->user_type == 'admin' ||
                                                auth()->user()->user_type == 'super_admin' ||
                                                auth()->user()->user_type == 'hr')
                                            <a data-toggle="modal" data-target="#modal_theme_warning"
                                                class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                                link="{{ route('worklog.delete', $worklog->id) }}" data-popup="tooltip"
                                                data-original-title="Delete" data-placement="bottom">
                                                <i class="icon-trash-alt"></i>
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="">No Worklog Found !!!</td>
                    </tr>
                @endif
            </tbody>

        </table>
        <span style="margin: 5px;float: right;">
            @if ($worklogs->total() != 0)
                {{ $worklogs->links() }}
            @endif
        </span>
    </div>



    <!-- popup modal -->
    <div id="updateStatus" class="modal fade" tabindex="-1">
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
                        'route' => 'worklog.updateStatus',
                        'method' => 'PUT',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                    ]) !!}
                    <input type="hidden" name="id" class="updateid">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Status :</label>
                        <div class="col-lg-9">
                            {!! Form::select('status', $statusList, null, ['id' => 'worklogStatus', 'class' => 'form-control']) !!}
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

    <!-- popup modal -->
    <div id="viewDescription" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Description</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="appendDesc">

                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).on('click', '.updateStatusClick', function() {
            let id = $(this).data('id');
            $('.updateid').val(id);
            $('#worklogStatus').val($(this).attr('data-value'));
        })

        $(document).on('click', '.description', function() {
            var desc = $(this).closest('td').find('.desc').val()
            $('.appendDesc').html(desc)
        })
    </script>

@endsection
