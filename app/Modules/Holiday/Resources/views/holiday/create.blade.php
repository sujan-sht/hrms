@extends('admin::layout')
@section('title')Holiday @stop
@section('breadcrum')
    <a href="{{ route('holiday.index') }}" class="breadcrumb-item">Holiday</a>
    <a class="breadcrumb-item active">Create</a>
@stop


@section('script')

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/validation/holiday1.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#start-timepicker').clockTimePicker();


            // Fixed width. Multiple selects
            $('.select-fixed-multiple').select2({
                minimumResultsForSearch: Infinity,
                width: 400
            });
        })

        $(document).on('click', '.plus', function() {            
            $.ajax({
                url: '{{ route("holiday.clone-province-district-fields") }}',
                type: 'GET',
                success: function(response) {
                    $('.cloneData').append(response.html);
                }
            });
        });

    $(document).on('click', '.minus', function() {
        // $(this).closest('.form-group.row.all').remove();
        $(this).closest('.clone-div').remove();
        $(this).remove();
    });

    $(document).on('change', '.provinceSelect', function() {
            var provinceIds = $(this).val();
            var $closestDistrictSelect = $(this).closest('.form-group').find('.districtSelect');

            if (provinceIds.length > 0) {
                $.ajax({
                    url: '{{ route("branch.get-districts-by-province") }}',
                    method: 'GET',
                    data: {
                        province_ids: provinceIds
                    },
                    success: function(response) {
                        $closestDistrictSelect.empty();
                        $.each(response.districts, function(key, district) {
                            $closestDistrictSelect.append($('<option>', {
                                value: key,
                                text: district
                            }));
                        });
                        $closestDistrictSelect.trigger('change');

                        $closestDistrictSelect.multiselect('destroy');

                        $closestDistrictSelect.multiselect({
                            includeSelectAllOption: true,
                            enableFiltering: true,
                            enableCaseInsensitiveFiltering: true
                        });
                    }
                });
            } else {
                $closestDistrictSelect.empty();
            }
        });

    </script>

@stop

@section('content')
    {!! Form::open([
        'route' => 'holiday.store',
        'method' => 'POST',
        'id' => 'holiday_submit',
        'class' => 'form-horizontal holidayForm',
        'role' => 'form',
        'files' => true,
    ]) !!}
    @include('holiday::holiday.partial.action', ['btnType' => 'Save Record'])
    {!! Form::close() !!}
@stop
