@extends('admin::layout')
@section('title') View Report @stop
@section('breadcrum')
    <a class="breadcrumb-item active">View Report</a>
@endSection

@section('content')

    <div class="card">
        <div class="bg-secondary card-header header-elements-inline border-bottom-0">
            <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(['route' => 'PMS.viewReport', 'method' => 'get']) !!}
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Fiscal Year:</label>
                        <div class="input-group">
                            {!! Form::select('fiscal_year_id', $fiscalYearList, $value = null, [
                                'id' => 'fiscalYearId',
                                'class' => 'form-control select-search',
                                'placeholder' => 'Select Fiscal Year',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Sub-Function:</label>
                        <div class="input-group">
                            {!! Form::select('department_id', $departmentList, $value = null, [
                                'id' => 'departmentId',
                                'class' => 'form-control select-search',
                                'placeholder' => 'Select Sub-Function',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Division:</label>
                        <div class="input-group">
                            {!! Form::select('division_id', $divisionList, $value = null, [
                                'id' => 'divisionId',
                                'class' => 'form-control select-search',
                                'placeholder' => 'Select Division',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Employee:</label>
                        <div class="input-group">
                            {!! Form::select(
                                'employee_id',
                                ['1' => 'Harry KC', '2' => 'Rita Adhikari', '3' => 'Evaan Karki'],
                                $value = null,
                                [
                                    'id' => 'employeeId',
                                    'class' => 'form-control select-search',
                                    'placeholder' => 'Select Employee',
                                ],
                            ) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">&nbsp;</label>
                        <button type="submit" class="btn bg-yellow mr-1"><i
                                class="icons icon-filter3 mr-1"></i>Filter</button>
                        <a href="{{ route('PMS.viewReport') }}" class="btn bg-secondary text-white"><i
                                class="icons icon-reset mr-1"></i>Reset</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
    <div>

        <div>

            <div class="card card-body">
                {{-- <table class="table table-bordered table-responsive">
                <thead class="bg-slate text-center text-white">
                    <tr>
                        <th rowspan="3">S.N</th>
                        <th rowspan="3">KRA</th>
                        <th rowspan="3">KPIs</th>
                        <th rowspan="3">Target</th>
                        <th rowspan="3">Frequency/Age</th>
                        <th rowspan="3">Weightage</th>
                        <th colspan="16">TARGET VS ACHIEVEMENT</th>
                        <th rowspan="3">YTD</th>
                        <th rowspan="3">Supporting Documents</th>
                    </tr>
                    <tr>
                        <th colspan="4">Q1</th>
                        <th colspan="4">Q2</th>
                        <th colspan="4">Q3</th>
                        <th colspan="4">Q4</th>
                    </tr>
                    <tr>
                        <th>TGT</th>
                        <th>ACH</th>
                        <th>ACH (%)</th>
                        <th>SCORE (%)</th>

                        <th>TGT</th>
                        <th>ACH</th>
                        <th>ACH(%)</th>
                        <th>SCORE</th>

                        <th>TGT</th>
                        <th>ACH</th>
                        <th>ACH(%)</th>
                        <th>SCORE</th>

                        <th>TGT</th>
                        <th>ACH</th>
                        <th>ACH(%)</th>
                        <th>SCORE</th>
                    </tr>
                </thead>
                <tbody>
                {{-- @foreach ($kraData as $key => $kra) --}}
                {{-- @php
                                $kpi_model = $kra->getKpis($kra->id);
                            @endphp
                            @foreach ($kpi_model as $key1 => $kpi)
                                <td>{{$kpi->title}}</td>
                                @php
                                    $target_model = $kpi->getTargets($kra->id, $kpi->id);
                                @endphp
                                @foreach ($target_model as $key2 => $target)
                                <tr>
                                    <td>{{$target->title}}</td>
                                    <td>{{$target->frequency}}</td>
                                    <td>{{$target->weightage . ' %'}}</td>

                                    @for ($i = 1; $i <= $target->no_of_quarter; $i++)
                                        @php
                                            $target_achievement_model = $target->getDetails($target->id, $i);
                                            if($target_achievement_model){
                                                $target_value = $target_achievement_model->target_value;
                                                $achieved_value = $target_achievement_model->achieved_value;
                                                $achieved_percent = $target_achievement_model->achieved_percent;
                                                $score = $target_achievement_model->score;

                                            }else{
                                                $target_value = '';
                                                $achieved_value = '';
                                                $achieved_percent = '';
                                                $score = '';
                                            }
                                        @endphp
                                        <td>{{$target_value}}</td>
                                        <td>{{$achieved_value}}</td>
                                        <td>{{$achieved_percent}}</td>
                                        <td>{{$score}}</td>

                                    @endfor

                                    <td>10%</td>

                                    <td></td>
                                </tr>
                                @endforeach
                            @endforeach --}}

                {{-- @endforeach --}}



                <table class="table table-bordered table-responsive">
                    <thead class="bg-slate text-center text-white">
                        <tr>
                            <th rowspan="3">S.N</th>
                            <th rowspan="3">KRA</th>
                            <th rowspan="3">KPIs</th>
                            <th rowspan="3">Target</th>
                            <th rowspan="3">Frequency/Age</th>
                            <th rowspan="3">Weightage</th>
                            <th colspan="16">TARGET VS ACHIEVEMENT</th>
                            <th rowspan="3">YTD</th>
                            <th rowspan="3">Supporting Documents</th>
                        </tr>
                        <tr>
                            <th colspan="4">Q1</th>
                            <th colspan="4">Q2</th>
                            <th colspan="4">Q3</th>
                            <th colspan="4">Q4</th>
                        </tr>
                        <tr>
                            <th>TGT</th>
                            <th>ACH</th>
                            <th>ACH</th>
                            <th>SCORE</th>

                            <th>TGT</th>
                            <th>ACH</th>
                            <th>ACH</th>
                            <th>SCORE</th>

                            <th>TGT</th>
                            <th>ACH</th>
                            <th>ACH</th>
                            <th>SCORE</th>

                            <th>TGT</th>
                            <th>ACH</th>
                            <th>ACH</th>
                            <th>SCORE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $final_weightage = 0;
                            // $total_score_of_quarter1 = 0;
                            // $total_score_of_quarter2 = 0;
                            // $total_score_of_quarter3 = 0;
                            // $total_score_of_quarter4 = 0;
                            $YTD = 0;
                            $average_YTD = 0;
                            $final_YTD = 0;

                        @endphp

                        @foreach ($kraData as $key => $kra)
                            @php
                                $kpi_model = $kra->getKpis($kra->id);
                            @endphp

                            @foreach ($kpi_model as $ky => $kpi)
                                @php
                                    $target_model = $kpi->getTargets($kra->id, $kpi->id);
                                @endphp

                                <tr>
                                    @if ($ky == 0)
                                        <td rowspan="{{ count($kpi_model) }}">{{ ++$key }}</td>
                                        <td rowspan="{{ count($kpi_model) }}">{{ $kra->title }}</td>
                                    @endif

                                    <td>{{ $kpi->title }}</td>



                                    {{-- <td>{{ $target->title }}</td>
                                    <td>{{ $target->frequency }}</td>
                                    <td>{{ $target->weightage . ' %' }}</td> --}}

                                    {{-- @for ($i = 1; $i <= $target->no_of_quarter; $i++)
                                        @php
                                            $target_achievement_model = $target->getDetails($target->id, $i);
                                            if ($target_achievement_model) {
                                                $target_value = $target_achievement_model->target_value;
                                                $achieved_value = $target_achievement_model->achieved_value;
                                                $achieved_percent = $target_achievement_model->achieved_percent;
                                                $score = $target_achievement_model->score;
                                            } else {
                                                $target_value = '';
                                                $achieved_value = '';
                                                $achieved_percent = '';
                                                $score = '';
                                            }
                                        @endphp
                                        <td>{{ $target_value }}</td>
                                        <td>{{ $achieved_value }}</td>
                                        <td>{{ $achieved_percent }}</td>
                                        <td>{{ $score }}</td>
                                    @endfor --}}

                                    <td>{{ optional($kpi->target)->title }}</td>
                                    <td>{{ optional($kpi->target)->frequency }}</td>
                                    <td>{{ optional($kpi->target)->weightage ? optional($kpi->target)->weightage . ' %' : '' }}
                                    </td>
                                    @php
                                        $final_weightage += optional($kpi->target)->weightage;
                                    @endphp

                                    @for ($i = 0; $i < 4; $i++)
                                        @php
                                            if (isset($kpi->target)) {
                                                $target_achievement_model = optional($kpi->target)->getDetails(
                                                    optional($kpi->target)->id,
                                                    $i + 1,
                                                );
                                                if ($target_achievement_model) {
                                                    $target_value = $target_achievement_model->target_value;
                                                    $achieved_value = $target_achievement_model->achieved_value;
                                                    $achieved_percent = $target_achievement_model->achieved_percent;
                                                    $score = $target_achievement_model->score;

                                                    $YTD += $score;
                                                } else {
                                                    $target_value = '';
                                                    $achieved_value = '';
                                                    $achieved_percent = '';
                                                    $score = '';
                                                }
                                            }
                                        @endphp
                                        <td>{{ $target_value ?? '-' }}</td>
                                        <td>{{ $achieved_value ?? '-' }}</td>
                                        <td>{{ $achieved_percent ? $achieved_percent . '%' : '' }}</td>
                                        <td>{{ $score ? $score . '%' : '' }}</td>
                                    @endfor

                                    @php
                                        $average_YTD = number_format($YTD / 4, 2);
                                        $final_YTD += $average_YTD;
                                    @endphp

                                    <td>{{ $average_YTD . ' %' }}</td>
                                    <td>Email Records</td>
                                </tr>
                            @endforeach
                        @endforeach
                        <tr>
                            <td colspan="5"><b>Final Score</b></td>
                            <td><b>{{ $final_weightage . ' %' }}</b></td>

                            <td colspan="3">&nbsp;</td>
                            <td><b></b></td>
                            {{-- <td><b>85%</b></td> --}}
                            <td colspan="3">&nbsp;</td>
                            <td><b></b></td>
                            {{-- <td><b>84%</b></td> --}}
                            <td colspan="3">&nbsp;</td>
                            <td><b></b></td>
                            {{-- <td><b>83%</b></td> --}}
                            <td colspan="3">&nbsp;</td>
                            <td><b></b></td>
                            {{-- <td><b>82%</b></td> --}}

                            <td><b>{{ $final_YTD . '%' }}</b></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endSection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
@endSection
