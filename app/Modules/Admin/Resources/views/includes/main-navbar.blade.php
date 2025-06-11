@inject('setting', '\App\Modules\Setting\Entities\Setting')
@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@inject('roleModel', '\App\Modules\User\Entities\Role')

<div class="navbar navbar-expand-lg navbar-dark navbar-static">
    <div class="d-flex flex-1 d-lg-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-paragraph-justify3"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-transmission"></i>
        </button>
    </div>

    <div class="navbar-brand text-center text-lg-left">
        <a href="{{ url('admin/dashboard') }}" target="" class="d-inline-block">
            @php
                $settingModel = $setting->getData();
                if ($settingModel) {
                    $image = $settingModel->getLogo();
                } else {
                    $image = asset('admin/gfs.png');
                }
            @endphp
            <img src="{{ $image }}" class="d-none d-sm-block" alt="" style="height: 35px;">
            <img src="{{ $image }}" class="d-sm-none" alt="" style="height: 35px;">
        </a>
    </div>

    <div class="collapse navbar-collapse order-2 order-lg-1" id="navbar-mobile">
        <ul class="navbar-nav ml-lg-auto">

            <a href="{{ route('pendingApproval') }}"
                class="navbar-nav-link navbar-nav-link-toggler border-warning rounded-pill" aria-expanded="true"
                data-popup="tooltip" data-placement="top" data-original-title="Pending Approval">
                <i class="icon-clipboard6"></i>
                <span class="badge badge-warning badge-pill ml-auto ml-lg-0">
                    {{ $totalPendingApprovals ?? 0 }}
                </span>
            </a>
            <li class="nav-item nav-item-dropdown-lg dropdown">
                <a href="#" class="navbar-nav-link navbar-nav-link-toggler rounded-pill" data-toggle="dropdown"
                    aria-expanded="true" data-popup="tooltip" data-placement="top">
                    <i class="icon-bell3"></i>
                    <span class="badge badge-warning badge-pill ml-auto ml-lg-0">
                        {{ $count_notification }}
                    </span>
                </a>

                <div class="dropdown-menu dropdown-menu-right dropdown-content wmin-lg-350">
                    <div class="dropdown-content-header">
                        <span class="font-weight-semibold">Notifications</span>
                        {{-- <a href="#" class="text-body"><i class="icon-compose"></i></a> --}}
                        <a href="{{ route('Notification.markAsReadAll') }}" class="text-body1"
                            style="font-size: 12px;">Mark As Read</a>

                    </div>
                    <div class="dropdown-content-body dropdown-scrollable">
                        <ul class="media-list">

                            @foreach ($listNotifications as $notification)
                                <li class="media">
                                    <div class="mr-3">
                                        @if ($notification->is_read == '1')
                                            <a
                                                class="btn bg-transparent border-success text-success rounded-pill border-2 btn-icon">
                                                <i class="icon-checkmark3"></i>
                                            </a>
                                        @else
                                            <a
                                                class="btn bg-transparent border-warning text-warning rounded-pill border-2 btn-icon">
                                                <i class="icon-info3"></i>
                                            </a>
                                        @endif
                                    </div>
                                    <div class="media-body">
                                        <div class="media-title">
                                            <a href="{{ route('Notification.checkLink', ['notification_id' => $notification->id]) }}"
                                                class="text-dark">
                                                {!! $notification->message !!}
                                                <div class="text-muted font-size-sm">
                                                    {{ $notification->created_at->diffForHumans() }}</div>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="dropdown-content-footer justify-content-center p-0">
                        <a href="{{ route('Notification.index') }}"
                            class="btn btn-light btn-block border-0 rounded-top-0" data-popup="tooltip" title=""
                            data-original-title="Load more"><i class="icon-menu7"></i></a>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <ul class="navbar-nav flex-row order-1 order-lg-2 flex-1 flex-lg-0 justify-content-end align-items-center">
        <li class="nav-item nav-item-dropdown-lg dropdown">
        </li>

        <li class="nav-item nav-item-dropdown-lg dropdown dropdown-user h-100">
            <a href="#"
                class="navbar-nav-link navbar-nav-link-toggler dropdown-toggle d-inline-flex align-items-center h-100"
                data-toggle="dropdown">
                @if (Auth::user()->user_type == 'super_admin')
                    <span class="d-none d-lg-inline-block pr-1">{{ $user->first_name . ' ' . $user->last_name }}</span>
                @else
                    @php
                        if (optional($user->userEmployer)->getFullName()) {
                            $fullName = optional($user->userEmployer)->getFullName();
                        } else {
                            $fullName = $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
                        }
                    @endphp

                    {{-- <div class="media">
                            <img src="{{ optional($user->userEmployer)->getImage() }}" class="rounded-circle"
                                alt="" style="width: 30px; margin-right: 10px;">

                        <div class="media-body">
                            <div class="font-weight-semibold">{{ $fullName }}</div>
                            <div class="font-size-sm line-height-sm opacity-50">
                                {{ str_replace('_', ' ', ucfirst($user->user_type)) }}</div>

                        </div>
                    </div> --}}

                    <span class="d-none d-lg-inline-block pr-1">{{ $fullName }}</span>
                @endif
            </a>
            @php
                $emp_id = Auth::user()->emp_id;
            @endphp
            <div class="dropdown-menu dropdown-menu-right">
                {{-- @if (Auth::user()->user_type == 'super_admin')
                    <a class="dropdown-item"><i class="icon-user-plus"></i>
                        {{ $user->first_name . ' ' . $user->last_name }}</a>
                @else
                    @if (auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'division_hr')
                        @if (request('view') == 'employee')
                            <a href="{{ route('dashboard') }}" class="dropdown-item"><i class="icon-home2"></i> Main
                                Dashboard</a>
                        @else
                            <a href="{{ route('dashboard') . '?view=employee' }}" class="dropdown-item"><i
                                    class="icon-home2"></i> Employee Dashboard</a>
                        @endif
                    @endif
                    <a href="{{ route('employee.viewSelfProfile', $emp_id) }}" class="dropdown-item"><i
                            class="icon-profile"></i>
                        My Profile</a>
                @endif --}}
                @if (Auth::user()->user_type == 'super_admin')
                    <a class="dropdown-item"><i class="icon-user-plus"></i>
                        {{ $user->first_name . ' ' . $user->last_name }}</a>
                @else
                    {{-- @if (auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'division_hr')
                    @if (request('view') == 'employee')
                        <a href="{{ route('dashboard') }}" class="dropdown-item"><i class="icon-home2"></i> Main
                            Dashboard</a>
                    @else
                        <a href="{{ route('dashboard') . '?view=employee' }}" class="dropdown-item"><i
                                class="icon-home2"></i> Employee Dashboard</a>
                    @endif
                @endif --}}
                    <a href="{{ route('employee.viewSelfProfile', $emp_id) }}" class="dropdown-item"><i
                            class="icon-profile"></i>
                        My Profile</a>
                @endif

                @if (!empty(auth()->user()->assignedRoles))
                    @foreach (auth()->user()->assignedRoles as $assignedRole)
                        @php
                            $role = $roleModel->where('user_type', auth()->user()->user_type)->first();
                        @endphp
                        @if ($role->id != $assignedRole->role_id)
                            <a href="{{ route('employee.changeUserType', ['role_id' => $assignedRole->role_id]) }}"
                                class="dropdown-item">
                                <i class="icon-home4"></i>
                                {{ optional($assignedRole->role)->name }} Dashboard</a>
                            <br>
                        @endif
                    @endforeach
                @endif


                <div class="dropdown-divider"></div>
                <a href="{{ route('change-password') }}" class="dropdown-item"><i class="icon-key"></i> Change
                    Password</a>
                <a href="{{ route('logout') }}" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
            </div>
        </li>
    </ul>
</div>
