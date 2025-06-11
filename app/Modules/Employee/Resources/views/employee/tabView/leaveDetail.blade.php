<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Leave Report
                        </legend>
                    </div>
                </div>
                <div class="leaveReportTable"></div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Leave Remaining
                        </legend>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>S.N</th>
                                <th>Leave Type</th>
                                <th>Opening Leave</th>
                                <th>Earned Leave</th>
                                <th>Total Leave</th>
                                <th>Leave Taken</th>
                                <th>Remaining Leave</th>
                            </tr>
                        </thead>
                        <tbody class="leaveRemainingTable">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('employee::employee.js.leaveDetailJsFunction')
