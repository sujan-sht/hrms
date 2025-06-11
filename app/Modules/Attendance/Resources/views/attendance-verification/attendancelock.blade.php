@extends('admin::layout')

@section('breadcrum')
    <a href="{{ route('monthlyAttendance') }}" class="breadcrumb-item">Attendance </a>
    <a class="breadcrumb-item active">Attendance Summary</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
    <style>
        tbody td {
            white-space: nowrap;
            overflow: hidden;
        }

        tbody {
            position: relative;
            z-index: 1;
        }

        /* Freeze the first four columns */
        .sticky-col {
            position: sticky;
            background-color: white;
            z-index: 5;
        }

        .sticky-col-2 {
            position: sticky;
            z-index: 4;
        }

        .first-col {
            width: 100px;
            min-width: 100px;
            max-width: 100px;
            left: 0px;
            background-color: white;
            z-index: 4;
        }

        .second-col {
            width: 220px;
            min-width: 220px;
            max-width: 220px;
            left: 100px;
            background-color: white;
            z-index: 3;
        }

        .third-col {
            width: 140px;
            min-width: 140px;
            max-width: 140px;
            left: 320px;
            background-color: white;
            z-index: 3;
        }

        .fourth-col {
            width: 175px;
            min-width: 175px;
            max-width: 175px;
            left: 460px;
            background-color: white;
            z-index: 3;
        }

        /* Make both header rows sticky */
        .table thead th {
            position: sticky;
            top: 0;
            z-index: 3;
        }

        /* Adjust z-index for sticky columns in case of overlap */
        .table thead th.sticky-col,
        .table tbody td.sticky-col {
            z-index: 6;
        }

        .table thead th.sticky-col-2,
        .table tbody td.sticky-col-2 {
            z-index: 5;
        }
    </style>
    <div id="loader" class="text-center mt-5" style="position: fixed; top:40%; left:40%; z-index:999;">
        <img id="monthlyAttendanceSummaryloading-image" src="{{ asset('admin/loading-gif.gif') }}" alt="Loading..."
            style="background: transparent;" />
    </div>

    <div id="summary-section">
        <div class="row">
            <div class="col-lg-12">
                <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
                    style="margin-top: -15px">
                    <i class="icon-help" style="font-size:2em"></i>
                </a>
            </div>
        </div>
        @include('attendance::monthly-attendance-summary.partial.attendanceverificationfilter')
        <div class="card card-body">
            <div class="media align-items-center align-items-md-start flex-column flex-md-row">
                <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                    <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
                </a>
                <div class="media-body text-center text-md-left">
                    <h6 class="media-title font-weight-semibold">Attendance Summary Verification</h6>
                    All the Attendance Summary Verification will be listed below. You can view the data.
                </div>
                @if ($show)
                    <div class="mt-1">
                        <a href="{{ route('exportMonthlySummaryAttendanceLock', array_merge(request()->all(), ['id' => $attendanceOrganizationLock])) }}"
                            class="btn btn-success ">
                            <i class="icon-file-excel"></i> Export
                        </a>
                    </div>
                    <div class="mt-1 ml-1">
                        <a href="{{ route('downloadMonthlySummaryAttendanceLock', array_merge(request()->all(), ['id' => $attendanceOrganizationLock])) }}"
                            class="btn btn-warning ">
                            <i class="icon-file-download"></i> Download
                        </a>
                    </div>
                @endif
            </div>
        </div>

        @if ($show)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-light btn-slate">
                            <th class="sticky-col first-col">S.N</th>
                            @if (Auth::user()->user_type != 'employee')
                                <th class="sticky-col second-col">Employee Name</th>
                                <th class="sticky-col third-col">Sub-Function</th>
                                <th class="sticky-col fourth-col">Designation</th>
                                <th class="sticky-col-2">Date of Join</th>
                            @endif
                            @foreach ($columns as $keyData => $column)
                                <th class="text-nowrap text-center sticky-col-2"
                                    style="{{ $keyData == '1' || $keyData == '2' ? 'border:1px solid #ddd;background-color:red' : '' }}">
                                    {{ $column }} <p></p>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fetchDatas as $key => $emp)
                            <tr>
                                <td class="sticky-col first-col">#{{ $key + 1 }}</td>
                                @if (Auth::user()->user_type != 'employee')
                                    <td class="d-flex text-nowrap sticky-col second-col">
                                        <div class="media">
                                            <div class="mr-3">
                                                <a href="#">
                                                    <img src="{{ $emp->employee->getImage() }}" class="rounded-circle"
                                                        width="40" height="40" alt="">
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <div class="media-title font-weight-semibold">
                                                    {{ $emp->employee->full_name }}</div>
                                                <span class="text-muted">ID: {{ $emp->employee->employee_code }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="sticky-col third-col">{{ optional($emp->employee->department)->title }}</td>
                                    <td class="sticky-col fourth-col">{{ optional($emp->employee->designation)->title }}
                                    </td>
                                    <td>{{ $emp->employee->nepali_join_date }}</td>
                                @endif
                                @foreach ($columns as $key => $column)
                                    <th style="{{ $key == '1' || $key == '2' ? 'border:1px solid #ddd' : '' }}">
                                        <div class="text-center">
                                            {{ $emp[$key] ?? 0 }}
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- @if (isset($emps))
            <div class="row">
                <div class="col-12">
                    <ul class="pagination pagination-rounded justify-content-end mb-3">
                        {{ $emps->appends(request()->all())->links() }}
                    </ul>
                </div>
            </div>
        @endif --}}


        <div class="text-center">
            <a href="javascript:;" class="btn btn-danger btn-labeled btn-labeled-left"><b><i
                        class="icon-database-insert"></i></b>Locked</a>
            @if (!$payrollStatus)
                <a href="javascript:;" class="btn btn-success btn-labeled btn-labeled-left" id="unLocked"><b><i
                            class="icon-database-insert"></i></b>UnLock</a>
            @else
                <a href="javascript:;" class="btn btn-success btn-labeled btn-labeled-left" id="unLockedFalseStatus"><b><i
                            class="icon-database-insert"></i></b>UnLock</a>
            @endif
        </div>

        <form id="unlockForm" action="{{ route('unlocked.attendance') }}" method="POST">
            @csrf
            <input type="text" hidden name="attendanceOrganizationLock" id="attendanceOrganizationLock"
                value="{{ @$attendanceOrganizationLock }}">
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#loader').show();

            setTimeout(function() {
                $('#loader').hide();
                $('#summary-section').show();
            }, 2000);

            $('#unLocked').on('click', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Alert!',
                    text: "Attendance locked summary will be deleted,Are You sure unlocked data !!",
                }).then(function(response) {
                    if (response.isConfirmed) {
                        var attendanceOrganizationLock = "{{ @$attendanceOrganizationLock }}";
                        $('#attendanceOrganizationLock').val(attendanceOrganizationLock);
                        $('#unlockForm').submit();
                    }
                    return false;
                });

            });

            $('#unLockedFalseStatus').on('click', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Alert!',
                    text: "Sorry, you can't unlock the payroll generated for this attendance summary.",
                });

            });


        });
    </script>


@endsection
