@extends('admin::layout')
@section('title') Attendees Detail Report @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Attendees Detail Report</a>
@stop

@section('content')

    @include('training::training-report.search-attendees-detail-report')

    <div class="row">
        <div class="col-md-12">
            <legend class="text-uppercase font-size-sm font-weight-bold">Attendees Detail Report</legend>
            <div class="card card-body">
                <div class=" table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-slate text-center text-white">
                            <tr style="background-color: #546e7a; text-white;">
                                <th>SN</th>
                                <th>Organization</th>
                                <th>Employee Name</th>
                                <th>Employee Code</th>
                                <th>Status</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Module Name</th>
                                <th>Trainer</th>
                                <th>Full Marks</th>
                                {{-- <th>Marks Obtained</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attendanceModels as $key=>$attendanceModel)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ optional(optional($attendanceModel->trainingInfo)->organization)->name }}</td>
                                    <td>{{ optional($attendanceModel->employeeModel)->full_name }}</td>
                                    <td>{{ optional($attendanceModel->employeeModel)->employee_code }}</td>
                                    <td>
                                        @if ($attendanceModel->status)
                                            <span
                                                class="badge badge-{{ $attendanceModel->getStatusWithColor()['color'] }}">{{ $attendanceModel->getStatusWithColor()['status'] }}</span>
                                        @endif
                                    </td>

                                    <td>{{ optional($attendanceModel->trainingInfo)->from_date }}</td>
                                    <td>{{ optional($attendanceModel->trainingInfo)->to_date }}</td>
                                    <td>{{ optional($attendanceModel->trainingInfo)->title }}</td>
                                    <td>{{ optional(optional($attendanceModel->trainingInfo)->trainer)->full_name }}</td>
                                    <td>{{ optional($attendanceModel->trainingInfo)->full_marks }}</td>
                                    {{-- <td>{{ $attendanceModel->marks_obtained }}</td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13">No Records Found !!!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop
