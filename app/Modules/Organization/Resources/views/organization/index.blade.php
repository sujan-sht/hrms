@extends('admin::layout')
@section('title')
    Organization
@endSection
@section('breadcrum')
    <a class="breadcrumb-item active">Organization</a>
    <a class="breadcrumb-item active">Overview</a>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
@endSection

@section('content')

    @include('organization::organization.partial.advance-search')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Organizations</h6>
                All the Organization Information will be listed below. You can Create and Modify the data.
            </div>
            {{-- @if ($showCreateBtn == true) --}}
            @if ($syncOrganization == 1)
                <div class="mt-1">
                    @if ($organizationModels->total() < 4)
                        <a href="{{ route('organization.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add
                            Organization</a>
                    @else
                        <a class="btn btn-success moreThanFour" data-popup="tooltip" data-placement="top"
                            data-original-title="Create">
                            Create
                        </a>
                    @endif
                </div>
            @endif
            {{-- @endif --}}
        </div>
    </div>

    @include('organization::organization.partial.upload_sheet')

    <div class="card card-body">

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Organization Id</th>
                        <th>Organization</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Mobile</th>
                        <th>Fax</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($organizationModels->total() != 0)
                        @foreach ($organizationModels as $key => $organizationModel)
                            <tr>
                                <td width="5%">#{{ $organizationModels->firstItem() + $key }}</td>
                                <td>{{ $organizationModel->id }}</td>
                                <td>
                                    <div class="media">
                                        <div class="mr-3">
                                            <a href="#">
                                                <img src="{{ $organizationModel->getImage() }}" class="rounded-circle"
                                                    width="40" height="40" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-title font-weight-semibold">{{ $organizationModel->name }}
                                            </div>
                                            <span class="text-muted">{{ $organizationModel->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $organizationModel->address }}</td>
                                <td>{{ $organizationModel->contact }}</td>
                                <td>{{ $organizationModel->mobile }}</td>
                                <td>{{ $organizationModel->fax }}</td>
                                <td class="d-flex">
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('organization.edit', $organizationModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                    @if ($organizationModel->employees_count == 0)
                                        <a class="btn btn-outline-danger btn-icon confirmDelete"
                                            link="{{ route('organization.delete', $organizationModel->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9">No Organizations Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $organizationModels->appends(request()->all())->links() }}
            </span>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('.moreThanFour').on('click', function() {
                Swal.fire({
                    title: 'Maximum Limit Reached!',
                    text: "You cannot add more Organizations. Please contact technical team for more detail.",
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'I Understand'
                })
            });

        });
    </script>

@endsection
