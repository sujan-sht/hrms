@extends('admin::employee.layout')

@section('breadcrum')
    <a href="{{ route('notice.index') }}" class="breadcrumb-item">Notice</a>
    <a class="breadcrumb-item active">List</a>

@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@stop

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Notices</h6>
            </div>
            @if ($menuRoles->assignedRoles('notice.create'))
                <div class="mt-1">
                    <a href="{{ route('notice.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
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
                    <th>Title</th>
                    <th>Projects</th>
                    <th>Notice Date</th>
                    <th>Notice Time</th>
                    <th>Attachment</th>
                    <th>Created By</th>
                    @if (
                        $menuRoles->assignedRoles('notice.edit') ||
                            $menuRoles->assignedRoles('notice.delete') ||
                            $menuRoles->assignedRoles('notice.view'))
                        <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if ($notice->count() != 0)
                    @foreach ($notice as $key => $value)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $value->title }}</td>
                            <td>{{ $value->projects }}</td>
                            <td>{{ $value->notice_date_nepali }}</td>
                            <td>{{ !empty($value->notice_time) ? date('g:i A', strtotime($value->notice_time)) : '-' }}
                            </td>
                            <td>
                                @if ($value->file != null)
                                    <a href="{{ asset('uploads/notice/' . $value->file) }}" target="_blank">
                                        {{ $value->file }}
                                    </a>
                                @else
                                    @foreach ($value->files as $file)
                                        - <a href="{{ asset('uploads/notice/' . $file->file) }}" target="_blank">
                                            {{ $file->file }}
                                        </a> <br>
                                    @endforeach
                                @endif
                            </td>
                            <td>{{ optional($value->creator)->user_type == 'super_admin' ? 'Administration' : ucfirst(optional($value->creator)->full_name) }}
                            </td>
                            <td>
                                @php
                                    $actionFlag = false;
                                    // if (auth()->user()->user_type == 'division_hr') {
                                    if ($value->created_by == auth()->user()->id) {
                                        $actionFlag = true;
                                    }
                                    // }
                                @endphp

                                @if ($menuRoles->assignedRoles('notice.edit'))
                                    @if ($actionFlag)
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('notice.edit', $value->id) }}" data-popup="tooltip"
                                            data-original-title="Edit" data-placement="bottom">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif
                                @endif
                                @if ($menuRoles->assignedRoles('notice.view'))
                                    <a class="btn btn-outline-secondary btn-icon mx-1"
                                        href="{{ route('notice.view', $value->id) }}" data-popup="tooltip"
                                        data-original-title="View" data-placement="bottom">
                                        <i class="icon-eye"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('notice.delete'))
                                    @if ($actionFlag)
                                        <a data-toggle="modal" data-target="#modal_theme_warning"
                                            class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                            link="{{ route('notice.delete', $value->id) }}" data-popup="tooltip"
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
                        <td class="">No Notice Found !!!</td>
                    </tr>
                @endif
            </tbody>

        </table>
        <span style="margin: 5px;float: right;">
            @if ($notice->count() != 0)
                {{ $notice->links() }}
            @endif
        </span>
    </div>
    </div>

@endsection
