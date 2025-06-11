@extends('admin::layout')
@section('title')Organizational Structure @stop
@section('breadcrum')
    <a href="{{ route('organizationalStructure.index') }}" class="breadcrumb-item">Organizational Structure</a>
    <a class="breadcrumb-item active">View Chart</a>
@stop
@section('script')
    <script src="{{ asset('admin/global/js/demo_charts/google/dark/other/org_chart.js') }}"></script>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div id="chart_div"></div>
            <input type="hidden" id="legendValuesOrg" value="{{ json_encode($orgStructureArr) }}">
        </div>
    </div>
@stop
