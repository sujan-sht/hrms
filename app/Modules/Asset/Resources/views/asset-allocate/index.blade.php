@extends('admin::layout')
@section('title') Asset Allocation @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Asset Allocations</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('asset::asset-allocate.partial.advance-filter', ['route' => route('assetAllocate.index')])

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Asset Allocations</h6>
            All the Asset Allocations Information will be listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1">
            <a href="{{ route('assetAllocate.export', request()->all()) }}" class="btn btn-primary rounded-pill"><i
                    class="icon-file-excel"></i> Export</a>

            <a href="{{ route('assetAllocate.create') }}" class="btn btn-success rounded-pill"><i
                    class="icon-plus2"></i> Add</a>
        </div>
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover" id="table2excel">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Employee</th>
                    <th>Asset</th>
                    <th>Quantity</th>
                    <th>Allocated Date</th>
                    <th>Return Date</th>
                    <th>NOD</th>
                    <th>Attachments</th>
                    <th>Allocated By</th>
                    <th>Created Date</th>
                    <th width="12%">
                        <span class="action">
                            Action
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $dateTime = new App\Helpers\DateTimeHelper();
                @endphp
                @if ($assetAllocateModels->total() != 0)
                    @foreach ($assetAllocateModels as $key => $assetAllocateModel)
                        <tr>
                            <td width="5%">#{{ ++$key }}</td>

                            <td>{{ optional($assetAllocateModel->employee)->full_name }}</td>
                            <td>{{ optional($assetAllocateModel->asset)->title }}</td>
                            <td>{{ $assetAllocateModel->quantity }}</td>

                            @php
                                $allocatedDate =
                                    setting('calendar_type') == 'BS'
                                        ? date_converter()->eng_to_nep_convert($assetAllocateModel->allocated_date)
                                        : getStandardDateFormat($assetAllocateModel->allocated_date);
                            @endphp
                            <td>{{ $assetAllocateModel->allocated_date ? $allocatedDate : '-' }}</td>

                            @php
                                $returnDate =
                                    setting('calendar_type') == 'BS'
                                        ? date_converter()->eng_to_nep_convert($assetAllocateModel->return_date)
                                        : getStandardDateFormat($assetAllocateModel->return_date);
                            @endphp
                            <td>{{ $assetAllocateModel->return_date ? $returnDate : '-' }}</td>

                            <td>{{ $assetAllocateModel->return_date ? $dateTime->DateDiffInDay(date('Y-m-d'), $assetAllocateModel->return_date) : '' }}
                            </td>
                            <td>
                                <ul class="media-list">
                                    @foreach ($assetAllocateModel->assetAllocateAttachment as $attachment)
                                        <li>
                                            <a href="{{ $attachment->attachment }}"
                                                target="_blank">{{ $attachment->title }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ optional(optional($assetAllocateModel->user)->userEmployer)->full_name }}</td>

                            <td>{{ setting('calendar_type') == 'BS' ? date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($assetAllocateModel->created_at))) : getStandardDateFormat($assetAllocateModel->created_at) }}
                            </td>

                            <td class="d-flex">
                                @if ($menuRoles->assignedRoles('assetAllocate.edit'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('assetAllocate.edit', $assetAllocateModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif

                                @if ($menuRoles->assignedRoles('assetAllocate.delete'))
                                    <a class="btn btn-outline-danger btn-icon confirmDelete mx-1"
                                        link="{{ route('assetAllocate.delete', $assetAllocateModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Asset Allocation Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $assetAllocateModels->appends(request()->all())->links() }}
        </span>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endSection
