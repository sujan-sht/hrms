<style>
    table, th, td {
        border: 1px solid;
    }
    table{
        width: 100%;
        border-collapse: collapse;
    }
</style>
@php
    $requested_amt =  $tada->billAmount() ?? 0;
    if($tada->status == 'fully settled') {
        $settled_amt = $requested_amt;
    } elseif($tada->status == 'request closed') {
        $settled_amt = $tada->request_closed_amt ?? 0;
    } else {
        $settled_amt = $tada->partiallySettledAmount() ?? 0;
    }

    $balance = $requested_amt - $settled_amt;
@endphp

<h5>TADA Information</h5>
<table>
    <thead >
        <tr>
            <th>Title</th>
            <th>Employee</th>
            <th>From Date</th>
            <th>To Date</th>
            <th>Total Bill Amount</th>
            <th>Status</th>
            @if($tada->getStatus() == 'Accepted')
                <th>Status Accepted Date</th>
            @endif
            <th>Total Requested Amount</th>
            <th>Total Settled Amount</th>
            <th>Balance</th>
            <th>Remarks</th>
            <th>Created Date</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $tada->title }}</td>
            <td>{{ optional($tada->employee)->first_name }} {{ optional($tada->employee)->last_name }}</td>
            <td>{{ $tada->nep_from_date  }}</td>
            <td>{{ $tada->nep_to_date }}</td>
            <td>{{ $tada->billAmount() ?? 0 }}</td>
            <td>{{ $tada->getStatus() }}</td>
            @if($tada->getStatus() == 'Accepted')
                <td>{{ $tada->request_closed_date }}</td>
            @endif
            <td>{{ $requested_amt }}</td>
            <td>{{ $settled_amt }}</td>
            <td>{{ $balance }}</td>
            <td>{{ ucfirst($tada->remarks ) }}</td>
            <td>{{ getStandardDateFormat($tada->created_at) }}</td>
        </tr>
    </tbody>
</table>

<br>
<h5>Employee Details</h5>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Phone(CUG No.)</th>
            <th>Official Email</th>
            <th>Designation</th>
            <th>Department</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ optional($tada->employee)->first_name }} {{ optional($tada->employee)->last_name }}</td>
            <td>{{ optional($tada->employee)->phone }}</td>
            <td>{{ optional($tada->employee)->official_email }}</td>
            <td>{{ optional($tada->employee)->designation_id >0 ? optional(optional($tada->employee)->designation)->title : '' }}</td>
            <td>{{ optional($tada->employee)->department_id > 0 ? optional(optional($tada->employee)->department)->title : '' }}</td>
        </tr>
    </tbody>
</table>

<br>
@if (!empty($tada->excel_file) && file_exists(public_path('uploads/tada/excels/'.$tada->excel_file)))
    <h5>Tada Excel File</h5>
    <table>
        <thead>
            <tr>
                <th>                
                    Excel File
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <tr>
                    <td>
                        {{-- <a href="{{asset('uploads/tada/excels/'.$tada->excel_file) }}" target="_blank"
                            data-popup="tooltip" data-original-title="Bill Image"> --}}
                            {{$tada->excel_file}}
                        {{-- </a> --}}
                    </td>
                </tr>
            </tr>
        </tbody>
    </table>
@endif

<br>
<h5>TADA Details</h5>
<table>
    <thead>
        <tr>
            <th>TADA Type</th>
            <th>Amount</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tada->tadaDetails as $detail)
            <tr>
                <td>{{optional($detail->tadaType)->title}}</td>
                <td>Rs. {{ $detail->amount ?? 0 }}</td>
                <td>{{ $detail->remark }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>
<h5>Bills</h5>
<table>
    <thead>
        <tr>
            <th>
                Bill Image
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tada->bills as $bill)
            @if (!empty($bill->image_src) && file_exists(public_path('uploads/tada/bills/'.$bill->image_src)))
                <tr>
                    <td>
                        {{-- <a href="{{asset('uploads/tada/bills/'.$bill->image_src) }}" target="_blank"
                            data-popup="tooltip" data-original-title="Bill Image"> --}}
                            {{$bill->image_src}}
                        {{-- </a> --}}
                    </td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>

<br>
@if($tada->status == 'partially settled' && !empty($tada->tadaPartiallySettled))
    <br>
    <h5 class="card-title">Partially Settled Details</h5>
    <table>
        <thead>
            <tr>
                <th>Settled By</th>
                <th>Settled Method</th>
                <th>Settled Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tada->tadaPartiallySettled as $detail)
                <tr>
                    <td>{{ optional($detail->settledBy)->first_name.' '.optional($detail->settledBy)->middle_name.' '.optional($detail->settledBy)->last_name }}</td>
                    <td>{{ $detail->settled_method ?? '' }}</td>
                    <td>Rs. {{ $detail->settled_amt ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
