
    <div class="card box_item" style="height: 375px;">
        <div class="card-header bg-transparent border-none">
            <h4>On Leave</h4>
        </div>
        <div class="card-body">
        @if(isset($todayLeaveList) && $todayLeaveList->count() > 0)
            <ul class="full-block">

                    @foreach($todayLeaveList as $key => $value)
                        <li>
                            <div class="avatar w18">
                                @if(is_null(optional($value->employee)->profile_pic))
                                        <img src="{{ asset('admin/default.png') }}"
                                            alt="{{optional($value->employee)->first_name.' '.optional($value->employee)->middle_name.' '.optional($value->employee)->last_name}}" />
                                @else
                                    <img src="{{ asset('uploads/employee/'.optional($value->employee)->profile_pic) }}"
                                        alt="{{optional($value->employee)->first_name.' '.optional($value->employee)->middle_name.' '.optional($value->employee)->last_name}}" />
                                @endif
                            </div>
                            <div class="details">
                                <h5>{{  optional($value->employee)->first_name.' '.optional($value->employee)->middle_name.' '.optional($value->employee)->last_name }}
                                    </h5>
                                    <span class="d-block">{{optional($value->leaveType)->title }}({{$value->is_half_leave == 1 ? 'Half Leave' : 'Full Leave'}})</span>
                                <div class="label bg-grey bg-padding">
                                    {{ optional(optional($value->employee)->designation)->title }}
                                </div>

                                <span class="d-block"><i class="fa fa-calendar-alt"></i> {{ date("jS F, Y", strtotime($value->start_date)) }}</span>

                            </div>

                        </li>
                    @endforeach

            </ul>
            @else
                <div class="d-flex flex-column justify-content-center align-items-center text-center h-100">
                    <svg width="40" fill="#ccc" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><g fill-rule="evenodd"><path d="M248.953 61.273c-5.367-1.238-10.723 2.102-11.965 7.47-1.242 5.366 2.106 10.726 7.473 11.968 24.812 5.734 47.492 18.332 65.582 36.422 53.184 53.183 53.184 139.726 0 192.91-53.184 53.187-139.727 53.187-192.91 0-53.188-53.184-53.188-139.727 0-192.91 15.27-15.27 33.34-26.406 53.71-33.11 5.231-1.718 8.079-7.355 6.36-12.59-1.723-5.234-7.363-8.081-12.594-6.359-23.367 7.684-44.086 20.453-61.582 37.953-60.965 60.965-60.965 160.16 0 221.125 30.48 30.48 70.52 45.723 110.563 45.723 40.039-.004 80.078-15.242 110.562-45.723 60.961-60.965 60.961-160.16 0-221.125-20.738-20.734-46.738-35.175-75.199-41.754zm0 0"/><path d="M498.414 432.707L393.883 328.176c53.601-84.055 41.863-194.485-29.266-265.617C324.277 22.219 270.641 0 213.59 0 156.535 0 102.898 22.219 62.559 62.559 22.215 102.899 0 156.535 0 213.589c0 57.052 22.215 110.688 62.559 151.028 40.34 40.34 93.972 62.555 151.023 62.555 40.945 0 80.387-11.485 114.594-33.29l104.531 104.532c8.746 8.75 20.414 13.566 32.856 13.566 12.437 0 24.105-4.816 32.855-13.566 18.11-18.117 18.11-47.59-.004-65.707zm-14.105 51.602c-4.98 4.976-11.637 7.718-18.746 7.718-7.114 0-13.77-2.742-18.75-7.718L336.507 374.004a9.946 9.946 0 00-7.055-2.922c-1.976 0-3.96.582-5.683 1.777-32.41 22.48-70.516 34.364-110.188 34.364-51.723 0-100.348-20.141-136.918-56.711-75.5-75.5-75.5-198.348 0-273.848 36.574-36.574 85.2-56.715 136.926-56.715 51.722 0 100.347 20.14 136.922 56.715 66.281 66.285 75.683 170.207 22.347 247.106a9.97 9.97 0 001.145 12.738l110.305 110.305c10.336 10.335 10.336 27.156 0 37.496zm0 0"/><path d="M273.805 153.371c-3.895-3.894-10.207-3.894-14.106 0l-46.11 46.11-46.112-46.11c-3.895-3.894-10.211-3.894-14.106 0-3.894 3.895-3.894 10.211 0 14.106l46.11 46.113-46.11 46.11c-3.894 3.894-3.894 10.21 0 14.105a9.931 9.931 0 007.05 2.922 9.94 9.94 0 007.056-2.922l46.109-46.11 46.11 46.11a9.947 9.947 0 007.054 2.922 9.94 9.94 0 007.055-2.922c3.894-3.895 3.894-10.211 0-14.106l-46.114-46.11 46.114-46.112c3.894-3.895 3.894-10.211 0-14.106zm0 0M206.977 77.328c5.492 0 9.972-4.48 9.972-9.976 0-5.493-4.48-9.973-9.972-9.973-5.497 0-9.977 4.48-9.977 9.973 0 5.496 4.48 9.976 9.977 9.976zm0 0"/></g></svg>
                    <p class="text-muted pt-3 mb-0">No Leave Found.</p>
                </div>
            @endif
        </div>
    </div>
