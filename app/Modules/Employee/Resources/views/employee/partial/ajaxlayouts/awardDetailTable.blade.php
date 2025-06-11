@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@forelse($award_details as $key => $item)
<tr>
    <td width="5%">#{{ ++$key }}</td>
    <td>{{ $item['title'] ?? null }}</td>
    <td>
        {{ $item['date'] ?? null }}
    </td>
    <td>
        @if (!empty($item['attachment']) && $item['attachment']->isNotEmpty())
    @foreach ($item['attachment'] as $value)
        <a href="{{ $value['url'] }}" target="_blank">{{ $value['name'] }}</a>
    @endforeach
@else
    -
@endif


    </td>
    {{-- <td>
        {{ $item['status'] ?? null }}
    </td> --}}
    @if ($employeeModel->status == 1)
    <td class="d-flex">
        <a class="btn btn-sm btn-outline-primary btn-icon mx-1 editAwardDetail" href="#" data-popup="tooltip"
            data-placement="top" data-original-title="Edit" data-id="{{ $item['id'] }}"
            data-all="{{ json_encode($item) }}">
            <i class="icon-pencil7"></i>
        </a>

        <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete" link="#" data-popup="tooltip"
            data-placement="top" data-original-title="Delete" data-id="{{ $item['id'] }}">
            <i class="icon-trash-alt"></i>
        </a>
    </td>
    @endif
</tr>
@empty
<tr>
    <td colspan="5">No Award Details Found !!!</td>
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
                deleteAwardDetail(id)
            }
        });

    });

    $('.editAwardDetail').on('click', function() {
        let all = $(this).data('all')
        editMedical(all);
        var that = $(this);
        editModal(that);



        $('#edit_title').val(all.title)
        $('#edit_date').val(all.date)
        $(".awardDetailId").val(all.id)
    })
</script>
