@extends('admin::layout')
@section('title')Event @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Event</a>
@stop
@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@stop
@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')


@section('content')


    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Events</h6>
                All the Event Informations will be listed below. You can Create and Modify the data.
            </div>
            <div class="mt-1">
                <a href="{{ route('event.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                    Add Event</a>
            </div>
        </div>
    </div>


    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Event Title</th>
                        <th>Event Start Date</th>
                        <th>Event End Date</th>
                        <th>Event Time</th>
                        <th>Created By</th>
                        @if (
                            $menuRoles->assignedRoles('event.edit') ||
                                $menuRoles->assignedRoles('event.delete') ||
                                $menuRoles->assignedRoles('event.view'))
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($event->total() != 0)
                        @php
                            if (auth()->user()->user_type == 'hr') {
                                $divisionHr = array_keys(employee_helper()->getParentUserList(['division_hr'], false));
                                array_push($divisionHr, auth()->user()->id);
                            }
                        @endphp
                        @foreach ($event as $key => $value)
                            <tr>
                                <td>#{{ $event->firstItem() + $key }}</td>
                                <td>{{ $value->title }}</td>
                                <td>{{ setting('calendar_type') == 'BS' ? date_converter()->eng_to_nep_convert($value->event_start_date) : getStandardDateFormat($value->event_start_date) }}
                                </td>
                                <td>{{ setting('calendar_type') == 'BS' ? date_converter()->eng_to_nep_convert($value->event_end_date) : getStandardDateFormat($value->event_end_date) }}
                                </td>
                                <td>{{ date('g:i A', strtotime($value->event_time)) }}</td>
                                <td>{{ optional($value->creator)->user_type == 'super_admin' ? 'Administration' : ucfirst(optional($value->createdBy)->full_name) }}
                                    @if (
                                        $menuRoles->assignedRoles('event.edit') ||
                                            $menuRoles->assignedRoles('event.delete') ||
                                            $menuRoles->assignedRoles('event.view'))
                                <td>
                                    @php
                                        $actionFlag = false;
                                        if (auth()->user()->user_type == 'hr') {
                                            if (in_array($value->created_by, $divisionHr)) {
                                                $actionFlag = true;
                                            }
                                        } elseif (auth()->user()->user_type != 'super_admin') {
                                            if (auth()->user()->id == $value->created_by) {
                                                $actionFlag = true;
                                            }
                                        } else {
                                            $actionFlag = true;
                                        }
                                    @endphp

                                    @if ($menuRoles->assignedRoles('event.view'))
                                        <a class="btn btn-outline-secondary btn-icon mx-1"
                                            href="{{ route('event.view', $value->id) }}" data-popup="tooltip"
                                            data-original-title="View" data-placement="bottom">
                                            <i class="icon-eye"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('event.edit'))
                                        @if ($actionFlag)
                                            <a class="btn btn-outline-primary btn-icon mx-1"
                                                href="{{ route('event.edit', $value->id) }}" data-popup="tooltip"
                                                data-original-title="Edit" data-placement="bottom">
                                                <i class="icon-pencil7"></i>
                                            </a>
                                        @endif
                                    @endif

                                    @if ($menuRoles->assignedRoles('event.delete'))
                                        @if ($actionFlag)
                                            <a data-toggle="modal" data-target="#modal_theme_warning"
                                                class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                                link="{{ route('event.delete', $value->id) }}" data-popup="tooltip"
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
                        <td colspan="4">No Event Found !!!</td>
                    </tr>
                    @endif
                </tbody>

            </table>
            <span style="margin: 5px;float: right;">
                @if ($event->total() != 0)
                    {{ $event->links() }}
                @endif
            </span>
        </div>
    </div>

@endsection
