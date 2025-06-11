@extends('admin::layout')
@section('title')Request Statistics @stop

@section('breadcrum')
    <a href="{{ route('employeerequest.index') }}" class="breadcrumb-item">Request Management </a>
    <a class="breadcrumb-item active"> Statistics </a>
@endsection
@section('script')
    <!-- Theme JS files -->
    <script src="{{ asset('admin/js/visualization/d3/d3.min.js') }}"></script>
    <script src="{{ asset('admin/js/visualization/d3/d3_tooltip.js') }}"></script>
    <!--End Theme JS files -->
@stop

@section('content')

    <script src="{{ asset('admin/js/visualization/grievanceStats.js') }}"></script>

    <div class="content">
        <!-- Zoom option -->
        @if ($data['department_id'] == '0' or $data['userType'] == 'super_admin' or $data['userType'] == 'admin')
            <div class="card">
                <div class="card-header header-elements-inline bg-teal">
                    <h5 class="card-title">Request Statistics</h5>
                    <div class="header-elements">

                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-xl-12">
                            <!-- donut with details -->
                            <div class="card card-body text-center">
                                <h6 class="font-weight-semibold mb-0 mt-1">Request Status</h6>
                                <div class="font-size-sm text-muted mb-3">{{ date('d M, Y') }}</div>

                                <div class="svg-center" id="donut_basic_details"></div>
                            </div>
                            <!-- /donut with details -->
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <script>
        /* grievance status */
        var status_data = [{
            "status": "Pending",
            "icon": "<i class='badge badge-mark border-danger-300 mr-2'></i>",
            "value": "{{ $data['pending'] }}",
            "color": "#EF5350"
        }, {
            "status": "Approved",
            "icon": "<i class='badge badge-mark border-blue-300 mr-2'></i>",
            "value": "{{ $data['approved'] }}",
            "color": "#152ADF"
        }, {
            "status": "Canceled",
            "icon": "<i class='badge badge-mark border-success-300 mr-2'></i>",
            "value": "{{ $data['canceled'] }}",
            "color": "#114004"
        }];
        StatisticWidgets("#donut_basic_details", 146, status_data);

        var color = ["#29B6F6", "#66BB6A", "#EF5350", "#152ADF", "#114004", '#EC407A'];
        @php $tcolor = 0; @endphp
        StatisticWidgets("#donut_basic_details1", 146, type_data);
    </script>
@stop
