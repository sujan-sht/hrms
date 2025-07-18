
<div class="card" style="height: 97%;">
    <div class="card-header bg-secondary text-white header-elements-inline">
        <h4>System Reminder</h4>
    </div>
    <div class="card-body">
        @if($reminderNotify->count() > 0)
            <ul class="full-block">

                @if(isset($reminderNotify) && $reminderNotify->count() > 0)
                    @foreach($reminderNotify as $key=>$notify)
                        <table class="table table-striped" width="100%">
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            @if(is_null($notify->employee->profile_pic))
                                                <img src="{{ asset('admin/default.png') }}"
                                                     alt="{{$notify->employee->first_name.' '.$notify->employee->middle_name.' '.$notify->employee->last_name}}"
                                                     width="80" height="80" class="rounded-circle"/>
                                            @else
                                                <img src="{{ asset('uploads/employee/'.$notify->employee->profile_pic) }}"
                                                     alt="{{$notify->employee->first_name.' '.$notify->employee->middle_name.' '.$notify->employee->last_name}}"
                                                     width="80" height="80" class="rounded-circle"/>
                                            @endif
                                        </div>

                                        <div>
                                            <div>
                                                <h6 class="pt-2">
                                                    <a href="{{ route('employment.view', $notify->employee->id) }}"
                                                       class="text-default font-weight-semibold letter-icon-title">
                                                        {{ $notify->title  }}
                                                    </a>
                                                </h6>
                                            </div>
                                            <div class="bg-danger-400 bg-padding rounded-round text-center pl-2 pr-2 w-50">
                                                @if($notify->display_type == 0)
                                                    Anniversary
                                                @elseif($notify->display_type == 1)
                                                    Birthday
                                                @elseif($notify->display_type == 2)
                                                    Assets
                                                @elseif($notify->display_type == 3)
                                                    Join Date
                                                @elseif($notify->display_type == 4)
                                                    Probation Period
                                                @elseif($notify->display_type == 5)
                                                    Interview Date
                                                @elseif($notify->display_type == 6)
                                                    Assets
                                                @elseif($notify->display_type == 7)
                                                    Event
                                                @elseif($notify->display_type == 9)
                                                    Change Request Approved
                                                @endif
                                            </div>
                                            <div class="text-muted font-size-sm"><i class="font-size-sm mr-1"></i>
                                                [ {{ date('jS M, Y',strtotime($notify->date))}} ]
                                            </div>
                                        </div>

                                    </div>
                                </td>

                                <td class="float-right">
                                    @if(date('d', strtotime($notify->date)) == date('d'))
                                        <h6 class="font-weight-semibold mr-3">
                                            Today
                                        </h6>
                                    @else
                                        <h6 class="font-weight-semibold mr-3">
                                            {{ date('d', strtotime($notify->date)) - date('d') }}
                                            days remaining
                                        </h6>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    @endforeach
                @endif
            </ul>
        @else
            <div class="d-flex flex-column justify-content-center align-items-center text-center h-100">
                <svg width="36" height="36" fill="#ccc" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M408.366 91.308C416.477 114.331 421 139.547 421 166c0 70.364-31.807 132.959-79.217 167.256 6.621 6.553 13.64 12.42 21.279 16.811C361.84 353.55 361 357.464 361 362c0 18.75 12.642 28.228 21.006 34.497C389.564 402.181 391 403.851 391 407s-1.436 4.819-8.994 10.503C373.642 423.772 361 433.25 361 452s12.642 28.228 21.006 34.497C389.564 492.181 391 493.851 391 497c0 8.291 6.709 15 15 15s15-6.709 15-15c0-18.75-12.642-28.228-21.006-34.497C392.436 456.819 391 455.149 391 452s1.436-4.819 8.994-10.503C408.358 435.228 421 425.75 421 407s-12.642-28.228-21.006-34.497C392.436 366.819 391 365.149 391 362c0-.461.222-.925.302-1.37 4.832.875 9.736 1.37 14.698 1.37 57.891 0 106-61.557 106-136 0-73.401-46.857-133.03-103.634-134.692zM0 226c0 68 40.439 124.842 91.368 134.098-.035.662-.368 1.23-.368 1.902 0 18.75 12.642 28.228 21.006 34.497C119.564 402.181 121 403.851 121 407s-1.436 4.819-8.994 10.503C103.642 423.772 91 433.25 91 452s12.642 28.228 21.006 34.497C119.564 492.181 121 493.851 121 497c0 8.291 6.709 15 15 15s15-6.709 15-15c0-18.75-12.642-28.228-21.006-34.497C122.436 456.819 121 455.149 121 452s1.436-4.819 8.994-10.503C138.358 435.228 151 425.75 151 407s-12.642-28.228-21.006-34.497C122.436 366.819 121 365.149 121 362c0-.56.148-1.027.256-1.516 18.1-3.408 34.957-12.94 49.167-27.079C122.899 299.138 91 236.466 91 166c0-26.453 4.523-51.669 12.634-74.692C46.857 92.97 0 152.599 0 226zM242.04 177.807l-1.597 9.595 8.643-4.482c2.168-1.128 4.541-1.685 6.914-1.685s4.746.557 6.914 1.685l8.643 4.482-1.597-9.595a14.984 14.984 0 014.263-13.14l6.943-6.841-9.639-1.45a15.027 15.027 0 01-11.177-8.13l-4.35-8.701-4.351 8.701a15.027 15.027 0 01-11.191 8.13l-9.624 1.45 6.943 6.826a15.012 15.012 0 014.263 13.155z"/><path d="M230.235 328.906C221.963 335.182 211 344.468 211 362c0 18.75 12.642 28.228 21.006 34.497C239.564 402.181 241 403.851 241 407s-1.436 4.819-8.994 10.503C223.642 423.772 211 433.25 211 452s12.642 28.228 21.006 34.497C239.564 492.181 241 493.851 241 497c0 8.291 6.709 15 15 15s15-6.709 15-15c0-18.75-12.642-28.228-21.006-34.497C242.436 456.819 241 455.149 241 452s1.436-4.819 8.994-10.503C258.358 435.228 271 425.75 271 407s-12.642-28.228-21.006-34.497C242.436 366.819 241 365.149 241 362s1.436-4.819 8.994-10.503c5.867-4.398 13.641-10.583 17.878-20.231C336.764 323.881 391 252.083 391 166 391 75.019 330.443 0 256 0S121 75.019 121 166c0 80.208 47.082 148.156 109.235 162.906zm-45.573-186.08a15.024 15.024 0 0112.041-10.21l31.597-4.746 14.282-28.579c5.098-10.166 21.738-10.166 26.836 0L283.7 127.87l31.597 4.746a15.03 15.03 0 0112.026 10.195 15 15 0 01-3.735 15.322l-22.764 22.427 5.244 31.523a14.967 14.967 0 01-5.977 14.59 14.998 14.998 0 01-15.732 1.187L256 213.139l-28.359 14.722c-5.068 2.607-11.162 2.153-15.732-1.187a14.968 14.968 0 01-5.977-14.59l5.244-31.523-22.778-22.412c-4.044-4-5.494-9.932-3.736-15.323z"/></svg>
                <p class="text-muted pt-3 mb-0">No Reminder Found.</p>
            </div>
        @endif
    </div>
</div>
