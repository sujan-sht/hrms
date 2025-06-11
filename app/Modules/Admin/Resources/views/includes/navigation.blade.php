@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@inject('dateconverter', '\App\Modules\Admin\Entities\DateConverter')
@inject('organization', 'App\Modules\Organization\Repositories\OrganizationRepository')


@php
    $currentRoute = Request::route()->getName();
    $Route = explode('.', $currentRoute);

    //get current nepali year and month
    $calendarType = 'nep';
    $nepDateArray = $dateconverter->eng_to_nep(date('Y'), date('m'), date('d'));
    $nepYear = $nepDateArray['year'];
    $nepMonth = $nepDateArray['month'];
    //
    $empId = optional(auth()->user()->userEmployer)->id;
    $organizationId = optional(auth()->user()->userEmployer)->organization_id;
    $organization_id = $organization->findFirstOrganizationId();
    $menuLists = App\Helpers\CareerMobilityHelpers::getSubMenus();

@endphp
<div class="sidebar-section">
    <ul class="nav nav-sidebar" data-nav-type="accordion">

        {{-- <li class="nav-item-header d-flex">
            <div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu" title="Main"></i>
        </li> --}}

        <li class="nav-item">
            <a href="{{ url('admin/dashboard') }}" class="nav-link @if ($Route[0] == 'dashboard') active @endif">
                <i class="icon-home4"></i><span>Dashboard</span>
            </a>
        </li>
        {{-- <li class="nav-item">
            <a href="{{ url('admin/analytical/dashboard') }}"
                class="nav-link @if ($Route[0] == 'analyticaldashboard') active @endif">
                <i class="icon-home4"></i><span>Analytical Dashboard</span>
            </a>
        </li> --}}

        {{-- <li class="nav-item-header">
            <div class="text-uppercase font-size-xs line-height-xs">Features</div> <i class="icon-menu"
                title="Features"></i>
        </li> --}}
        <li class="nav-item-header">
            <div class="text-uppercase font-size-xs line-height-xs">All Features</div> <i class="icon-menu"
                title="features"></i>
        </li>

        @if (auth()->user()->user_type == 'supervisor' || auth()->user()->user_type == 'employee')
            <li
                class="nav-item nav-item-submenu {{ request()->routeIs('employee.directory') || request()->routeIs('organization.overview') || request()->routeIs('organization.codeOfConduct') ? 'nav-item-open nav-item-expanded' : '' }}">
                <a href="#" class="nav-link">
                    <i class="icon-office"></i> <span>Organization</span>
                </a>
                <ul class="nav nav-group-sub">
                    <li class="nav-item">
                        <a href="{{ route('organization.overview') }}"
                            class="nav-link {{ request()->routeIs('organization.overview') ? 'active' : '' }}">
                            <i class="icon-city"></i><span>Overview</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('employee.directory') }}"
                            class="nav-link {{ request()->routeIs('employee.directory') ? 'active' : '' }}">
                            <i class="icon-address-book2"></i><span>Employee Directory</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('organization.codeOfConduct') }}"
                            class="nav-link {{ request()->routeIs('organization.codeOfConduct') ? 'active' : '' }}">
                            <i class="icon-file-text3"></i><span>Code of Conduct</span>
                        </a>
                    </li>
                </ul>
            </li>
        @else
            <li
                class="nav-item nav-item-submenu {{ request()->routeIs('organization.index') || request()->routeIs('branch.index') || request()->routeIs('organizationalStructure.index') ? 'nav-item-open nav-item-expanded' : '' }}">
                <a href="#" class="nav-link">
                    <i class="icon-city"></i> <span>Organization</span>
                </a>
                <ul class="nav nav-group-sub">
                    <li class="nav-item">
                        <a href="{{ route('organization.index') }}"
                            class="nav-link {{ request()->routeIs('organization.index') ? 'active' : '' }}">
                            <i class="icon-city"></i><span>Overview</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('branch.index') }}"
                            class="nav-link {{ request()->routeIs('branch.index') ? 'active' : '' }}">
                            <i class="icon-office"></i><span>Branches</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('organizationalStructure.index') }}"
                            class="nav-link {{ request()->routeIs('organizationalStructure.index') ? 'active' : '' }}">
                            <i class="icon-office"></i><span>Structure</span>
                        </a>
                    </li>
                </ul>
            </li>

            @if (
                $menuRoles->assignedRoles('employee.index') ||
                    $menuRoles->assignedRoles('employee.create') ||
                    $menuRoles->assignedRoles('employee.archivedDirectory'))
                <li
                    class='nav-item nav-item-submenu {{ request()->routeIs(' employee.create') ||
                    request()->routeIs('employee.index') ||
                    request()->routeIs('employee.archivedDirectory')
                        ? 'nav-item-open
                                                                                                                                                                            nav-item-expanded'
                        : '' }}'>
                    <a href="#" class="nav-link">
                        <i class="icon-users"></i><span>PIMS</span>
                    </a>
                    <ul class="nav nav-group-sub">

                        @if ($menuRoles->assignedRoles('employee.create'))
                            <li class="nav-item">
                                <a href="{{ route('employee.create') }}"
                                    class="nav-link {{ request()->routeIs('employee.create') ? 'active' : '' }}">
                                    <i class="icon-user-plus"></i><span>Add Employee</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('employee.index'))
                            <li class="nav-item">
                                <a href="{{ route('employee.index') }}"
                                    class="nav-link {{ request()->routeIs('employee.index') ? 'active' : '' }}">
                                    <i class="icon-users"></i><span>Employee Directory</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('employee.archivedDirectory'))
                            <li class="nav-item">
                                <a href="{{ route('employee.archivedDirectory') }}"
                                    class="nav-link {{ request()->routeIs('employee.archivedDirectory') ? 'active' : '' }}">
                                    <i class="icon-address-book2"></i><span>Former Directory</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('employee.pendingPreviousJobDetail'))
                            <li class="nav-item">
                                <a href="{{ route('employee.pendingPreviousJobDetail') }}"
                                    class="nav-link {{ request()->routeIs('employee.pendingPreviousJobDetail') ? 'active' : '' }}">
                                    <i class="icon-address-book2"></i><span>Pending Previous Job Detail</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('employee.create'))
                            <li class="nav-item">
                                <a href="{{ route('request-change.index') }}"
                                    class="nav-link {{ request()->routeIs('request-change.index') ? 'active' : '' }}">
                                    <i class="icon-address-book2"></i><span>Changes Requested</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            <li
                class='nav-item nav-item-submenu {{ request()->routeIs(' employee.jobEndDateReport') ||
                request()->routeIs('employee.documentExpiryDateReport') ||
                request()->routeIs('employee.approvalFlowReport')
                    ? 'nav-item-open nav-item-expanded'
                    : '' }}'>
                <a href="#" class="nav-link">
                    <i class="icon-clipboard2"></i> <span>Employee Report</span>
                </a>
                <ul class="nav nav-group-sub">
                    @if ($menuRoles->assignedRoles('employee.jobEndDateReport'))
                        <li class="nav-item">
                            <a href="{{ route('employee.jobEndDateReport') }}"
                                class="nav-link {{ request()->routeIs('employee.jobEndDateReport') ? 'active' : '' }}">
                                <i class="icon-file-text3"></i><span>Contract Expiry Report</span>
                            </a>
                        </li>
                    @endif

                    @if ($menuRoles->assignedRoles('employee.probationEndDateReport'))
                        <li class="nav-item">
                            <a href="{{ route('employee.probationEndDateReport') }}"
                                class="nav-link {{ request()->routeIs('employee.probationEndDateReport') ? 'active' : '' }}">
                                <i class="icon-file-text3"></i><span>Probation Expiry Report</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('employee.documentExpiryDateReport'))
                        <li class="nav-item">
                            <a href="{{ route('employee.documentExpiryDateReport') }}"
                                class="nav-link {{ request()->routeIs('employee.documentExpiryDateReport') ? 'active' : '' }}">
                                <i class="icon-file-text3"></i><span>Document Expiry Report</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('employee.approvalFlowReport'))
                        <li class="nav-item">
                            <a href="{{ route('employee.approvalFlowReport', ['type' => 'leave_attendance_document']) }}"
                                class="nav-link {{ request()->routeIs('employee.approvalFlowReport', ['type' => 'leave_attendance_document']) ? 'active' : '' }}">
                                <i class="icon-file-text3"></i><span>Approval Flow Report</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            <li
                class='nav-item nav-item-submenu  {{ request()->routeIs([
                    ' employee.careerMobilityConfirmation.index',
                    'employee.carrierMobility',
                    'employee.carrierMobilityReport',
                    'employee.careerMobilityAppointment.index',
                    'employee.careerMobilityTransfer.index',
                    'employee.careerMobilityPromotion.index',
                    'employee.careerMobilityDemotion.index',
                    'employee.careerMobilityTemporaryTransfer.index',
                    'employee.careerMobilityExtensionOfProbationaryPeriod.index',
                ])
                    ? 'nav-item-open nav-item-expanded'
                    : '' }}'>
                <a href="#" class="nav-link">
                    <i class="icon-clipboard2"></i> <span>Career Mobility</span>
                </a>
                <ul class="nav nav-group-sub">
                    @foreach ($menuLists as $menuList)
                        @if ($menuRoles->assignedRoles($menuList['assign_role']))
                            <li class="nav-item">
                                <a href="{{ $menuList['route'] }}"
                                    class="nav-link {{ request()->routeIs($menuList['active']) ? 'active' : '' }}">
                                    <i class="{{ $menuList['icon'] }}"></i><span>{{ $menuList['name'] }}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                    @if ($menuRoles->assignedRoles('employee.carrierMobilityReport'))
                        <li class="nav-item">
                            <a href="{{ route('employee.carrierMobilityReport') }}"
                                class="nav-link {{ request()->routeIs('employee.carrierMobilityReport') ? 'active' : '' }}">
                                <i class="icon-file-text3"></i><span>Career Mobility Report</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (
            $menuRoles->assignedRoles('leaveType.index') ||
                $menuRoles->assignedRoles('leave.index') ||
                $menuRoles->assignedRoles('leave.report') ||
                $menuRoles->assignedRoles('leave.showTeamleaves') ||
                $menuRoles->assignedRoles('leaveOpening.index') ||
                $menuRoles->assignedRoles('leave.encashableLeave') ||
                $menuRoles->assignedRoles('leave.monthlyReport') ||
                $menuRoles->assignedRoles('leave.calendar') ||
                $menuRoles->assignedRoles('leave.create') ||
                $menuRoles->assignedRoles('substituteLeave.teamRequest') ||
                $menuRoles->assignedRoles('substituteLeave.index') ||
                $menuRoles->assignedRoles('leave.encashment') ||
                $menuRoles->assignedRoles('leave.encashmentActivity') ||
                $menuRoles->assignedRoles('leave.leaveOverview'))
            <li
                class='nav-item nav-item-submenu {{ $Route[0] == ' leaveType' ||
                $Route[0] == 'leave' ||
                $Route[0] == 'leaveOpening' ||
                $Route[0] == 'substituteLeave'
                    ? 'nav-item-open nav-item-expanded'
                    : '' }}'>
                <a href="#" class="nav-link @if ($Route[0] == 'leaveType' || $Route[0] == 'leave' || $Route[0] == 'leaveOpening' || $Route[0] == 'substituteLeave') active @endif">
                    <i class="icon-clipboard2"></i> <span>Leave</span>
                </a>
                <ul class="nav nav-group-sub">
                    @if ($menuRoles->assignedRoles('leaveType.index'))
                        <li class="nav-item">
                            <a href="{{ route('leaveType.index', ['leave_year_id' => getCurrentLeaveYearId()]) }}"
                                class="nav-link @if ($Route[0] == 'leaveType') active @endif">
                                <i class="icon-clipboard3"></i><span>Leave Type</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('leave.create'))
                        @if (auth()->user()->user_type == 'supervisor' || auth()->user()->user_type == 'employee')
                            <li class="nav-item">
                                <a href="{{ route('leave.create') }}"
                                    class="nav-link @if ($Route[0] == 'leave' && $Route[1] == 'create') active @endif">
                                    <i class="icon-hand"></i><span>Apply Leave</span>
                                </a>
                            </li>
                        @endif
                    @endif
                    @if ($menuRoles->assignedRoles('leave.showTeamleaves'))
                        @if (auth()->user()->user_type == 'supervisor')
                            <li class="nav-item">
                                <a href="{{ route('leave.showTeamleaves') }}"
                                    class="nav-link @if ($Route[0] == 'leave' && $Route[1] == 'showTeamleaves') active @endif">
                                    <i class="icon-hand"></i><span>Team Request</span>
                                </a>
                            </li>
                        @endif
                    @endif
                    @if ($menuRoles->assignedRoles('substituteLeave.teamRequest'))
                        @if (auth()->user()->user_type == 'supervisor')
                            <li class="nav-item">
                                <a href="{{ route('substituteLeave.teamRequest') }}"
                                    class="nav-link @if ($Route[0] == 'substituteLeave' && $Route[1] == 'teamRequest') active @endif">
                                    <i class="icon-hand"></i><span>Team Substitute Request</span>
                                </a>
                            </li>
                        @endif
                    @endif
                    @if ($menuRoles->assignedRoles('substituteLeave.index'))
                        <li class="nav-item">
                            <a href="{{ route('substituteLeave.index') }}"
                                class="nav-link @if ($Route[0] == 'substituteLeave' && $Route[1] == 'index') active @endif">
                                <i class="icon-file-text"></i><span>Request Substitute Leave</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('substituteLeave.claimedSubstituteLeaves'))
                        <li class="nav-item">
                            <a href="{{ route('substituteLeave.claimedSubstituteLeaves') }}"
                                class="nav-link @if ($Route[0] == 'substituteLeave' && $Route[1] == 'claimedSubstituteLeaves') active @endif">
                                <i class="icon-file-text"></i><span>Claimed Substitute Leave</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('leave.index'))
                        <li class="nav-item">
                            <a href="{{ route('leave.index', ['leave_year_id' => getCurrentLeaveYearId()]) }}"
                                class="nav-link @if ($Route[0] == 'leave' && $Route[1] == 'index') active @endif">
                                <i class="icon-clipboard2"></i><span>Leave History</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('leave.report'))
                        <li class="nav-item">
                            <a href="{{ route('leave.report') }}"
                                class="nav-link @if ($Route[0] == 'leave' && $Route[1] == 'report') active @endif">
                                <i class="icon-file-text3"></i><span>Leave Approved Report</span>
                            </a>
                        </li>
                    @endif
                    {{-- @if ($menuRoles->assignedRoles('leaveOpening.index'))
                <li class="nav-item">
                    <a href="{{ route('leaveOpening.index') }}"
                        class="nav-link @if ($Route[0] == 'leaveOpening' && ($Route[1] == 'index' || $Route[1] == 'show')) active @endif">
                        <i class="icon-file-text3"></i><span>Leave Summary</span>
                    </a>
                </li>
                @endif --}}
                    @if ($menuRoles->assignedRoles('leave.monthlyReport'))
                        <li class="nav-item">
                            <a href="{{ route('leave.monthlyReport', ['leave_year_id' => getCurrentLeaveYearId()]) }}"
                                class="nav-link @if ($Route[0] == 'leave' && $Route[1] == 'monthlyReport') active @endif">
                                <i class="icon-calendar"></i><span>Monthly Leave</span>
                            </a>
                        </li>
                    @endif
                    {{-- @if ($menuRoles->assignedRoles('leave.leaveOverview'))
                <li class="nav-item">
                    <a href="{{ route('leave.leaveOverview') }}"
                        class="nav-link @if ($Route[0] == 'leave' && $Route[1] == 'leaveOverview') active @endif">
                        <i class="icon-file-text3"></i><span>Leave Overview</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('leave.encashableLeave'))
                <li class="nav-item">
                    <a href="{{ route('leave.encashableLeave', ['organization_id' => $organizationId ?? $organization_id]) }}"
                        class="nav-link @if ($Route[0] == 'leave' && $Route[1] == 'encashableLeave') active @endif">
                        <i class="icon-credit-card"></i><span>Encashable Leave</span>
                    </a>
                </li>
                @endif --}}
                    @if ($menuRoles->assignedRoles('leave.calendar'))
                        <li class="nav-item">
                            <a href="{{ route('leave.calendar') }}"
                                class="nav-link @if ($Route[0] == 'leave' && $Route[1] == 'calendar') active @endif">
                                <i class="icon-calendar"></i><span>Leave Calendar</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('leave.encashment'))
                        <li class="nav-item">
                            <a href="{{ route('leave.encashment') }}"
                                class="nav-link @if ($Route[0] == 'leave' && $Route[1] == 'encashment') active @endif">
                                <i class="icon-calendar"></i><span>Leave Encashment</span>
                            </a>
                        </li>
                    @endif

                    @if ($menuRoles->assignedRoles('leave.encashmentActivity'))
                        <li class="nav-item">
                            <a href="{{ route('leave.encashmentActivity') }}"
                                class="nav-link @if ($Route[0] == 'leave' && $Route[1] == 'encashmentActivity') active @endif">
                                <i class="icon-calendar"></i><span>Leave Encashment Logs</span>
                            </a>
                        </li>
                    @endif

                    @if ($menuRoles->assignedRoles('leave.monthlySummary'))
                        <li class="nav-item">
                            <a href="{{ route('leave.monthlySummary') }}"
                                class="nav-link @if ($Route[0] == 'leave' && $Route[1] == 'Test') active @endif">
                                <i class="icon-calendar"></i><span>Monthly Summary</span>
                            </a>
                        </li>
                    @endif

                    @if ($menuRoles->assignedRoles('leave.annualSummary'))
                        <li class="nav-item">
                            <a href="{{ route('leave.annualSummary') }}"
                                class="nav-link @if ($Route[0] == 'leave' && $Route[1] == 'Test') active @endif">
                                <i class="icon-calendar"></i><span>Annual Summary</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif


        @if (Module::isModuleEnabled('Labour'))
            @if ($menuRoles->assignedRoles('skillSetup.index') || $menuRoles->assignedRoles('labour.index'))
                <li
                    class='nav-item nav-item-submenu {{ $Route[0] == ' skillSetup' ? 'nav-item-open nav-item-expanded' : '' }}'>
                    <a href="#" class="nav-link @if ($Route[0] == 'skillSetup') active @endif">
                        <i class="icon-clipboard2"></i> <span>Labour Management</span>
                    </a>
                    <ul class="nav nav-group-sub">
                        @if ($menuRoles->assignedRoles('skillSetup.index'))
                            <li class="nav-item">
                                <a href="{{ route('skillSetup.index') }}"
                                    class="nav-link @if ($Route[0] == 'skillSetup' && $Route[1] == 'index') active @endif">
                                    <i class="icon-book"></i><span>Skill Setup</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('labour.index'))
                            <li class="nav-item">
                                <a href="{{ route('labour.index') }}"
                                    class="nav-link @if ($Route[0] == 'labour' && $Route[1] == 'index') active @endif">
                                    <i class="icon-touch"></i><span>Labour Information Setup</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('labour.viewLabourMonthly'))
                            <li class="nav-item">
                                @if ($empId && $organizationId && auth()->user()->user_type != 'division_hr' && auth()->user()->user_type != 'hr')
                                    @php
                                        $route = route('labour.viewLabourMonthly', [
                                            'org_id' => $organizationId,
                                            'emp_id' => ['empId' => $empId],
                                            'calendar_type' => 'nep',
                                            'nep_year' => $nepYear,
                                            'nep_month' => $nepMonth,
                                        ]);

                                    @endphp
                                    <a href="{{ $route }}"
                                        class="nav-link @if ($Route[0] == 'labour' && $Route[1] == 'viewLabourMonthly') active @endif">
                                    @else
                                        @php
                                            $route = route('labour.viewLabourMonthly', [
                                                'org_id' => $organization_id,
                                                'calendar_type' => 'nep',
                                                'nep_year' => $nepYear,
                                                'nep_month' => $nepMonth,
                                            ]);

                                        @endphp
                                        <a href="{{ $route }}"
                                            class="nav-link @if ($Route[0] == 'labour' && $Route[1] == 'viewLabourMonthly') active @endif">
                                @endif
                                <i class="icon-calendar3"></i><span>Monthly Labour Attendance</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('labour.wageManagement'))
                            <li class="nav-item">
                                @php
                                    if (setting('calendar_type') == 'BS') {
                                        $route = route('labour.wageManagement', [
                                            'org_id' => $organization_id,
                                            'calendar_type' => $calendarType,
                                            'nep_year' => $nepYear,
                                            'nep_month' => $nepMonth,
                                        ]);
                                    } else {
                                        $route = route('labour.wageManagement', [
                                            'org_id' => $organization_id,
                                            'calendar_type' => 'eng',
                                            'eng_year' => date('Y'),
                                            'eng_month' => (int) date('m'),
                                        ]);
                                    }
                                @endphp
                                <a href="{{ $route }}"
                                    class="nav-link @if ($Route[0] == 'labour' && $Route[1] == 'index') active @endif">
                                    <i class="icon-touch"></i><span>Wages Management</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif

        @if (
            $menuRoles->assignedRoles('shift.index') ||
                $menuRoles->assignedRoles('shiftGroup.index') ||
                $menuRoles->assignedRoles('attendanceRequest.index'))

            @php
                $condition = 'false';
                if ($Route[0] == 'shift' || $Route[0] == 'shiftGroup' || $Route[0] == 'attendanceRequest') {
                    $condition = 'true';
                }
            @endphp
            @if ($condition)
                <li
                    class="nav-item nav-item-submenu {{ $condition == 'true' ? 'nav-item-open nav-item-expanded' : '' }}">
                    <a href="#" class="nav-link {{ $condition == 'true' ? 'active' : '' }}">
                        <i class="icon-touch"></i> <span>Attendance Request</span>
                    </a>
                    <ul class="nav nav-group-sub">
                        @if ($menuRoles->assignedRoles('attendanceRequest.index'))
                            <li class="nav-item">
                                <a href="{{ route('attendanceRequest.index') }}"
                                    class="nav-link @if ($Route[0] == 'attendanceRequest' && $Route[1] == 'index') active @endif">
                                    <i class="icon-list2"></i><span>All Requests</span>
                                </a>
                            </li>
                        @endif


                        @if (
                            $menuRoles->assignedRoles('viewAttendanceCalendar') &&
                                in_array(auth()->user()->user_type, ['hr', 'division_hr', 'supervisor']))
                            <li class="nav-item">
                                <a href="{{ route('viewAttendanceCalendar') }}"
                                    class="nav-link @if ($Route[0] == 'viewAttendanceCalendar') active @endif">
                                    <i class="icon-calendar2"></i><span>View Attendance Calendar</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('shift.index'))
                            <li class="nav-item">
                                <a href="{{ route('shift.index') }}"
                                    class="nav-link @if ($Route[0] == 'shift') active @endif">
                                    <i class="icon-watch2"></i><span>Shift</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('shiftGroup.index'))
                            <li class="nav-item">
                                <a href="{{ route('shiftGroup.index') }}"
                                    class="nav-link @if ($Route[0] == 'shiftGroup') active @endif">
                                    <i class="icon-grid52"></i><span>Shift Group</span>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->user_type == 'supervisor')
                            <li class="nav-item">
                                <a href="{{ route('attendanceRequest.showTeamAttendance') }}"
                                    class="nav-link @if ($Route[0] == 'attendanceRequest' && $Route[1] == 'showTeamAttendance') active @endif">
                                    <i class="icon-hand"></i><span>Team Request</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif


            <li
                class="nav-item nav-item-submenu {{ request()->routeIs('rawAttendance') ||
                request()->routeIs('viewMonthlyAttendanceCalendar') ||
                request()->routeIs('regularAttendanceReport', ['date_range' => date('Y-m-d')])
                    ? 'nav-item-open nav-item-expanded'
                    : '' }}">
                <a href="#" class="nav-link">
                    <i class="icon-touch"></i> <span>Attendance Report</span>
                </a>
                <ul class="nav nav-group-sub">
                    @if ($menuRoles->assignedRoles('rawAttendance'))
                        <li class="nav-item">

                            <a href="{{ route('rawAttendance') }}"
                                class="nav-link {{ request()->routeIs('rawAttendance') ? 'active' : '' }}">
                                <i class="icon-touch"></i><span>Raw Attendance Report</span>
                            </a>
                        </li>
                    @endif
                    @php
                        $divDate =
                            setting('calendar_type') == 'BS'
                                ? date_converter()->eng_to_nep_convert(date('Y-m-d'))
                                : date('Y-m-d');
                    @endphp
                    @if ($menuRoles->assignedRoles('regularAttendanceReport'))
                        @php
                            $date_range = date('Y-m-d');
                        @endphp
                        <li class="nav-item">
                            <a href="{{ route('regularAttendanceReport', ['date_range' => $date_range]) }}"
                                class="nav-link {{ request()->routeIs('regularAttendanceReport', ['date_range' => $date_range]) ? 'active' : '' }}">
                                <i class="icon-stack"></i><span>Daily Attendance Report</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('dailyAttendance'))
                        <li class="nav-item">
                            @if ($empId && $organizationId && auth()->user()->user_type != 'division_hr' && auth()->user()->user_type != 'hr')
                                @php
                                    $route = route('dailyAttendance', [
                                        'org_id' => $organizationId,
                                        'emp_id' => ['empId' => $empId],
                                        'calendar_type' => 'eng',
                                        'eng_year' => date('Y'),
                                        'eng_month' => (int) date('m'),
                                    ]);
                                @endphp
                                <a href="{{ $route }}"
                                    class="nav-link @if ($Route[0] == 'dailyAttendance') active @endif">
                                @else
                                    @php
                                        $route = route('dailyAttendance', [
                                            'org_id' => $organization_id,
                                            'calendar_type' => 'eng',
                                            'eng_year' => date('Y'),
                                            'eng_month' => (int) date('m'),
                                        ]);
                                    @endphp
                                    <a href="{{ $route }}"
                                        class="nav-link @if ($Route[0] == 'dailyAttendance') active @endif">
                            @endif
                            <i class="icon-calendar3"></i><span>Monthly Attendance Report</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            @if ($empId && $organizationId && auth()->user()->user_type != 'division_hr' && auth()->user()->user_type != 'hr')
                                @php
                                    $route = route('viewMonthlyAttendanceCalendar', [
                                        'org_id' => $organizationId,
                                        'emp_id' => $empId,
                                        'calendar_type' => 'eng',
                                        'eng_year' => date('Y'),
                                        'eng_month' => (int) date('m'),
                                    ]);
                                @endphp
                                <a href="{{ $route }}"
                                    class="nav-link @if ($Route[0] == 'viewMonthlyAttendanceCalendar') active @endif">
                                @else
                                    @php
                                        $route = route('viewMonthlyAttendanceCalendar', [
                                            'org_id' => $organization_id,
                                            'calendar_type' => 'eng',
                                            'eng_year' => date('Y'),
                                            'eng_month' => (int) date('m'),
                                        ]);
                                    @endphp
                                    <a href="{{ $route }}"
                                        class="nav-link @if ($Route[0] == 'viewMonthlyAttendanceCalendar') active @endif">
                            @endif
                            <i class="icon-calendar"></i><span>Attendance Calendar</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('monthlyAttendance'))
                        <li class="nav-item">
                            @if ($empId && $organizationId)
                                @php
                                    if (setting('calendar_type') == 'BS') {
                                        $route = route('monthlyAttendance', [
                                            'org_id' => $organizationId,
                                            'emp_id' => ['empId' => $empId],
                                            'calendar_type' => $calendarType,
                                            'nep_year' => $nepYear,
                                            'nep_month' => $nepMonth,
                                        ]);
                                    } else {
                                        $route = route('monthlyAttendance', [
                                            'org_id' => $organizationId,
                                            'emp_id' => ['empId' => $empId],
                                            'calendar_type' => 'eng',
                                            'eng_year' => date('Y'),
                                            'eng_month' => (int) date('m'),
                                        ]);
                                    }
                                @endphp
                                <a href="{{ $route }}"
                                    class="nav-link @if ($Route[0] == 'monthlyAttendance') active @endif">
                                @else
                                    @php
                                        if (setting('calendar_type') == 'BS') {
                                            $route = route('monthlyAttendance', [
                                                'org_id' => $organization_id,
                                                'calendar_type' => $calendarType,
                                                'nep_year' => $nepYear,
                                                'nep_month' => $nepMonth,
                                            ]);
                                        } else {
                                            $route = route('monthlyAttendance', [
                                                'org_id' => $organization_id,
                                                'calendar_type' => 'eng',
                                                'eng_year' => date('Y'),
                                                'eng_month' => (int) date('m'),
                                            ]);
                                        }
                                    @endphp
                                    <a href="{{ $route }}"
                                        class="nav-link @if ($Route[0] == 'monthlyAttendance') active @endif">
                            @endif
                            <i class="icon-touch"></i><span>Attendance Records</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('monthlyAttendanceSummary'))
                        <li class="nav-item">
                            <a href="{{ route('monthlyAttendanceRange') }}"
                                class="nav-link @if (Route::is('monthlyAttendanceRange')) active @endif">
                                <i class="icon-stack"></i><span>Date Range Attendance Report</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('monthlyAttendanceSummary', ['organization_id' => $organizationId ?? $organization_id, 'calendar_type' => $calendarType, 'nep_year' => $nepYear, 'nep_month' => $nepMonth]) }}"
                                class="nav-link @if ($Route[0] == 'monthlyAttendanceSummary') active @endif">
                                <i class="icon-calendar52"></i><span>Attendance Summary</span>
                            </a>
                        </li>
                    @endif

                    @if (setting('attendance_lock') == 11)
                        @if ($menuRoles->assignedRoles('monthlyAttendanceSummary'))
                            <li class="nav-item">
                                <a href="{{ route('monthlyAttendanceSummaryVerification', ['organization_id' => $organizationId ?? $organization_id, 'calendar_type' => $calendarType, 'nep_year' => $nepYear, 'nep_month' => $nepMonth]) }}"
                                    class="nav-link @if ($Route[0] == 'monthlyAttendanceSummaryVerification') active @endif">
                                    <i class="icon-calendar52"></i><span>Attendance Summary Verification</span>
                                </a>
                            </li>
                        @endif
                    @endif
                    {{--
                @if ($menuRoles->assignedRoles('siteAttendance.viewMonthly'))
                <li class="nav-item">
                    @if ($empId && $organizationId && auth()->user()->user_type != 'division_hr' && auth()->user()->user_type != 'hr')
                    @php
                    if (setting('calendar_type') == 'BS') {
                    $route = route('siteAttendance.viewMonthly', [
                    'org_id' => $organizationId,
                    'emp_id' => ['empId' => $empId],
                    'calendar_type' => $calendarType,
                    'nep_year' => $nepYear,
                    'nep_month' => $nepMonth,
                    ]);
                    } else {
                    $route = route('siteAttendance.viewMonthly', [
                    'org_id' => $organizationId,
                    'emp_id' => ['empId' => $empId],
                    'calendar_type' => 'eng',
                    'eng_year' => date('Y'),
                    'eng_month' => (int) date('m'),
                    ]);
                    }
                    @endphp
                    <a href="{{ $route }}"
                        class="nav-link @if ($Route[0] == 'siteAttendance' && $Route[1] == 'viewMonthly') active @endif">
                        @else
                        @php
                        if (setting('calendar_type') == 'BS') {
                        $route = route('siteAttendance.viewMonthly', [
                        'org_id' => $organization_id,
                        'calendar_type' => $calendarType,
                        'nep_year' => $nepYear,
                        'nep_month' => $nepMonth,
                        ]);
                        } else {
                        $route = route('siteAttendance.viewMonthly', [
                        'org_id' => $organization_id,
                        'calendar_type' => 'eng',
                        'eng_year' => date('Y'),
                        'eng_month' => (int) date('m'),
                        ]);
                        }
                        @endphp
                        <a href="{{ $route }}"
                            class="nav-link @if ($Route[0] == 'siteAttendance' && $Route[1] == 'viewMonthly') active @endif">
                            @endif
                            <i class="icon-calendar3"></i><span>Monthly Site Attendance</span>
                        </a>
                </li>
                @endif --}}



                    @if ($menuRoles->assignedRoles('appAttendanceReport'))
                        <li class="nav-item">
                            <a href="{{ route('appAttendanceReport') }}"
                                class="nav-link @if ($Route[0] == 'appAttendanceReport') active @endif">
                                <i class="icon-stack"></i><span>App Attendance Report</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>

        @endif

        @if (Module::isModuleEnabled('NewShift'))
            @if (
                $menuRoles->assignedRoles('newShift.assignShift') ||
                    $menuRoles->assignedRoles('newShift.weeklyReport') ||
                    $menuRoles->assignedRoles('rosterRequest.index'))
                <li
                    class='nav-item nav-item-submenu {{ $Route[0] == ' newShift' ? 'nav-item-open nav-item-expanded' : '' }}'>
                    <a href="#" class="nav-link @if ($Route[0] == 'newShift') active @endif">
                        <i class="icon-clipboard2"></i> <span>Roster</span>
                    </a>
                    <ul class="nav nav-group-sub">
                        @if ($menuRoles->assignedRoles('newShift.assignShift'))
                            <li class="nav-item">
                                <a href="{{ route('newShift.assignShift') }}"
                                    class="nav-link @if ($Route[0] == 'newShift' && $Route[1] == 'assignShift') active @endif">
                                    <i class="icon-touch"></i><span>Roster Planning</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('newShift.weeklyReport'))
                            <li class="nav-item">
                                <a href="{{ route('newShift.weeklyReport') }}"
                                    class="nav-link @if ($Route[0] == 'newShift' && $Route[1] == 'weeklyReport') active @endif">
                                    <i class="icon-touch"></i><span>Weekly Report</span>
                                </a>
                            </li>
                        @endif
                        {{-- @if ($menuRoles->assignedRoles('rosterRequest.index'))
                <li class="nav-item">
                    <a href="{{ route('rosterRequest.index') }}"
                        class="nav-link @if ($Route[0] == 'rosterRequest' && $Route[1] == 'index') active @endif">
                        <i class="icon-touch"></i><span>Requests</span>
                    </a>
                </li>
                @endif --}}
                    </ul>

                </li>
            @endif
        @endif

        {{-- Overtime Request Start --}}
        @if (Module::isModuleEnabled('OvertimeRequest'))
            @if ($menuRoles->assignedRoles('overtimeRequest.index') || $menuRoles->assignedRoles('overtimeRequest.teamRequests'))
                @php
                    $condition = 'false';
                    if ($Route[0] == 'overtimeRequest') {
                        $condition = 'true';
                    }
                @endphp
                <li
                    class="nav-item nav-item-submenu {{ $condition == 'true' ? 'nav-item-open nav-item-expanded' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="icon-clipboard6"></i> <span>Overtime Management</span>
                    </a>
                    <ul class="nav nav-group-sub">
                        @if ($menuRoles->assignedRoles('overtimeRequest.index'))
                            <li class="nav-item">
                                <a href="{{ route('overtimeRequest.index') }}"
                                    class="nav-link @if ($Route[0] == 'overtimeRequest' && $Route[1] == 'index') active @endif">
                                    <i class="icon-diff-added"></i><span>Overtime Requests</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('overtimeRequest.teamRequests') && auth()->user()->user_type == 'supervisor')
                            <li class="nav-item">
                                <a href="{{ route('overtimeRequest.teamRequests') }}"
                                    class="nav-link @if ($Route[0] == 'overtimeRequest' && $Route[1] == 'teamRequests') active @endif">
                                    <i class="icon-diff-added"></i><span>Team Overtime Requests</span>
                                </a>
                            </li>
                        @endif

                        {{-- @if ($menuRoles->assignedRoles('overtimeRequest.viewReport'))
                <li class="nav-item">
                    <a href="{{ route('overtimeRequest.viewReport') }}"
                        class="nav-link @if ($Route[0] == 'overtimeRequest' && $Route[1] == 'viewReport') active @endif">
                        <i class="icon-diff-added"></i><span>Overtime Report</span>
                    </a>
                </li>
                @endif --}}
                    </ul>
                </li>
            @endif
        @endif
        {{-- Overtime Request End --}}

        @if (Module::isModuleEnabled('Tada'))

            @if (
                $menuRoles->assignedRoles('tadaType.index') ||
                    $menuRoles->assignedRoles('tada.index') ||
                    $menuRoles->assignedRoles('fuelConsumption') ||
                    $menuRoles->assignedRoles('tada.showTeamClaim') ||
                    $menuRoles->assignedRoles('tadaType.showTeamRequest') ||
                    $menuRoles->assignedRoles('tadaRequest.index'))
                <li
                    class='nav-item nav-item-submenu @if ($Route[0] == ' tada' || $Route[0] == 'tadaType' || $Route[0] == 'tadaRequest' || $Route[0] == 'fuelConsumption') nav-item-open nav-item-expanded @endif'>
                    <a href="#" class="nav-link @if ($Route[0] == 'tada' || $Route[0] == 'tadaType' || $Route[0] == 'tadaRequest' || $Route[0] == 'fuelConsumption') active @endif"><i
                            class="icon-stack3"></i> <span>Claim & Request</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="PMS">
                        @if ($menuRoles->assignedRoles('tadaType.index'))
                            <li class="nav-item">
                                <a href="{{ route('tadaType.index') }}"
                                    class="nav-link @if ($Route[0] == 'tadaType') active @endif">
                                    <i class="icon-stack3"></i><span>Types</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('tada.index'))
                            <li class="nav-item">
                                <a href="{{ route('tada.index') }}"
                                    class="nav-link @if ($Route[0] == 'tada' && $Route[1] == 'index') active @endif">
                                    <i class="icon-coins"></i><span>Claim</span>
                                </a>
                            </li>
                        @endif

                        @if (Module::isModuleEnabled('FuelConsumption'))
                            @if ($menuRoles->assignedRoles('fuelConsumption'))
                                <li class="nav-item">
                                    <a href="{{ route('fuelConsumption') }}"
                                        class="nav-link @if ($Route[0] == 'fuelConsumption') active @endif">
                                        <i class="icon-coins"></i><span>Claim Fuel</span>
                                    </a>
                                </li>
                            @endif
                        @endif


                        @if ($menuRoles->assignedRoles('tada.showTeamClaim'))
                            <li class="nav-item">
                                <a href="{{ route('tada.showTeamClaim') }}"
                                    class="nav-link @if ($Route[0] == 'tada' && $Route[1] == 'showTeamClaim') active @endif">
                                    <i class="icon-coins"></i><span>Team Claim</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('tadaRequest.index'))
                            <li class="nav-item">
                                <a href="{{ route('tadaRequest.index') }}"
                                    class="nav-link @if ($Route[0] == 'tadaRequest' && $Route[1] == 'index') active @endif">
                                    <i class="icon-coin-dollar"></i><span>Request</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('tadaRequest.showTeamRequest'))
                            <li class="nav-item">
                                <a href="{{ route('tadaRequest.showTeamRequest') }}"
                                    class="nav-link @if ($Route[0] == 'tadaRequest' && $Route[1] == 'showTeamRequest') active @endif">
                                    <i class="icon-coin-dollar"></i><span>Team Request</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('transportation.view'))
                            <li class="nav-item">
                                <a href="{{ route('transportation.view') }}"
                                    class="nav-link @if ($Route[0] == 'transportation' && $Route[1] == 'view') active @endif">
                                    <i class="icon-coins"></i><span>Transportation Type</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('expensehead.index'))
                            <li class="nav-item">
                                <a href="{{ route('expensehead.index') }}"
                                    class="nav-link @if ($Route[0] == 'expensehead' && $Route[1] == 'index') active @endif">
                                    <i class="icon-coins"></i><span>Expense Head</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('ertype.index'))
                            <li class="nav-item">
                                <a href="{{ route('ertype.index') }}"
                                    class="nav-link @if ($Route[0] == 'ertype' && $Route[1] == 'index') active @endif">
                                    <i class="icon-coins"></i><span>Expense Reimbursement</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif

        {{-- Business Trip Start --}}
        @if (Module::isModuleEnabled('BusinessTrip'))
            @if (
                $menuRoles->assignedRoles('travelRequestType.index') ||
                    $menuRoles->assignedRoles('businessTrip.index') ||
                    $menuRoles->assignedRoles('businessTrip.teamRequests') ||
                    $menuRoles->assignedRoles('businessTrip.allowanceSetup') ||
                    $menuRoles->assignedRoles('bussinessTripe.report') ||
                    $menuRoles->assignedRoles('travelexpense.index'))
                @php
                    $condition = 'false';
                    if ($Route[0] == 'businessTrip') {
                        $condition = 'true';
                    }
                @endphp
                <li
                    class="nav-item nav-item-submenu {{ $condition == 'true' ? 'nav-item-open nav-item-expanded' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="icon-clipboard6"></i> <span>Travel Request Management</span>
                    </a>
                    <ul class="nav nav-group-sub">
                        @if ($menuRoles->assignedRoles('travelRequestType.index'))
                            <li class="nav-item">
                                <a href="{{ route('travelRequestType.index') }}"
                                    class="nav-link @if ($Route[0] == 'travelRequestType' && $Route[1] == 'index') active @endif">
                                    <i class="icon-diff-added"></i><span>Types</span>
                                </a>
                            </li>
                        @endif
                        {{-- @if ($menuRoles->assignedRoles('businessTrip.allowanceSetup'))
                <li class="nav-item">
                    <a href="{{ route('businessTrip.allowanceSetup') }}"
                        class="nav-link @if ($Route[0] == 'businessTrip' && $Route[1] == 'allowanceSetup') active @endif">
                        <i class="icon-task"></i><span>Per Day Allowance Setup</span>
                    </a>
                </li>
                @endif --}}

                        @if ($menuRoles->assignedRoles('businessTrip.allowanceSetup'))
                            <li class="nav-item">
                                <a href="{{ route('businessTrip.allowanceSetupTest') }}"
                                    class="nav-link @if ($Route[0] == 'businessTrip' && $Route[1] == 'allowanceSetupTest') active @endif">
                                    <i class="icon-task"></i><span>Per Day Allowance Setup</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('businessTrip.report'))
                            <li class="nav-item">
                                <a href="{{ route('businessTrip.report') }}"
                                    class="nav-link @if ($Route[0] == 'businessTrip' && $Route[1] == 'report') active @endif">
                                    <i class="icon-task"></i><span>Per Day Allowance Setup Report</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('businessTrip.index'))
                            <li class="nav-item">
                                <a href="{{ route('businessTrip.index') }}"
                                    class="nav-link @if ($Route[0] == 'businessTrip' && $Route[1] == 'index') active @endif">
                                    <i class="icon-diff-added"></i><span>Travel Requests</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('businessTrip.teamRequests') && auth()->user()->user_type == 'supervisor')
                            <li class="nav-item">
                                <a href="{{ route('businessTrip.teamRequests') }}"
                                    class="nav-link @if ($Route[0] == 'businessTrip' && $Route[1] == 'teamRequests') active @endif">
                                    <i class="icon-diff-added"></i><span>Team Travel Requests</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('travelexpense.index'))
                            <li class="nav-item">
                                <a href="{{ route('travelexpense.index') }}"
                                    class="nav-link @if ($Route[0] == 'travelexpense' && $Route[1] == 'index') active @endif">
                                    <i class="icon-diff-added"></i><span>Travel Expenses</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif
        {{-- Business Trip End --}}

        {{-- @if (Module::isModuleEnabled('Payroll'))
        @if ($menuRoles->assignedRoles('thresholdBenefitSetup.index') || $menuRoles->assignedRoles('taxSlab.index') || $menuRoles->assignedRoles('incomeSetup.index') || $menuRoles->assignedRoles('deductionSetup.index') || $menuRoles->assignedRoles('massIncrement.index') || $menuRoles->assignedRoles('arrearAdjustment.index') || $menuRoles->assignedRoles('holdPayment.index') || $menuRoles->assignedRoles('payroll.index'))
        @php
        $condition = 'false';
        if (
        $Route[0] == 'thresholdBenefitSetup' ||
        $Route[0] == 'incomeSetup' ||
        $Route[0] == 'deductionSetup' ||
        $Route[0] == 'employeeSetup' ||
        $Route[0] == 'taxSlab' ||
        $Route[0] == 'payroll' ||
        $Route[0] == 'massIncrement' ||
        $Route[0] == 'arrearAdjustment' ||
        $Route[0] == 'holdPayment' ||
        $Route[0] == 'stopPayment'
        ) {
        $condition = 'true';
        }
        @endphp
        @if ($condition)
        <li class="nav-item nav-item-submenu {{ $condition == 'true' ? 'nav-item-open nav-item-expanded' : '' }}">
            <a href="#" class="nav-link {{ $condition == 'true' ? 'active' : '' }}">
                <i class="icon-coins"></i> <span>Payroll Management</span>
            </a>
            <ul class="nav nav-group-sub" data-submenu-title="Payroll">
                @if ($menuRoles->assignedRoles('thresholdBenefitSetup.index'))
                <li class="nav-item">
                    <a href="{{ route('thresholdBenefitSetup.index') }}"
                        class="nav-link @if ($Route[0] == 'thresholdBenefitSetup') active @endif">
                        <i class="icon-coins"></i><span>Threshold Benefit Setup</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('taxSlab.index'))
                <li class="nav-item">
                    <a href="{{ route('taxSlab.index') }}" class="nav-link @if ($Route[0] == 'taxSlab') active @endif">
                        <i class="icon-library2"></i><span>Tax Slab Setup</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('incomeSetup.index'))
                <li class="nav-item">
                    <a href="{{ route('incomeSetup.index') }}"
                        class="nav-link @if ($Route[0] == 'incomeSetup') active @endif">
                        <i class="icon-coins"></i><span>Income Setup</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('deductionSetup.index'))
                <li class="nav-item">
                    <a href="{{ route('deductionSetup.index') }}"
                        class="nav-link @if ($Route[0] == 'deductionSetup') active @endif">
                        <i class="icon-cash3"></i><span>Deduction Setup</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('leaveAmountSetup.index'))
                <li class="nav-item">
                    <a href="{{ route('leaveAmountSetup.index') }}"
                        class="nav-link @if ($Route[0] == 'leaveAmountSetup') active @endif">
                        <i class="icon-cash3"></i><span>Leave Deduction Setup</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('bonusSetup.index'))
                <li class="nav-item">
                    <a href="{{ route('bonusSetup.index') }}"
                        class="nav-link @if ($Route[0] == 'bonusSetup') active @endif">
                        <i class="icon-cash3"></i><span>Bonus Setup</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('taxExcludeSetup.index'))
                <li class="nav-item">
                    <a href="{{ route('taxExcludeSetup.index') }}"
                        class="nav-link @if ($Route[0] == 'taxExcludeSetup') active @endif">
                        <i class="icon-cash3"></i><span>Tax Exclude Setup</span>
                    </a>
                </li>
                @endif
                <li class='nav-item nav-item-submenu  @if ($Route[0] == ' employeeSetup') nav-item-open
                    nav-item-expanded @endif'>
                    <a href="#" class="nav-link @if ($Route[0] == 'employeeSetup') active @endif"><i
                            class="icon-users"></i> <span>Salary Scale Setup</span></a>
                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="{{ route('employeeSetup.grossSalary') }}"
                                class="nav-link @if ($Route[0] == 'employeeSetup' && $Route[1] == 'grossSalary') active @endif ">
                                <i class="icon-coins"></i><span>Gross Salary</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employeeSetup.incomeTest') }}"
                                class="nav-link @if ($Route[0] == 'employeeSetup' && $Route[1] == 'income') active @endif ">
                                <i class="icon-coins"></i><span>Income</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employeeSetup.deductionTest') }}"
                                class="nav-link @if ($Route[0] == 'employeeSetup' && $Route[1] == 'deduction') active @endif">
                                <i class="icon-cash3"></i><span>Deduction</span>
                            </a>

                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employeeSetup.taxExclude') }}"
                                class="nav-link @if ($Route[0] == 'employeeSetup' && $Route[1] == 'taxExclude') active @endif">
                                <i class="icon-cash3"></i><span>Tax Exclude</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employeeSetup.bonus') }}"
                                class="nav-link @if ($Route[0] == 'employeeSetup' && $Route[1] == 'bonus') active @endif">
                                <i class="icon-cash3"></i><span>Bonus</span>
                            </a>
                        </li>
                    </ul>

                </li>
                @if ($menuRoles->assignedRoles('massIncrement.index'))
                <li class="nav-item">
                    <a href="{{ route('massIncrement.index') }}"
                        class="nav-link @if ($Route[0] == 'massIncrement') active @endif">
                        <i class="icon-briefcase" aria-hidden="true"></i><span>Mass Increment</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('arrearAdjustment.index'))
                <li class="nav-item">
                    <a href="{{ route('arrearAdjustment.index') }}"
                        class="nav-link @if ($Route[0] == 'arrearAdjustment') active @endif">
                        <i class="icon-cash3"></i><span>Arrear Adjustment</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('holdPayment.index'))
                <li class="nav-item">
                    <a href="{{ route('holdPayment.index') }}"
                        class="nav-link @if ($Route[0] == 'holdPayment') active @endif">
                        <i class="icon-cash3"></i><span>Hold Payment</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('stopPayment.index'))
                <li class="nav-item">
                    <a href="{{ route('stopPayment.index') }}"
                        class="nav-link @if ($Route[0] == 'stopPayment') active @endif">
                        <i class="icon-cash3"></i><span>Stop Payment</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('bonus.index'))
                <li class="nav-item">
                    <a href="{{ route('bonus.index') }}"
                        class="nav-link @if ($Route[0] == 'bonus' && $Route[1] == 'index') active @endif">
                        <i class="icon-list2"></i><span>Bonus List</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('payroll.index'))
                <li class="nav-item">
                    <a href="{{ route('payroll.index') }}"
                        class="nav-link @if ($Route[0] == 'payroll' && $Route[1] == 'index') active @endif">
                        <i class="icon-list2"></i><span>Payroll List</span>
                    </a>
                </li>
                @endif

                @if ($menuRoles->assignedRoles('payroll.yearlyPaySlip'))
                <li class="nav-item">
                    <a href="{{ route('payroll.yearlyPaySlip') }}"
                        class="nav-link @if ($Route[0] == 'payroll' && $Route[1] == 'yearlyPaySlip') active @endif">
                        <i class="icon-coins"></i><span>Yearly PaySlip</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('payroll.log.report'))
                <li class="nav-item">
                    <a href="{{ route('payroll.log.report') }}"
                        class="nav-link @if ($Route[0] == 'payroll' && $Route[1] == 'log') active @endif">
                        <i class="icon-gradient"></i><span>Master Report</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('payroll.fnfSettlement'))
                <li class="nav-item">
                    <a href="{{ route('payroll.fnfSettlement') }}"
                        class="nav-link @if ($Route[0] == 'payroll' && $Route[1] == 'fnfSettlement') active @endif">
                        <i class="icon-gradient"></i><span>FNF Settlement</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        @endif
        @endif --}}
        @if (Module::isModuleEnabled('Payroll'))
            @if (
                $menuRoles->assignedRoles('thresholdBenefitSetup.index') ||
                    $menuRoles->assignedRoles('taxSlab.index') ||
                    $menuRoles->assignedRoles('incomeSetup.index') ||
                    $menuRoles->assignedRoles('deductionSetup.index') ||
                    $menuRoles->assignedRoles('massIncrement.index') ||
                    $menuRoles->assignedRoles('employeeMassIncrement.index') ||
                    $menuRoles->assignedRoles('arrearAdjustment.index') ||
                    $menuRoles->assignedRoles('holdPayment.index') ||
                    $menuRoles->assignedRoles('payroll.index'))
                @php
                    $condition = 'false';
                    if (
                        $Route[0] == 'thresholdBenefitSetup' ||
                        $Route[0] == 'incomeSetup' ||
                        $Route[0] == 'deductionSetup' ||
                        $Route[0] == 'employeeSetup' ||
                        $Route[0] == 'taxSlab' ||
                        $Route[0] == 'payroll' ||
                        $Route[0] == 'massIncrement' ||
                        $Route[0] == 'employeeMassIncrement' ||
                        $Route[0] == 'arrearAdjustment' ||
                        $Route[0] == 'holdPayment' ||
                        $Route[0] == 'stopPayment'
                    ) {
                        $condition = 'true';
                    }
                @endphp
                @if ($condition)
                    <li
                        class="nav-item nav-item-submenu {{ $condition == 'true' ? 'nav-item-open nav-item-expanded' : '' }}">
                        <a href="#" class="nav-link {{ $condition == 'true' ? 'active' : '' }}">
                            <i class="icon-coins"></i> <span>Payroll Management</span>
                        </a>
                        <ul class="nav nav-group-sub" data-submenu-title="Payroll">
                            @if ($menuRoles->assignedRoles('thresholdBenefitSetup.index'))
                                <li class="nav-item">
                                    <a href="{{ route('thresholdBenefitSetup.index') }}"
                                        class="nav-link @if ($Route[0] == 'thresholdBenefitSetup') active @endif">
                                        <i class="icon-coins"></i><span>Threshold Benefit Setup</span>
                                    </a>
                                </li>
                            @endif
                            @if ($menuRoles->assignedRoles('taxSlab.index'))
                                <li class="nav-item">
                                    <a href="{{ route('taxSlab.index') }}"
                                        class="nav-link @if ($Route[0] == 'taxSlab') active @endif">
                                        <i class="icon-library2"></i><span>Tax Slab Setup</span>
                                    </a>
                                </li>
                            @endif
                            @if ($menuRoles->assignedRoles('incomeSetup.index'))
                                <li class="nav-item">
                                    <a href="{{ route('incomeSetup.index') }}"
                                        class="nav-link @if ($Route[0] == 'incomeSetup') active @endif">
                                        <i class="icon-coins"></i><span>Income Setup</span>
                                    </a>
                                </li>
                            @endif
                            @if ($menuRoles->assignedRoles('deductionSetup.index'))
                                <li class="nav-item">
                                    <a href="{{ route('deductionSetup.index') }}"
                                        class="nav-link @if ($Route[0] == 'deductionSetup') active @endif">
                                        <i class="icon-cash3"></i><span>Deduction Setup</span>
                                    </a>
                                </li>
                            @endif
                            {{-- @if ($menuRoles->assignedRoles('leaveAmountSetup.index'))
                <li class="nav-item">
                    <a href="{{ route('leaveAmountSetup.index') }}"
                        class="nav-link @if ($Route[0] == 'leaveAmountSetup') active @endif">
                        <i class="icon-cash3"></i><span>Leave Deduction Setup</span>
                    </a>
                </li>
                @endif --}}
                            @if ($menuRoles->assignedRoles('bonusSetup.index'))
                                <li class="nav-item">
                                    <a href="{{ route('bonusSetup.index') }}"
                                        class="nav-link @if ($Route[0] == 'bonusSetup') active @endif">
                                        <i class="icon-cash3"></i><span>Add On Income Setup</span>
                                    </a>
                                </li>
                            @endif
                            @if ($menuRoles->assignedRoles('taxExcludeSetup.index'))
                                <li class="nav-item">
                                    <a href="{{ route('taxExcludeSetup.index') }}"
                                        class="nav-link @if ($Route[0] == 'taxExcludeSetup') active @endif">
                                        <i class="icon-cash3"></i><span>Tax Exclude Setup</span>
                                    </a>
                                </li>
                            @endif
                            <li
                                class='nav-item nav-item-submenu  @if ($Route[0] == ' employeeSetup') nav-item-open
                    nav-item-expanded @endif'>
                                <a href="#"
                                    class="nav-link @if ($Route[0] == 'employeeSetup') active @endif"><i
                                        class="icon-users"></i> <span> Salary Scale Setup</span></a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item">
                                        <a href="{{ route('employeeSetup.grossSalary') }}"
                                            class="nav-link @if ($Route[0] == 'employeeSetup' && $Route[1] == 'grossSalary') active @endif ">
                                            <i class="icon-coins"></i><span>Gross Salary</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('employeeSetup.income') }}"
                                            class="nav-link @if ($Route[0] == 'employeeSetup' && $Route[1] == 'income') active @endif ">
                                            <i class="icon-coins"></i><span>Income</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('employeeSetup.deduction') }}"
                                            class="nav-link @if ($Route[0] == 'employeeSetup' && $Route[1] == 'deduction') active @endif">
                                            <i class="icon-cash3"></i><span>Deduction</span>
                                        </a>

                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('employeeSetup.taxExclude') }}"
                                            class="nav-link @if ($Route[0] == 'employeeSetup' && $Route[1] == 'taxExclude') active @endif">
                                            <i class="icon-cash3"></i><span>Tax Exclude</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('employeeSetup.bonus') }}"
                                            class="nav-link @if ($Route[0] == 'employeeSetup' && $Route[1] == 'bonus') active @endif">
                                            <i class="icon-cash3"></i><span>Add On Income</span>
                                        </a>
                                    </li>
                                </ul>

                            </li>
                            {{-- @if ($menuRoles->assignedRoles('massIncrement.index'))
                <li class="nav-item">
                    <a href="{{ route('massIncrement.index') }}"
                        class="nav-link @if ($Route[0] == 'massIncrement') active @endif">
                        <i class="icon-briefcase" aria-hidden="true"></i><span>Mass Increment</span>
                    </a>
                </li>
                @endif --}}
                            @if ($menuRoles->assignedRoles('employeeMassIncrement.index'))
                                <li class="nav-item">
                                    <a href="{{ route('employeeMassIncrement.index') }}"
                                        class="nav-link @if ($Route[0] == 'employeeMassIncrement') active @endif">
                                        <i class="icon-briefcase" aria-hidden="true"></i><span>Mass Increment</span>
                                    </a>
                                </li>
                            @endif
                            @if ($menuRoles->assignedRoles('arrearAdjustment.index'))
                                <li class="nav-item">
                                    <a href="{{ route('arrearAdjustment.index') }}"
                                        class="nav-link @if ($Route[0] == 'arrearAdjustment') active @endif">
                                        <i class="icon-cash3"></i><span>Arrear Adjustment</span>
                                    </a>
                                </li>
                            @endif
                            @if ($menuRoles->assignedRoles('holdPayment.index'))
                                <li class="nav-item">
                                    <a href="{{ route('holdPayment.index') }}"
                                        class="nav-link @if ($Route[0] == 'holdPayment') active @endif">
                                        <i class="icon-cash3"></i><span>Hold Payment</span>
                                    </a>
                                </li>
                            @endif
                            @if ($menuRoles->assignedRoles('stopPayment.index'))
                                <li class="nav-item">
                                    <a href="{{ route('stopPayment.index') }}"
                                        class="nav-link @if ($Route[0] == 'stopPayment') active @endif">
                                        <i class="icon-cash3"></i><span>Stop Payment</span>
                                    </a>
                                </li>
                            @endif
                            @if ($menuRoles->assignedRoles('bonus.index'))
                                <li class="nav-item">
                                    <a href="{{ route('bonus.index') }}"
                                        class="nav-link @if ($Route[0] == 'bonus' && $Route[1] == 'index') active @endif">
                                        <i class="icon-list2"></i><span>Add On Income List</span>
                                    </a>
                                </li>
                            @endif
                            @if ($menuRoles->assignedRoles('payroll.index'))
                                <li class="nav-item">
                                    <a href="{{ route('payroll.index') }}"
                                        class="nav-link @if ($Route[0] == 'payroll' && $Route[1] == 'index') active @endif">
                                        <i class="icon-list2"></i><span>Payroll List</span>
                                    </a>
                                </li>
                            @endif
                            {{-- @if ($menuRoles->assignedRoles('payroll.index'))
                <li class="nav-item">
                    <a href="{{ route('payroll.yearlyTax.report') }}"
                        class="nav-link @if ($Route[0] == 'payroll') active @endif">
                        <i class="icon-list2"></i><span>Yearly Forecast</span>
                    </a>
                </li>
                @endif --}}
                            @if ($menuRoles->assignedRoles('payroll.yearlyPaySlip'))
                                <li class="nav-item">
                                    <a href="{{ route('payroll.yearlyPaySlip') }}"
                                        class="nav-link @if ($Route[0] == 'payroll' && $Route[1] == 'yearlyPaySlip') active @endif">
                                        <i class="icon-coins"></i><span>Yearly PaySlip</span>
                                    </a>
                                </li>
                            @endif
                            @if ($menuRoles->assignedRoles('payroll.log.report'))
                                <li class="nav-item">
                                    <a href="{{ route('payroll.log.report') }}"
                                        class="nav-link @if ($Route[0] == 'payroll' && $Route[1] == 'log') active @endif">
                                        <i class="icon-gradient"></i><span>Master Report</span>
                                    </a>
                                </li>
                            @endif
                            @if ($menuRoles->assignedRoles('payroll.fnfSettlement'))
                                <li class="nav-item">
                                    <a href="{{ route('payroll.fnfSettlement') }}"
                                        class="nav-link @if ($Route[0] == 'payroll' && $Route[1] == 'fnfSettlement') active @endif">
                                        <i class="icon-gradient"></i><span>F&F Settlement</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('payroll.fnfSettlement-reports') }}"
                                        class="nav-link @if ($Route[0] == 'payroll' && $Route[1] == 'fnfSettlement-reports') active @endif">
                                        <i class="icon-gradient"></i><span>F&F Reports</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('payroll.fnfSettlement-projection-reports') }}"
                                        class="nav-link @if ($Route[0] == 'payroll' && $Route[1] == 'fnfSettlement-projection-reports') active @endif">
                                        <i class="icon-gradient"></i><span>F&F Projection Reports</span>
                                    </a>
                                </li>
                            @endif

                            <li
                                class='nav-item nav-item-submenu  @if ($Route[0] == ' allowanceReport.index') nav-item-open
                    nav-item-expanded @endif'>
                                <a href="#"
                                    class="nav-link @if ($Route[0] == 'allowanceReport.index') active @endif"><i
                                        class="icon-users"></i> <span>Allowance Report</span></a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item">
                                        <a href="{{ route('allowanceReport.index', ['type' => 'food']) }}"
                                            class="nav-link @if (request('type') === 'food') active @endif">
                                            <i class="icon-coins"></i><span>Food Allowance</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('allowanceReport.index', ['type' => 'shift']) }}"
                                            class="nav-link @if (request('type') === 'shift') active @endif">
                                            <i class="icon-coins"></i><span>Shift Allowance</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('allowanceReport.index', ['type' => 'night']) }}"
                                            class="nav-link @if (request('type') === 'night') active @endif">
                                            <i class="icon-cash3"></i><span>Night Allowance</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('allowanceReport.index', ['type' => 'holiday']) }}"
                                            class="nav-link @if (request('type') === 'holiday') active @endif">
                                            <i class="icon-cash3"></i><span>Holiday Allowance</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('allowanceReport.allReport') }}"
                                            class="nav-link @if (route('allowanceReport.allReport')) active @endif">
                                            <i class="icon-cash3"></i><span>All Allowance Report</span>
                                        </a>
                                    </li>


                                </ul>

                            </li>
                        </ul>
                    </li>
                @endif
            @endif
        @endif

        @if (Module::isModuleEnabled('Payroll'))
            @php
                $condition = 'false';
                if ($Route[0] == 'reports') {
                    $condition = 'true';
                }
            @endphp
            @if ($condition)
                <li
                    class='nav-item nav-item-submenu  @if ($Route[0] == ' reports') nav-item-open nav-item-expanded @endif'>
                    <a href="#" class="nav-link @if ($Route[0] == 'reports') active @endif"><i
                            class="icon-calendar2"></i>
                        <span>Payroll Reports</span></a>
                    <ul class="nav nav-group-sub">
                        @if ($menuRoles->assignedRoles('reports.payrollReport'))
                            <li class="nav-item">
                                <a href="{{ route('reports.payrollReport') }}"
                                    class="nav-link @if ($Route[0] == 'reports' && $Route[1] == 'payrollReport') active @endif ">
                                    <i class="icon-calendar3"></i><span>Payroll Report</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('reports.branchPayrollReport'))
                            <li class="nav-item">
                                <a href="{{ route('reports.branchPayrollReport') }}"
                                    class="nav-link @if ($Route[0] == 'reports' && $Route[1] == 'branchPayrollReport') active @endif ">
                                    <i class="icon-calendar3"></i><span>Unit Report</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('reports.branchSummaryReport'))
                            <li class="nav-item">
                                <a href="{{ route('reports.branchSummaryReport') }}"
                                    class="nav-link @if ($Route[0] == 'reports' && $Route[1] == 'branchSummaryReport') active @endif ">
                                    <i class="icon-calendar3"></i><span>Unit Summary Report</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('reports.citReport'))
                            <li class="nav-item">
                                <a href="{{ route('reports.citReport') }}"
                                    class="nav-link @if ($Route[0] == 'reports' && $Route[1] == 'citReport') active @endif ">
                                    <i class="icon-calendar3"></i><span>CIT Report</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('reports.ssfReport'))
                            <li class="nav-item">
                                <a href="{{ route('reports.ssfReport') }}"
                                    class="nav-link @if ($Route[0] == 'reports' && $Route[1] == 'ssfReport') active @endif ">
                                    <i class="icon-calendar3"></i><span>SSF Report</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('reports.pfReport'))
                            <li class="nav-item">
                                <a href="{{ route('reports.pfReport') }}"
                                    class="nav-link @if ($Route[0] == 'reports' && $Route[1] == 'pfReport') active @endif ">
                                    <i class="icon-calendar3"></i><span>PF Report</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('reports.tdsReports'))
                            <li class="nav-item">
                                <a href="{{ route('reports.tdsReports') }}"
                                    class="nav-link @if ($Route[0] == 'reports' && $Route[1] == 'tdsReports') active @endif ">
                                    <i class="icon-calendar3"></i><span>TDS Report</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('reports.annualProjectionReport'))
                            <li class="nav-item">
                                <a href="{{ route('reports.annualProjectionReport') }}"
                                    class="nav-link @if ($Route[0] == 'reports' && $Route[1] == 'annualProjectionReport') active @endif ">
                                    <i class="icon-calendar3"></i><span>Annual Projection Report</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif



        @if (Module::isModuleEnabled('Advance'))
            @if ($menuRoles->assignedRoles('advance.index') || $menuRoles->assignedRoles('advance.paymentLedger'))
                @php
                    $condition = 'false';
                    if ($Route[0] == 'advance') {
                        $condition = 'true';
                    }
                @endphp
                @if ($condition)
                    <li
                        class="nav-item nav-item-submenu {{ $condition == 'true' ? 'nav-item-open nav-item-expanded' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="icon-coins"></i> <span>Advance Management</span>
                        </a>
                        <ul class="nav nav-group-sub" data-submenu-title="Payroll">
                            @if ($menuRoles->assignedRoles('advance.index'))
                                <li class="nav-item">
                                    <a href="{{ route('advance.index') }}"
                                        class="nav-link @if ($Route[0] == 'advance' && $Route[1] == 'index') active @endif">
                                        <i class="icon-coins"></i><span>Advance</span>
                                    </a>
                                </li>
                            @endif
                            @if ($menuRoles->assignedRoles('advance.paymentLedger'))
                                <li class="nav-item">
                                    <a href="{{ route('advance.paymentLedger') }}"
                                        class="nav-link @if ($Route[0] == 'advance' && $Route[1] == 'paymentLedger') active @endif">
                                        <i class="icon-coins"></i><span>Payment Ledger</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            @endif
        @endif



        @if (Module::isModuleEnabled('Loan'))
            @if ($menuRoles->assignedRoles('loan.index'))
                <li
                    class="nav-item nav-item-submenu {{ request()->routeIs('loan.index') ? 'nav-item-open nav-item-expanded' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="icon-coin-dollar"></i> <span>Loan Management</span>
                    </a>
                    <ul class="nav nav-group-sub" data-submenu-title="LoanMenus">
                        @if ($menuRoles->assignedRoles('loan.index'))
                            <li class="nav-item">
                                <a href="{{ route('loan.index') }}"
                                    class="nav-link {{ request()->routeIs('loan.index') ? 'active' : '' }}">
                                    <i class="icon-coin-dollar"></i><span>All Loans</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif





        @if (Module::isModuleEnabled('Notice'))
            @if ($menuRoles->assignedRoles('notice.index'))
                <li class="nav-item">
                    <a href="{{ route('notice.index') }}"
                        class="nav-link @if ($Route[0] == 'notice') active @endif">
                        <i class="icon-info22"></i><span>Notice</span>
                    </a>
                </li>
            @endif
        @endif

        @if (Module::isModuleEnabled('Warning'))
            @if ($menuRoles->assignedRoles('warning.index'))
                <li class="nav-item">
                    <a href="{{ route('warning.index') }}"
                        class="nav-link @if ($Route[0] == 'warning') active @endif">
                        <i class="icon-info22"></i><span>Warnings</span>
                    </a>
                </li>
            @endif
        @endif

        @if (Module::isModuleEnabled('Event'))

            @if ($menuRoles->assignedRoles('event.index') || $menuRoles->assignedRoles('holiday.index'))
                @php
                    $condition = 'false';
                    if ($Route[0] == 'event' || $Route[0] == 'holiday') {
                        $condition = 'true';
                    }
                @endphp
                @if ($condition)
                    <li
                        class="nav-item nav-item-submenu {{ $condition == 'true' ? 'nav-item-open nav-item-expanded' : '' }}">
                        <a href="#" class="nav-link {{ $condition == 'true' ? 'active' : '' }}">
                            <i class="icon-newspaper"></i> <span>Event & Holiday</span>
                        </a>
                        <ul class="nav nav-group-sub" data-submenu-title="Event & Holiday">
                            @if ($menuRoles->assignedRoles('event.index'))
                                <li class="nav-item">
                                    <a href="{{ route('event.index') }}"
                                        class="nav-link @if ($Route[0] == 'event') active @endif">
                                        <i class="icon-newspaper"></i><span>Event</span>
                                    </a>
                                </li>
                            @endif

                            @if ($menuRoles->assignedRoles('holiday.index'))
                                <li class="nav-item">
                                    <a href="{{ route('holiday.index', ['fiscal_year_id' => getCurrentFiscalYearId()]) }}"
                                        class="nav-link @if ($Route[0] == 'holiday') active @endif">
                                        <i class="icon-road"></i><span>Holiday</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            @endif
        @endif


        @if (Module::isModuleEnabled('Worklog'))

            @php
                $worklogRoles = $Route[0] == 'worklog';
            @endphp
            @if ($menuRoles->assignedRoles('worklog.index'))
                @if (auth()->user()->user_type == 'employee')
                    <li class="nav-item">
                        <a href="{{ route('worklog.index') }}"
                            class="nav-link @if ($Route[0] == 'worklog') active @endif">
                            <i class="icon-stack-text"></i><span>Work Log</span>
                        </a>
                    </li>
                @else
                    <li
                        class='nav-item nav-item-submenu {{ $worklogRoles ? ' nav-item-open nav-item-expanded' : '' }} '>
                        <a href="#" class="nav-link {{ $worklogRoles ? ' active' : '' }}"><i
                                class="icon-stack-text"></i> <span>Work Log</span></a>
                        <ul class="nav nav-group-sub" data-submenu-title="PMS">

                            @if ($menuRoles->assignedRoles('worklog.index'))
                                <li class="nav-item">
                                    <a href="{{ route('worklog.index') }}"
                                        class="nav-link @if ($Route[0] == 'worklog') active @endif">
                                        <i class="icon-stack-text"></i><span>List</span>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif
            @endif
        @endif


        @if (Module::isModuleEnabled('BoardingTask'))

            @if ($menuRoles->assignedRoles('boardingTask.index'))
                <li class="nav-item">
                    <a href="{{ route('boardingTask.index') }}"
                        class="nav-link @if ($Route[0] == 'boardingTask') active @endif">
                        <i class="icon-clipboard6"></i><span>Boarding Task</span>
                    </a>
                </li>
            @endif
        @endif


        @if (Module::isModuleEnabled('Onboarding'))

            @if (
                $menuRoles->assignedRoles('mrf.index') ||
                    $menuRoles->assignedRoles('applicant.index') ||
                    $menuRoles->assignedRoles('interviewLevel.index') ||
                    $menuRoles->assignedRoles('interview.index') ||
                    $menuRoles->assignedRoles('evaluation.index') ||
                    $menuRoles->assignedRoles('offerLetter.index') ||
                    $menuRoles->assignedRoles('onboard.index'))
                @php
                    $condition = 'false';
                    if (
                        $Route[0] == 'mrf' ||
                        $Route[0] == 'applicant' ||
                        $Route[0] == 'interviewLevel' ||
                        $Route[0] == 'interview' ||
                        $Route[0] == 'evaluation' ||
                        $Route[0] == 'offerLetter' ||
                        $Route[0] == 'onboard'
                    ) {
                        $condition = 'true';
                    }
                @endphp
                <li
                    class="nav-item nav-item-submenu {{ $condition == 'true' ? 'nav-item-open nav-item-expanded' : '' }}">
                    <a href="#" class="nav-link @if ($condition == 'true') active @endif">
                        <i class="icon-books"></i> <span>Onboarding</span>
                    </a>
                    <ul class="nav nav-group-sub">
                        @if ($menuRoles->assignedRoles('mrf.index'))
                            <li class="nav-item">
                                <a href="{{ route('mrf.index') }}"
                                    class="nav-link @if ($Route[0] == 'mrf') active @endif">
                                    <i class="icon-design"></i><span>MRF</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('applicant.index'))
                            <li class="nav-item">
                                <a href="{{ route('applicant.index') }}"
                                    class="nav-link @if ($Route[0] == 'applicant') active @endif">
                                    <i class="icon-users"></i><span>Applicant</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('interviewLevel.index'))
                            <li class="nav-item">
                                <a href="{{ route('interviewLevel.index') }}"
                                    class="nav-link @if ($Route[0] == 'interviewLevel') active @endif">
                                    <i class="icon-question4"></i><span>Questionnaire</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('interview.index'))
                            <li class="nav-item">
                                <a href="{{ route('interview.index') }}"
                                    class="nav-link @if ($Route[0] == 'interview') active @endif">
                                    <i class="icon-bubbles9"></i><span>Interview</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('evaluation.index'))
                            <li class="nav-item">
                                <a href="{{ route('evaluation.index') }}"
                                    class="nav-link @if ($Route[0] == 'evaluation') active @endif">
                                    <i class="icon-pen2"></i><span>Evaluation</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('offerLetter.index'))
                            <li class="nav-item">
                                <a href="{{ route('offerLetter.index') }}"
                                    class="nav-link @if ($Route[0] == 'offerLetter') active @endif">
                                    <i class="icon-profile"></i><span>Offer Letter</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('onboard.index'))
                            <li class="nav-item">
                                <a href="{{ route('onboard.index') }}"
                                    class="nav-link @if ($Route[0] == 'onboard') active @endif">
                                    <i class="icon-user-check"></i><span>Onboard</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif

        @if (Module::isModuleEnabled('Offboarding'))
            @if ($menuRoles->assignedRoles('clearance.index') || $menuRoles->assignedRoles('resignation.index'))
                @php
                    $condition = 'false';
                    if ($Route[0] == 'resignation' || $Route[0] == 'clearance') {
                        $condition = 'true';
                    }
                @endphp
                <li
                    class="nav-item nav-item-submenu {{ $condition == 'true' ? 'nav-item-open nav-item-expanded' : '' }}">
                    <a href="#" class="nav-link @if ($condition == 'true') active @endif">
                        <i class="icon-cube4"></i> <span>Offboarding</span>
                    </a>
                    <ul class="nav nav-group-sub">
                        @if ($menuRoles->assignedRoles('clearance.index'))
                            <li class="nav-item">
                                <a href="{{ route('clearance.index') }}"
                                    class="nav-link @if ($Route[0] == 'clearance') active @endif">
                                    <i class="icon-bubble-lines4"></i><span>Clearance Setup</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('resignation.index'))
                            <li class="nav-item">
                                <a href="{{ route('resignation.index') }}"
                                    class="nav-link @if ($Route[0] == 'resignation' && $Route[1] == 'index') active @endif">
                                    <i class="icon-pen-minus"></i><span>Resignation</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif

        @if (Module::isModuleEnabled('Appraisal'))
            @if (
                $menuRoles->assignedRoles('score.index') ||
                    $menuRoles->assignedRoles('ratingScale.index') ||
                    $menuRoles->assignedRoles('competenceLibrary.index') ||
                    $menuRoles->assignedRoles('competence.index') ||
                    $menuRoles->assignedRoles('appraisal.index') ||
                    $menuRoles->assignedRoles('questionnaire.index'))
                <li
                    class='nav-item nav-item-submenu {{ $Route[0] == ' score' ||
                    $Route[0] == 'ratingScale' ||
                    $Route[0] == 'competenceLibrary' ||
                    $Route[0] == 'competence' ||
                    $Route[0] == 'appraisal' ||
                    $Route[0] == 'questionnaire'
                        ? 'nav-item-open nav-item-expanded'
                        : '' }}'>
                    <a href="#" class="nav-link @if (
                        $Route[0] == 'score' ||
                            $Route[0] == 'ratingScale' ||
                            $Route[0] == 'competenceLibrary' ||
                            $Route[0] == 'competence' ||
                            $Route[0] == 'appraisal' ||
                            // $Route[0] == 'appraisalReport' ||
                            $Route[0] == 'questionnaire') active @endif">
                        <i class="icon-medal-first "></i> <span>Appraisal</span>
                    </a>
                    <ul class="nav nav-group-sub">

                        @if ($menuRoles->assignedRoles('score.index'))
                            <li
                                class='nav-item nav-item-submenu  @if (
                                    $Route[0] == ' score' ||
                                        $Route[0] == 'ratingScale' ||
                                        $Route[0] == 'competenceLibrary' ||
                                        $Route[0] == 'competence' ||
                                        $Route[0] == 'questionnaire') nav-item-open nav-item-expanded @endif'>
                                <a href="#"
                                    class="nav-link @if (
                                        $Route[0] == 'score' ||
                                            $Route[0] == 'ratingScale' ||
                                            $Route[0] == 'competenceLibrary' ||
                                            $Route[0] == 'questionnaire' ||
                                            $Route[0] == 'competence') active @endif"><i
                                        class="icon-users"></i>
                                    <span>Setup</span></a>
                                <ul class="nav nav-group-sub">
                                    @if ($menuRoles->assignedRoles('score.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('score.index') }}"
                                                class="nav-link @if ($Route[0] == 'score' && $Route[1] == 'index') active @endif">
                                                <i class="icon-square-up"></i><span>Score Management</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if ($menuRoles->assignedRoles('ratingScale.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('ratingScale.index') }}"
                                                class="nav-link @if ($Route[0] == 'ratingScale' && $Route[1] == 'index') active @endif">
                                                <i class="icon-stats-bars2 "></i><span>Appraisal Rating Scale</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if ($menuRoles->assignedRoles('competenceLibrary.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('competenceLibrary.index') }}"
                                                class="nav-link @if ($Route[0] == 'competenceLibrary' && $Route[1] == 'index') active @endif">
                                                <i class="icon-books"></i><span>Competence Library</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if ($menuRoles->assignedRoles('competence.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('competence.index') }}"
                                                class="nav-link @if ($Route[0] == 'competence' && $Route[1] == 'index') active @endif">
                                                <i class="icon-books"></i><span>Competencies</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if ($menuRoles->assignedRoles('questionnaire.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('questionnaire.index') }}"
                                                class="nav-link @if ($Route[0] == 'questionnaire' && $Route[1] == 'index') active @endif">
                                                <i class="icon-file-text"></i><span>Forms</span>
                                            </a>
                                        </li>
                                    @endif

                                </ul>

                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('appraisal.index'))
                            <li
                                class='nav-item nav-item-submenu  @if ($Route[0] == ' appraisal' || $Route[0] == 'performanceEvaluationSummary') nav-item-open nav-item-expanded @endif'>
                                <a href="#"
                                    class="nav-link @if ($Route[0] == 'appraisal' || $Route[0] == 'performanceEvaluationSummary') active @endif"><i
                                        class="icon-cogs"></i> <span>Roll Out</span></a>
                                <ul class="nav nav-group-sub">
                                    @if ($menuRoles->assignedRoles('appraisal.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('appraisal.index') }}"
                                                class="nav-link @if ($Route[0] == 'appraisal') active @endif">
                                                <i class="icon-cash"></i><span>Appraisal</span>
                                            </a>
                                        </li>
                                    @endif


                                    {{-- @if ($menuRoles->assignedRoles('appraisalReport'))
                        <li class="nav-item">
                            <a href="{{ route('appraisalReport') }}"
                                class="nav-link @if ($Route[0] == 'appraisalReport') active @endif">
                                <i class="icon-stats-bars2 "></i><span>Appraisal Report</span>
                            </a>
                        </li>
                        @endif --}}

                                    {{-- @if ($menuRoles->assignedRoles('performanceEvaluationSummary'))
                        <li class="nav-item">
                            <a href="{{ route('performanceEvaluationSummary') }}"
                                class="nav-link @if ($Route[0] == 'performanceEvaluationSummary') active @endif">
                                <i class="icon-stats-bars2 "></i><span>Appraisal Report</span>
                            </a>
                        </li>
                        @endif --}}

                                </ul>

                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif

        @if (Module::isModuleEnabled('PMS'))
            @if (
                $menuRoles->assignedRoles('kra.index') ||
                    $menuRoles->assignedRoles('kpi.index') ||
                    $menuRoles->assignedRoles('target.index') ||
                    $menuRoles->assignedRoles('employee-target.view') ||
                    $menuRoles->assignedRoles('PMSViewFinalReport'))
                <li
                    class='nav-item nav-item-submenu @if (
                        $Route[0] == ' kra' ||
                            $Route[0] == 'kpi' ||
                            $Route[0] == 'target' ||
                            $Route[0] == 'employee-target' ||
                            $Route[0] == 'PMSViewFinalReport') nav-item-open nav-item-expanded @endif'>
                    <a href="#" class="nav-link @if (
                        $Route[0] == 'kra' ||
                            $Route[0] == 'kpi' ||
                            $Route[0] == 'target' ||
                            $Route[0] == 'employee-target' ||
                            $Route[0] == 'PMSViewFinalReport') active @endif"><i
                            class="icon-stats-growth"></i>
                        <span>PMS</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="PMS">
                        <li
                            class='nav-item nav-item-submenu  @if ($Route[0] == ' kra' || $Route[0] == 'kpi' || $Route[0] == 'target' || $Route[0] == 'employee-target') nav-item-open nav-item-expanded @endif'>
                            <a href="#" class="nav-link @if ($Route[0] == 'kra' || $Route[0] == 'kpi' || $Route[0] == 'target' || $Route[0] == 'employee-target') active @endif"><i
                                    class="icon-users"></i> <span>Setup</span></a>
                            <ul class="nav nav-group-sub">
                                @if ($menuRoles->assignedRoles('kra.index'))
                                    <li class="nav-item">
                                        <a href="{{ route('kra.index') }}"
                                            class="nav-link @if ($Route[0] == 'kra' && $Route[1] == 'index') active @endif">
                                            <i class="icon-cog4"></i><span>KRA</span>
                                        </a>
                                    </li>
                                @endif
                                @if ($menuRoles->assignedRoles('kpi.index'))
                                    <li class="nav-item">
                                        <a href="{{ route('kpi.index') }}"
                                            class="nav-link @if ($Route[0] == 'kpi') active @endif">
                                            <i class="icon-cogs"></i><span>KPI</span>
                                        </a>
                                    </li>
                                @endif
                                @if ($menuRoles->assignedRoles('target.index'))
                                    <li class="nav-item">
                                        <a href="{{ route('target.index') }}"
                                            class="nav-link @if ($Route[0] == 'target') active @endif">
                                            <i class="icon-target"></i><span>Target</span>
                                        </a>
                                    </li>
                                @endif
                                @if ($menuRoles->assignedRoles('set-form.index'))
                                    <li class="nav-item">
                                        <a href="{{ route('set-form.index') }}"
                                            class="nav-link @if ($Route[0] == 'setForm') active @endif">
                                            <i class="icon-cog52"></i><span>Set Form</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        @if ($menuRoles->assignedRoles('PMSViewFinalReport'))
                            <li class="nav-item">
                                <a href="{{ route('PMSViewFinalReport') }}"
                                    class="nav-link @if ($Route[0] == 'PMSViewFinalReport') active @endif">
                                    <i class="icon-eye"></i><span>View Final Report</span>
                                </a>
                            </li>
                        @endif
                        {{-- <li class='nav-item nav-item-submenu  @if ($Route[0] == ' employee-target.view' || $Route[0] == 'PMSViewFinalReport') nav-item-open nav-item-expanded @endif'>
                    <a href="#"
                        class="nav-link @if ($Route[0] == 'employee-target.view' || $Route[0] == 'PMSViewFinalReport') active @endif"><i
                            class="icon-cogs"></i> <span>Roll Out</span></a>
                    <ul class="nav nav-group-sub">
                        @if ($menuRoles->assignedRoles('employee-target.view'))
                        <li class="nav-item">
                            <a href="{{ route('employee-target.view') }}"
                                class="nav-link @if ($Route[0] == 'employee-target') active @endif">
                                <i class="icon-list2"></i><span>View Employee Target</span>
                            </a>
                        </li>
                        @endif

                        @if ($menuRoles->assignedRoles('PMSViewFinalReport'))
                        <li class="nav-item">
                            <a href="{{ route('PMSViewFinalReport') }}"
                                class="nav-link @if ($Route[0] == 'PMSViewFinalReport') active @endif">
                                <i class="icon-eye"></i><span>View Final Report</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li> --}}
                    </ul>
                </li>
            @endif
        @endif


        @if (Module::isModuleEnabled('Training'))
            @if (
                $menuRoles->assignedRoles('training.index') ||
                    $menuRoles->assignedRoles('training-report') ||
                    $menuRoles->assignedRoles('training-MIS-report') ||
                    $menuRoles->assignedRoles('training-attendees-detail-report') ||
                    $menuRoles->assignedRoles('training-annual-calendar-report'))
                <li
                    class='nav-item nav-item-submenu @if (
                        $Route[0] == ' training' ||
                            $Route[0] == 'training-report' ||
                            $Route[0] == 'training-MIS-report' ||
                            $Route[0] == 'training-attendees-detail-report' ||
                            $Route[0] == 'training-annual-calendar-report') nav-item-open nav-item-expanded @endif'>
                    <a href="#" class="nav-link @if (
                        $Route[0] == 'training' ||
                            $Route[0] == 'training-report' ||
                            $Route[0] == 'training-MIS-report' ||
                            $Route[0] == 'training-attendees-detail-report' ||
                            $Route[0] == 'training-annual-calendar-report') active @endif"><i
                            class="icon-collaboration"></i> <span>Training</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Training">

                        @if ($menuRoles->assignedRoles('training.index'))
                            <li class="nav-item">
                                <a href="{{ route('training.index') }}"
                                    class="nav-link @if ($Route[0] == 'training') active @endif">
                                    <i class="icon-reading"></i><span>Training</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('training-report'))
                            <li class="nav-item">
                                <a href="{{ route('training-report') }}"
                                    class="nav-link @if ($Route[0] == 'training-report') active @endif">
                                    <i class="icon-eye"></i><span>View Report</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('training-MIS-report'))
                            <li class="nav-item">
                                <a href="{{ route('training-MIS-report') }}"
                                    class="nav-link @if ($Route[0] == 'training-MIS-report') active @endif">
                                    <i class="icon-stack"></i><span>MIS Report</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('training-attendees-detail-report'))
                            <li class="nav-item">
                                <a href="{{ route('training-attendees-detail-report') }}"
                                    class="nav-link @if ($Route[0] == 'training-attendees-detail-report') active @endif">
                                    <i class="icon-stack"></i><span>Attendees Detail Report</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('training-annual-calendar-report'))
                            <li class="nav-item">
                                <a href="{{ route('training-annual-calendar-report') }}"
                                    class="nav-link @if ($Route[0] == 'training-annual-calendar-report') active @endif">
                                    <i class="icon-stack"></i><span> Annual Training Report</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif
        @endif

        @if (Module::isModuleEnabled('Document'))
            @if ($menuRoles->assignedRoles('document.index') || $menuRoles->assignedRoles('shared-list.document'))
                <li
                    class='nav-item nav-item-submenu @if ($Route[0] == ' document' or $Route[0] == 'shared-list') nav-item-open
            nav-item-expanded @endif'>
                    <a href="#" class="nav-link @if ($Route[0] == 'document' or $Route[0] == 'shared-list') active @endif"><i
                            class="icon-folder"></i> <span>Document</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Document">

                        @if ($menuRoles->assignedRoles('document.index'))
                            <li class="nav-item">
                                <a href="{{ route('document.index') }}"
                                    class="nav-link @if ($Route[0] == 'document') active @endif">
                                    <i class="icon-folder"></i><span>My Document</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('shared-list.document'))
                            <li class="nav-item">
                                <a href="{{ route('shared-list.document') }}"
                                    class="nav-link @if ($Route[0] == 'shared-list') active @endif">
                                    <i class="icon-eye"></i><span>Shared With Me</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif

        @if (Module::isModuleEnabled('Template'))
            @if (
                $menuRoles->assignedRoles('cheatSheet.index') ||
                    $menuRoles->assignedRoles('templateType.index') ||
                    $menuRoles->assignedRoles('letterManagement.index') ||
                    $menuRoles->assignedRoles('template.index'))
                <li
                    class='nav-item nav-item-submenu @if (
                        $Route[0] == ' cheatSheet' or
                            $Route[0] == 'templateType' or
                            $Route[0] == 'template' or
                            $Route[0] == 'letterManagement') nav-item-open nav-item-expanded @endif'>
                    {{-- class='nav-item nav-item-submenu @if ($Route[0] == 'cheatSheet' or $Route[0] == 'templateType' or $Route[0] == 'template') nav-item-open nav-item-expanded @endif'> --}}
                    <a href="#" class="nav-link @if ($Route[0] == 'cheatSheet' or $Route[0] == 'templateType' or $Route[0] == 'template') active @endif"><i
                            class="icon-stack-empty"></i> <span>Templates</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="PMS">
                        @if ($menuRoles->assignedRoles('cheatSheet.index'))
                            <li class="nav-item">
                                <a href="{{ route('cheatSheet.index') }}"
                                    class="nav-link @if ($Route[0] == 'cheatSheet') active @endif">
                                    <i class="icon-info22"></i><span>Cheat Sheet</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('templateType.index'))
                            <li class="nav-item">
                                <a href="{{ route('templateType.index') }}"
                                    class="nav-link @if ($Route[0] == 'templateType') active @endif">
                                    <i class="icon-menu5"></i><span>Template Type</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('template.index'))
                            <li class="nav-item">
                                <a href="{{ route('template.index') }}"
                                    class="nav-link @if ($Route[0] == 'template') active @endif">
                                    <i class="icon-file-text"></i><span>Template</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('letterManagement.index'))
                            <li class="nav-item">
                                <a href="{{ route('letterManagement.index') }}"
                                    class="nav-link @if ($Route[0] == 'letterManagement') active @endif">
                                    <i class="icon-file-text"></i><span>Letter Management</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif
        {{--
        @php
        $condition = false;
        if ($Route[0] == 'mrfApprovalFlow' || $Route[0] == 'leaveDeductionSetup' || $Route[0] == 'siteAttendance') {
        $condition = true;
        }
        @endphp
        @if ($menuRoles->assignedRoles('mrfApprovalFlow.index') || $menuRoles->assignedRoles('leaveDeductionSetup.index') || $menuRoles->assignedRoles('siteAttendance.roleSetup'))
        <li class="nav-item nav-item-submenu {{ $condition == true ? 'nav-item-open nav-item-expanded' : '' }}">
            <a href="#" class="nav-link {{ $condition == true ? 'active' : '' }}">
                <i class="icon-cogs"></i> <span>Setup</span>
            </a>
            <ul class="nav nav-group-sub">
                @if (Module::isModuleEnabled('Onboarding'))
                @if ($menuRoles->assignedRoles('mrfApprovalFlow.index'))
                <li class="nav-item">
                    <a href="{{ route('mrfApprovalFlow.index') }}"
                        class="nav-link @if ($Route[0] == 'mrfApprovalFlow') active @endif">
                        <i class="icon-ladder"></i><span>MRF Approval Flow</span>
                    </a>
                </li>
                @endif
                @endif
                @if ($menuRoles->assignedRoles('leaveDeductionSetup.index'))
                <li class="nav-item">
                    <a href="{{ route('leaveDeductionSetup.index') }}"
                        class="nav-link @if ($Route[0] == 'leaveDeductionSetup') active @endif">
                        <i class="icon-cog3"></i><span>Leave Deduction Setup</span>
                    </a>
                </li>
                @endif

                @if ($menuRoles->assignedRoles('siteAttendance.roleSetup'))
                <li class="nav-item">
                    <a href="{{ route('siteAttendance.roleSetup') }}"
                        class="nav-link @if ($Route[0] == 'siteAttendance' && $Route[1] == 'roleSetup') active @endif">
                        <i class="icon-hand"></i><span>Division/Site Attendance Role Setup</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif --}}

        @if (Module::isModuleEnabled('Grievance'))
            @if ($menuRoles->assignedRoles('grievance.index'))
                <li class="nav-item">
                    <a href="{{ route('grievance.index') }}"
                        class="nav-link @if ($Route[0] == 'grievance') active @endif">
                        <i class="icon-clipboard6"></i><span>Grievance List</span>
                    </a>
                </li>
            @endif
        @endif
        @if (Module::isModuleEnabled('MeetingRoom'))
            @if ($menuRoles->assignedRoles('meetingRoom.index'))
                <li class="nav-item">
                    <a href="{{ route('meetingRoom.index') }}"
                        class="nav-link @if ($Route[0] == 'meetingRoom') active @endif">
                        <i class="icon-clipboard6"></i><span>Meeting Rooms</span>
                    </a>
                </li>
            @endif
        @endif

        @if (Module::isModuleEnabled('BulkUpload'))
            @if (
                $menuRoles->assignedRoles('bulkupload.familyDetail') ||
                    $menuRoles->assignedRoles('bulkupload.emergencyDetail') ||
                    $menuRoles->assignedRoles('bulkupload.benefitDetail') ||
                    $menuRoles->assignedRoles('bulkupload.bankDetail') ||
                    $menuRoles->assignedRoles('bulkupload.educationDetail') ||
                    $menuRoles->assignedRoles('bulkupload.previousJobDetail') ||
                    $menuRoles->assignedRoles('bulkupload.contractDetail') ||
                    $menuRoles->assignedRoles('bulkupload.medicalDetail') ||
                    $menuRoles->assignedRoles('bulkupload.leaveDetail') ||
                    $menuRoles->assignedRoles('bulkupload.leaveHistoryDetail') ||
                    $menuRoles->assignedRoles('bulkupload.researchDetail') ||
                    $menuRoles->assignedRoles('bulkupload.visaImmigrationDetail') ||
                    $menuRoles->assignedRoles('bulkupload.documentDetail') ||
                    $menuRoles->assignedRoles('bulkupload.attendanceLog') ||
                    // ||$menuRoles->assignedRoles('bulkupload.performanceDetail')
                    $menuRoles->assignedRoles('bulkupload.employeeDetail') ||
                    $menuRoles->assignedRoles('bulkupload.darbandis') ||
                    $menuRoles->assignedRoles('bulkupload.holiday') ||
                    $menuRoles->assignedRoles('bulkupload.user') ||
                    $menuRoles->assignedRoles('bulkupload.labour') ||
                    $menuRoles->assignedRoles('bulkupload.carrierMobility') ||
                    $menuRoles->assignedRoles('bulkupload.attendanceOverStay'))
                @php
                    $bulkUploadCondition = 'false';
                    if ($Route[0] == 'bulkupload') {
                        $bulkUploadCondition = 'true';
                    }
                @endphp
            @endif
        @endif


        {{-- Asset Module Start --}}
        @if (Module::isModuleEnabled('Asset'))
            @if (
                $menuRoles->assignedRoles('asset.index') ||
                    $menuRoles->assignedRoles('assetQuantity.index') ||
                    $menuRoles->assignedRoles('assetAllocate.index'))
                @php
                    $condition = 'false';
                    if ($Route[0] == 'asset' || $Route[0] == 'assetQuantity' || $Route[0] == 'assetAllocate') {
                        $condition = 'true';
                    }
                @endphp
                <li
                    class="nav-item nav-item-submenu {{ $condition == 'true' ? 'nav-item-open nav-item-expanded' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="icon-section"></i> <span>Asset Management</span>
                    </a>
                    <ul class="nav nav-group-sub">
                        @if ($menuRoles->assignedRoles('asset.index'))
                            <li class="nav-item">
                                <a href="{{ route('asset.index') }}"
                                    class="nav-link @if ($Route[0] == 'asset') active @endif">
                                    <i class="icon-diff-added"></i><span>Assets</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('assetQuantity.index'))
                            <li class="nav-item">
                                <a href="{{ route('assetQuantity.index') }}"
                                    class="nav-link @if ($Route[0] == 'assetQuantity') active @endif">
                                    <i class="icon-list-numbered"></i><span>Stocks</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('assetAllocate.index'))
                            <li class="nav-item">
                                <a href="{{ route('assetAllocate.index') }}"
                                    class="nav-link @if ($Route[0] == 'assetAllocate') active @endif">
                                    <i class="icon-people"></i><span>Allocate</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif
        {{-- Asset Module End --}}

        {{-- Insurance Module Start --}}
        @if (Module::isModuleEnabled('Insurance'))
            @php
                $condition = 'false';
                if (request()->routeIs('insurance.type.index') || request()->routeIs('insurance.index')) {
                    $condition = 'true';
                }
            @endphp
            <li
                class="nav-item nav-item-submenu {{ request()->routeIs('insurance.index') ? 'nav-item-open nav-item-expanded' : '' }}">
                <a href="#" class="nav-link">
                    <i class="icon-coins"></i> <span>Insurance Management</span>
                </a>
                <ul class="nav nav-group-sub">
                    @if ($menuRoles->assignedRoles('insurance.index'))
                        <li class="nav-item">
                            <a href="{{ route('insurance.index') }}"
                                class="nav-link {{ request()->routeIs('insurance.index') ? 'active' : '' }}">
                                <i class="icon-diff-added"></i><span>All Insurance</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        {{-- Insurance Module End --}}




        {{-- Poll Start --}}
        @if (Module::isModuleEnabled('Poll'))
            @if (
                $menuRoles->assignedRoles('poll.index') ||
                    $menuRoles->assignedRoles('poll.allocationList') ||
                    $menuRoles->assignedRoles('poll.viewReport'))
                @php
                    $condition = 'false';
                    if ($Route[0] == 'poll') {
                        $condition = 'true';
                    }
                @endphp
                <li
                    class="nav-item nav-item-submenu {{ $condition == 'true' ? 'nav-item-open nav-item-expanded' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="icon-megaphone"></i> <span>Poll Management</span>
                    </a>
                    <ul class="nav nav-group-sub">
                        @if ($menuRoles->assignedRoles('poll.index'))
                            <li class="nav-item">
                                <a href="{{ route('poll.index') }}"
                                    class="nav-link @if ($Route[0] == 'poll' && $Route[1] == 'index') active @endif">
                                    <i class="icon-diff-added"></i><span>Polls</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('poll.allocationList'))
                            <li class="nav-item">
                                <a href="{{ route('poll.allocationList') }}"
                                    class="nav-link @if ($Route[0] == 'poll' && $Route[1] == 'allocationList') active @endif">
                                    <i class="icon-task"></i><span>Poll Allocations</span>
                                </a>
                            </li>
                        @endif
                        @if ($menuRoles->assignedRoles('poll.viewReport'))
                            <li class="nav-item">
                                <a href="{{ route('poll.viewReport', ['status' => 'active']) }}"
                                    class="nav-link @if ($Route[0] == 'poll' && $Route[1] == 'viewReport') active @endif">
                                    <i class="icon-stats-bars2"></i><span>View Report</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif
        {{-- Poll End --}}

        {{-- Survey Start --}}
        @if (Module::isModuleEnabled('Survey'))
            @if ($menuRoles->assignedRoles('survey.index') || $menuRoles->assignedRoles('survey.allocationList'))
                @php
                    $condition = 'false';
                    if ($Route[0] == 'survey') {
                        $condition = 'true';
                    }
                @endphp
                <li
                    class="nav-item nav-item-submenu {{ $condition == 'true' ? 'nav-item-open nav-item-expanded' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="icon-clipboard6"></i> <span>Survey Management</span>
                    </a>
                    <ul class="nav nav-group-sub">
                        @if ($menuRoles->assignedRoles('survey.index'))
                            <li class="nav-item">
                                <a href="{{ route('survey.index') }}"
                                    class="nav-link @if ($Route[0] == 'survey' && $Route[1] == 'index') active @endif">
                                    <i class="icon-diff-added"></i><span>Surveys</span>
                                </a>
                            </li>
                        @endif

                        @if ($menuRoles->assignedRoles('survey.allocationList'))
                            <li class="nav-item">
                                <a href="{{ route('survey.allocationList') }}"
                                    class="nav-link @if ($Route[0] == 'survey' && $Route[1] == 'allocationList') active @endif">
                                    <i class="icon-task"></i><span>Survey Allocations</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif
        {{-- Survey End --}}
        <li class="nav-item-header">
            <div class="text-uppercase font-size-xs line-height-xs">System Control</div> <i class="icon-menu"
                title="systems"></i>
        </li>
        @if (
            $menuRoles->assignedRoles('setting.index') ||
                $menuRoles->assignedRoles('dropdown.index') ||
                $menuRoles->assignedRoles('role.index') ||
                $menuRoles->assignedRoles('department.index') ||
                $menuRoles->assignedRoles('designation.index') ||
                $menuRoles->assignedRoles('level.index') ||
                $menuRoles->assignedRoles('module.index'))
            <li
                class='nav-item nav-item-submenu {{ request()->routeIs(' setting.index') ||
                request()->routeIs('dropdown.index') ||
                request()->routeIs('role.index') ||
                request()->routeIs('department.index') ||
                request()->routeIs('designation.index') ||
                request()->routeIs('level.index') ||
                request()->routeIs('module.index')
                    ? 'nav-item-open nav-item-expanded'
                    : '' }}'>
                <a href="#" class="nav-link ">
                    <i class="icon-cog4"></i> <span>System Configuration</span>
                </a>
                <ul class="nav nav-group-sub">

                    @if ($menuRoles->assignedRoles('setting.index'))
                        <li class="nav-item">
                            <a href="{{ route('setting.index') }}"
                                class="nav-link {{ request()->routeIs('setting.index') ? 'active' : '' }}">
                                <i class="icon-cog4"></i><span>General Setup</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('dropdown.index'))
                        <li class="nav-item">
                            <a href="{{ route('dropdown.index') }}"
                                class="nav-link {{ request()->routeIs('dropdown.index') ? 'active' : '' }}">
                                <i class="icon-circle-down2"></i><span>Dropdown Setup</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('role.index'))
                        <li class="nav-item">
                            <a href="{{ route('role.index') }}"
                                class="nav-link {{ request()->routeIs('role.index') ? 'active' : '' }}">
                                <i class="icon-pencil5 "></i><span>Role Setup</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('function.index'))
                        <li class="nav-item">
                            <a href="{{ route('function.index') }}"
                                class="nav-link {{ request()->routeIs('function.index') ? 'active' : '' }}">
                                <i class="icon-store"></i><span>Function Setup</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('department.index'))
                        <li class="nav-item">
                            <a href="{{ route('department.index') }}"
                                class="nav-link {{ request()->routeIs('department.index') ? 'active' : '' }}">
                                <i class="icon-store"></i><span>Sub Function Setup</span>
                            </a>
                        </li>
                    @endif

                    @if ($menuRoles->assignedRoles('designation.index'))
                        <li class="nav-item">
                            <a href="{{ route('designation.index') }}"
                                class="nav-link {{ request()->routeIs('designation.index') ? 'active' : '' }}">
                                <i class="icon-vcard"></i><span>Designation Setup</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('level.index'))
                        <li class="nav-item">
                            <a href="{{ route('level.index') }}"
                                class="nav-link {{ request()->routeIs('level.index') ? 'active' : '' }}">
                                <i class="icon-clipboard3"></i><span>Grade Setup</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('module.index'))
                        <li class="nav-item">
                            <a href="{{ route('module.index') }}"
                                class="nav-link {{ request()->routeIs('module.index') ? 'active' : '' }}">
                                <i class="icon-clipboard6"></i><span>Module Control</span>
                            </a>
                        </li>
                    @endif
                    {{-- @if ($menuRoles->assignedRoles('setting.create'))
                <li class="nav-item">
                    <a href="{{ route('setting.create') }}"
                        class="nav-link {{ request()->routeIs('setting.create') ? 'active' : '' }}">
                        <i class="icon-cog4"></i><span>Create Setting</span>
                    </a>
                </li>
                @endif --}}
                    {{-- @if ($menuRoles->assignedRoles('setting.create'))
                <li class="nav-item">
                    <a href="{{ route('allowance.create') }}"
                        class="nav-link @if ($Route[0] == 'allowance' && $Route[1] == 'create') active @endif">
                        <i class="icon-cog4"></i><span>Travel allowance setup</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('fiscalYearSetup.index'))
                <li class="nav-item">
                    <a href="{{ route('fiscalYearSetup.index') }}"
                        class="nav-link @if ($Route[0] == 'fiscalYearSetup') active @endif">
                        <i class="icon-calendar"></i><span>Fiscal Year</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('leaveyearsetup.index'))
                <li class="nav-item">
                    <a href="{{ route('leaveyearsetup.index') }}"
                        class="nav-link @if ($Route[0] == 'leaveYearSetup') active @endif">
                        <i class="icon-calendar"></i><span>Leave Year</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('leaveEncashmentSetup.index'))
                <li class="nav-item">
                    <a href="{{ route('leaveEncashmentSetup.index') }}"
                        class="nav-link @if ($Route[0] == 'leaveEncashmentSetup') active @endif">
                        <i class="icon-calendar"></i><span>Leave Encashment Setup</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('forceLeaveSetup.index'))
                <li class="nav-item">
                    <a href="{{ route('forceLeaveSetup.index') }}"
                        class="nav-link @if ($Route[0] == 'forceLeaveSetup') active @endif">
                        <i class="icon-calendar"></i><span>Force Leave Setup</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('province-setup.index'))
                <li class="nav-item">
                    <a href="{{ route('province-setup.index') }}"
                        class="nav-link @if ($Route[0] == 'province-setup') active @endif">
                        <i class="icon-calendar"></i><span>Province Setup</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('deviceManagement.index'))
                <li class="nav-item">
                    <a href="{{ route('deviceManagement.index') }}"
                        class="nav-link @if ($Route[0] == 'deviceManagement') active @endif">
                        <i class="icon-multitouch"></i><span>Biometric Device</span>
                    </a>
                </li>
                @endif
                @if (Module::isModuleEnabled('GeoFence'))

                @if ($menuRoles->assignedRoles('geoFence.index'))
                <li class="nav-item">
                    <a href="{{ route('geoFence.index') }}"
                        class="nav-link @if ($Route[0] == 'geoFence') active @endif">
                        <i class="icon-location4"></i><span>GeoFence Location</span>
                    </a>
                </li>
                @endif
                @endif
                @if (Module::isModuleEnabled('Attendance'))
                @if ($menuRoles->assignedRoles('webAttendance.allocationList'))
                <li class="nav-item">
                    <a href="{{ route('webAttendance.allocationList') }}"
                        class="nav-link @if ($Route[0] == 'webAttendance') active @endif">
                        <i class="icon-alarm"></i><span>Web Attendance Restriction Setup</span>
                    </a>
                </li>
                @endif
                @endif
                @if (Module::isModuleEnabled('ApprovalFlow'))

                @if ($menuRoles->assignedRoles('approvalFlow.index'))
                <li class="nav-item">
                    <a href="{{ route('approvalFlow.index') }}"
                        class="nav-link @if ($Route[0] == 'approvalFlow') active @endif">
                        <i class="icon-users2"></i><span>Approval Flow</span>
                    </a>
                </li>
                @endif
                @endif --}}
                    {{-- @if (Module::isModuleEnabled('Onboarding'))
                @if ($menuRoles->assignedRoles('mrfApprovalFlow.index'))
                <li class="nav-item">
                    <a href="{{ route('mrfApprovalFlow.index') }}"
                        class="nav-link @if ($Route[0] == 'mrfApprovalFlow') active @endif">
                        <i class="icon-ladder"></i><span>MRF Approval Flow</span>
                    </a>
                </li>
                @endif
                @endif
                @if ($menuRoles->assignedRoles('leaveDeductionSetup.index'))
                <li class="nav-item">
                    <a href="{{ route('leaveDeductionSetup.index') }}"
                        class="nav-link @if ($Route[0] == 'leaveDeductionSetup') active @endif">
                        <i class="icon-cog3"></i><span>Leave Deduction Setup</span>
                    </a>
                </li>
                @endif

                @if ($menuRoles->assignedRoles('siteAttendance.roleSetup'))
                <li class="nav-item">
                    <a href="{{ route('siteAttendance.roleSetup') }}"
                        class="nav-link @if ($Route[0] == 'siteAttendance' && $Route[1] == 'roleSetup') active @endif">
                        <i class="icon-hand"></i><span>Division/Site Attendance Role Setup</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('otRateSetup.index'))
                <li class="nav-item">
                    <a href="{{ route('otRateSetup.index') }}"
                        class="nav-link @if ($Route[0] == 'otRateSetup') active @endif">
                        <i class="icon-calendar"></i><span>OT Rate Setup</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('darbandi.index'))
                <li class="nav-item">
                    <a href="{{ route('darbandi.index') }}"
                        class="nav-link @if ($Route[0] == 'darbandi') active @endif">
                        <i class="icon-cog4"></i><span>Darbandi Setup</span>
                    </a>
                </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('employeeVisibilitySetup.index') }}"
                        class="nav-link @if ($Route[0] == 'employeevisibility') active @endif">
                        <i class="icon-cog4"></i><span>Employee Visibility</span>
                    </a>
                </li>

                @if ($menuRoles->assignedRoles('setting.viewEmailSetup'))
                <li class="nav-item">
                    <a href="{{ route('setting.viewEmailSetup') }}"
                        class="nav-link @if ($Route[0] == 'setting' && $Route[1] == 'viewEmailSetup') active @endif">
                        <i class="icon-envelop3"></i><span>Email Setup</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('module.apiModuleSetup'))
                <li class="nav-item">
                    <a href="{{ route('module.apiModuleSetup') }}"
                        class="nav-link @if ($Route[0] == 'module' && $Route[1] == 'apiModuleSetup') active @endif">
                        <i class="icon-cog4"></i><span>App Module Setup</span>
                    </a>
                </li>
                @endif
                @if ($menuRoles->assignedRoles('setting.activityLogReport'))
                <li class="nav-item">
                    <a href="{{ route('setting.activityLogReport') }}"
                        class="nav-link @if ($Route[0] == 'setting' && $Route[1] == 'activityLogReport') active @endif">
                        <i class="icon-file-text3"></i><span>Activity Logs</span>
                    </a>
                </li>
                @endif --}}
                </ul>
            </li>
        @endif
        {{-- @if (Module::has('LeaveYearType') && Module::isEnabled('LeaveYearType')) --}}
        @if ($menuRoles->assignedRoles('leaveyearsetup.index'))

            <li
                class="nav-item nav-item-submenu {{ request()->routeIs('leaveyearsetup.index') || request()->routeIs('leaveEncashmentSetup.index') || request()->routeIs('forceLeaveSetup.index') ? 'nav-item-open nav-item-expanded' : '' }}">
                <a href="#" class="nav-link">
                    <i class="icon-cogs"></i> <span>Leave Setting</span>
                </a>
                <ul class="nav nav-group-sub">
                    @if ($menuRoles->assignedRoles('leaveYearSetup.index'))
                        <li class="nav-item">
                            <a href="{{ route('leaveYearSetup.index') }}"
                                class="nav-link {{ request()->routeIs('leaveYearSetup.index') ? 'active' : '' }}">
                                <i class="icon-list"></i><span> Leave Year Setup</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('leaveEncashmentSetup.index'))
                        <li class="nav-item">
                            <a href="{{ route('leaveEncashmentSetup.index') }}"
                                class="nav-link {{ request()->routeIs('leaveEncashmentSetup.index') ? 'active' : '' }}">
                                <i class="icon-list"></i><span> Leave Encashment Setup</span>
                            </a>
                        </li>
                    @endif

                    @if ($menuRoles->assignedRoles('forceLeaveSetup.index'))
                        <li class="nav-item">
                            <a href="{{ route('forceLeaveSetup.index') }}"
                                class="nav-link {{ request()->routeIs('forceLeaveSetup.index') ? 'active' : '' }}">
                                <i class="icon-list"></i><span> Force Leave Setup</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        {{-- @endif --}}


        @if ($menuRoles->assignedRoles('setting.payrollSetting'))
            <li
                class='nav-item nav-item-submenu {{ request()->routeIs(' fiscalYearSetup.index') ||
                request()->routeIs('setting.payrollSetting') ||
                request()->routeIs('gross-salary.create')
                    ? 'nav-item-open
                                                                                                                                            nav-item-expanded'
                    : '' }}'>
                <a href="#" class="nav-link @if ($Route[0] == 'employeeSetup') active @endif"><i
                        class="icon-clipboard6"></i> <span>Payroll Setting</span></a>
                <ul class="nav nav-group-sub">
                    @if ($menuRoles->assignedRoles('fiscalYearSetup.index'))
                        <li class="nav-item">
                            <a href="{{ route('fiscalYearSetup.index') }}"
                                class="nav-link {{ request()->routeIs('fiscalYearSetup.index') ? 'active' : '' }}">
                                <i class="icon-list"></i><span> Fiscal Year Setup</span>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('setting.payrollSetting') }}"
                            class="nav-link  {{ request()->routeIs('setting.payrollSetting') ? 'active' : '' }}">
                            <i class="icon-calendar"></i><span>Payroll Setup</span>
                        </a>
                    </li>
                    @if ($menuRoles->assignedRoles('gross-salary.create'))
                        <li class="nav-item">
                            <a href="{{ route('gross-salary.create') }}"
                                class="nav-link {{ request()->routeIs('gross-salary.create') ? 'active' : '' }}">
                                <i class="icon-cog4"></i><span>Gross Salary Setup</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (
            $menuRoles->assignedRoles('deviceManagement.index') ||
                $menuRoles->assignedRoles('geoFence.index') ||
                $menuRoles->assignedRoles('webAttendance.allocationList'))
            <li
                class='nav-item nav-item-submenu {{ request()->routeIs(' deviceManagement.index') ||
                request()->routeIs('geoFence.index') ||
                request()->routeIs('webAttendance.allocationList')
                    ? 'nav-item-open
                                                                                                                                            nav-item-expanded'
                    : '' }}'>
                <a href="#" class="nav-link"><i class="icon-clipboard6"></i> <span>Attendance
                        Setting</span></a>
                <ul class="nav nav-group-sub">
                    @if ($menuRoles->assignedRoles('deviceManagement.index'))
                        <li class="nav-item">
                            <a href="{{ route('deviceManagement.index') }}"
                                class="nav-link {{ request()->routeIs('deviceManagement.index') ? 'active' : '' }}">
                                <i class="icon-list"></i><span> Biometric Device Setup</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('geoFence.index'))
                        <li class="nav-item">
                            <a href="{{ route('geoFence.index') }}"
                                class="nav-link  {{ request()->routeIs('geoFence.index') ? 'active' : '' }}">
                                <i class="icon-calendar"></i><span>Geofence Setup</span>
                            </a>
                        </li>
                    @endif
                    @if ($menuRoles->assignedRoles('webAttendance.allocationList'))
                        <li class="nav-item">
                            <a href="{{ route('webAttendance.allocationList') }}"
                                class="nav-link {{ request()->routeIs('webAttendance.allocationList') ? 'active' : '' }}">
                                <i class="icon-cog4"></i><span>Web Attendance Setup</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        <li
            class="nav-item nav-item-submenu {{ request()->routeIs('bulkupload.familyDetail') ||
            request()->routeIs('bulkupload.emergencyDetail') ||
            request()->routeIs('bulkupload.employeeDetail') ||
            request()->routeIs('bulkupload.benefitDetail') ||
            request()->routeIs('bulkupload.bankDetail') ||
            request()->routeIs('bulkupload.educationDetail') ||
            request()->routeIs('bulkupload.previousJobDetail') ||
            request()->routeIs('bulkupload.carrierMobility') ||
            request()->routeIs('bulkupload.contractDetail') ||
            request()->routeIs('bulkupload.medicalDetail') ||
            request()->routeIs('bulkupload.leaveDetail') ||
            request()->routeIs('bulkupload.leaveHistoryDetail') ||
            request()->routeIs('bulkupload.researchDetail') ||
            request()->routeIs('bulkupload.visaImmigrationDetail') ||
            request()->routeIs('bulkupload.documentDetail') ||
            request()->routeIs('bulkupload.attendanceLog') ||
            request()->routeIs('bulkupload.employeeJobDescription') ||
            request()->routeIs('bulkupload.approvalFlowView') ||
            request()->routeIs('bulkupload.empBiometricDetail') ||
            request()->routeIs('bulkupload.darbandis') ||
            request()->routeIs('bulkupload.holiday') ||
            request()->routeIs('bulkupload.user') ||
            request()->routeIs('bulkupload.branch') ||
            request()->routeIs('bulkupload.labour')
                ? 'nav-item-open nav-item-expanded'
                : '' }}">
            <a href="#" class="nav-link">
                <i class="icon-folder-open"></i> <span>Bulk Upload Setting</span>
            </a>
            <ul class="nav nav-group-sub" data-submenu-title="Bulk Upload Management">
                @if ($menuRoles->assignedRoles('bulkupload.familyDetail'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.familyDetail') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.familyDetail') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Family Detail</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.emergencyDetail'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.emergencyDetail') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.emergencyDetail') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Emergency Detail</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.employeeDetail'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.employeeDetail') }}"
                            class="nav-link  {{ request()->routeIs('bulkupload.employeeDetail') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Employee Detail</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.benefitDetail'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.benefitDetail') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.benefitDetail') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Benefit Detail</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.bankDetail'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.bankDetail') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.bankDetail') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Bank Detail</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.educationDetail'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.educationDetail') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.educationDetail') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Education Detail</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.previousJobDetail'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.previousJobDetail') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.previousJobDetail') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Previous Job Detail</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.carrierMobility'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.carrierMobility') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.carrierMobility') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Employee Career Mobility</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.contractDetail'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.contractDetail') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.contractDetail') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Contract Detail</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.medicalDetail'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.medicalDetail') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.medicalDetail') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Medical Detail</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.leaveDetail'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.leaveDetail') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.leaveDetail') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Leave Detail</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.leaveHistoryDetail'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.leaveHistoryDetail') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.leaveHistoryDetail') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Leave History Detail</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.researchDetail'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.researchDetail') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.researchDetail') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Research & Publication
                                Detail</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.visaImmigrationDetail'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.visaImmigrationDetail') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.visaImmigrationDetail') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Visa/Immigration Doc Detail</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.documentDetail'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.documentDetail') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.documentDetail') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Document Detail</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.attendanceLog'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.attendanceLog') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.attendanceLog') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Attendance Log</span>
                        </a>
                    </li>
                @endif

                @if ($menuRoles->assignedRoles('bulkupload.employeeJobDescription'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.employeeJobDescription') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.employeeJobDescription') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Employee Job Description</span>
                        </a>
                    </li>
                @endif

                @if ($menuRoles->assignedRoles('bulkupload.approvalFlowView'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.approvalFlowView') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.approvalFlowView') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Approval Flow Detail </span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.empBiometricDetail'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.empBiometricDetail') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.empBiometricDetail') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Employee Biometric</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.darbandis'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.darbandis') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.darbandis') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Darbandi Upload</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.holiday'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.holiday') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.holiday') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Holiday Upload</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.user'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.user') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.user') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>User Upload</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.branch'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.branch') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.branch') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Unit Upload</span>
                        </a>
                    </li>
                @endif
                @if ($menuRoles->assignedRoles('bulkupload.labour'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.labour') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.labour') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Labour Upload</span>
                        </a>
                    </li>
                @endif

                @if ($menuRoles->assignedRoles('bulkupload.attendanceOverStay'))
                    <li class="nav-item">
                        <a href="{{ route('bulkupload.attendanceOverStay') }}"
                            class="nav-link {{ request()->routeIs('bulkupload.attendanceOverStay') ? 'active' : '' }}">
                            <i class="icon-file-spreadsheet2"></i><span>Actual Overtime Upload </span>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

        @if (Module::isModuleEnabled('Setting'))
            @if ($menuRoles->assignedRoles('activitiesLog.index'))
                <li class="nav-item">
                    <a href="{{ route('activitiesLog.index') }}"
                        class="nav-link @if ($Route[0] == 'activitiesLog') active @endif">
                        <i class="icon-info22"></i><span>Activitiy Logs</span>
                    </a>
                </li>
            @endif
        @endif


</div>
