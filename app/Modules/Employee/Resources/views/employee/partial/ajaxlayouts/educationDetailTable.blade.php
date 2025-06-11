@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@forelse($education_details as $key => $item)
    <tr>
        <td width="5%">#{{ ++$key }}</td>
        <td>{{ $item['type_of_institution'] ?? null }}</td>
        <td>{{ $item['institution_name'] ?? null }}</td>
        <td>{{ $item['passed_year'] ?? null }}</td>
        {{-- <td>{{ $item['level'] ?? null }}</td>
        <td>{{ $item['course_name'] ?? null }}</td>
        <td>{{ !is_null($item['score']) && !is_null($item['division']) ? $item['score'] . ' | ' . $item['division'] : '-' }}
        </td>
        <td>{{ !is_null($item['faculty']) && !is_null($item['specialization']) ? $item['faculty'] . ' | ' . $item['specialization'] : '-' }}
        </td>
        <td>{{ !is_null($item['university_name']) && !is_null($item['major_subject']) ? $item['university_name'] . ' | ' . $item['major_subject'] : '-' }}
        </td>
        {{-- <td>
            @if (!empty($item['equivalent_certificates']))
                @foreach ($item['equivalent_certificates'] as $certificate)
                    <a href="{{ $certificate }}" target="_blank">{{ $certificate }}</a>
                @endforeach
            @else
                -
            @endif
        </td> --}}
        <td>
            @if (!empty($item['degree_certificates']))
                @foreach ($item['degree_certificates'] as $degree)
                    <a href="{{ $degree }}" target="_blank">{{ $degree }}</a>
                @endforeach
            @else
                -
            @endif
        </td> --}}
        @php
            $data = [];
        @endphp
        @if ($employeeModel->status == 1)
            <td class="d-flex">
                @if ($menuRoles->assignedRoles('educationDetail.update'))
                    <a class="btn btn-sm btn-outline-primary btn-icon mx-1 editEducation" href="#"
                        data-popup="tooltip" data-placement="top" data-original-title="Edit" data-id="{{ $item['id'] }}"
                        data-all="{{ $item['id'] }}">
                        <i class="icon-pencil7"></i>
                    </a>
                @endif
                @if ($menuRoles->assignedRoles('educationDetail.delete'))
                    <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete" link="#" data-popup="tooltip"
                        data-placement="top" data-original-title="Delete" data-id="{{ $item['id'] }}">
                        <i class="icon-trash-alt"></i>
                    </a>
                @endif
            </td>
        @endif
    </tr>
@empty
    <tr>
        <td colspan="5">No Education Details Found !!!</td>
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
                deleteEducationDetail(id)
            }
        });

    });

    $('.editEducation').on('click', function() {
        let all = $(this).data('all')
        editEducation(all);
        var that = $(this);
        editModal(that);
    })
</script>
