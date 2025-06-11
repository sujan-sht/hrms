@extends('admin::layout')
@section('title') Stocks @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Stocks</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('asset::asset-quantity.partial.advance-filter', ['route' => route('assetQuantity.index')])

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Stocks</h6>
            All the Stocks Information will be listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1">
            <a href="{{ route('assetQuantity.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                Add</a>
        </div>
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Asset</th>
                    <th>Code</th>
                    <th>Quantity</th>
                    <th>Remaining Quantity</th>
                    <th>Expiry Date</th>
                    <th>Created By</th>
                    <th>Created Date</th>
                    <th width="12%">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($assetQuantityModels->total() != 0)
                    @foreach ($assetQuantityModels as $key => $assetQuantityModel)
                        <tr>
                            <td width="5%">#{{ ++$key }}</td>

                            <td>{{ optional($assetQuantityModel->asset)->title }}</td>
                            <td>{{ $assetQuantityModel->code }}</td>
                            <td>{{ $assetQuantityModel->quantity }}</td>
                            <td>{{ $assetQuantityModel->remaining_quantity }}</td>
                            @php
                                $expDate =
                                    setting('calendar_type') == 'BS'
                                        ? date_converter()->eng_to_nep_convert($assetQuantityModel->expiry_date)
                                        : getStandardDateFormat($assetQuantityModel->expiry_date);
                            @endphp
                            <td>{{ $assetQuantityModel->expiry_date ? $expDate : '-' }}</td>

                            <td>{{ optional($assetQuantityModel->user)->full_name }}</td>

                            <td>{{ setting('calendar_type') == 'BS' ? date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($assetQuantityModel->created_at))) : getStandardDateFormat($assetQuantityModel->created_at) }}
                            </td>

                            <td class="d-flex">
                                @if ($menuRoles->assignedRoles('assetQuantity.edit'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('assetQuantity.edit', $assetQuantityModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif

                                @if ($menuRoles->assignedRoles('assetQuantity.delete'))
                                    <a class="btn btn-outline-danger btn-icon confirmDelete mx-1"
                                        link="{{ route('assetQuantity.delete', $assetQuantityModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Asset Quantity Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $assetQuantityModels->appends(request()->all())->links() }}
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
