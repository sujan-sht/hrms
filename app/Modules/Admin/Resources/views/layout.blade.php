<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    <title>Bidhee HRMS - @yield('title')</title>

    <!-- Global stylesheets -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Signika+Negative&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
        type="text/css">

    <link href="{{ asset('admin/global/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/custom.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <link href="{{ asset('admin/css/additional.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{{ asset('admin/css/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/nrj_custom.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/dataTables.bootstrap4.css') }}">

    <!-- Core JS files -->
    <script src="{{ asset('admin/global/js/main/jquery.min.js') }}"></script>
    {{-- <script src="/bootstrap.min.js"></script> --}}
    <script src="{{ asset('admin/global/js/main/bootstrap.bundle.min.js') }}"></script>
    <!-- /core JS files -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script> --}}
    <script src="{{ asset('admin/global/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

    <!-- Theme JS files -->
    <script src="{{ asset('admin/global/js/plugins/ui/moment/moment.min.js') }}"></script>
    {{-- <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script> --}}
    <script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>

    <script src="{{ asset('admin/global/js/demo_pages/dashboard.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/extensions/jquery_ui/interactions.min.js') }}"></script>

    {{-- Nepali calendar --}}
    <link rel="stylesheet" href="{{ asset('admin/nepali_calender4/css/nepali.datepicker.v4.0.min.css') }}">
    <script type="text/javascript" src="{{ asset('admin/nepali_calender4/js/nepali.datepicker.v4.0.min.js') }}"></script>

    <script src="{{ asset('admin/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin/js/dataTables.js') }}"></script>
    <script src="{{ asset('admin/js/dataTables.bootstrap4.js') }}"></script>


    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/js/nrj_custom.js') }}"></script>
    <script src="{{ asset('admin/js/check-all.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('admin/assets/css/toastr.min.css') }}">
    <script src="{{ asset('admin/assets/js/plugins/toastr/toastr.min.js') }}"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
 <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>


    @yield('script')

    @yield('popupScript')
    <script src="{{ asset('admin/assets/js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".select-module-search").select2();
        });
    </script>
    <style type="text/css">
        .select2-results__option.select2-results__option--highlighted {
            background-color: #d0d0d0 !important;
        }
    </style>

    <style type="text/css">
        .table-responsive {
            height: 500px;
            overflow: scroll;
            background-image: url({{ asset('admin/hrms_background.png') }});
            background-position: center;
            background-size: cover;
        }

        thead tr:nth-child(1) th {
            background: #546e7a;
            /* position: sticky; */
            top: 0px;
            z-index: 1;
        }

        thead tr:nth-child(2) th {
            background: #546e7a;
            position: sticky;
            top: 60px;
            /* z-index: 2; */
        }

        thead tr:nth-child(3) th {
            background: #546e7a;
            position: sticky;
            top: 88px;
            z-index: 3;
        }

        #overlay {
            position: fixed;
            top: 0;
            z-index: 9999;
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(0, 0, 0, 0.6);
        }

        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner:before {
            content: '';
            box-sizing: border-box;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin-top: -10px;
            margin-left: -10px;
            border-radius: 50%;
            border: 2px solid #ccc;
            border-top-color: #000;
            animation: spinner .6s linear infinite;
        }

        .error {
            color: red;
        }

        #loading {
            position: fixed;
            display: flex;
            display: none;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0.7;
            background-color: #fff;
            z-index: 99;
        }

        #loading-image {
            z-index: 100;
            margin-left: 44%;
            margin-top: 17%;
            width: 20%;
        }

.cke_notifications_area{
    display: none !important;
}
    </style>

    @yield('css')


    <script type="text/javascript">
        $(document).ready(function() {
            $('.numeric').keyup(function() {
                if (this.value.match(/[^0-9.]/g)) {
                    this.value = this.value.replace(/[^0-9.]/g, '');
                }
            });

            $('.citizen-numeric').keyup(function() {
                if (this.value.match(/[^0-9-.]/g)) {
                    this.value = this.value.replace(/[^0-9-.]/g, '');
                }
            });

            function refreshTime() {
                var x = new Date()
                var ampm = x.getHours() >= 12 ? ' PM' : ' AM';
                hours = x.getHours() % 12;
                hours = hours ? hours : 12;
                hours = hours.toString().length == 1 ? 0 + hours.toString() : hours;
                minutes = x.getMinutes();
                minutes = minutes.toString().length == 1 ? 0 + minutes.toString() : minutes;
                seconds = x.getSeconds();
                seconds = seconds.toString().length == 1 ? 0 + seconds.toString() : seconds;
                x1 = hours + ":" + minutes + " " + ampm;
                x1 = hours + ":" + minutes + ":" + seconds + " " + ampm;
                document.getElementById('displayTime').innerHTML = x1;
            }
            setInterval(refreshTime, 1000);
            // refreshTime();

            // var todayDate = '2080-09-05'
            // console.log(todayDate);
            // $(".nepali-calendar-disable-previous-date").nepaliDatePicker({
            //     disableBefore : todayDate
            // });

            localStorage.setItem('user_type', '{{ auth()->user()->user_type ?? null }}');
            localStorage.setItem('calendar_type', "{{ setting('calendar_type') }}");
            localStorage.setItem('leave_year_calendar_type', "{{ leaveYearSetup('calendar_type') }}");

            $(".nepali-calendar").nepaliDatePicker();




        });
    </script>

    @stack('custom_script')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    <!-- Main navbar -->
    @php
        use Illuminate\Support\Facades\Auth;
        $user = Auth::user();
        $user_type = $user->user_type;
        $imagePath = asset('admin/default.png');
    @endphp

    @include('admin::includes.main-navbar')
    <!-- /main navbar -->


    <!-- Page content -->
    <div class="page-content">


        <!-- Main sidebar -->
        <div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg sidebar-main-resized">

            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!-- User menu -->
                @include('admin::includes.user-menu')
                <!-- /user menu -->

                <!-- Main navigation -->
                @include('admin::includes.navigation')
                <!-- /main navigation -->

            </div>
            <!-- /sidebar content -->

        </div>
        <!-- /main sidebar -->

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Inner content -->
            <div class="content-inner">

                <!-- Page header -->
                @include('admin::includes.page-header')
                <!-- /page header -->

                <!-- Content area -->
                <div class="content">
                    <div id="loading">
                        <img id="loading-image" src="{{ asset('admin/loader1.gif') }}" alt="Loading..." />
                    </div>
                    @yield('content')
                </div>
                <!-- /content area -->

                <!-- Footer -->
                @include('admin::includes.footer')
                <!-- /footer -->

            </div>
            <!-- /inner content -->

        </div>
        <!-- /main content -->

        <div id="cover-spin"></div>
    </div>
    <!-- /page content -->
<script>
    document.querySelectorAll('.ck-editor').forEach(function(el) {
        CKEDITOR.replace(el, {
            width: '100%',
            height: 300
        });
    });

    //


</script>

</body>

</html>
