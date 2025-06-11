<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body pb-0">
                <a href="{{ route('organization.index') }}" class="text-dark">
                    <div class="row">
                        <div class="col-md-8">
                            <h1 class="font-weight-semibold mb-0">{{ $totalOrganization ?? 0 }}</h1>
                            <h5>Organizations</h5>
                        </div>
                        <div class="col-md-4 text-right">
                            <i class="icon-office icon-3x text-secondary mt-1 mb-3"></i>
                        </div>
                    </div>
            </div>
            </a>
            <img src="{{ asset('admin/widget-bg-secondary.png') }}">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body pb-0">
                <a href="{{ route('employee.index') }}" class="text-dark">
                    <div class="row">
                        <div class="col-md-8">
                            <h1 class="font-weight-semibold mb-0">{{ $totalEmployee ?? 0 }}</h1>
                            <h5>Employees</h5>
                        </div>
                        <div class="col-md-4 text-right">
                            <i class="icon-users2 icon-3x text-secondary mt-1 mb-3"></i>
                        </div>
                    </div>
                </a>
            </div>
            <img src="{{ asset('admin/widget-bg-secondary.png') }}">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body pb-0">
                <a href="{{ route('leave.index') }}" class="text-dark">
                    <div class="row">
                        <div class="col-md-8">
                            <h1 class="font-weight-semibold mb-0">
                                {{ $totalLeave ?? 0 }}</h1>
                            <h5>Leaves</h5>
                        </div>
                        <div class="col-md-4 text-right">
                            <i class="icon-clipboard2 icon-3x text-secondary mt-1 mb-3"></i>
                        </div>
                    </div>
                </a>
            </div>
            <img src="{{ asset('admin/widget-bg-secondary.png') }}">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body pb-0">
                <a href="{{ route('viewMonthlyAttendanceCalendar', [
                    'org_id' => 1,
                    'calendar_type' => 'eng',
                    'eng_year' => date('Y'),
                    'eng_month' => (int) date('m'),
                ]) }}"
                    class="text-dark">
                    <div class="row">
                        <div class="col-md-8">
                            <h1 class="font-weight-semibold mb-0">
                                {{ $totalAttendance ?? 0 }}
                            </h1>
                            <h5>Attendances</h5>
                        </div>
                        <div class="col-md-4 text-right">
                            <i class="icon-touch icon-3x text-secondary mt-1 mb-3"></i>
                        </div>
                    </div>
                </a>
            </div>
            <img src="{{ asset('admin/widget-bg-secondary.png') }}">
        </div>
    </div>
    @if (Module::isModuleEnabled('Onboarding'))
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body pb-0">
                    <a href="{{ route('interview.index') }}" class="text-dark">
                        <div class="row">
                            <div class="col-md-8">
                                <h1 class="font-weight-semibold mb-0">{{ sprintf('%02d', $totalInterview) }}</h1>
                                <h5>Interviews</h5>
                            </div>
                            <div class="col-md-4 text-right">
                                <i class="icon-bubbles9 icon-3x text-secondary mt-1 mb-3"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <img src="{{ asset('admin/widget-bg-secondary.png') }}">
            </div>
        </div>
    @endif
    @if (Module::isModuleEnabled('Worklog'))
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body pb-0">
                    <a href="{{ route('worklog.index') }}" class="text-dark">
                        <div class="row">
                            <div class="col-md-8">
                                <h1 class="font-weight-semibold mb-0">{{ sprintf('%02d', $workReportCount) }}</h1>
                                <h5>Work Logs</h5>
                            </div>
                            <div class="col-md-4 text-right">
                                <i class="icon-stack-text icon-3x text-secondary mt-1 mb-3"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <img src="{{ asset('admin/widget-bg-secondary.png') }}">
            </div>
        </div>
    @endif
    @if (Module::isModuleEnabled('Training'))
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body pb-0">
                    <a href="{{ route('training.index') }}" class="text-dark">
                        <div class="row">
                            <div class="col-md-8">
                                <h1 class="font-weight-semibold mb-0">{{ sprintf('%02d', $trainingCount) }}</h1>
                                <h5>Training</h5>
                            </div>
                            <div class="col-md-4 text-right">
                                <i class="icon-reading icon-3x text-secondary mt-1 mb-3"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <img src="{{ asset('admin/widget-bg-secondary.png') }}">
            </div>
        </div>
    @endif
    @if (Module::isModuleEnabled('PMS'))
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body pb-0">
                    <a href="{{ route('PMS.viewReport') }}" class="text-dark">
                        <div class="row">
                            <div class="col-md-8">
                                <h1 class="font-weight-semibold mb-0">01</h1>
                                <h5>PMS</h5>
                            </div>
                            <div class="col-md-4 text-right">
                                <i class="icon-stats-growth icon-3x text-secondary mt-1 mb-3"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <img src="{{ asset('admin/widget-bg-secondary.png') }}">
            </div>
        </div>
    @endif
</div>
