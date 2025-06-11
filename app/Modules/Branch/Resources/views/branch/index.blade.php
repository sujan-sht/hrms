@extends('admin::layout')
@section('title')
    Branch
@endSection
@section('breadcrum')
    <a class="breadcrumb-item active">Branches</a>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#provinceSelect').each(function() {
                var provinceSelect = $(this);
                updateDistricts(provinceSelect);
            });
        });


        $(document).on('change', '#provinceSelect', function() {
            updateDistricts($(this));
        });

        function updateDistricts(provinceSelect) {
            var provinceIds = provinceSelect.val();
            if (provinceIds.length > 0) {
                $.ajax({
                    url: '{{ route('branch.get-districts-by-province') }}',
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
                        $('#districtSelect').trigger('change'); // Refresh the Select2



                        var incomeSelect = $('#districtSelect');
                        incomeSelect.empty();
                        $.each(response.districts, function(key, value) {
                            incomeSelect.append('<option value="' + key + '">' + value + '</option>');
                        });

                        // Destroy the existing multiselect instance if it's initialized
                        incomeSelect.multiselect('destroy');

                        // Reinitialize the multiselect with filtering enabled
                        incomeSelect.multiselect({
                            enableFiltering: true,
                            enableCaseInsensitiveFiltering: true
                        });



                    }
                });
            } else {
                // If no province is selected, clear the district dropdown
                $('#districtSelect').empty();
            }
        }
    </script>
@endSection

@section('content')
    @include('branch::branch.partial.advance-search')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Branches</h6>
                All the Branches Information will be listed below. You can Create and Modify the data.
            </div>
            <div class="mt-1 mr-1">
                <a href="{{ route('branch.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add
                    Branch</a>
            </div>
            <div class="mt-1">
                <a href="{{ route('branch.export', request()->all()) }}" class="btn btn-success rounded-pill"><i
                        class="icon-file-excel"></i> Export</a>
            </div>
        </div>
    </div>

    <div class="card card-body">

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Organization Name</th>
                        <th>Branch Name</th>
                        <th>Branch Code</th>
                        <th>Province</th>
                        <th>District</th>
                        <th>Location</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Manager</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($branchModels->total() != 0)
                        @foreach ($branchModels as $key => $branchModel)
                            <tr>
                                <td width="5%">#{{ $branchModels->firstItem() + $key }}</td>
                                <td>
                                    <div class="media">
                                        <div class="mr-3">
                                            <a href="#">
                                                <img src="{{ optional($branchModel->organizationModel)->getImage() }}"
                                                    class="rounded-circle" width="40" height="40" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-title font-weight-semibold">
                                                {{ optional($branchModel->organizationModel)->name }}
                                            </div>
                                            <span
                                                class="text-muted">{{ optional($branchModel->organizationModel)->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $branchModel->name }}</td>
                                <td>{{ $branchModel->branche_code }}</td>
                                <td>{{ optional($branchModel->ProvincesModel)->title }}</td>
                                <td>{{ optional($branchModel->districtModel)->district_name }}</td>
                                <td>{{ $branchModel->location }}</td>
                                <td>{{ $branchModel->contact }}</td>
                                <td>{{ $branchModel->email }}</td>
                                <td>{{ optional($branchModel->managerEmployeeModel)->getFullName() }}</td>
                                <td class="d-flex">
                                    <a class="btn btn-outline-primary btn-icon mx-1"href="{{ route('branch.edit', $branchModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                    <a class="btn btn-outline-danger btn-icon confirmDelete"
                                        link="{{ route('branch.delete', $branchModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">No Branches Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $branchModels->appends(request()->all())->links() }}
            </span>
        </div>
    </div>

@endsection
