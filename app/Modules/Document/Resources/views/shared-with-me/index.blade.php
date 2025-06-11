@extends('admin::layout')
@section('title') Document @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Documents</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('document::shared-with-me.partial.advance-filter', ['route' => route('shared-list.document')])

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Documents</h6>
                All the Documents Information will be listed below.
            </div>
        </div>
    </div>

    <div class="card card-body">

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th width="12%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($sharedDocumentModels->total() != 0)
                        @foreach ($sharedDocumentModels as $key => $sharedDocumentModel)
                            <tr>
                                <td width="5%">#{{ $sharedDocumentModels->firstItem() + $key }}</td>

                                <td>{{ $sharedDocumentModel->title }}</td>
                                <td>{{ Str::limit($sharedDocumentModel->description, 50) }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $sharedDocumentModel->getStatusWithColor()['color'] }}">{{ $sharedDocumentModel->getStatusWithColor()['status'] }}
                                    </span>
                                </td>
                                <td>{{ $sharedDocumentModel->created_at ? date('M d, Y', strtotime($sharedDocumentModel->created_at)) : '-' }}</td>

                                <td class="d-flex">
                                    <a class="btn btn-outline-secondary btn-icon mx-1" href="{{ route('document.show', $sharedDocumentModel->id) }}" data-popup="tooltip" data-placement="top" data-original-title="View">
                                        <i class="icon-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Document Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $sharedDocumentModels->appends(request()->all())->links() }}
            </span>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    {{-- <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script> --}}
    {{-- <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script> --}}
    <script>
        $(document).ready(function() {
            // initiate select2
            $('.select2').select2();
        });
    </script>
@endSection
