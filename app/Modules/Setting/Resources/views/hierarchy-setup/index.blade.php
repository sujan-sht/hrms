@extends('admin::layout')
@section('title') Hierarchies @stop
@section('breadcrum')
    <a class="breadcrumb-item active"> Hierarchies </a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@section('content')


    @include('setting::hierarchy-setup.partial.advance_filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Hierarchies</h6>
                All the Hierarchies Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('hierarchySetup.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('hierarchySetup.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add</a>
                </div>
            @endif

        </div>
    </div>

    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Organization</th>
                        <th>Created Date</th>
                        <th width="12%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($hierarchySetupModels->total() != 0)
                        @foreach ($hierarchySetupModels as $key => $hierarchySetupModel)
                            <tr>
                                <td width="5%">#{{ $hierarchySetupModels->firstItem() + $key }}</td>
                                <td>{{ optional($hierarchySetupModel->getOrganization)->name }}</td>
                                <td>{{ $hierarchySetupModel->created_at ? date('M d, Y', strtotime($hierarchySetupModel->created_at)) : '-' }}
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-outline-secondary btn-icon"
                                        href="{{ route('hierarchySetup.view', $hierarchySetupModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="View">
                                        <i class="icon-eye"></i>
                                    </a>
                                    @if ($menuRoles->assignedRoles('hierarchySetup.edit'))
                                        <a class="btn btn-sm btn-outline-primary btn-icon mx-1"
                                            href="{{ route('hierarchySetup.edit', $hierarchySetupModel->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('hierarchySetup.delete'))
                                        <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete"
                                            link="{{ route('hierarchySetup.delete', $hierarchySetupModel->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10">No Record Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $hierarchySetupModels->appends(request()->all())->links() }}
            </span>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endSection
