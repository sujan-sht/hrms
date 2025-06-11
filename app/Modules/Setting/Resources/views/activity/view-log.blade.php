@extends('admin::layout')
@section('title') Activity Logs @endsection

@section('breadcrum')
<a class="breadcrumb-item">Activities logs</a>
@endsection

@section('content')

<style>
    table {
  table-layout: fixed;
}

</style>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-4">
        <legend class="text-uppercase font-size-sm font-weight-bold">All Activities logs</legend>
            </div>
            <div class="col-sm-6">
                <form method="GET" action="{{ route('activitiesLog.index') }}" class="form-inline mb-3">
                    <div class="form-group mr-2">
                        <label for="from_date" class="mr-2">From:</label>
                        <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}" class="form-control">
                    </div>
                    <div class="form-group mr-2">
                        <label for="to_date" class="mr-2">To:</label>
                        <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('activitiesLog.index') }}" class="btn btn-danger ml-1">Reset</a>
                </form>
            </div>
        </div>

        <div class="">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th width="2%">#</th>
                        <th >Description</th>
                        <th width="9%">Caused By</th>
                        <th>Properties</th>
                        <th width="9%">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $index => $activity)
                    <tr>
                        <td>{{ $activities->firstItem() + $index }}</td>
                        <td style="width:200">{{ $activity->description }}</td>
                        <td>
                            @if($activity->causer)
                            {{ $activity->causer->first_name ?? '' }}
                            {{ $activity->causer->middle_name ?? '' }}
                            {{ $activity->causer->last_name ?? '' }}
                            @else
                            System
                            @endif
                        </td>
                        <td>
                            @foreach($activity->properties->toArray() as $key => $value)
                                <strong>{{ ucfirst($key) }}:</strong><br>
                                @if(is_array($value))
                                    <ul class="mb-2">
                                        @foreach($value as $subKey => $subValue)
                                            <li>
                                                <strong>{{ ucfirst($subKey) }}:</strong>
                                                {{ is_array($subValue) ? json_encode($subValue) : $subValue }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    {{ $value }}<br>
                                @endif
                            @endforeach
                        </td>

                        <td>{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No activity logs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $activities->appends(request()->query())->links() }}
        </div>
    </div>

    @endsection
