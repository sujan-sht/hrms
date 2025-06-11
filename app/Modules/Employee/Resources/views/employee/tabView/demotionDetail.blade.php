@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Demotion History</legend>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>S.N</th>
                                <th>Organization</th>
                                <th>Unit</th>
                                <th>Sub-Function</th>
                                <th>Grade</th>
                                <th>Designation</th>
                                <th>Functional Title</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody class="employeeDemotionTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('employee::employee.js.demotionDetailJsFunction')
