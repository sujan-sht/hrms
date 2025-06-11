@extends('admin::layout')
@section('title') Onboards @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Onboards</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('onboarding::onboard.partial.advance_filter')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Onboards</h6>
            All the MRF Information will be listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1">
            <a href="{{ route('onboard.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                Add</a>
        </div>
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="btn-slate text-light">
                    <th>S.N</th>
                    <th>MRF</th>
                    <th>Applicant</th>
                    <th>Boarding Task</th>
                    <th>Date</th>
                    <th>Created At</th>
                    <th width="12%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($onboardModels->total() != 0)
                    @foreach ($onboardModels as $key => $onboardModel)
                        <tr>
                            <td width="5%">#{{ $onboardModels->firstItem() + $key }}</td>
                            <td>{{ optional($onboardModel->mrfModel)->title }}</td>
                            <td>{{ optional($onboardModel->applicantModel)->getFullName() }}</td>
                            <td>{{ optional($onboardModel->boardingTaskModel)->title }}</td>
                            <td>{{ $onboardModel->onboard_date ? date('M d, Y', strtotime($onboardModel->onboard_date)) : '-' }}
                            </td>
                            <td>{{ $onboardModel->created_at ? date('M d, Y', strtotime($onboardModel->created_at)) : '-' }}
                            </td>
                            <td>
                                <a href="{{ route('onboard.view', $onboardModel->id) }}"
                                    class="btn btn-sm btn-outline-secondary btn-icon updateStatus mx-1"
                                    data-popup="tooltip" data-placement="top" data-original-title="View">
                                    <i class="icon-eye"></i>
                                </a>
                                @if ($menuRoles->assignedRoles('onboard.edit'))
                                    <a class="btn btn-sm btn-outline-primary btn-icon mx-1"
                                        href="{{ route('onboard.edit', $onboardModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('onboard.delete'))
                                    <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete"
                                        link="{{ route('onboard.delete', $onboardModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Record Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $onboardModels->appends(request()->all())->links() }}
        </span>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
@endSection
