<div class="card" style="height: 350px;">
    <div class="card-header bg-transparent header-elements-inline">
        <h4 class="card-title font-weight-semibold">
            Pending Approval
        </h4>
        <div class="header-elements">
            <div class="list-icons ml-3">
                <a class="btn btn-success btn-sm rounded-pill" href="{{ route('pendingApproval') }}">
                    View More
                </a>
            </div>
        </div>
    </div>


    <div class="card-body table-responsive" style="overflow-x: hidden; padding:0">

        @if (count($requestApprovals) > 0)
            <table class="table table-striped" width="100%">
                <tbody>
                    @foreach ($requestApprovals as $key => $requestApproval)
                        @php $days = \Carbon\Carbon::now()->diffInDays($requestApproval['date'], false); @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <a href="" class="btn btn-secondary rounded-pill btn-icon btn-sm">
                                            <span class="">P</span>
                                        </a>
                                    </div>

                                    <div>
                                        {{-- <span class="badge badge-info badge-pill">  </span> --}}
                                        <a href="#" class="text-dark font-weight-semibold letter-icon-title">
                                            {{ $requestApproval['title'] }}</a>
                                        <div class="text-muted font-size-sm"><i
                                                class="icon-calendar2 font-size-sm mr-1"></i>{{ getStandardDateFormat($requestApproval['date']) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="float-right">
                                <h6 class="font-weight-semibold mr-3">
                                    @php
                                        $badge = '';
                                        switch ($requestApproval['type']) {
                                            case 'claim':
                                                $badge = 'secondary';
                                                break;
                                            case 'request':
                                                $badge = 'info';
                                                break;
                                            case 'leave':
                                                $badge = 'danger';
                                                break;
                                            case 'attendance':
                                                $badge = 'warning';
                                                break;

                                            default:
                                                $badge = 'primary';
                                                break;
                                        }

                                    @endphp
                                    <span
                                        class="badge badge-{{ $badge }}">{{ ucfirst($requestApproval['type']) }}</span>
                                </h6>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="d-flex flex-column justify-content-center align-items-center text-center h-100">
                <svg width="36" height="36" fill="#ccc" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512">
                    <path
                        d="M482 108h-14V94c0-16.542-13.458-30-30-30h-24V52c0-17.645-14.355-32-32-32s-32 14.355-32 32v12h-84V52c0-17.645-14.355-32-32-32s-32 14.355-32 32v12h-84V52c0-17.645-14.355-32-32-32S54 34.355 54 52v12H30C13.458 64 0 77.458 0 94v324c0 16.542 13.458 30 30 30h14v14c0 16.542 13.458 30 30 30h408c16.542 0 30-13.458 30-30V138c0-16.542-13.458-30-30-30zM370 52c0-6.617 5.383-12 12-12s12 5.383 12 12v44c0 6.617-5.383 12-12 12s-12-5.383-12-12zm-148 0c0-6.617 5.383-12 12-12s12 5.383 12 12v44c0 6.617-5.383 12-12 12s-12-5.383-12-12zM74 52c0-6.617 5.383-12 12-12s12 5.383 12 12v44c0 6.617-5.383 12-12 12s-12-5.383-12-12zM30 84h24v12c0 17.645 14.355 32 32 32s32-14.355 32-32V84h84v12c0 17.645 14.355 32 32 32s32-14.355 32-32V84h84v12c0 17.645 14.355 32 32 32s32-14.355 32-32V84h24c5.514 0 10 4.486 10 10v90H20V94c0-5.514 4.486-10 10-10zm462 378c0 5.514-4.486 10-10 10H74c-5.514 0-10-4.486-10-10v-14h125c5.523 0 10-4.478 10-10s-4.477-10-10-10H30c-5.514 0-10-4.486-10-10V204h428v214c0 5.514-4.486 10-10 10H279c-5.523 0-10 4.478-10 10s4.477 10 10 10h159c16.542 0 30-13.458 30-30V128h14c5.514 0 10 4.486 10 10z" />
                    <path
                        d="M271.214 389.279a10.001 10.001 0 0014.509-10.542l-7.107-41.439 30.107-29.347a10 10 0 00-5.542-17.058l-41.607-6.045-18.607-37.703a10 10 0 00-17.934 0l-18.607 37.703-41.607 6.045a10 10 0 00-5.542 17.058l30.107 29.347-7.107 41.439c-.644 3.752.898 7.543 3.978 9.78s7.163 2.532 10.531.762L234 369.714zm-65.8-27.132l4.571-26.65a9.997 9.997 0 00-2.876-8.851l-19.362-18.873 26.758-3.888a10.002 10.002 0 007.529-5.471L234 274.168l11.966 24.247a10.004 10.004 0 007.529 5.471l26.758 3.888-19.362 18.873a9.998 9.998 0 00-2.876 8.851l4.571 26.65-23.933-12.583a9.99 9.99 0 00-9.306 0zM234 428c-2.63 0-5.21 1.069-7.07 2.93-1.86 1.86-2.93 4.44-2.93 7.07s1.07 5.21 2.93 7.069c1.86 1.86 4.44 2.931 7.07 2.931s5.21-1.07 7.07-2.931c1.86-1.859 2.93-4.439 2.93-7.069s-1.07-5.21-2.93-7.07A10.071 10.071 0 00234 428z" />
                </svg>
                <p class="text-muted pt-3 mb-0">No Request For Approval Found.</p>
            </div>
        @endif

    </div>
</div>
