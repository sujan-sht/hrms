@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@forelse($bank_details as $key => $item)
    <tr>
        <td width="5%">#{{ ++$key }}</td>
        <td>{{ $item->bank_name }}</td>
        <td>{{ $item->bank_code }}</td>
        <td>{{ $item->bank_address }}</td>
        <td>{{ $item->bank_branch }}</td>
        <td>{{ $item->account_type }}</td>
        <td>{{ $item->account_number }}</td>
        @if ($employeeModel->status == 1)
            <td class="d-flex">
                @if ($menuRoles->assignedRoles('bankDetail.update'))
                    <a class="btn btn-sm btn-outline-primary btn-icon mx-1 editBank" href="#" data-popup="tooltip"
                        data-placement="top" data-original-title="Edit" data-id="{{ $item->id }}"
                        data-all="{{ $item }}">
                        <i class="icon-pencil7"></i>
                    </a>
                @endif
                @if ($menuRoles->assignedRoles('bankDetail.delete'))
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
        <td colspan="5">No Bank Details Found !!!</td>
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
                deleteBankDetail(id)
            }
        });

    });

    $('.editBank').on('click', function() {
        let all = $(this).data('all')
        editBank(all);
        var that = $(this);
        editModal(that);
    })
</script>
