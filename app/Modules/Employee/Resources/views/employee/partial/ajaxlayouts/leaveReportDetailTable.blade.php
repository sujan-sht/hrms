<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="text-light btn-slate">
                <th>S.N</th>
                <th>Leave Type</th>
                <th>Leave Date</th>
                <th>No. of Days</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leave_report as $key => $item)
                <tr>
                    <td width="5%">#{{ ++$key }}</td>
                    <td>{{ ucfirst(optional($item->leaveTypeModel)->name) ?? '-' }}</td>
                    <td>{{ $item->getDateRangeWithCount()['range'] }}</td>
                    <td>
                        @if(isset($item->generated_by) && $item->generated_by == 11)
                            {{ $item->generated_no_of_days }}
                        @else
                            {{ $item->getDateRangeWithCount()['count'] }}
                        @endif
                    </td>
                    <td>{{ $item->reason }}</td>
                    <td>
                        <span class="badge badge-{{ $item->getStatusWithColor()['color'] }}">{{ $item->getStatusWithColor()['status'] }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No Leave Details Found !!!</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
