<div class="col-xl-3 col-sm-6">
    <div class="card text-white" style="height: 350px;">
        <div class="card-body text-center table-responsive" style="background-color: #334193">
            <div class="pollBody">
                <div class="w-100 float-left mb-3">
                    <h6> {{ $pollFinalReport['poll_name'] }} </h6>
                </div>
                {!! Form::hidden('poll_id', $poll_id, ['class' => 'poll']) !!}

                <div class="w-100 float-left mt-1">
                    <div class="d-flex justify-content-center flex-column  p-2">
                        @if (isset($pollFinalReport['responses']))
                            @foreach ($pollFinalReport['responses'] as $pollOptionId => $responseCount)
                                @inject('pollOptionRepo', '\App\Modules\Poll\Repositories\PollOptionRepository')
                                @php
                                    $pollOptionModel = $pollOptionRepo->find($pollOptionId);
                                    $resp_perc = 0;
                                    if ($pollFinalReport['total_responses'] > 0) {
                                        $resp_perc = number_format(($responseCount / $pollFinalReport['total_responses']) * 100, 2);
                                    }
                                @endphp
                                <div class="mb-2">
                                    <div class="w-100 d-flex justify-content-between">
                                        <span>
                                            @if ($pollFinalReport['isVoted'] == 'no' && $pollFinalReport['isExpired'] == 'no')
                                                <input type="radio" name="poll_option_id" value="{{ $pollOptionId }}" class="pollOption">
                                            @endif

                                            <label>{{ $pollOptionModel->option }}</label>
                                        </span>
                                        <div>{{ $responseCount }}</div>
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
</div>


