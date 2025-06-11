<div class="card" style="height: 350px;">
    <div class="card-header bg-transparent header-elements-inline">
        <h4 class="card-title font-weight-semibold">
            Pending Claim Requests
        </h4>
    </div>
    <div class="table-responsive card-body">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Total Requested Amount</th>
                </tr>
            </thead>
            <tbody>
               
                @if (!empty($tadas))
                    @foreach ($tadas as $key => $tada)
                    @php
                        $requested_amt = $tada->billAmount() ?? 0;
                    @endphp
                        <tr>
                            <td width="5%">{{ $tadas->firstItem() + $key }}</td>
                           <td>{{ $tada->title }}</td>
                           <td>{{ date('Y-m-d', strtotime($tada->created_at)) }}</td>
                           <td>Rs. {{ $requested_amt }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Claim Request Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>