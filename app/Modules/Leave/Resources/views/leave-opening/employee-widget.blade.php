@if(auth()->user()->user_type == 'employee')
    <div class="stats">
        <div class="row">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('leave.index') }}" class="text-dark">
                            <div class="row">
                                <div class="col-md-8">
                                    <h1 class="font-weight-semibold mb-0">
                                        {{ isset($countLeave['Total']) ? sprintf('%02d', $countLeave['Total']) : 0 }}</h1>
                                    <h6>Leave Request</h6>
                                </div>
                                <div class="col-md-4 text-right">
                                    <i class="icon-briefcase3 icon-3x text-secondary mt-1 mb-3"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <img src="{{ asset('admin/widget-bg-secondary.png') }}">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('leave.index',['status'=>1]) }}" class="text-dark">
                            <div class="row">
                                <div class="col-md-8">
                                    <h1 class="font-weight-semibold mb-0">
                                        {{ isset($countLeave['Pending']) ? sprintf('%02d', $countLeave['Pending']) : 0 }}</h1>
                                    <h6>Pending Leave</h6>
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
                    <div class="card-body">
                        <a href="{{ route('leave.index',['status'=>3]) }}" class="text-dark">
                            <div class="row">
                                <div class="col-md-8">
                                    <h1 class="font-weight-semibold mb-0">
                                        {{ isset($countLeave['Accepted']) ? sprintf('%02d', $countLeave['Accepted']) : 0 }}</h1>
                                    <h6>Approved Leave</h6>
                                </div>
                                <div class="col-md-4 text-right">
                                    <i class="icon-user-check icon-3x text-secondary mt-1 mb-3"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <img src="{{ asset('admin/widget-bg-secondary.png') }}">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('leave.index',['status'=>4]) }}" class="text-dark">
                            <div class="row">
                                <div class="col-md-8">
                                    <h1 class="font-weight-semibold mb-0">
                                        {{ isset($countLeave['Rejected']) ? sprintf('%02d', $countLeave['Rejected']) : 0 }}
                                    </h1>
                                    <h6>Declined Leave</h6>
                                </div>
                                <div class="col-md-4 text-right">
                                    <i class="icon-user-cancel icon-3x text-secondary mt-1 mb-3"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <img src="{{ asset('admin/widget-bg-secondary.png') }}">
                </div>
            </div>

        </div>
    </div>
@endif

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Employee Leave Summary</h6>
            All the Employee Leave Summary Information will be listed below.
        </div>
        <div class="mt-1">
            <a href="{{ route('leaveOpening.exportLeaveSummaryReport',['id'=>$id, 'filters[leave_year_id]' => request('leave_year_id'), 'filters[leave_type_id]' => request('leave_type_id')]) }} }}"
                class="btn btn-success rounded-pill"><i class="icon-file-excel"></i> Export</a>
        </div>
    </div>
</div>

{{-- <div class="stats">
    <div class="row">
        <div class="col-xl-3 col-sm-6">
            <div class="card bg-secondary text-white"
                style="background-image: url(http://127.0.0.1:8000/admin/global/images/backgrounds/panel_bg.png); background-size: contain;">
                <div class="card-body text-center">
                    <div class="card-img-actions d-inline-block mb-3" style="width:150px; height:150px;">
                        <img class="img-fluid rounded-circle" src="http://127.0.0.1:8000/admin/default.png"
                            alt="" style="width: 100%; height: 100%; object-fit: cover;">
                        <div class="card-img-actions-overlay card-img rounded-circle">
                            <input type="hidden" value="1" id="user_parent_id_21">


                            <a data-toggle="modal" data-target="#"
                                class="btn btn-outline-white border-2 btn-icon rounded-pill remove_user_access"
                                link="" data-popup="tooltip" data-placement="bottom"
                                data-original-title="User Access Granted"><i class="icon-user-check"></i></a>
                            <a data-toggle="modal" data-target="#modal_parent_link"
                                class="ml-1 btn btn-outline-white border-2 btn-icon rounded-pill user_parent_link"
                                empid="21" data-placement="bottom" data-popup="tooltip"
                                data-original-title="Link With Parent"><i class="icon-user-plus"></i></a>

                        </div>
                    </div>

                    <h6 class="font-weight-semibold mb-0">Niraj Thike</h6>
                    <span class="d-block opacity-75">Sr. Software Engineer</span>
                    <ul class="list-inline list-inline-condensed mb-0 mt-2">
                        <li class="list-inline-item"><a href="http://127.0.0.1:8000/admin/employee/view/21"
                                class="btn btn-outline-primary btn-icon text-light border-1" data-popup="tooltip"
                                data-placement="bottom" data-original-title="View Employee">
                                <i class="icon-eye"></i></a>
                        </li>
                        <li class="list-inline-item"><a href="http://127.0.0.1:8000/admin/employee/edit/21"
                                class="btn btn-outline-success btn-icon text-light border-1" data-popup="tooltip"
                                data-placement="bottom" data-original-title="Edit Employee">
                                <i class="icon-pencil"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a data-toggle="modal" data-target="#modal_theme_warning_status"
                                class="btn btn-outline-warning text-light btn-icon border-1 status_employee"
                                employment_id="21" data-popup="tooltip" data-placement="bottom"
                                data-original-title="Move To Archive"><i class="icon-basket"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="ribbon-container">
                    <div class="ribbon bg-success">
                        <a class="text-light" href="" data-popup="tooltip"
                            data-original-title="Employee Status" data-placement="bottom">Active</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
