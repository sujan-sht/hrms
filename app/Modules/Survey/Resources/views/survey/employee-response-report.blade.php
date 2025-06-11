@extends('admin::layout')
@section('title') View Survey Report @endSection
@section('breadcrum')
<a class="breadcrumb-item active">View Survey Report</a>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <legend class="text-uppercase font-size-sm font-weight-bold">Survey Title</legend>
                            <ul class="media-list">
                                <li class="media mt-2">
                                    <span class="font-weight-semibold mr-3">{{ $surveyTitle }}</span>
                                </li>
                            </ul>
                            <br>
                            <legend class="text-uppercase font-size-sm font-weight-bold">Survey Responses</legend>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        @foreach ($surveyResponses as $key => $surveyResponse)
                                            <div class="col-lg-12 mb-2">
                                                <div class="row">
                                                    <div class="col-form-label col-lg-12">
                                                        <span class="font-weight-semibold mr-1">Q{{ ++$key }}.</span>
                                                        <span>{{ optional($surveyResponse->surveyQuestion)->question }}</span>
                                                    </div>
                                                </div>
                                                @foreach (json_decode($surveyResponse->answer) as $answer)
                                                    <span>Ans: {{ $answer }}</span>
                                                    <br>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endSection
