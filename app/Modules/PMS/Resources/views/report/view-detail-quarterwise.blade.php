<div class="card card-body">
    <table class="table table-bordered mt-2">
        <thead class="bg-slate text-center text-white">
            <tr>
                <th rowspan="3">S.N</th>
                <th rowspan="3" width="150px">KRA</th>
                <th rowspan="3">KPIs</th>
                <th rowspan="3">Target</th>
                <th rowspan="3">Frequency/Age</th>
                <th rowspan="3">Weightage</th>
                <th rowspan="3">Eligibility</th>
                <th colspan="4">TARGET VS ACHIEVEMENT</th>
            </tr>
            <tr>
                <th colspan="4">Q{{ $quarter }}</th>
            </tr>
            <tr>
                <th>TGT</th>
                <th style="padding: 0px 30px;">ACH</th>
                <th>ACH (%)</th>
                <th>SCORE (%)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $final_weightage = 0;
                $final_score = 0;
                $final_achieved_perc = 0;
                $count = 1;
            @endphp
            @if (!empty($targetAchievement))
                @foreach ($targetAchievement as $key => $targetAchievement)
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ optional($targetAchievement->first()->kraInfo)->title }}</td>
                        <td>{{ optional($targetAchievement->first()->kpiInfo)->title }}</td>
                        <td>{{ optional($targetAchievement->first()->targetInfo)->title }}</td>
                        <td>{{ optional($targetAchievement->first()->targetInfo)->frequency }}</td>
                        <td>{{ isset(optional($targetAchievement->first()->targetInfo)->weightage) ? optional($targetAchievement->first()->targetInfo)->weightage . '%' : '' }}
                        </td>
                        <td>{{ isset(optional($targetAchievement->first()->targetInfo)->eligibility) ? optional($targetAchievement->first()->targetInfo)->eligibility . '%' : '' }}
                        </td>

                        @php
                            $final_weightage += optional($targetAchievement->first()->targetInfo)->weightage;
                            $target_value = $targetAchievement[0]->target_value ?? '';
                            $achieved_value = $targetAchievement[0]->achieved_value ?? '';
                            $achieved_percent = $targetAchievement[0]->achieved_percent ?? '';
                            $score = $scorearray[0] = $targetAchievement[0]->score ?? '';

                            if ($achieved_percent != '') {
                                $final_achieved_perc += $achieved_percent;
                            }
                            if ($score != '') {
                                $final_score += $score;
                            }
                        @endphp
                        <td>{{ $target_value }}</td>
                        <td>{{ $achieved_value }}</td>
                        <td>{{ $achieved_percent }}</td>
                        <td>{{ $score }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5"><b>Final Score</b></td>
                    <td><b>{{ $final_weightage }}%</b></td>

                    <td colspan="3">&nbsp;</td>
                    <td><b>{{ $final_achieved_perc }}%</b></td>
                    <td><b>{{ $final_score }}%</b></td>
                </tr>
            @else
                <tr>
                    <td colspan="11">No Records Found !!!</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
