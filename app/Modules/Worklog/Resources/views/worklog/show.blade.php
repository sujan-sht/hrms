@extends('admin::layout')
@section('title') Work Log @stop

@section('breadcrum')
    <a href="{{ route('worklog.index') }}" class="breadcrumb-item">Work Log</a>
    <a class="breadcrumb-item active">View</a>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@stop

@section('content')

    @php
        $colors = ['Rejected' => 'danger', 'Pending' => 'warning', 'Completed' => 'success', 'Todo' => 'warning', 'In Progress' => 'info', 'Done' => 'success'];
    @endphp

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th width=15%>Title</th>
                    <th width=10%>Time</th>
                    <th width=10%>Status</th>
                    <th width=10%>Priority</th>
                    <th width=10%>Assigned To</th>
                    <th width=10%>Employee</th>
                    <th width="35%" class="text-center">Remarks</th>
                </tr>
            </thead>
            <tbody class="tbody">
                @if (count($worklog->workLogDetail) > 0)
                    @foreach ($worklog->workLogDetail as $item)
                        <tr>
                            <td>#{{ $loop->iteration }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->hours ?? 0 }}</td>
                            <td class="badgeContainer">
                                <span
                                    class="badge badge-{{ $colors[$item->getStatus() ?? 'Pending'] }}">{{ $item->getStatus() }}</span>
                            </td>
                            <td>{{ $item->priority ?? '' }}</td>
                            <td>{{ $item->assigned_to }}</td> 
                            <td>
                                <div class="media">
                                    <div class="mr-3">
                                        <a href="#">
                                            <img src="{{ optional($item->employee)->getImage() }}" class="rounded-circle"
                                                width="40" height="40" alt="">
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
                            <td class="text-center">{{ $item->detail ?? '' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="">No Worklog Details Found !!!</td>
                    </tr>
                @endif
            </tbody>

        </table>
      
    </div>





@endsection
