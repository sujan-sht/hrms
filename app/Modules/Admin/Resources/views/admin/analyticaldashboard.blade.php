@extends('admin::layout')

@section('title')
    Dashboard
@endsection

@section('breadcrum')
    <a href="{{ route('dashboard') }}" class="breadcrumb-item">Dashboard</a>
@endsection

@section('content')
    <legend class="text-uppercase font-size-sm font-weight-bold">List Reports</legend>
    <div class="row">
        <div class="col-md-8">
            @include('admin::employee.partial.leaves')
        </div>
    </div>

    {{-- <legend class="text-uppercase font-size-sm font-weight-bold">Chart Reports</legend> --}}
    <div class="row">
        {{-- <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="card-title">Training Report</h3>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <div class="chart has-fixed-height" id="area_basic"></div>
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="col-md-4">
            @include('admin::employee.partial.job-end')
        </div> --}}
    </div>


    {{-- <div class="card">
        <div class="card-header text-center">
            <h3 class="card-title">Recruitment Report</h3>
        </div>

        <div class="card-body">
            <div class="chart-container">
                <div class="chart has-fixed-height" id="area_stacked"></div>
            </div>
        </div>
    </div> --}}
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/visualization/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_charts/echarts/light/pies/pie_donut.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_charts/echarts/light/pies/pie_basic.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_charts/echarts/light/pies/pie_nested.js') }}"></script>
    <script src="{{ asset('global/js/demo_charts/echarts/light/pies/pie_infographic.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_charts/echarts/light/bars/columns_basic.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_charts/echarts/light/lines/area_basic.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_charts/echarts/light/lines/area_stacked.js') }}"></script>

    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ Config::get('admin.google-map') }}">
    </script>
@endsection
