@extends('admin::layout')

@section('title')
    {{ $title }}s
@endsection

@section('breadcrum')
    <a class="breadcrumb-item active"> Roster </a>
    <a class="breadcrumb-item active"> Weekly Report </a>
@endsection


@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    <script src="{{ asset('admin/validation/validation.js') }}"></script>

    <script>
        customValidation('filterForm');
    </script>
@endsection


@inject('atdReportRepo', '\App\Modules\Attendance\Repositories\AttendanceReportRepository')
@inject('holidayDetailModel', '\App\Modules\Holiday\Entities\HolidayDetail')
@inject('leaveModel', '\App\Modules\Leave\Entities\Leave')

@section('content')

    <script>
         $('body').on('change', '.month', function() {
            month = $(this).val();
            weekRanges = "{{ json_encode($weekRange) }}";
            parseWeeklist = (JSON.parse(weekRanges.replace(/&quot;/g, '"')));

            appendData = [];

            arr = parseWeeklist[padWithLeadingZeros(month, 2)];

            for (var [weekNumber, dateRange] of Object.entries(arr)) {
                appendData.push({
                    id: weekNumber,
                    text: dateRange
                });
            }

            $('.week_range').html('').select2({
                placeholder: 'Select Week Range',
                data: appendData,
            });

            week = "{{ request('week_range') }}"
            $('.week_range').val(week).trigger('change');

        })

        function padWithLeadingZeros(num, totalLength) {
            return String(num).padStart(totalLength, '0');
        }
        $(document).on('change', '.sortBy', function() {
            var value = $(this).val();
            var search_form = $('.filterForm').serialize() + '&sortBy=' + value;
            var url = window.location.origin + "" + window.location.pathname + '?' + search_form;
            window.location = url;
        });
    </script>

    @include('newshift::shift.partial.new-shift-filter')
    {{-- @if (request('month') && isset($dates)) --}}
    @if (request('start_date'))
        <div class="card card-body">
            <div class="d-flex flex-row-reverse mb-1">
                <div class="px-2">
                    <a class="btn btn-outline-primary btn-icon mx-1"
                    href="{{ route('newShift.downloadWeeklyReport', request()->all()) }}"
                    data-popup="tooltip" data-placement="top" data-original-title="Download PDF">
                    <i class="icon-download"></i>
                    </a>
                </div>
                {{-- <div class="px-2">
                    <div class="form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::select(
                                'sortBy',
                                [10 => 10, 20 => 20, 50 => 50, 100 => 100],
                                request()->get('sortBy') ? request()->get('sortBy') : 20,
                                [
                                    'class' => 'form-control sortBy',
                                    'placeholder' => 'Select',
                                ],
                            ) !!}
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="table-responsive">
                <table class="table table-bordered ">
                    <thead style="position:sticky;top:0;">
                        <tr class="text-white">
                            <th>#</th>
                            <th>Employee Name</th>
                            @foreach ($dates as $date)
                                @php
                                    $dateArr[] = $date->format('Y-m-d');
                                @endphp
                                <th class="text-center">
                                    {{ $date->format('M d ') }}
                                    ({{ $date->format('D') }})
                                </th>
                            @endforeach

                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $holidayDetails = $holidayDetailModel
                                ->with('holiday')
                                ->whereIn('eng_date', $dateArr)
                                ->get();
                            $leaves = $leaveModel
                                ->whereIn('date', $dateArr)
                                ->where('status', 3)
                                ->whereIn('employee_id', $emplists->pluck('employee_id'))
                                // ->pluck('date');
                                ->get();
                            // dd($leaves->toArray(), $emplists->pluck('employee_id')->toArray());
                            $color = ['success', 'danger', 'indigo', 'teal', 'warning'];
                        @endphp

                        @foreach ($emplists as $key => $empModel)

                            <tr class="table table-{{ $color[rand(0, 4)] }}">
                                <td> #{{ $emplists->firstItem() + $key }} </td>
                                <td>
                                    <div class="media">
                                        <div class="mr-3">
                                            <a href="#">
                                                <img src="{{ $empModel->getImage() }}" class="rounded-circle"
                                                    width="40" height="40" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-title font-weight-semibold">
                                                {{ $empModel->getFullName() }}</div>
                                            @if (auth()->user()->user_type != 'employee')
                                                <span class="text-muted">Code :
                                                    {{ $empModel->employee_code }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                @php
                                    $newShiftModel = $empModel->newShift->whereIn('eng_date', $dateArr);

                                    $genderType = optional($empModel->getGender()->first())->dropvalue;
                                    if ($genderType == 'Male') {
                                        $genderType = 3;
                                    } elseif ($genderType == 'Female') {
                                        $genderType = 2;
                                    }
                                    $dayOffs = array_values($empModel->getEmployeeDayList());
                                @endphp
                                @foreach ($dates as $date)
                                    @php
                                        $shiftArr = ['D' => 'DayOff'];
                                        $date = $date->format('Y-m-d');

                                        $holiday = $holidayDetails
                                            ->where('eng_date', $date)
                                            ->whereIn('holiday.gender_type', [1, $genderType])
                                            ->whereIn('holiday.religion_type', [1, $empModel->religion])
                                            ->whereIn('holiday.organization_id', [null, $empModel->organization_id])
                                            ->first();

                                        $leave = $leaves
                                            ->where('date', $date)
                                            ->where('employee_id', $empModel->id)
                                            ->first();
                                        $shiftValue = '';

                                        if ($leave) {
                                            $shiftArr = array_merge($shiftArr, ['L' => $leave->getLeaveKind()]);
                                            $shiftValue = 'L';
                                        }

                                        if ($holiday) {
                                            $holdiayArr = ['H' => $holiday->sub_title];
                                            $shiftArr = array_merge($shiftArr, $holdiayArr);
                                            $shiftValue = 'H';
                                        }

                                        $shiftArr = array_merge($shiftArr, $shiftGrouplists);
                                        $shift = $newShiftModel->where('eng_date', $date)->first();
                                        if (isset($shift) && $shift->newShiftEmployeeDetails[0]) {
                                            $shiftValue = in_array($shift->newShiftEmployeeDetails[0]['type'], ['D', 'H', 'L']) ? $shift->newShiftEmployeeDetails[0]['type'] : $shift->newShiftEmployeeDetails[0]['shift_id'];
                                        } else {
                                            if (in_array(date('l', strtotime($date)), $dayOffs)) {
                                                $shiftValue = 'D';
                                            } elseif ($shiftValue == 'H') {
                                                $shiftValue = 'H';
                                            } elseif ($shiftValue == 'L') {
                                                $shiftValue = 'L';
                                            } else {
                                                $shiftValue = 1;
                                            }
                                        }

                                        switch ($shiftValue) {
                                            case 'D':
                                                $backgroundColor = 'lightgray';
                                                break;

                                            case 'H':
                                                $backgroundColor = 'yellowgreen';
                                                break;

                                            case 'L':
                                                $backgroundColor = 'firebrick';
                                                break;

                                            default:
                                                $backgroundColor = 'floralwhite';
                                                break;
                                        }

                                    @endphp
                                    <td style ='background-color:{{ $backgroundColor }}'>
                                        @if (isset($shift->newShiftEmployeeDetails) && count($shift->newShiftEmployeeDetails) > 0)
                                            @foreach ($shift->newShiftEmployeeDetails as $key => $newShiftEmployeeDetail)
                                                @if (isset($newShiftEmployeeDetail->type))
                                                    @if (isset($newShiftEmployeeDetail->shift_group_id) && $newShiftEmployeeDetail->type == 'S')

                                                    {{-- {{  dump($newShiftEmployeeDetail->getShiftGroup) }} --}}

                                                        <span>{{ optional($newShiftEmployeeDetail->getShiftGroup)->group_name }}
                                                            <br>
                                                            @if (optional($newShiftEmployeeDetail->getShiftGroup)->shiftSeason_info != null)
                                                               ( {{ optional($newShiftEmployeeDetail->getShiftGroup)->shiftSeason_info->date_from }} TO {{ optional($newShiftEmployeeDetail->getShiftGroup)->shiftSeason_info->date_to }})
                                                            @endif
                                                            {{-- ( {{optional($newShiftEmployeeDetail->getShift)->start_time}} - {{optional($newShiftEmployeeDetail->getShift)->end_time}})</span> --}}
                                                        <br>
                                                    @else
                                                        @if ($newShiftEmployeeDetail->type == 'D')
                                                            <span>DayOff</span>
                                                        @elseif($newShiftEmployeeDetail->type == 'H')
                                                            @php
                                                                $isHoliday = $atdReportRepo->isHoliday($empModel, 'date', $date);
                                                                $holidayName = $atdReportRepo->getHolidayName('date', $date);
                                                            @endphp
                                                            @if ($isHoliday)
                                                                <span>{{ $holidayName }}</span>
                                                            @endif
                                                        @elseif($newShiftEmployeeDetail->type == 'L')
                                                            @php
                                                                $leave = $leaveModel->where('date', $date)->where('status', 3)->where('employee_id', $empModel->id)->first();
                                                            @endphp
                                                            @if ($leave)
                                                                <span>{{ $leave->getLeaveKind() }}</span>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                @endforeach

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <ul class="pagination pagination-rounded justify-content-end mb-3">
                    @if ($emplists->total() != 0)
                        {{ $emplists->appends(request()->all())->links() }}
                    @endif
                </ul>
            </div>
        </div>



        <script>
            $('.month').trigger('change')
        </script>
    @endif
@endsection

@section('popupScript')
@endsection
