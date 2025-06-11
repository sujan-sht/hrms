@extends('admin::layout')
@section('title') Polls @endSection
@section('breadcrum')
<a href="{{ route('poll.index') }}" class="breadcrumb-item">Polls</a>
<a class="breadcrumb-item active">Report</a>
@stop

@section('content')

    <div class="container center">
        <ul class="nav nav-pills d-flex justify-content-center">
            <li class="nav-item">
                <a class="nav-link text-center {{ request()->get('status') == 'active' ? 'active' : '' }}" href="{{ route('poll.viewReport', ['status'=>'active']) }}">Active
                    Polls</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-center {{ request()->get('status') == 'expired' ? 'active' : ''}}" href="{{ route('poll.viewReport', ['status'=>'expired']) }}">Expired
                    Polls</a>
            </li>
        </ul>
    </div>

    <div class="row">
        @foreach ($pollFinalReports as $pollFinalReport)
            <div class="col-xl-3 col-sm-6">
                <div class="card text-white" style="height: 350px;">
                    <div class="card-body text-center table-responsive" style="background-color: #334193">
                        <div>
                            <div class="w-100 float-left mb-3">
                                <h6>{{ $pollFinalReport['poll_name'] }}</h6>
                            </div>
                            <div class="w-100 float-left mt-1">
                                @if (isset($pollFinalReport['responses']))
                                    @foreach ($pollFinalReport['responses'] as $option_name => $response_count)
                                        @php
                                            $resp_perc = 0;
                                            if($pollFinalReport['total_responses'] > 0){
                                                $resp_perc = number_format(($response_count / $pollFinalReport['total_responses']) * 100, 2);
                                            }
                                        @endphp
                                        <div class="mb-2">
                                            <div class="w-100 d-flex justify-content-between">
                                                <label>{{ $option_name }}</label>
                                                <div>{{ $response_count }}</div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $resp_perc }}%" aria-valuenow="1"
                                                    aria-valuemin="{{ $resp_perc }}+2" aria-valuemax="100">
                                                    {{ $resp_perc }}%
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endSection
