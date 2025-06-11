@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@forelse($researchAndPublication_details as $key => $item)
    <tr>
        <td width="5%">#{{ ++$key }}</td>
        <td>{{ $item->research_title }}</td>
        <td>{{ $item->note }}</td>
        @if ($employeeModel->status == 1)
        <td class="d-flex">
            @if ($menuRoles->assignedRoles('researchAndPublicationDetail.update'))
                <a class="btn btn-sm btn-outline-primary btn-icon mx-1 editResearchAndPublication" href="#" data-popup="tooltip"
                    data-placement="top" data-original-title="Edit" data-id="{{ $item->id }}"
                    data-all="{{ $item }}">
                    <i class="icon-pencil7"></i>
                </a>
            @endif
            @if ($menuRoles->assignedRoles('researchAndPublicationDetail.delete'))
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
        <td colspan="5">No Research And Publication Details Found !!!</td>
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
                deleteResearchAndPublicationDetail(id)
            }
        });

    });

    $('.editResearchAndPublication').on('click', function() {
        let all = $(this).data('all')
        editResearchAndPublication(all);
        var that = $(this);
        editModal(that);
    })
</script>
