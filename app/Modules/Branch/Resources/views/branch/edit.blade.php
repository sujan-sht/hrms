@extends('admin::layout')
@section('title') Branch @endSection
@section('breadcrum')
    <a href="{{ route('branch.index') }}" class="breadcrumb-item">Branches</a>
    <a class="breadcrumb-item active">Edit</a>
@endSection

@section('script')
    <script>
    $(document).ready(function() {

        $('#districtSelect').select2({
            placeholder: "Select Districts",
            allowClear: true
        });

        var provinceId = $('#provinceSelect').val();
        var selectedDistrictId = '{{ $district_id ?? null }}';
        updateDistricts(provinceId, selectedDistrictId);


    });

    $(document).on('change', '#provinceSelect', function() {
            var provinceIds = $(this).val();
            updateDistricts(provinceIds);
        });

    function updateDistricts(provinceIds, selectedDistrictId = null) {
        $.ajax({
            url: '{{ route("branch.get-districts-by-province") }}',
            method: 'GET',
            data: {
                province_ids: provinceIds
            },
            success: function(response) {
                $('#districtSelect').empty();
                $.each(response.districts, function(key, district) {
                    $('#districtSelect').append($('<option>', {
                        value: key,
                        text: district
                    }));
                });

                if (selectedDistrictId) {
                    $('#districtSelect').val(selectedDistrictId).trigger('change');
                }

                // $('#districtSelect').trigger('change');

            }
        });

    }


    </script>
@endSection

@section('content')

    {!! Form::model($branchModel,['method'=>'PUT','route'=>['branch.update',$branchModel->id],'class'=>'form-horizontal','id'=>'branchFormSubmit','role'=>'form','files'=>true]) !!}

        @include('branch::branch.partial.action',['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endSection
