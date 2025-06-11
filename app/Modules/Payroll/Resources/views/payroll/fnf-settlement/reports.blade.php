@extends('admin::layout')
@section('title') FNF Settlement Reports @endSection
@section('breadcrum')
<a href="{{ route('payroll.index') }}" class="breadcrumb-item">Payroll</a>
<a class="breadcrumb-item active">FNF Settlement Reports</a>
@stop

@section('css')
<link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet" type="text/css">
<style type="text/css" media="print">
    @page {
        size: auto;
        /* auto is the initial value */
        margin: 0 50px 0 50px;
        /* this affects the margin in the printer settings */
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
            style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
    </div>
</div>
@include('payroll::payroll.fnf-settlement.partial.filter-report')
<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of F&F Reports</h6>
            All the F&F Reports will listed below.
        </div>
        
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="btn-slate text-light">
                    <th>S.N</th>
                    <th>Employee</th>
                    <th>Created At</th>
                    <th width="25%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if($fullAndFinalModel->total() != 0)
                    @foreach($fullAndFinalModel as $key => $data)
                        <tr>
                            <th>{{$key+1}}</th>
                            <th>{{$data->employee->getFullName()}}</th>
                            <th>{{$data->created_at}}</th>
                            <th>
                                <a href="{{ route('payroll.fnfSettlement-reports-view', $data->id) }}" class="btn btn-sm btn-outline-secondary btn-icon updateStatus mr-1" >
                                    <i class="icon-eye"></i>
                                </a>
                            </th>
                        </tr>
                    @endforeach
                @else
                <tr>
                    <td colspan="5">No Record Found !!!</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $fullAndFinalModel->appends(request()->all())->links() }}
        </span>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('admin/js/jquery.table2excel.js') }}"></script>

@endsection
