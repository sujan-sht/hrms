@extends('admin::layout')
@section('title') MIS Training Report @stop
@section('breadcrum')
    <a class="breadcrumb-item active">MIS Training Report</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <legend class="text-uppercase font-size-sm font-weight-bold">Training MIS Report</legend>
            <div class="card card-body">
                <div class=" table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-slate text-center text-white">
                            <tr style="background-color: #546e7a; text-white;">
                                <th>SN</th>
                                <th>Organization</th>
                                <th>Module Name</th>
                                <th>Facilitator's Name</th>
                                <th>Training Type</th>
                                <th>Functional Type </th>
                                <th>Training Location</th>
                                <th>From</th>
                                <th>To</th>
                                <th>No. of Days</th>
                                <th>No. of Participants</th>
                                <th>No. of Mandays</th>
                                <th>Planned Budget</th>
                                <th>Actual Expense Incurred</th>
                                <th>Month</th>
                                <th>Employee/Dealer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($trainingModels) && !empty($trainingModels))
                                @foreach ($trainingModels as $key => $trainingModel)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ optional($trainingModel->organization)->name }}</td>
                                        <td>{{ $trainingModel->title }}</td>
                                        <td>{{ $trainingModel->facilitator_name }}</td>
                                        <td>{{ $trainingModel->type }}</td>
                                        <td>{{ $trainingModel->functional_type }}</td>
                                        <td>{{ $trainingModel->location }}</td>
                                        <td>{{ $trainingModel->from_date }}</td>
                                        <td>{{ $trainingModel->to_date }}</td>
                                        <td>{{ $trainingModel->no_of_days }}</td>
                                        <td>{{ $trainingModel->no_of_participants }}</td>
                                        <td>{{ $trainingModel->no_of_mandays }}</td>
                                        <td>{{ 'Rs. ' . $trainingModel->planned_budget }}</td>
                                        <td>{{ 'Rs. ' . $trainingModel->actual_expense_incurred }}</td>
                                        {{-- <td>{{ optional($trainingModel->monthInfo)->dropvalue }}</td> --}}
                                        <td>
                                            {!! $trainingModel->getMonth() !!}

                                        </td>
                                        <td>{{ $trainingModel->training_for }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="13">No Records Found !!!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop
