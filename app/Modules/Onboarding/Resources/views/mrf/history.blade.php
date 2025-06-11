@extends('admin::layout')
@section('title') Manpower Requisition Forms @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Manpower Requisition Forms</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

@include('onboarding::mrf.partial.advance_filter', ['route' => 'mrf.history'])

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Manpower Requisition Forms (MRF)</h6>
            All the MRF Information will be listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1">
            <a href="{{ route('mrf.index') }}" class="btn btn-info rounded-pill mr-1">Current</a>
            <a href="{{ route('mrf.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add</a>
        </div>
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="btn-slate text-light">
                    <th>S.N</th>
                    <th>Ref Number</th>
                    <th>Title</th>
                    <th>Organization</th>
                    <th>Sub-Function</th>
                    <th>Designation</th>
                    <th>Publish Date</th>
                    <th>Last Submission Date</th>
                    <th>Status</th>
                    <th width="12%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($mrfModels->total() != 0)
                    @foreach ($mrfModels as $key => $mrfModel)
                        <tr>
                            <td width="5%">#{{ $mrfModels->firstItem() + $key }}</td>
                            <td>{{ $mrfModel->reference_number }}</td>
                            <td>{{ $mrfModel->title }}</td>
                            <td>{{ optional($mrfModel->organizationModel)->name }}</td>
                            <td>{{ optional($mrfModel->getDepartment)->title }}</td>
                            <td>{{ optional($mrfModel->getDesignation)->title }}</td>

                            <td>
                                @if (setting('calendar_type') == 'BS')
                                    {{ date_converter()->eng_to_nep_convert($mrfModel['start_date']) ?? '-' }}
                                @else
                                    {{ date('M d Y', strtotime($mrfModel['start_date'])) ?? '-' }}
                                @endif
                            </td>

                            <td>
                                @if (setting('calendar_type') == 'BS')
                                    {{ date_converter()->eng_to_nep_convert($mrfModel['end_date']) ?? '-' }}
                                @else
                                    {{ date('M d Y', strtotime($mrfModel['end_date'])) ?? '-' }}
                                @endif
                            </td>

                            {{-- <td>{{ $mrfModel->start_date ? date('M d, Y', strtotime($mrfModel->start_date)) : '-' }}</td>
                                <td>{{ $mrfModel->end_date ? date('M d, Y', strtotime($mrfModel->end_date)) : '-' }}</td> --}}
                            <td>
                                <span
                                    class="badge badge-{{ $mrfModel->getStatusWithColor()['color'] }}">{{ $mrfModel->getStatusWithColor()['status'] }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('mrf.view', $mrfModel->id) }}"
                                    class="btn btn-sm btn-outline-secondary btn-icon updateStatus mr-1"
                                    data-popup="tooltip" data-placement="top" data-original-title="View">
                                    <i class="icon-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="11">No Record Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $mrfModels->appends(request()->all())->links() }}
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
