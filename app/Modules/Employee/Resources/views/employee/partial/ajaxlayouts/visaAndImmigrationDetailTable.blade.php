@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@forelse($visaAndImmigration_details as $key => $item)
    <tr>
        <td width="5%">#{{ ++$key }}</td>
        <td>{{ optional($item->countryInfo)->name }}</td>
        <td>{{ $item->visa_type }}</td>
        <td>{{ $item->issued_date }}</td>
        <td>{{ $item->visa_expiry_date }}</td>
        <td>{{ $item->passport_number }}</td>
        <td>{{ $item->note }}</td>
        @if ($employeeModel->status == 1)
        <td class="d-flex">
            @if ($menuRoles->assignedRoles('visaAndImmigrationDetail.update'))
                <a class="btn btn-sm btn-outline-primary btn-icon mx-1 editVisaAndImmigration" href="#" data-popup="tooltip"
                    data-placement="top" data-original-title="Edit" data-id="{{ $item->id }}"
                    data-all="{{ $item }}">
                    <i class="icon-pencil7"></i>
                </a>
            @endif
            @if ($menuRoles->assignedRoles('visaAndImmigrationDetail.delete'))
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
        <td colspan="5">No Visa/Immigration Doc Details Found !!!</td>
    </tr>
@endforelse


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
                deleteVisaAndImmigrationDetail(id)
            }
        });

    });

    $('.editVisaAndImmigration').on('click', function() {
        let all = $(this).data('all')
        editVisaAndImmigration(all);
        var that = $(this);
        editModal(that);
    })
</script>
