@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@forelse($skill_details as $key => $item)
    <tr>
        <td width="5%">#{{ ++$key }}</td>
        <td>{{ $item->skill_name ?? null }}</td>
        <td>
            {{ $item->rating ?? null }}
        </td>
        @if ($employeeModel->status == 1)
            <td class="d-flex">
                <a class="btn btn-sm btn-outline-primary btn-icon mx-1 editSkillDetail" href="#" data-popup="tooltip"
                    data-placement="top" data-original-title="Edit" data-id="{{ $item->id }}"
                    data-all="{{ $item }}">
                    <i class="icon-pencil7"></i>
                </a>

                <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete" link="#" data-popup="tooltip"
                    data-placement="top" data-original-title="Delete" data-id="{{ $item->id }}">
                    <i class="icon-trash-alt"></i>
                </a>
            </td>
        @endif
    </tr>
@empty
    <tr>
        <td colspan="5">No Skill Details Found !!!</td>
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
                deleteSkillDetail(id)
            }
        });

    });

    $('.editSkillDetail').on('click', function() {
        let all = $(this).data('all')
        editMedical(all);
        var that = $(this);
        editModal(that);
        console.log(all);

        $('#edit_skill_name').val(all.skill_name)
        $('input[name="rating"][value="' + all.rating_number + '"]').prop('checked', true);
        $(".skillDetailId").val(all.id)
    })
</script>
