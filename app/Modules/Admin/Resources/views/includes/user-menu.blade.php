<div class="sidebar-section sidebar-user my-1">
    <div class="sidebar-section-body">
        <div class="media">
            <a href="#" class="mr-3">
                @if(auth()->user()->user_type == 'super_admin')
                    <img src="{{ asset('admin/default.png') }}" class="rounded-circle" alt="">
                @else
                    <img src="{{ optional($user->userEmployer)->getImage() }}" class="rounded-circle" alt="">
                @endif
            </a>

            <div class="media-body">
                @if(auth()->user()->user_type == 'super_admin')
                    <div class="font-weight-semibold">{{ $user->first_name.' '. $user->last_name }}</div>
                @else
                    @php
                        if(optional($user->userEmployer)->getFullName()) {
                            $fullName = optional($user->userEmployer)->getFullName();
                        } else {
                            $fullName = $user->first_name.' '.$user->middle_name.' '.$user->last_name;
                        }
                    @endphp
                    <div class="font-weight-semibold">{{ $fullName }}</div>
                @endif

                {{-- @php
                    $userType = str_replace('_', ' ', $user->user_type);
                    $userType = ucwords($userType);
                    $userType = str_replace('Hr', 'HR', $userType);
                @endphp
                <div class="font-size-sm line-height-sm opacity-50">{{ $userType }}</div> --}}
                
                @php
                    $role = $user->role;
                    if (isset($role[0]) && !empty($role[0])) {
                        $userRole = $role[0]->name;
                    }else{
                        $userRole = 'Super Admin';
                    }
                @endphp
                <div class="font-size-sm line-height-sm opacity-50">{{ $userRole }}</div>

            </div>

            <div class="ml-3 align-self-center">
                <button type="button" class="btn btn-outline-light-100 text-white border-transparent btn-icon rounded-pill btn-sm sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
                    <i class="icon-transmission"></i>
                </button>
                <button type="button" class="btn btn-outline-light-100 text-white border-transparent btn-icon rounded-pill btn-sm sidebar-mobile-main-toggle d-lg-none">
                    <i class="icon-cross2"></i>
                </button>
            </div>

        </div>
    </div>
</div>
