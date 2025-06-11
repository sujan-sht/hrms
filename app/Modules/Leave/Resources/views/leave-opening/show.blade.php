@extends('admin::layout')
@section('title') Leave Opening Summary @stop
@section('breadcrum')
    <a href="{{ route('leaveOpening.index') }}" class="breadcrumb-item">Leave Opening</a>
    <a class="breadcrumb-item active">Summary</a>
@stop
@section('css')
    <style>
        .zui-table {
            border: none;
            border-collapse: separate;
            border-spacing: 0;
            /* font: normal 13px Arial, sans-serif; */
        }

        .zui-table thead th {
            border: none;
            /* color: #336B6B; */
            padding: 10px;
            text-align: left;
            white-space: nowrap;
        }

        .zui-table tbody td {
            /* color: #333; */
            padding: 10px;
            white-space: nowrap;
        }

        .zui-wrapper {
            position: relative;
        }

        .zui-scroller {
            margin-left: 371px;
            overflow-x: scroll;
            overflow-y: visible;
            padding-bottom: 5px;
            width: auto;
        }

        .zui-table .zui-sticky-col {
            left: 0;
            position: absolute;
            top: auto;
            width: 92px;
            text-align: center;
            /* border-top-color: black; */
            padding: 0.75rem 1.25rem;

        }

        .zui-table .zui-sticky-col2 {
            left: 91px;
            position: absolute;
            top: auto;
            width: 285px;
            text-align: center;
            /* border-top-color: black; */
            padding: 0.75rem 1.25rem;
        }

        .zui-table .zui-sticky-col3 {
            /* border-right: solid 1px #380294; */
            left: 293px;
            position: absolute;
            top: auto;
            width: 75px;
            text-align: center;
            /* border-top-color: black; */
            padding: 0.75rem 1.25rem;
            background: #5485a3;
        }

        thead tr:nth-child(1) th {
            /* background: #8e70c1; */
            z-index: 10;
            color: #fff;
        }
    </style>
@endsection

@section('content')

    @include('leave::leave-opening.filter')

    @include('leave::leave-opening.employee-widget')

    @if (count($employeeLeaveSummaries) > 0)

        <div class="card card-body">
            <div class="zui-wrapper">

                <div class="table-responsive1 zui-scroller">
                    <table class="table table-striped zui-table">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th class="zui-sticky-col" style="padding: 20px;">S.N</th>
                                <th class="zui-sticky-col2" style="padding: 20px;">Employee Name</th>
                                @if (empty(request('leave_type_id')))
                                    @if (!empty($allLeaveTypes))
                                        @foreach ($allLeaveTypes as $leaveType)
                                            <th>
                                                {{ $leaveType->name }} <br> &nbsp;(Remaining)
                                            </th>
                                        @endforeach
                                    @endif
                                @else
                                    <th>{{ $leaveTypeList[request('leave_type_id')] }} <br> &nbsp;(Remaining)</th>
                                @endif
                                <th>Total Leaves <br> &nbsp;(Remaining)</th>
                                {{-- <th>Percentage</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($employeeLeaveSummaries) > 0)
                                @foreach ($employeeLeaveSummaries as $key => $employeeLeaveSummary)
                                    <tr>
                                        <td width="5%" class="zui-sticky-col" style="padding: 1.19rem;">#{{ $employeeLeaveSummaries->firstItem() + $key }}</td>
                                        @php
                                            if ($employeeLeaveSummary->profile_pic != null && $employeeLeaveSummary->profile_pic != '') {
                                                $profile_pic = asset('uploads/employee/profile_pic/' . $employeeLeaveSummary->profile_pic);
                                            } else {
                                                $profile_pic = asset('admin/default.png');
                                            }

                                            if (!empty($employeeLeaveSummary->middle_name)) {
                                                $full_name = $employeeLeaveSummary->first_name . ' ' . $employeeLeaveSummary->middle_name . ' ' . $employeeLeaveSummary->last_name;
                                            } else {
                                                $full_name = $employeeLeaveSummary->first_name . ' ' . $employeeLeaveSummary->last_name;
                                            }
                                        @endphp

                                        <td class="zui-sticky-col2 d-flex1 text-nowrap" >
                                            <div class="media">
                                                <div class="mr-1">
                                                    <a href="#">
                                                        <img src="{{ $profile_pic }}" class="rounded-circle" width="40"
                                                            height="40" alt="">
                                                    </a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="media-title font-weight-semibold">{{ $full_name }}</div>
                                                    <span class="text-muted">Code: {{ $employeeLeaveSummary->employee_code }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        @php
                                            // $totalRemaining = 0;
                                            // $totalOpening = 0;
                                            $totalLeaves = 0;
                                        @endphp
                                         @if (empty(request('leave_type_id')))
                                            @foreach ($allLeaveTypes as $leaveType)
                                                <td>
                                                    <h6>
                                                        @php
                                                            // $totalRemaining += $employeeLeaveSummary->leaveRemaining[$leaveType->id] ?? 0;
                                                            // $totalOpening += $employeeLeaveSummary->leaveOpening[$leaveType->id] ?? 0;
                                                            $totalLeaves += $employeeLeaveSummary->leaveRemaining[$leaveType->id] ?? 0;
                                                        @endphp
                                                        <span
                                                            style="font-size: 21px;">{{ $employeeLeaveSummary->leaveRemaining[$leaveType->id] ?? 0 }}</span>
                                                        {{-- / <span
                                                            class="text-secondary">{{ $employeeLeaveSummary->leaveOpening[$leaveType->id] ?? 0 }}</span> --}}
                                                    </h6>
                                                </td>
                                            @endforeach
                                        @else
                                            @php
                                                $leaveRemaining = $employeeLeaveSummary->leaveRemaining[request('leave_type_id')] ?? 0;
                                                $totalLeaves += $leaveRemaining;
                                            @endphp
                                                <td>
                                                    <h6>
                                                        <span style="font-size: 21px;">{{ $leaveRemaining }}</span>
                                                        {{-- <span style="font-size: 21px;">{{ $totalRemaining }}</span>  --}}
                                                        {{-- / <span
                                                            class="text-secondary">{{ $totalOpening }}</span> --}}
                                                    </h6>
                                                </td>
                                        @endif
                                        <td>
                                            <span style="font-size: 21px;">{{ $totalLeaves }}</span>
                                        </td>
                                            {{-- <td>
                                        @php
                                            if($totalOpening == 0 || $totalRemaining == 0) {
                                                $percentage = 0;
                                            } else {
                                                $percentage = ($totalRemaining/$totalOpening) * 100;
                                            }
                                            if($percentage > 0 && $percentage <= 20) {
                                                $textColor = 'danger';
                                            } elseif ($percentage > 20 && $percentage <= 40) {
                                                $textColor = 'warning';
                                            } elseif ($percentage > 40 && $percentage <= 60) {
                                                $textColor = 'info';
                                            } elseif ($percentage > 60 && $percentage <= 80) {
                                                $textColor = 'primary';
                                            } elseif ($percentage > 80 && $percentage <= 100) {
                                                $textColor = 'success';
                                            } else {
                                                $textColor = 'none';
                                            }
                                        @endphp
                                        <h3 class="text-{{ $textColor }}">{{ round($percentage, 0)  }} %</h3>
                                    </td> --}}
                                        </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">No Employee Leave Details Found !!!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12">
                <span class="float-right pagination align-self-end mt-3">
                    {{ $employeeLeaveSummaries->appends(request()->all())->links() }}
                </span>
            </div>
        </div>
    @endif

@endsection
