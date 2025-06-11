@extends('admin::layout')

@section('title')
    {{ $title }}s
@endsection

@section('breadcrum')
    <a href="{{ route('shift.index') }}" class="breadcrumb-item">Shift </a>
    <a class="breadcrumb-item active"> List </a>
@endsection

@section('script')
    <!-- Your scripts here -->
@endsection

@section('content')
    <script>
        // Your JavaScript code here
    </script>

    @include('newshift::shift.partial.new-shift-filter')

    @if (isset($dates))
        <div class="card card-body">
            {!! Form::open([
                'method' => 'POST',
                'route' => 'shift.newShiftStore',
                'id' => 'event_submit',
                'class' => 'form-horizontal shiftForm',
                'role' => 'form',
                'files' => true,
            ]) !!}
            <div class="d-flex flex-row-reverse mb-1">
                <div class="px-2">
                    <button type="submit" class="btn btn-xl bg-success text-white">Publish</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered ">
                    <thead>
                        <tr class="text-white">
                            <th class="snCol">#</th>
                            <th class="empCol">Employee Name</th>
                            @foreach ($dates as $date)
                                <th class="text-center dayCol">
                                    {{ $date->format('M d ') }}
                                    ({{ $date->format('D') }})
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employeesData as $k => $employee)
                            <tr class="table table-{{ $employee['color'] }}">
                                <td class="snCol"> #{{ $employee['key'] }} </td>
                                <td class="empCol">
                                    <div class="media">
                                        <div class="mr-3">
                                            <a href="#">
                                                <img src="{{ $employee['empModel']->getImage() }}" class="rounded-circle"
                                                    width="40" height="40" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-title font-weight-semibold">
                                                {{ $employee['empModel']->getFullName() }}</div>
                                            @if (auth()->user()->user_type != 'employee')
                                                <span class="text-muted">Code :
                                                    {{ $employee['empModel']->employee_code }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                @foreach ($employee['dates'] as $k => $dateData)
                                    <td style='background-color:{{ $dateData['backgroundColor'] }}' class="dayCol" data-key="{{ $k }}">
                                        <div class="form-group append-clone">
                                            @include('newshift::shift.partial.clone', [
                                                'count' => 0,
                                                'empModel' => $employee['empModel'],
                                                'date' => $dateData['date'],
                                                'shiftArr' => $dateData['shiftArr'],
                                                'shiftValue' => $dateData['shiftValue']
                                            ])
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {!! Form::close() !!}
        </div>

        <div class="row">
            <div class="col-12">
                <ul class="pagination pagination-rounded justify-content-end mb-3">
                    @if ($emplists->total() != 0)
                        {{ $emplists->appends(request()->all())->links() }}
                    @endif
                </ul>
            </div>
        </div>
    @endif
@endsection
