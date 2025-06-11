@extends('admin::layout')
@section('title')
    Organization
@endSection
@section('breadcrum')
    <a class="breadcrumb-item active">Organizations</a>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
@endSection

@section('content')



    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Organizations</h6>
                All the Organization Information will be listed below.
            </div>

        </div>
    </div>


    <div class="card card-body">

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        {{-- <th>Organization Id</th> --}}
                        <th>Organization</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($organizationLists->count() > 0)
                        @foreach ($organizationLists as $key => $organization)
                            <tr>
                                <td width="5%">#{{ $loop->iteration }}</td>
                                {{-- <td>{{ $key }}</td> --}}

                                <td>{{ $organization }}</td>
                                <td class="d-flex">
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('organization.darbandiReport', $key) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Darbandi Report">
                                        <i class="icon-eye"></i>
                                    </a>

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

    </div>



@endsection
