@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@php
    $dateTime = new App\Helpers\DateTimeHelper;
@endphp
@forelse($assetAllocateModels as $key => $assetAllocateModel)
    <tr>
        <td width="5%">#{{ ++$key }}</td>
        <td>{{ optional($assetAllocateModel->asset)->title }}</td>
        <td>{{ $assetAllocateModel->quantity }}</td>
        <td>{{ optional(optional($assetAllocateModel->user)->userEmployer)->full_name }}</td>
        <td>{{ getStandardDateFormat($assetAllocateModel->allocated_date) }}</td>
        <td>{{ getStandardDateFormat($assetAllocateModel->return_date) }}</td>
        <td>{{ $assetAllocateModel->return_date ? $dateTime->DateDiffInDay(date('Y-m-d'), $assetAllocateModel->return_date) : ''}}</td>
    </tr>
@empty
    <tr>
        <td colspan="5">No Asset Allocations Found !!!</td>
    </tr>
@endforelse

{{-- @forelse($asset_details as $key => $item)
    <tr>
        <td width="5%">#{{ ++$key }}</td>
        <td>{{ ucfirst(optional($item->assetTypeInfo)->dropvalue) ?? '-' }}</td>
        <td>{{ $item->asset_detail }}</td>
        <td>{{ $item->given_date }}</td>
        <td>{{ $item->return_date }}</td>
        @if ($employeeModel->status == 1)
        <td class="d-flex">
            @if ($menuRoles->assignedRoles('assetDetail.update'))
                <a class="btn btn-sm btn-outline-primary btn-icon mx-1 editAsset" href="#" data-popup="tooltip"
                    data-placement="top" data-original-title="Edit" data-id="{{ $item->id }}"
                    data-all="{{ $item }}">
                    <i class="icon-pencil7"></i>
                </a>
            @endif
            @if ($menuRoles->assignedRoles('assetDetail.delete'))
                <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete" link="#" data-popup="tooltip"
                    data-placement="top" data-original-title="Delete" data-id="{{ $item->id }}">
                    <i class="icon-trash-alt"></i>
                </a>
            @endif
        </td>
        @endif
    </tr>
@empty
    <tr>
        <td colspan="5">No Asset Details Found !!!</td>
    </tr>
@endforelse --}}


<script>
    $('.confirmDelete').on('click', function() {
        let id = $(this).data('id')
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteAssetDetail(id)
            }
        });

    });

    $('.editAsset').on('click', function() {

        // console.log(that);
        let all = $(this).data('all')
        editAsset(all)
        var that = $(this);
        editModal(that);
    })
</script>
