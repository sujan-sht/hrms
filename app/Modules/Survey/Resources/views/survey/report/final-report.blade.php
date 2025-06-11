@extends('admin::layout')

@section('breadcrum')
    <a href="{{ route('survey.index') }}" class="breadcrumb-item">Surveys </a>
    <a class="breadcrumb-item active">Report</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    {{-- @include('attendance::monthly-attendance-summary.partial.filter') --}}

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">Survey Report</h6>
                All the Questions Response will be listed below.
            </div>
            {{-- @if ($show)
            <div class="mt-1">
                <a href="{{ route('exportMonthlySummary', request()->all()) }}" class="btn btn-success"><i class="icon-file-excel"></i> Export</a>
            </div>
            @endif --}}
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th class="text-center">Employee</th>
                    @foreach ($surveyQuestions as $question)
                        <th class="text-center">{{ $question }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @forelse ($surveyResponses as  $key => $surveyResponse)
                    <tr>
                        <td>{{ $i }}</td>

                        <td class="d-flex text-nowrap">
                            <div class="media">
                                <div class="mr-3">
                                    <a href="#">
                                        <img src="{{ $surveyResponse['image'] }}" class="rounded-circle" width="40"
                                            height="40" alt="">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-semibold">{{ $surveyResponse['fullName'] }}</div>
                                    <span class="text-muted">ID: {{ $surveyResponse['code'] }}</span>
                                </div>
                            </div>
                        </td>

                        @foreach ($surveyResponse['responses'] as $answer)
                            <td>
                                @if (Str::length($answer) > 20)
                                    <div class="text-center ans">
                                        <a data-toggle="modal" data-target="#updateStatus" data-popup="tooltip"
                                            data-placement="top" data-original-title="View All" style="color: #2196f3;">
                                            {{ Str::limit($answer, 20) }}
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center">
                                        {{ $answer }}
                                    </div>
                                @endif
                                {!! Form::hidden('desc', $answer, ['class' => 'answerDesc']) !!}
                            </td>
                        @endforeach
                    </tr>
                    @php $i++; @endphp
                @empty
                    <tr>
                        <td>No Response Found !!!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- <div class="row">
        <div class="col-12">
            <ul class="pagination pagination-rounded justify-content-end mb-3">
                {{ $emps->appends(request()->all())->links() }}
            </ul>
        </div>
    </div> --}}


    <!-- popup modal -->
    <div id="updateStatus" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Answer</h5>
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
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.ans').on('click', function() {
                var desc = $(this).closest('td').find('.answerDesc').val()
                $('.appendDesc').html(desc)
            })
        })
    </script>
@endsection
