@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@forelse($insurance_details as $key => $item)
    <tr>
        <td width="5%">#{{ ++$key }}</td>
        <td>{{ $item->company_name }}</td>
        <td>{{ ($item->gpa_enable == 11)? 'Yes' : 'No' }}</td>
        <td>{{ ($item->gmi_enable == 11)? 'Yes' : 'No' }}</td>
        @if ($employeeModel->status == 1)
            <td class="d-flex">
                <a class="btn btn-sm btn-outline-secondary btn-icon mx-1 viewInsurance" href="#" data-popup="tooltip"
                    data-placement="top" data-original-title="View" data-id="{{ $item->id }}"
                    data-all="{{ $item }}">
                    <i class="icon-eye"></i>
                </a>
                @if ($menuRoles->assignedRoles('insuranceDetail.update'))
                    <a class="btn btn-sm btn-outline-primary btn-icon mx-1 editInsurance" href="#" data-popup="tooltip"
                        data-placement="top" data-original-title="Edit" data-id="{{ $item->id }}"
                        data-all="{{ $item }}">
                        <i class="icon-pencil7"></i>
                    </a>
                @endif
                @if ($menuRoles->assignedRoles('insuranceDetail.delete'))
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
        <td colspan="5">No Insurance Details Found !!!</td>
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
                deleteInsuranceDetail(id)
            }
        });

    });

    $('.editInsurance').on('click', function() {
        let all = $(this).data('all')
        editInsurance(all);
        var that = $(this);
        editModal(that);
    })

    $('.viewInsurance').on('click', function() {
        let all = $(this).data('all')
        viewInsurance(all);
        var that = $(this);
        viewModal(that);
    })
</script>
