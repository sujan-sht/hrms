@extends('admin::layout')
@section('title') Performance Evaluation Summary @stop

@section('breadcrum')
    <a href="{{ route('appraisal.index') }}" class="breadcrumb-item">Appraisal</a>
    <a class="breadcrumb-item active">PERFORMANCE EVALUATION SUMMARY SHEET</a>
@endsection

@section('content')
    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    @if (auth()->user()->user_type != 'employee')
        @include('appraisal::performance-evaluation-summary.action.filter')
    @endif
    @if (isset($selected_employee))
        <div class="card">
            <div class="card-body">
                <h5>Employee Not Holding Target / KPI</h5>
                <br>
                <span><strong> Name of the Employee : </strong> {{ $selected_employee->full_name }}</span><br>
                <span><strong>Sub-Function : </strong> {{ optional($selected_employee->department)->title }}</span><br>
                <span><strong>Division : </strong>{{ optional($selected_employee->organizationModel)->name }}</span><br>
                <span><strong>Name of the Reporting Officer : </strong>
                    {{ isset($appraisalApprovalFlow) ? optional(optional($appraisalApprovalFlow->firstApprovalUserModel)->userEmployer)->full_name : '' }}</span><br>
                <span><strong>Name of the Reviewing Officer :
                    </strong>{{ isset($appraisalApprovalFlow) ? optional(optional($appraisalApprovalFlow->lastApprovalUserModel)->userEmployer)->full_name : '' }}</span>
                <br>
                <br>

                <h6>Rating Scale: </h6>
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
                <br>
                <h6>For Employee Not Holding Target / KPI: </h6>
                <div>
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>SN</th>
                                <th>Form to be Reviewed</th>
                                <th>Overall Marks</th>
                                <th>Weightage</th>
                                <th>Calculation Basis</th>
                                <th>Final Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalFinalScore = 0;
                            @endphp
                            @if (!empty($final_reports))
                                @foreach ($final_reports as $key => $final_report)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $final_report['form'] }}</td>
                                        <td>{{ $final_report['overall_marks'] }}</td>
                                        <td>{{ $final_report['weightage'] }}</td>
                                        <td>{{ $final_report['calculation_basis'] }}</td>
                                        <td>{{ $final_report['final_score'] }}</td>
                                        @php
                                            $totalFinalScore += $final_report['final_score'];
                                        @endphp
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5"><b>Total Final Score</b></td>
                                    <td><b>{{ number_format($totalFinalScore, 2) }}</b></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <br>
                <br>
                <h6>STRENGTH, AREAS OF IMPROVEMENT & TRAINING NEED OF APPRAISEE</h6>
                <span>(Could include temperament, team working in team, stress levels, emotional maturity etc.)</span>
                <div class="mt-2">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>To be filled by Reporting Officer in consultation with Appraisee based on performance
                                    evaluations</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Strengths</td>
                                <td>{{ $developmentPlan->strength ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>Areas of Development</td>
                                <td>{{ $developmentPlan->development ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>What can Reviewer do to support?(Describe how a reviewer help the employee in their
                                    development areas to
                                    overcome it?)</td>
                                <td>{{ $developmentPlan->support ?? '' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <br>
                <br>
                <span>
                    <h6>RECOMMENDATIONS</h6>
                    (Regarding Job Enrichment, Job Enlargement, Promotion, Job Transfer etc.)
                </span>
                <div class="mt-2">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>RECOMMENDATION</th>
                                <th>JUSTIFICATION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Increment</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Promotion</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Transfer</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {{-- </div> --}}
            </div>
        </div>
    @endif


@endsection
