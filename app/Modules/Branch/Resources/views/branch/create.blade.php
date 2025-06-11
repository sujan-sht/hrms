@extends('admin::layout')
@section('title') Branch @endSection
@section('breadcrum')
    <a href="{{ route('branch.index') }}" class="breadcrumb-item">Branches</a>
    <a class="breadcrumb-item active">Create</a>
@endSection

@section('script')
    <script>
    $(document).ready(function() {

        $('#districtSelect').select2({
            placeholder: "Select Districts",
            allowClear: true
        });

        $('#provinceSelect').change(function() {
            var provinceIds = $(this).val();
            if (provinceIds.length > 0) {
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
                        $('#districtSelect').trigger('change');

                    }
                });
            } else {
                $('#districtSelect').empty();
            }

        });
    });

    </script>
@endSection

@section('content')

    {!! Form::open(['route'=>'branch.store','method'=>'POST','class'=>'form-horizontal','id'=>'branchFormSubmit','role'=>'form','files' => true]) !!}

        @include('branch::branch.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
