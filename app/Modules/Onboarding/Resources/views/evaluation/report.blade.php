@extends('admin::layout')
@section('title') Evaluation @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Evaluation</a>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <legend class="text-uppercase font-size-sm font-weight-bold">Interview Evaluation Form</legend>
                <p>
                    Candidate Name : <span class="text-muted">{{ optional($parentModel->applicantModel)->full_name }}</span>
                </p>
                <p>
                    Address : <span class="text-muted">{{ optional($parentModel->applicantModel)->address }}</span>
                </p>
                <p>
                    Gender : <span class="text-muted">{{ optional($parentModel->applicantModel)->getGender() }}</span>
                </p>
                <p>
                    Experience : <span class="text-muted">{{ optional($parentModel->applicantModel)->experience }} Years</span>
                </p>
                <p>
                    Qualification : {!! optional($parentModel->applicantModel)->skills !!}
                </p>
            </div>
            <div class="col-md-12">
                <legend class="text-uppercase font-size-sm font-weight-bold">Rating Scale</legend>
                <div class="row">
                    <div class="col-md-1">5. Excellent</div>
                    <div class="col-md-1">4. Good</div>
                    <div class="col-md-1">3. Average</div>
                    <div class="col-md-1">2. Fair</div>
                    <div class="col-md-1">1. Poor</div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <table class="table table-boarded">
                    <thead>
                        <tr class="text-white">
                            <th>S.N</th>
                            <th>Attribute</th>
                            @foreach($evaluationModels as $evaluationModel)
                                <th>Interviewer <br>({{ optional($evaluationModel->employeeModel)->full_name }})</th>
                            @endforeach
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($interviewQuestionModels as $key => $interviewQuestionModel)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $interviewQuestionModel->question }}</td>
                                @foreach($evaluationModels as $evaluationModel)
                                    <td>{{ $evaluationModel->getScore($interviewQuestionModel->id) }}</td>
                                    {{-- <td>{{ $evaluationModel->getScore($interviewQuestionModel->id, $evaluationModel->id) }}</td> --}}
                                @endforeach
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2">
                    <p>Score : <b>{{ $parentModel->total_score / count($evaluationModels) }} / {{ $key * 5 }}</b></p>
                    @php
                        $score = (($parentModel->total_score / count($evaluationModels)) / ($key * 5) * 5);
                    @endphp
                    <p>Score (Percentage) : <b>{{ round($score, 2) }}</b> out of 5</p>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i class="icon-backward2"></i></b>Go Back</a>
        </div>

    </div>
</div>
@endsection
