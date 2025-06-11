<div class="row">
    <div class="col-lg-3">
        <div class="card sticky-top">
            <div class="card-header bg-transparent">
                <h6 class="card-title">
                    Advance Filter
                </h6>
            </div>
            <div class="card-body">
                <form action="http://127.0.0.1:8000/admin/organization/master-report/getLeaveReport" id="leaveSearchForm"
                    method="get">

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="example-email" class="form-label">Date Range</label>
                            <input placeholder="e.g : YYYY-MM-DD to YYYY-MM-DD" class="form-control leaveDateRange"
                                autocomplete="on" name="date_range" type="text">
                        </div>

                        <div class="col-md-12 mb-2">
                            <label class="form-label">Branch</label>
                            <select id="branch_id" class="form-control select2 branch-filter" name="branch_id">
                                <option selected="selected" value="">Select Branch</option>
                                <option value="1">Bidhee Koteshwor</option>
                                <option value="3">Bidhee Australia</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-2">
                            <label class="form-label">Attendance Request Type</label>
                            <select id="leave_type_id" class="form-control select2 leave-type-filter"
                                name="leave_type_id">
                                <option selected="selected" value="">Select Leave Type</option>
                                <option value="25">Annual Leave</option>
                                <option value="28">Casual Leave</option>
                                <option value="29">Sick Leave</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-left mt-2">
                        <button class="btn bg-success mr-2 text-white" type="submit">
                            <i class="icon-filter3 mr-1"></i>Search
                        </button>

                    </div>
                </form>


            </div>
        </div>
    </div>
    <div class="col-lg-9">

        <section class="leave-detail">
            <!-- Leave Summary -->
            <div class="card">
                <div class="card-header header-elements-sm-inline">
                    <h6 class="card-title">
                        Attendance Summary
                    </h6>
                    <div class="header-elements">
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body">
                                <div class="media">
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-pointer icon-3x text-info"></i>
                                    </div>

                                    <div class="media-body text-right">
                                        <h3 class="font-weight-semibold mb-0">
                                            2
                                        </h3>
                                        <span class="text-uppercase font-size-sm text-muted">Pending</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body">
                                <div class="media">
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-enter6 icon-3x text-secondary"></i>
                                    </div>

                                    <div class="media-body text-right">
                                        <h3 class="font-weight-semibold mb-0">
                                            3
                                        </h3>
                                        <span class="text-uppercase font-size-sm text-muted">Forwarded</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body">
                                <div class="media">
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-exit icon-3x text-teal"></i>
                                    </div>

                                    <div class="media-body text-right">
                                        <h3 class="font-weight-semibold mb-0">
                                            0
                                        </h3>
                                        <span class="text-uppercase font-size-sm text-muted">Accepted</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body">
                                <div class="media">
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-trash icon-3x text-danger"></i>
                                    </div>

                                    <div class="media-body text-right">
                                        <h3 class="font-weight-semibold mb-0">
                                            1
                                        </h3>
                                        <span class="text-uppercase font-size-sm text-muted">Rejected</span>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>

                    <div class="row">


                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive1">
                                        <table class="table table-dark bg-secondary">
                                            {{-- <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Username</th>
                                                </tr>
                                            </thead> --}}
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Missed Checkout</td>
                                                    <td>12</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Missed Check In</td>
                                                    <td>1</td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Early Departure</td>
                                                    <td>10</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="card">
                                {{-- <div class="card-header header-elements-inline">
                                    <h6 class="card-title">Attendance Type</h6>
                                </div> --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Leave Summary -->


        </section>
    </div>

</div>
