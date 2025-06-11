@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@forelse($employeeTransferModels as $key => $employeeTransferModel)
    <tr>
        <td width="5%">#{{ ++$key }}</td>
        <td>{{ optional($employeeTransferModel->organization)->name }}</td>
        <td>{{ optional($employeeTransferModel->branch)->name }}</td>
        <td>{{ optional($employeeTransferModel->department)->title }}</td>
        <td>{{ optional($employeeTransferModel->level)->title }}</td>
        <td>{{ optional($employeeTransferModel->designation)->title }}</td>
        <td>{{ $employeeTransferModel->job_title }}</td>
        <td>{{ getStandardDateFormat($employeeTransferModel->date) }}</td>




        {{-- <td>{{ date('M d, Y', strtotime($employeeTransferModel->transfer_date)) }}</td>
        <td>{{ optional($employeeTransferModel->fromOrganizationModel)->name }}</td>
        <td>{{ optional($employeeTransferModel->toOrganizationModel)->name }}</td>
        <td>{{ $employeeTransferModel->remarks }}</td> --}}
     
        {{-- @if ($employeeModel->status == 1)
        <td class="d-flex">
            @if ($menuRoles->assignedRoles('employeeTransfer.update'))
                <a class="btn btn-sm btn-outline-primary btn-icon mx-1 editView" data-popup="tooltip"
                    data-placement="top" data-original-title="Edit" data-id="{{ $employeeTransferModel->id }}"
                    data-all="{{ $employeeTransferModel }}">
                    <i class="icon-pencil7"></i>
                </a>
            @endif
            @if ($menuRoles->assignedRoles('employeeTransfer.delete'))
                <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete" data-popup="tooltip"
                    data-placement="top" data-original-title="Delete" data-id="{{ $employeeTransferModel->id }}">
                    <i class="icon-trash-alt"></i>
                </a>
            @endif
        </td>
        @endif --}}
    </tr>
@empty
    <tr>
        <td colspan="6">No employee transfer details found !!!</td>
    </tr>
@endforelse


{{-- <script>
    $('.confirmDelete').on('click', function() {
        let id = $(this).data('id');
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
                deleteData(id);
            }
        });
    });

    $('.editView').on('click', function() {
        let all = $(this).data('all')
        fetchData(all);
        editModal($(this));
    })
</script> --}}
