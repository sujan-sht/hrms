@extends('admin::layout')
@section('title') Annual Training Report @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Annual Training Report</a>
@stop

@section('content')

    {{-- @include('training::training-report.search-attendees-detail-report') --}}

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">Annual Training Report</h6>
                All the Training Information will be listed below.
            </div>
            <div class="ml-1">
                <a id="annualTrainingReport" class="btn btn-success rounded-pill">Export Report</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-body">
                <div class=" table-responsive">
                    <table class="table table-bordered" id="convertTable2excel">
                        <thead class="bg-slate text-center text-white">
                            <tr>
                                <th colspan="11"></th>
                                <th colspan="5">TRAINING DETAIL</th>
                                <th colspan="2"></th>
                                <th colspan="1">BUDGET</th>
                                <th colspan="12">MONTH LIST</th>
                            </tr>

                            <tr style="background-color: #546e7a; text-white;">
                                <th>SN</th>
                                <th>Organization</th>
                                <th>Training Title</th>
                                <th>Training Objective</th>
                                <th>Category</th>
                                <th>Training Type</th>
                                <th>Functional Type</th>
                                <th>Physical/ Virtual</th>
                                <th>Targeted Participants</th>
                                <th>Employee/ Dealer</th>
                                <th>Sub-Function</th>
                                <th>Frequency</th>
                                <th># of Pax/training</th>
                                <th>Frequency</th>
                                <th>Total No of Participants</th>
                                <th>Training Days</th>
                                <th>Mandays </th>
                                <th>Training facilitator </th>
                                {{-- <th>Trainers charge </th>
                                <th>Other Training Expenses </th>
                                <th>Participants Expenses </th>
                                <th>Total Cost </th> --}}
                                <th>Planned Budget</th>
                                @foreach ($monthLists as $month)
                                    <th>{{ $month }}</th>
                                @endforeach

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trainings as $key=>$training)
                                {{-- {{dd($training)}} --}}
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ optional($training->organization)->name }}</td>
                                    <td>{{ $training->title }}</td>
                                    <td>{{ $training->description }}</td>
                                    <td>{{ $training->facilitator }}</td>
                                    <td>{{ $training->type }}</td>
                                    <td>{{ $training->functional_type }}</td>
                                    <td>{{ $training->location }}</td>
                                    <td>{{ $training->targeted_participant }}</td>
                                    <td>{{ $training->training_for }}</td>
                                    <td>{{ optional($training->department)->title }}</td>
                                    <td>{{ $training->frequency }}</td>
                                    <td>{{ $training->pax_training }}</td>
                                    <td>{{ $training->no_of_participants }}</td>
                                    <td>{{ $training->no_of_days }}</td>
                                    <td>{{ $training->no_of_mandays }}</td>
                                    <td>{{ $training->facilitator_name }}</td>
                                    <td>{{ $training->facilitator_name }}</td>
                                    <td>{{ $training->planned_budget }}</td>
                                    @foreach ($monthLists as $monthKey => $month)
                                        @if ($training->month && in_array($monthKey, $training->month))
                                            <td style="font-size:15px;text-align:center;">Yes</td>
                                        @else
                                            <td></td>
                                        @endif
                                    @endforeach

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13">No Records Found !!!</td>
                                </tr>
                            @endforelse
                            {{-- @forelse ($trainings as $key=>$training)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ optional(optional($training->trainingInfo)->division)->dropvalue }}</td>
                                    <td>{{ optional($training->employeeModel)->full_name }}</td>
                                    <td>{{ optional($training->employeeModel)->employee_code }}</td>
                                    <td>{{ optional($training->trainingInfo)->from_date }}</td>
                                    <td>{{ optional($training->trainingInfo)->to_date }}</td>
                                    <td>{{ optional($training->trainingInfo)->title }}</td>
                                    <td>{{ optional($training->trainingInfo)->facilitator_name }}</td>
                                    <td>{{ optional($training->trainingInfo)->full_marks }}</td>
                                    <td>{{ $training->marks_obtained }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="13">No Records Found !!!</td>
                                </tr>
                            @endforelse --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ asset('admin/js/jquery.table2excel.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#annualTrainingReport').on('click', function() {
                var table = $('#convertTable2excel');
                if (table && table.length) {
                    $(table).table2excel({
                        exclude: '.noExl',
                        name: 'Annual Training Report',
                        filename: 'annual_training_report.xls',
                        fileext: '.xls',
                        exclude_img: true,
                        exclude_links: true,
                        exclude_inputs: true
                    })
                }
            })
        })
    </script>
@endsection
