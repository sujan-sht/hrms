@extends('admin::layout')
@section('title')Notice @stop

@section('breadcrum')
    <a href="{{ route('notice.index') }}" class="breadcrumb-item">Notice</a>
    <a class="breadcrumb-item active">Create</a>
@endsection

@section('script')
    <!-- Theme JS files -->
    {{-- <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script> --}}
    {{-- <script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('admin/validation/notice.js') }}"></script> --}}
    {{-- <script src="{{ asset('admin/global/js/demo_pages/form_checkboxes_radios.js') }}"></script> --}}

    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
{{--
    <script type="text/javascript" src="{{ asset('admin/nepalidatepicker/nepali.datepicker.v2.2.min.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('admin/nepalidatepicker/nepali.datepicker.v2.2.min.css') }}" />
    <style>
        .nepali-calendar {
            width: 95% !important
        }
    </style>
    <script>
        $(document).ready(function() {
            $(".nepali-calendar").nepaliDatePicker();
        });
    </script> --}}
    <!-- /theme JS files -->
    <script>
        $(document).ready(function() {
            //$('.multiselect').multiselect();
            $('.multiselect-select-all').multiselect({
                includeSelectAllOption: true
            });
            console.log($('input[type=radio][name="is_encashable"]').val());
            enable_encashable($('input[type=radio][name="is_encashable"]').val());


            $('input[type=radio][name="is_encashable"]').change(function() {
                enable_encashable($(this).val());
            });

        })

        function enable_encashable(val) {
            // console.log('encashable val' + val);
            if (val == 1) {
                $('.total_encashable').show();
            } else {
                $('.total_encashable').hide();
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#start-timepicker').clockTimePicker();

            $('.check_type').on('click', function() {
                var type = $(this).val();
                console.log(type);
                $('.schedule-row').addClass('d-none');

                if (type == 2) {
                    $('.schedule-row').removeClass('d-none');
                }
            })

        })
    </script>

@stop

@section('content')

    <!-- Form inputs -->

    {!! Form::open([
        'route' => 'notice.store',
        'method' => 'POST',
        'id' => 'notice_submit',
        'class' => 'form-horizontal',
        'role' => 'form',
        'files' => true,
         'enctype' => 'multipart/form-data'
    ]) !!}
    @include('notice::notice.partial.action', ['btnType' => 'Save'])
    {!! Form::close() !!}
    <!-- /form inputs -->

@stop
