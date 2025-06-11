@extends('admin::layout')

@section('title')
    Dashboard
@endsection
@section('css')
    <style>
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-body {
            position: static !important;
            /* Ensure it's not absolute or fixed */
            overflow: hidden;
            /* Prevent content from overflowing */
        }
    </style>
@endsection

@section('breadcrum')
    <a href="{{ route('dashboard') }}" class="breadcrumb-item">Regular</a>
    <div class="custom-control custom-switch ml-3 mt-2" class="breadcrumb-item">
        <input type="checkbox" class="custom-control-input" id="customSwitch1">
        <label class="custom-control-label" for="customSwitch1">Graphical</label>
    </div>
@endsection

@section('content')
    <div class="row regular">
        <div class="col-md-9">
            @include('admin::admin.partial.counter')
            <div class="row">
                <div class="col-md-4">
                    @include('admin::employee.partial.announcements')
                </div>
                <div class="col-md-4">
                    @include('admin::employee.partial.events')
                </div>
                <div class="col-md-4">
                    @include('admin::employee.partial.birthday')
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    @include('admin::employee.partial.on-leave')

                </div>
                <div class="col-lg-4">
                    @include('admin::employee.partial.mobile-app-attendance')
                </div>
            </div>
        </div>
        <div class="col-md-3">
            @include('admin::admin.partial.reminder', ['height' => '427px'])
            @include('admin::employee.partial.job-end')
        </div>
    </div>

    <div class="row graphical">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-transparent d-flex justify-content-between">
                            <h3 class="card-title">Employee Distribution on Level & Martial Status</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <div class="chart has-fixed-height" id="levelChart"
                                                    data-value="{{ json_encode($levelData) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <div class="chart has-fixed-height" id="maritalStatusChart"
                                                    data-value="{{ json_encode($maritalStatusData) }}"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-transparent d-flex justify-content-between">
                            <h3 class="card-title">Employee Distribution & Gender Status</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <div class="chart has-fixed-height" id="pie_basic"></div>
                                                <input type="hidden" id="legendValues"
                                                    value="{{ json_encode($organizationwiseEmployees) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    @include('admin::admin.partial.gender_stat')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row regular">
        <div class="col-md-12">
            @include('admin::employee.partial.leaves')
        </div>
    </div>
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

    <script>
        $(document).ready(function() {
            $(".toggle-btn").click(function() {
                console.log("Toggle button clicked");
                let cardBody = $(this).closest(".card").find(".card-body");
                let icon = $(this).find("i");

                console.log("Card body visibility before toggle:", cardBody.is(":visible"));

                cardBody.slideToggle(200, function() {
                    console.log("Card body visibility after toggle:", cardBody.is(":visible"));
                    if (cardBody.is(":visible")) {
                        icon.removeClass("icon-plus3").addClass("icon-minus3");
                    } else {
                        icon.removeClass("icon-minus3").addClass("icon-plus3");
                    }
                });

                return false;
            });

            $(".remove-btn").click(function() {
                console.log("Remove button clicked");
                $(this).closest(".card").fadeOut(300);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".graphical").hide();
            // Graphical Toggle Button (Custom Switch)
            $('#customSwitch1').change(function() {
                if (this.checked) {
                    console.log("Graphical Toggle Button is ON");
                    $('.regular').hide();
                    $('.graphical').show();
                } else {
                    console.log("Graphical Toggle Button is OFF");
                    $('.regular').show();
                    $('.graphical').hide();
                }
            });
        })
    </script>
@endsection
