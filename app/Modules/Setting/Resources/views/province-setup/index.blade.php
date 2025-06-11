@extends('admin::layout')
@section('title') Province Setup @stop
@section('breadcrum')
    <a href="{{ route('darbandi.index') }}" class="breadcrumb-item">Province Setup</a>
    <a class="breadcrumb-item active"> Add Province </a>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // $('#provinceSelect').each(function() {
            //         var provinceSelect = $(this);
            //         updateDistricts(provinceSelect);
            //     });
        });

        $(document).on('change', '#provinceSelect', function() {
            updateDistricts($(this));
        });

        function updateDistricts(provinceSelect) {
            var provinceIds = provinceSelect.val();
            // if (provinceIds.length > 0) {
            $.ajax({
                url: '{{ route('branch.get-districts-by-provinces') }}',
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



                    var incomeSelect = $('#districtSelect');
                    incomeSelect.empty();
                    $.each(response.districts, function(key, value) {
                        incomeSelect.append('<option value="' + key + '">' + value + '</option>');
                    });

                    incomeSelect.multiselect('destroy');

                    incomeSelect.multiselect({
                        enableFiltering: true,
                        enableCaseInsensitiveFiltering: true
                    });



                }
            });
            // }
        }
    </script>
@stop


@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')


    @include('setting::province-setup.partial.advance-filter')



    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Provinces</h6>
                All the Province Information will be listed below. You can Create and Modify the data.
            </div>
            <div class="mt-1 mr-2">
                <a href="{{ route('province-setup.create') }}" class="btn btn-success rounded-pill">Add Province</a>
            </div>


        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive ">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-light btn-slate">
                            <th>#</th>
                            <th>Province</th>
                            <th>Districts</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($provinceDistricts) > 0)
                            @foreach ($provinceDistricts as $key => $value)
                                @php
                                    $districtNames = $districts
                                        ->whereIn('id', $value->district_id)
                                        ->pluck('district_name');
                                @endphp
                                @if ($districtNames->isNotEmpty())
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $value->title ?? '' }}</td>
                                        <td>
                                            @foreach ($districtNames as $index => $districtName)
                                                <span
                                                    class="badge badge-secondary mt-1">{{ $districtName }}</span>{{ $index < $districtNames->count() - 1 ? ',' : '' }}
                                            @endforeach
                                            {{-- {{ $districtNames }} --}}
                                        </td>
                                        <td>
                                            <a class="btn btn-outline-primary btn-icon mx-1"
                                                href="{{ route('province-setup.edit', $value->id) }}" data-popup="tooltip"
                                                data-placement="bottom" data-original-title="Edit"><i
                                                    class="icon-pencil7"></i></a>

                                            <a class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                                link="{{ route('province-setup.delete', $value->id) }}"
                                                data-placement="bottom" data-popup="tooltip" data-original-title="Delete"><i
                                                    class="icon-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td>No Device Data Found!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Warning modal -->
    <div id="modal_theme_warning" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h6 class="modal-title">Are you sure to Delete a Device?</h6>
                </div>

                <div class="modal-body">
                    <a class="btn btn-success get_link" href="">Yes</a> &nbsp; | &nbsp;
                    <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /warning modal -->





@endsection
