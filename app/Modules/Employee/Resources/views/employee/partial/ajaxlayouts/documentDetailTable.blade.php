@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@forelse($document_details as $key => $item)
    @php
        if (setting('calendar_type') == 'BS'){
            $item->issued_date = date_converter()->eng_to_nep_convert($item->issued_date);
            $item->expiry_date = date_converter()->eng_to_nep_convert($item->expiry_date);
        }
    @endphp
    <tr>
        <td width="5%">#{{ ++$key }}</td>
        <td>{{ $item->document_name }}</td>
        <td>{{ $item->id_number }}</td>
        <td>{{ $item->issued_date }}</td>
        <td>{{ $item->expiry_date }}</td>
        <td>
            <a href="/uploads/employee/document_details/{{$item->file}}" target="_blank" rel="noopener noreferrer">{{ $item->file }}</a>
        </td>
        @if ($employeeModel->status == 1)
        <td class="d-flex">
            @if ($menuRoles->assignedRoles('documentDetail.update'))
                <a class="btn btn-sm btn-outline-primary btn-icon mx-1 editDocument" href="#" data-popup="tooltip"
                    data-placement="top" data-original-title="Edit" data-id="{{ $item->id }}"
                    data-all="{{ $item }}">
                    <i class="icon-pencil7"></i>
                </a>
            @endif
            @if ($menuRoles->assignedRoles('documentDetail.delete'))
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
        <td colspan="5">No Document Details Found !!!</td>
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
                deleteDocumentDetail(id)
            }
        });

    });

    $('.editDocument').on('click', function() {
        let all = $(this).data('all')
        editDocument(all);
        var that = $(this);
        editModal(that);
    })
</script>
