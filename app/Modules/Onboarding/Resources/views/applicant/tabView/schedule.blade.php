<div class="card">
    <div class="card-body">
        <legend class="text-uppercase font-size-sm font-weight-bold">Interview Schedule</legend>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Grade of Interview</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Venue</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($interviewModels->total() != 0)
                        @foreach ($interviewModels as $key => $interviewModel)
                            <tr>
                                <td width="5%">#{{ $interviewModels->firstItem() + $key }}</td>
                                <td>{{ optional($interviewModel->interviewLevelModel)->title }}</td>
                                <td>{{ $interviewModel->date ? date('M d, Y', strtotime($interviewModel->date)) : '-' }}
                                </td>
                                <td>{{ $interviewModel->time ? date('h:i A', strtotime(date('Y-m-d') . ' ' . $interviewModel->time)) : '-' }}
                                </td>
                                <td>{{ $interviewModel->venue }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $interviewModel->getStatusWithColor()['color'] }}">{{ $interviewModel->getStatusWithColor()['status'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">No Record Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
