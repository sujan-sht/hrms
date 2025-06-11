@extends('admin::layout')
@section('title') PMS Final Report @stop
@section('breadcrum')
    <a class="breadcrumb-item active">PMS Final Report</a>
@endSection

@section('content')

    @if (auth()->user()->user_type != 'employee')
        @include('pms::report.partial.search')
    @endif
    @if (isset($selected_employee))
        <div class="row">
            @foreach ($targetReportQuarterwise as $quarter => $targetReport)
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h4 class="card-title">Q{{ $quarter }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center mb-3 mb-lg-0">
                                        <a href="#"
                                            class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                            <i class="icon-target2"></i>
                                        </a>
                                        <div class="ml-3">
                                            <h5 class="font-weight-semibold mb-0">
                                                {{ number_format($targetReport['totalTargetValue'], 2) }}</h5>
                                            <span class="text-muted">TARGET</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center mb-3 mb-lg-0">
                                        <a href="#"
                                            class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                            <i class="icon-trophy2"></i>
                                        </a>
                                        <div class="ml-3">
                                            <h5 class="font-weight-semibold mb-0">
                                                {{ number_format($targetReport['totalAchievedValue'], 2) }}</h5>
                                            <span class="text-muted">ACHIEVEMENT</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center mb-3 mb-lg-0">
                                        <a href="#"
                                            class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                            <i class="icon-medal-second"></i>
                                        </a>
                                        <div class="ml-3">
                                            <h5 class="font-weight-semibold mb-0">
                                                {{ number_format($targetReport['totalAvgAchievementPerc'], 2) }}%</h5>
                                            <span class="text-muted">ACHIEVEMENT</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center mb-3 mb-lg-0">
                                        <a href="#"
                                            class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                            <i class="icon-medal-star"></i>
                                        </a>
                                        <div class="ml-3">
                                            <h5 class="font-weight-semibold mb-0">
                                                {{ number_format($targetReport['totalAvgScore'], 2) }}%</h5>
                                            <span class="text-muted">SCORE</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <button class="btn btn-success rounded-pill border-2 icon-eye viewDetail"
                                        data-bs-toggle="collapse" data-id="{{ $quarter }}"
                                        data-employeeId="{{ $selected_employee->id }}">
                                        View Detail
                                    </button>
                                    <button class="btn btn-primary rounded-pill border-2 icon-eye viewAll"
                                        data-bs-toggle="collapse">View All</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Quarter wise report --}}
        <div id="viewTargetAchievementDetail">
        </div>

        {{-- Final Report --}}
        <div class="card card-body viewFinalReport d-none">
            <h1 class="text-center mt-4">{{ $setting->company_name ?? '' }}</h1>
            <h3 class="text-center">Key Performance Indicator Achievement {{ $fiscalYear->fiscal_year ?? '' }}</h3>
            @if (isset($selected_employee))
                <div class="col-lg-12 mb-3 mt-1">
                    <span><b>Employee : </b> {{ $selected_employee->full_name }}</span><br>
                    <span><b>Organization : </b> {{ optional($selected_employee->organizationModel)->name }}</span><br>
                    <span><b>Sub-Function : </b> {{ optional($selected_employee->department)->title }}</span><br>
                    <span><b>Designation : </b> {{ optional($selected_employee->designation)->title }}</span>
                </div>
                <h4>Rating Scale</h4>
                <div>
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>SCORE</th>
                                <th>Explanation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($ratingScaleModels))
                                @foreach ($ratingScaleModels as $ratingScaleModel)
                                    <tr>
                                        <td>{{ $ratingScaleModel->score }}</td>
                                        <td>{{ $ratingScaleModel->indication . ' (' . $ratingScaleModel->explanation . ') ' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <br>
            @endif

            <table class="table table-bordered table-responsive mt-2">
                <thead class="bg-slate text-center text-white">
                    <tr>
                        <th rowspan="3">S.N</th>
                        <th rowspan="3" width="150px">KRA</th>
                        <th rowspan="3">KPIs</th>
                        <th rowspan="3">Target</th>
                        <th rowspan="3">Frequency/Age</th>
                        <th rowspan="3">Weightage</th>
                        <th rowspan="3">Eligibility</th>
                        <th colspan="16">TARGET VS ACHIEVEMENT</th>
                        <th rowspan="3">YTD</th>
                        <th rowspan="3">Remarks</th>
                        <th rowspan="3">Attachment</th>
                        {{-- <th rowspan="3">Action</th> --}}
                        {{-- <th rowspan="3">Supporting Documents</th>
                        <th rowspan="3">Attachment</th> --}}
                    </tr>
                    <tr>
                        <th colspan="4">Q1</th>
                        <th colspan="4">Q2</th>
                        <th colspan="4">Q3</th>
                        <th colspan="4">Q4</th>
                    </tr>
                    <tr>
                        <th>TGT</th>
                        <th style="padding: 0px 30px;">ACH</th>
                        <th>ACH (%)</th>
                        <th>SCORE (%)</th>

                        <th>TGT</th>
                        <th style="padding: 0px 30px;">ACH</th>
                        <th>ACH (%)</th>
                        <th>SCORE (%)</th>

                        <th>TGT</th>
                        <th style="padding: 0px 30px;">ACH</th>
                        <th>ACH (%)</th>
                        <th>SCORE (%)</th>

                        <th>TGT</th>
                        <th style="padding: 0px 30px;">ACH</th>
                        <th>ACH (%)</th>
                        <th>SCORE (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $final_weightage = 0;

                        $final_achieved_perc_Q1 = 0;
                        $final_achieved_perc_Q2 = 0;
                        $final_achieved_perc_Q3 = 0;
                        $final_achieved_perc_Q4 = 0;

                        $final_score_Q1 = 0;
                        $final_score_Q2 = 0;
                        $final_score_Q3 = 0;
                        $final_score_Q4 = 0;
                        // $YTD = 0;
                        $average_YTD = 0;
                        $final_YTD = 0;
                        $count = 1;
                    @endphp
                    @if (!empty($targetAchievementModel))
                        @foreach ($targetAchievementModel as $key => $targetAchievement)
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
                                @endphp
                                @for ($i = 0; $i < 4; $i++)
                                    @php
                                        $target_value = $targetAchievement[$i]->target_value ?? '';
                                        $achieved_value = $targetAchievement[$i]->achieved_value ?? '';
                                        $achieved_percent = $targetAchievement[$i]->achieved_percent ?? '';
                                        $score = $scorearray[$i] = $targetAchievement[$i]->score ?? '';

                                        $remarks = $targetAchievement[$i]->remarks ?? '';

                                        // $YTD += $score;
                                        if ($i == 0) {
                                            if ($achieved_percent != '') {
                                                $final_achieved_perc_Q1 += $achieved_percent;
                                            }
                                            if ($score != '') {
                                                $final_score_Q1 += $score;
                                            }
                                        } elseif ($i == 1) {
                                            if ($achieved_percent != '') {
                                                $final_achieved_perc_Q2 += $achieved_percent;
                                            }
                                            if ($score != '') {
                                                $final_score_Q2 += $score;
                                            }
                                        } elseif ($i == 2) {
                                            if ($achieved_percent != '') {
                                                $final_achieved_perc_Q3 += $achieved_percent;
                                            }
                                            if ($score != '') {
                                                $final_score_Q3 += $score;
                                            }
                                        } elseif ($i == 3) {
                                            if ($achieved_percent != '') {
                                                $final_achieved_perc_Q4 += $achieved_percent;
                                            }
                                            if ($score != '') {
                                                $final_score_Q4 += $score;
                                            }
                                        }
                                    @endphp
                                    <td>{{ $target_value }}</td>
                                    <td>{{ $achieved_value }}</td>
                                    <td>{{ $achieved_percent }}</td>
                                    <td>{{ $score }}</td>
                                @endfor
                                @php

                                    $scorearr = array_filter($scorearray, function ($value) {
                                        return $value != 0;
                                    });
                                    if (count($scorearr) > 0) {
                                        $average_YTD = number_format(array_sum($scorearr) / count($scorearr), 2);
                                    } else {
                                        $average_YTD = 0;
                                    }

                                    $final_YTD += $average_YTD;
                                @endphp

                                <td>{{ $average_YTD . ' %' }}</td>
                                {{-- <td>{{ optional($targetAchievement->first()->targetInfo)->remarks }}</td> --}}


                                <td>{{ $remarks }}</td>
                                <td>
                                    <ul>
                                        @if (count(optional($targetAchievement->first()->targetInfo)->TargetAttachments) > 0)
                                            @foreach (optional($targetAchievement->first()->targetInfo)->TargetAttachments as $file)
                                                <li>
                                                    <a href="{{ $file->attachment }}" target="_blank"
                                                        class="">{{ $file->title }}</a>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </td>

                                {{-- <td>
                                    <a data-toggle="modal" data-target="#updateStatus"
                                        class="btn btn-outline-warning btn-icon updateStatus mr-2" data-id="" data-status=""
                                        data-popup="tooltip" data-placement="top" data-original-title="Status">
                                        <i class="icon-flag3"></i>
                                    </a>
                                </td> --}}
                                {{-- <td>Supporting doc</td>
                                <td>attachment</td> --}}
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5"><b>Final Score</b></td>
                            <td><b>{{ $final_weightage . ' %' }}</b></td>

                            <td colspan="3">&nbsp;</td>
                            <td><b>{{ $final_achieved_perc_Q1 }}%</b></td>
                            <td><b>{{ $final_score_Q1 . ' %' }}</b></td>

                            <td colspan="2">&nbsp;</td>
                            <td><b>{{ $final_achieved_perc_Q2 }}%</b></td>
                            <td><b>{{ $final_score_Q2 . ' %' }}</b></td>

                            <td colspan="2">&nbsp;</td>
                            <td><b>{{ $final_achieved_perc_Q3 }}%</b></td>
                            <td><b>{{ $final_score_Q3 . ' %' }}</b></td>

                            <td colspan="2">&nbsp;</td>
                            <td><b>{{ $final_achieved_perc_Q4 }}%</b></td>
                            <td><b>{{ $final_score_Q4 . ' %' }}</b></td>

                            <td><b>{{ $final_YTD . '%' }}</b></td>
                            <td></td>
                            {{-- <td></td> --}}
                            {{-- @if ($menuRoles->assignedRoles('substituteLeave.updateStatus') && auth()->user()->user_type != 'supervisor')
                                <a data-toggle="modal" data-target="#updateStatus"
                                    class="btn btn-outline-warning btn-icon updateStatus mr-2"
                                    data-id="{{ $employeeSubstituteLeaveModel->id }}"
                                    data-status="{{ $employeeSubstituteLeaveModel->status }}" data-popup="tooltip"
                                    data-placement="top" data-original-title="Status">
                                    <i class="icon-flag3"></i>
                                </a>
                            @endif --}}
                        </tr>
                    @else
                        <tr>
                            <td colspan="27">No Records Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endif


    <!-- popup modal -->
    {{-- <div id="updateStatus" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Update Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open([
                        'route' => 'substituteLeave.updateStatus',
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                    ]) !!}
                    {!! Form::hidden('id', null, ['id' => 'modelId']) !!}
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Status :</label>
                        <div class="col-lg-9">
                            {!! Form::select('status', $statusList, null, ['id' => 'modelStatus', 'class' => 'form-control select-search']) !!}
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn bg-success text-white">Save Changes</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div> --}}
@endSection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.viewDetail').on('click', function() {
                var employee_id = $(this).attr('data-employeeId')
                var quarter = $(this).attr('data-id')
                var form_data = {
                    employee_id,
                    quarter
                }
                $.ajax({
                    type: 'GET',
                    url: "{{ route('PMS.viewDetailQuarterwise') }}",
                    dataType: 'json',
                    data: form_data,
                    success: function(response) {
                        $('#viewTargetAchievementDetail').show()
                        $('#viewTargetAchievementDetail').html(response.result);
                    }
                })
            })

            $('.viewAll').on('click', function() {
                $('.viewFinalReport').toggleClass('d-none')
                $('#viewTargetAchievementDetail').hide()
            })

            // $('.updateStatus').on('click', function() {
            //     var id = $(this).attr('data-id');
            //     var status = $(this).attr('data-status');
            //     $('#modelId').val(id);
            //     $('#modelStatus').select2("val", status);
            // });
        });
    </script>
@endSection
