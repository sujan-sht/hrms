@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@forelse($training_attendance as $key => $item)
    {{-- @dd($item) --}}
    <tr>
        <td width="5%">#{{ ++$key }}</td>
        <td>{{ optional($item->trainingInfo)->title }}</td>
        <td>{{ optional($item->trainingInfo)->type }}</td>
        <td>{{ optional($item->trainingInfo)->location }}</td>
        <td>{{ optional($item->trainingInfo)->from_date }}</td>
        <td>{{ optional($item->trainingInfo)->to_date }}</td>
        {{-- <td>{{ optional($item->trainingInfo)->status }}</td> --}}

        <td>
            @if ($item->status)
                <span
                    class="badge badge-{{ $item->getStatusWithColor()['color'] }}">{{ $item->getStatusWithColor()['status'] }}</span>
            @endif
        </td>

        <td>{{ $item->rating }}</td>

        @if ($employeeModel->status == 1)
            <td class="d-flex">
                <a class="btn btn-outline-secondary btn-icon mx-1"
                    href="{{ route('training.view-training-certificate', ['training_id' => $item->training_id, 'id' => $item->id]) }}"
                    data-popup="tooltip" data-placement="top" data-original-title="View Training Certificate">
                    <i class="icon-eye"></i>
                </a>

                <a class="btn btn-outline-warning btn-icon print-window" target="_blank"
                    href="{{ route('training.print-training-certificate', ['training_id' => $item->training_id, 'id' => $item->id]) }}"
                    data-popup="tooltip" data-placement="top" data-original-title="Print Training Certificate">
                    <i class="icon-printer"></i>
                </a>

                {{-- @if ($menuRoles->assignedRoles('assetDetail.update')) --}}
                @if (is_null($item->rating))
                    <a class="btn btn-sm btn-outline-primary btn-icon mx-1 editTraining" href="#"
                        data-popup="tooltip" data-placement="top" data-original-title="Give Rating"
                        data-id="{{ $item->id }}" data-all="{{ $item }}"
                        data-trainer="{{ optional($item->trainingInfo)->trainer }}">
                        <i class="icon-stars"></i>
                    </a>
                @endif
                {{-- @endif --}}
            </td>
        @endif
    </tr>
@empty
    <tr>
        <td colspan="5">No Training Found !!!</td>
    </tr>
@endforelse


<script>
    $('.editTraining').on('click', function() {
        let all = $(this).data('all')
        let trainer = $(this).data('trainer')
        console.log(typeof trainer);
        let html = '';
        if (typeof trainer === "object") {

            html = '<tr><td>Full Name</td><td>' + trainer.full_name + '</td></tr>';
            html += '<tr><td>Email</td><td>' + trainer.email + '</td></tr>';
            html += '<tr><td>Phone</td><td>' + trainer.phone + '</td></tr>';
            html += '<tr><td>Remark</td><td>' + trainer.remark + '</td></tr>';

        } else {
            html = '<tr><td>No Trainer Found!</td></tr>';
        }

        $('#appendTrainer').html(html);

        console.log(trainer);
        editTraining(all)
        var that = $(this);
        editModal(that);
    })
</script>
