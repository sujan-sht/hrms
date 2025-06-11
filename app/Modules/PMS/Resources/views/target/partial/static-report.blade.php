@extends('admin::layout')
@section('title') PMS @stop
@section('breadcrum') PMS @stop

@section('content')


    <div class="card">
        <div class="bg-slate card-header header-elements-inline border-bottom-0">
            <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Employee:</label>
                        <div class="input-group">
                            {!! Form::select('employee_id', ['Tester', 'Developer'], $value = null, [
                                'id' => 'employee',
                                'class' => 'form-control select-search',
                                'placeholder' => 'Select Employee',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Sub-Function:</label>
                        <div class="input-group">
                            {!! Form::select('employee_id', ['HR', 'Administration'], $value = null, [
                                'id' => 'employee',
                                'class' => 'form-control select-search',
                                'placeholder' => 'Select Sub-Function',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Division:</label>
                        <div class="input-group">
                            {!! Form::select('employee_id', ['Corporate', 'Head Office'], $value = null, [
                                'id' => 'employee',
                                'class' => 'form-control select-search',
                                'placeholder' => 'Select Division',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">&nbsp;</label>
                        <a href="#pmsList" class="btn bg-primary ml-2" data-bs-toggle="collapse">
                            <i class="icon-search4 pr-2"></i>Search
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="collapse" id="pmsList">

        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h4 class="card-title">Q1</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <a href="#"
                                        class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                        <i class="icon-target2"></i>
                                    </a>
                                    <div class="ml-3">
                                        <h5 class="font-weight-semibold mb-0">34</h5>
                                        <span class="text-muted">TARGET</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <a href="#"
                                        class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                        <i class="icon-trophy2"></i>
                                    </a>
                                    <div class="ml-3">
                                        <h5 class="font-weight-semibold mb-0">30.5</h5>
                                        <span class="text-muted">ACHIEVEMENT</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <a href="#"
                                        class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                        <i class="icon-medal-second"></i>
                                    </a>
                                    <div class="ml-3">
                                        <h5 class="font-weight-semibold mb-0">89%</h5>
                                        <span class="text-muted">ACHIEVEMENT</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <a href="#"
                                        class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                        <i class="icon-medal-star"></i>
                                    </a>
                                    <div class="ml-3">
                                        <h5 class="font-weight-semibold mb-0">85%</h5>
                                        <span class="text-muted">SCORE</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <a href="#q1-link-collapsed" class="btn btn-success rounded-pill border-2 btn-icon"
                                    data-bs-toggle="collapse">
                                    View Detail
                                </a>
                                <a href="#final-link-collapsed" class="btn btn-primary rounded-pill border-2 btn-icon"
                                    data-bs-toggle="collapse">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h4 class="card-title">Q2</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <a href="#"
                                        class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                        <i class="icon-target2"></i>
                                    </a>
                                    <div class="ml-3">
                                        <h5 class="font-weight-semibold mb-0">36</h5>
                                        <span class="text-muted">TARGET</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <a href="#"
                                        class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                        <i class="icon-trophy2"></i>
                                    </a>
                                    <div class="ml-3">
                                        <h5 class="font-weight-semibold mb-0">31</h5>
                                        <span class="text-muted">ACHIEVEMENT</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <a href="#"
                                        class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                        <i class="icon-medal-second"></i>
                                    </a>
                                    <div class="ml-3">
                                        <h5 class="font-weight-semibold mb-0">83%</h5>
                                        <span class="text-muted">ACHIEVEMENT</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <a href="#"
                                        class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                        <i class="icon-medal-star"></i>
                                    </a>
                                    <div class="ml-3">
                                        <h5 class="font-weight-semibold mb-0">84%</h5>
                                        <span class="text-muted">SCORE</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <a href="#q2-link-collapsed" class="btn btn-success rounded-pill border-2 btn-icon"
                                    data-bs-toggle="collapse">
                                    View Detail
                                </a>
                                <a href="#final-link-collapsed" class="btn btn-primary rounded-pill border-2 btn-icon"
                                    data-bs-toggle="collapse">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h4 class="card-title">Q3</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <a href="#"
                                        class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                        <i class="icon-target2"></i>
                                    </a>
                                    <div class="ml-3">
                                        <h5 class="font-weight-semibold mb-0">33</h5>
                                        <span class="text-muted">TARGET</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <a href="#"
                                        class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                        <i class="icon-trophy2"></i>
                                    </a>
                                    <div class="ml-3">
                                        <h5 class="font-weight-semibold mb-0">26.5</h5>
                                        <span class="text-muted">ACHIEVEMENT</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <a href="#"
                                        class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                        <i class="icon-medal-second"></i>
                                    </a>
                                    <div class="ml-3">
                                        <h5 class="font-weight-semibold mb-0">87%</h5>
                                        <span class="text-muted">ACHIEVEMENT</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <a href="#"
                                        class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                        <i class="icon-medal-star"></i>
                                    </a>
                                    <div class="ml-3">
                                        <h5 class="font-weight-semibold mb-0">83%</h5>
                                        <span class="text-muted">SCORE</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <a href="#q3-link-collapsed" class="btn btn-success rounded-pill border-2 btn-icon"
                                    data-bs-toggle="collapse">
                                    View Detail
                                </a>
                                <a href="#final-link-collapsed" class="btn btn-primary rounded-pill border-2 btn-icon"
                                    data-bs-toggle="collapse">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h4 class="card-title">Q4</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <a href="#"
                                        class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                        <i class="icon-target2"></i>
                                    </a>
                                    <div class="ml-3">
                                        <h5 class="font-weight-semibold mb-0">34</h5>
                                        <span class="text-muted">TARGET</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <a href="#"
                                        class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                        <i class="icon-trophy2"></i>
                                    </a>
                                    <div class="ml-3">
                                        <h5 class="font-weight-semibold mb-0">29.80</h5>
                                        <span class="text-muted">ACHIEVEMENT</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <a href="#"
                                        class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                        <i class="icon-medal-first"></i>
                                    </a>
                                    <div class="ml-3">
                                        <h5 class="font-weight-semibold mb-0">95%</h5>
                                        <span class="text-muted">ACHIEVEMENT</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <a href="#"
                                        class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                                        <i class="icon-medal-star"></i>
                                    </a>
                                    <div class="ml-3">
                                        <h5 class="font-weight-semibold mb-0">82%</h5>
                                        <span class="text-muted">SCORE</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <a href="#q4-link-collapsed" class="btn btn-success rounded-pill border-2 btn-icon"
                                    data-bs-toggle="collapse">
                                    View Detail
                                </a>
                                <a href="#final-link-collapsed" class="btn btn-primary rounded-pill border-2 btn-icon"
                                    data-bs-toggle="collapse">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="collapse" id="q1-link-collapsed">
            <div class="card card-body">
                <table class="table table-bordered">
                    <thead class="bg-slate text-center">
                        <tr>
                            <th rowspan="3">S.N</th>
                            <th rowspan="3">KRA</th>
                            <th rowspan="3">KPIs</th>
                            <th rowspan="3">Target</th>
                            <th rowspan="3">Frequency/Age</th>
                            <th rowspan="3">Weightage</th>
                            <th colspan="4">TARGET VS ACHIEVEMENT</th>
                        </tr>
                        <tr>
                            <th colspan="4">Q1</th>
                        </tr>
                        <tr>
                            <th>TGT</th>
                            <th>ACH</th>
                            <th>ACH (%)</th>
                            <th>SCORE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2">1</td>
                            <td rowspan="2">Training Analytics</td>
                            <td>Plan Corporate Training Calendar for next year</td>
                            <td>By end of FY</td>
                            <td>Yearly</td>
                            <td>10%</td>
                            <td>1</td>
                            <td>1</td>
                            <td>100%</td>
                            <td>10%</td>
                        </tr>
                        <tr>
                            <td>Prepare Training MIS</td>
                            <td>Before 5th of every month</td>
                            <td>Monthly</td>
                            <td>20%</td>
                            <td>3</td>
                            <td>2</td>
                            <td>67%</td>
                            <td>13%</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Employee Induction</td>
                            <td>New employee orientation</td>
                            <td>On first day of joining</td>
                            <td>Daily</td>
                            <td>10%</td>
                            <td>10</td>
                            <td>10</td>
                            <td>100%</td>
                            <td>10%</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Management Trainee</td>
                            <td>Coordinate with facititators to ensure classroom session</td>
                            <td>Classroom session rating above 4</td>
                            <td>Yearly</td>
                            <td>20%</td>
                            <td>4</td>
                            <td>4.5</td>
                            <td>113%</td>
                            <td>20%</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Individual Development Plan</td>
                            <td>Talent pool updated record</td>
                            <td>By 3rd of every month</td>
                            <td>Monthly</td>
                            <td>10%</td>
                            <td>3</td>
                            <td>2</td>
                            <td>67%</td>
                            <td>7%</td>
                        </tr>
                        <tr>
                            <td rowspan="2">5</td>
                            <td rowspan="2">Training Achievement</td>
                            <td>Training mandays achievemeny</td>
                            <td>Archieve 4 training mandays</td>
                            <td>Quaterly</td>
                            <td>10%</td>
                            <td>4</td>
                            <td>3.5</td>
                            <td>88%</td>
                            <td>9%</td>
                        </tr>
                        <tr>
                            <td>Training feedback</td>
                            <td>Archieve rating above 4</td>
                            <td>Quaterly</td>
                            <td>10%</td>
                            <td>4</td>
                            <td>4.5</td>
                            <td>113%</td>
                            <td>10%</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Employer Branding</td>
                            <td>Design and Update LinkedIn Posts</td>
                            <td>5 posts on LinkedIn per month</td>
                            <td>Monthly</td>
                            <td>10%</td>
                            <td>5</td>
                            <td>3</td>
                            <td>60%</td>
                            <td>6%</td>
                        </tr>
                        <tr>
                            <td colspan="5"><b>Final Score</b></td>
                            <td><b>100%</b></td>
                            <td colspan="3">&nbsp;</td>
                            <td><b>85%</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="collapse" id="q2-link-collapsed">
            <div class="card card-body">
                <table class="table table-bordered">
                    <thead class="bg-slate text-center">
                        <tr>
                            <th rowspan="3">S.N</th>
                            <th rowspan="3">KRA</th>
                            <th rowspan="3">KPIs</th>
                            <th rowspan="3">Target</th>
                            <th rowspan="3">Frequency/Age</th>
                            <th rowspan="3">Weightage</th>
                            <th colspan="4">TARGET VS ACHIEVEMENT</th>
                        </tr>
                        <tr>
                            <th colspan="4">Q2</th>
                        </tr>
                        <tr>
                            <th>TGT</th>
                            <th>ACH</th>
                            <th>ACH (%)</th>
                            <th>SCORE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2">1</td>
                            <td rowspan="2">Training Analytics</td>
                            <td>Plan Corporate Training Calendar for next year</td>
                            <td>By end of FY</td>
                            <td>Yearly</td>
                            <td>10%</td>
                            <td>1</td>
                            <td>1</td>
                            <td>100%</td>
                            <td>10%</td>
                        </tr>
                        <tr>
                            <td>Prepare Training MIS</td>
                            <td>Before 5th of every month</td>
                            <td>Monthly</td>
                            <td>20%</td>
                            <td>3</td>
                            <td>3</td>
                            <td>100%</td>
                            <td>20%</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Employee Induction</td>
                            <td>New employee orientation</td>
                            <td>On first day of joining</td>
                            <td>Daily</td>
                            <td>10%</td>
                            <td>12</td>
                            <td>12</td>
                            <td>100%</td>
                            <td>10%</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Management Trainee</td>
                            <td>Coordinate with facititators to ensure classroom session</td>
                            <td>Classroom session rating above 4</td>
                            <td>Yearly</td>
                            <td>20%</td>
                            <td>4</td>
                            <td>3</td>
                            <td>75%</td>
                            <td>15%</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Individual Development Plan</td>
                            <td>Talent pool updated record</td>
                            <td>By 3rd of every month</td>
                            <td>Monthly</td>
                            <td>10%</td>
                            <td>3</td>
                            <td>2</td>
                            <td>67%</td>
                            <td>7%</td>
                        </tr>
                        <tr>
                            <td rowspan="2">5</td>
                            <td rowspan="2">Training Achievement</td>
                            <td>Training mandays achievemeny</td>
                            <td>Archieve 4 training mandays</td>
                            <td>Quaterly</td>
                            <td>10%</td>
                            <td>4</td>
                            <td>2</td>
                            <td>50%</td>
                            <td>5%</td>
                        </tr>
                        <tr>
                            <td>Training feedback</td>
                            <td>Archieve rating above 4</td>
                            <td>Quaterly</td>
                            <td>10%</td>
                            <td>4</td>
                            <td>3</td>
                            <td>75%</td>
                            <td>8%</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Employer Branding</td>
                            <td>Design and Update LinkedIn Posts</td>
                            <td>5 posts on LinkedIn per month</td>
                            <td>Monthly</td>
                            <td>10%</td>
                            <td>5</td>
                            <td>5</td>
                            <td>100%</td>
                            <td>10%</td>
                        </tr>
                        <tr>
                            <td colspan="5"><b>Final Score</b></td>
                            <td><b>100%</b></td>
                            <td colspan="3">&nbsp;</td>
                            <td><b>84%</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="collapse" id="q3-link-collapsed">
            <div class="card card-body">
                <table class="table table-bordered">
                    <thead class="bg-slate text-center">
                        <tr>
                            <th rowspan="3">S.N</th>
                            <th rowspan="3">KRA</th>
                            <th rowspan="3">KPIs</th>
                            <th rowspan="3">Target</th>
                            <th rowspan="3">Frequency/Age</th>
                            <th rowspan="3">Weightage</th>
                            <th colspan="4">TARGET VS ACHIEVEMENT</th>
                        </tr>
                        <tr>
                            <th colspan="4">Q3</th>
                        </tr>
                        <tr>
                            <th>TGT</th>
                            <th>ACH</th>
                            <th>ACH (%)</th>
                            <th>SCORE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2">1</td>
                            <td rowspan="2">Training Analytics</td>
                            <td>Plan Corporate Training Calendar for next year</td>
                            <td>By end of FY</td>
                            <td>Yearly</td>
                            <td>10%</td>
                            <td>1</td>
                            <td>1</td>
                            <td>100%</td>
                            <td>10%</td>
                        </tr>
                        <tr>
                            <td>Prepare Training MIS</td>
                            <td>Before 5th of every month</td>
                            <td>Monthly</td>
                            <td>20%</td>
                            <td>3</td>
                            <td>2</td>
                            <td>67%</td>
                            <td>13%</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Employee Induction</td>
                            <td>New employee orientation</td>
                            <td>On first day of joining</td>
                            <td>Daily</td>
                            <td>10%</td>
                            <td>10</td>
                            <td>5</td>
                            <td>50%</td>
                            <td>5%</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Management Trainee</td>
                            <td>Coordinate with facititators to ensure classroom session</td>
                            <td>Classroom session rating above 4</td>
                            <td>Yearly</td>
                            <td>20%</td>
                            <td>4</td>
                            <td>4</td>
                            <td>100%</td>
                            <td>20%</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Individual Development Plan</td>
                            <td>Talent pool updated record</td>
                            <td>By 3rd of every month</td>
                            <td>Monthly</td>
                            <td>10%</td>
                            <td>3</td>
                            <td>4</td>
                            <td>133%</td>
                            <td>10%</td>
                        </tr>
                        <tr>
                            <td rowspan="2">5</td>
                            <td rowspan="2">Training Achievement</td>
                            <td>Training mandays achievemeny</td>
                            <td>Archieve 4 training mandays</td>
                            <td>Quaterly</td>
                            <td>10%</td>
                            <td>4</td>
                            <td>3</td>
                            <td>75%</td>
                            <td>8%</td>
                        </tr>
                        <tr>
                            <td>Training feedback</td>
                            <td>Archieve rating above 4</td>
                            <td>Quaterly</td>
                            <td>10%</td>
                            <td>4</td>
                            <td>3.5</td>
                            <td>88%</td>
                            <td>9%</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Employer Branding</td>
                            <td>Design and Update LinkedIn Posts</td>
                            <td>5 posts on LinkedIn per month</td>
                            <td>Monthly</td>
                            <td>10%</td>
                            <td>5</td>
                            <td>4</td>
                            <td>80%</td>
                            <td>8%</td>
                        </tr>
                        <tr>
                            <td colspan="5"><b>Final Score</b></td>
                            <td><b>100%</b></td>
                            <td colspan="3">&nbsp;</td>
                            <td><b>83%</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="collapse" id="q4-link-collapsed">
            <div class="card card-body">
                <table class="table table-bordered">
                    <thead class="bg-slate text-center">
                        <tr>
                            <th rowspan="3">S.N</th>
                            <th rowspan="3">KRA</th>
                            <th rowspan="3">KPIs</th>
                            <th rowspan="3">Target</th>
                            <th rowspan="3">Frequency/Age</th>
                            <th rowspan="3">Weightage</th>
                            <th colspan="4">TARGET VS ACHIEVEMENT</th>
                        </tr>
                        <tr>
                            <th colspan="4">Q4</th>
                        </tr>
                        <tr>
                            <th>TGT</th>
                            <th>ACH</th>
                            <th>ACH (%)</th>
                            <th>SCORE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2">1</td>
                            <td rowspan="2">Training Analytics</td>
                            <td>Plan Corporate Training Calendar for next year</td>
                            <td>By end of FY</td>
                            <td>Yearly</td>
                            <td>10%</td>
                            <td>1</td>
                            <td>1</td>
                            <td>100%</td>
                            <td>10%</td>
                        </tr>
                        <tr>
                            <td>Prepare Training MIS</td>
                            <td>Before 5th of every month</td>
                            <td>Monthly</td>
                            <td>20%</td>
                            <td>3</td>
                            <td>1</td>
                            <td>33%</td>
                            <td>7%</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Employee Induction</td>
                            <td>New employee orientation</td>
                            <td>On first day of joining</td>
                            <td>Daily</td>
                            <td>10%</td>
                            <td>10</td>
                            <td>5</td>
                            <td>50%</td>
                            <td>5%</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Management Trainee</td>
                            <td>Coordinate with facititators to ensure classroom session</td>
                            <td>Classroom session rating above 4</td>
                            <td>Yearly</td>
                            <td>20%</td>
                            <td>4</td>
                            <td>4</td>
                            <td>100%</td>
                            <td>20%</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Individual Development Plan</td>
                            <td>Talent pool updated record</td>
                            <td>By 3rd of every month</td>
                            <td>Monthly</td>
                            <td>10%</td>
                            <td>3</td>
                            <td>4</td>
                            <td>133%</td>
                            <td>10%</td>
                        </tr>
                        <tr>
                            <td rowspan="2">5</td>
                            <td rowspan="2">Training Achievement</td>
                            <td>Training mandays achievemeny</td>
                            <td>Archieve 4 training mandays</td>
                            <td>Quaterly</td>
                            <td>10%</td>
                            <td>4</td>
                            <td>5</td>
                            <td>125%</td>
                            <td>10%</td>
                        </tr>
                        <tr>
                            <td>Training feedback</td>
                            <td>Archieve rating above 4</td>
                            <td>Quaterly</td>
                            <td>10%</td>
                            <td>4</td>
                            <td>4.8</td>
                            <td>120%</td>
                            <td>10%</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Employer Branding</td>
                            <td>Design and Update LinkedIn Posts</td>
                            <td>5 posts on LinkedIn per month</td>
                            <td>Monthly</td>
                            <td>10%</td>
                            <td>5</td>
                            <td>5</td>
                            <td>100%</td>
                            <td>10%</td>
                        </tr>
                        <tr>
                            <td colspan="5"><b>Final Score</b></td>
                            <td><b>100%</b></td>
                            <td colspan="3">&nbsp;</td>
                            <td><b>82%</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="collapse" id="final-link-collapsed">
            <div class="card card-body">
                <table class="table table-bordered table-responsive">
                    <thead class="bg-slate text-center">
                        <tr>
                            <th rowspan="3">S.N</th>
                            <th rowspan="3">KRA</th>
                            <th rowspan="3">KPIs</th>
                            <th rowspan="3">Target</th>
                            <th rowspan="3">Frequency/Age</th>
                            <th rowspan="3">Weightage</th>
                            <th colspan="16">TARGET VS ACHIEVEMENT</th>
                            <th rowspan="3">YTD</th>
                            <th rowspan="3">Supporting Documents</th>
                        </tr>
                        <tr>
                            <th colspan="4">Q1</th>
                            <th colspan="4">Q2</th>
                            <th colspan="4">Q3</th>
                            <th colspan="4">Q4</th>
                        </tr>
                        <tr>
                            <th>TGT</th>
                            <th>ACH</th>
                            <th>ACH(%)</th>
                            <th>SCORE</th>
                            <th>TGT</th>
                            <th>ACH</th>
                            <th>ACH(%)</th>
                            <th>SCORE</th>
                            <th>TGT</th>
                            <th>ACH</th>
                            <th>ACH(%)</th>
                            <th>SCORE</th>
                            <th>TGT</th>
                            <th>ACH</th>
                            <th>ACH(%)</th>
                            <th>SCORE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2">1</td>
                            <td rowspan="2">Training Analytics</td>
                            <td>Plan Corporate Training Calendar for next year</td>
                            <td>By end of FY</td>
                            <td>Yearly</td>
                            <td>10%</td>

                            <td>1</td>
                            <td>1</td>
                            <td>100%</td>
                            <td>10%</td>

                            <td>1</td>
                            <td>1</td>
                            <td>100%</td>
                            <td>10%</td>

                            <td>1</td>
                            <td>1</td>
                            <td>100%</td>
                            <td>10%</td>

                            <td>1</td>
                            <td>1</td>
                            <td>100%</td>
                            <td>10%</td>

                            <td>10%</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Prepare Training MIS</td>
                            <td>Before 5th of every month</td>
                            <td>Monthly</td>
                            <td>20%</td>
                            <td>3</td>
                            <td>2</td>
                            <td>67%</td>
                            <td>13%</td>
                            <td>3</td>
                            <td>3</td>
                            <td>100%</td>
                            <td>20%</td>
                            <td>3</td>
                            <td>2</td>
                            <td>67%</td>
                            <td>13%</td>
                            <td>3</td>
                            <td></td>
                            <td>33%</td>
                            <td>7%</td>
                            <td>13%</td>
                            <td>Email records</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Employee Induction</td>
                            <td>New employee orientation</td>
                            <td>On first day of joining</td>
                            <td>Daily</td>
                            <td>10%</td>
                            <td>10</td>
                            <td>10</td>
                            <td>100%</td>
                            <td>10%</td>
                            <td>12</td>
                            <td>12</td>
                            <td>100%</td>
                            <td>10%</td>
                            <td>10</td>
                            <td>5</td>
                            <td>50%</td>
                            <td>5%</td>
                            <td>10</td>
                            <td>5</td>
                            <td>50%</td>
                            <td>5%</td>
                            <td>8%</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Management Trainee</td>
                            <td>Coordinate with facititators to ensure classroom session</td>
                            <td>Classroom session rating above 4</td>
                            <td>Yearly</td>
                            <td>20%</td>
                            <td>4</td>
                            <td>4.5</td>
                            <td>113%</td>
                            <td>20%</td>
                            <td>4</td>
                            <td>3</td>
                            <td>75%</td>
                            <td>15%</td>
                            <td>4</td>
                            <td>4</td>
                            <td>100%</td>
                            <td>20%</td>
                            <td>4</td>
                            <td>4</td>
                            <td>100%</td>
                            <td>20%</td>
                            <td>19%</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Individual Development Plan</td>
                            <td>Talent pool updated record</td>
                            <td>By 3rd of every month</td>
                            <td>Monthly</td>
                            <td>10%</td>
                            <td>3</td>
                            <td>2</td>
                            <td>67%</td>
                            <td>7%</td>
                            <td>3</td>
                            <td>2</td>
                            <td>67%</td>
                            <td>7%</td>
                            <td>3</td>
                            <td>4</td>
                            <td>133%</td>
                            <td>10%</td>
                            <td>3</td>
                            <td>4</td>
                            <td>133%</td>
                            <td>10%</td>
                            <td>8%</td>
                            <td>Email records</td>
                        </tr>
                        <tr>
                            <td rowspan="2">5</td>
                            <td rowspan="2">Training Achievement</td>
                            <td>Training mandays achievemeny</td>
                            <td>Archieve 4 training mandays</td>
                            <td>Quaterly</td>
                            <td>10%</td>
                            <td>4</td>
                            <td>3.5</td>
                            <td>88%</td>
                            <td>9%</td>
                            <td>4</td>
                            <td>2</td>
                            <td>50%</td>
                            <td>5%</td>
                            <td>4</td>
                            <td>3</td>
                            <td>75%</td>
                            <td>8%</td>
                            <td>4</td>
                            <td>5</td>
                            <td>125%</td>
                            <td>10%</td>
                            <td>8%</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Training feedback</td>
                            <td>Archieve rating above 4</td>
                            <td>Quaterly</td>
                            <td>10%</td>
                            <td>4</td>
                            <td>4.5</td>
                            <td>113%</td>
                            <td>10%</td>
                            <td>4</td>
                            <td>3</td>
                            <td>75%</td>
                            <td>8%</td>
                            <td>4</td>
                            <td>3.5</td>
                            <td>88%</td>
                            <td>9%</td>
                            <td>4</td>
                            <td>4.8</td>
                            <td>120%</td>
                            <td>10%</td>
                            <td>9%</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Employer Branding</td>
                            <td>Design and Update LinkedIn Posts</td>
                            <td>5 posts on LinkedIn per month</td>
                            <td>Monthly</td>
                            <td>10%</td>
                            <td>5</td>
                            <td>3</td>
                            <td>60%</td>
                            <td>6%</td>
                            <td>5</td>
                            <td>5</td>
                            <td>100%</td>
                            <td>10%</td>
                            <td>5</td>
                            <td>4</td>
                            <td>80%</td>
                            <td>8%</td>
                            <td>5</td>
                            <td>5</td>
                            <td>100%</td>
                            <td>10%</td>
                            <td>9%</td>
                            <td>Post details</td>
                        </tr>
                        <tr>
                            <td colspan="5"><b>Final Score</b></td>
                            <td><b>100%</b></td>
                            <td colspan="3">&nbsp;</td>
                            <td><b>85%</b></td>
                            <td colspan="3">&nbsp;</td>
                            <td><b>84%</b></td>
                            <td colspan="3">&nbsp;</td>
                            <td><b>83%</b></td>
                            <td colspan="3">&nbsp;</td>
                            <td><b>82%</b></td>
                            <td><b>83%</b></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endSection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
@endSection
