<style>
    table,
    th,
    td {
        border: 1px solid;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .container {
        display: flex;
        justify-content: flex-end;
        /* Align items to the end of the container */
    }

    .col {
        width: auto;
        /* Adjust the width as needed */
    }

    .signature {
        font-weight: bold;
    }
</style>


<h3>Travel Request</h3>
<table>
    <thead>
        <tr>
            <th>Employee</th>
            <th>Title</th>
            <th>Type</th>
            <th>Requested days</th>
            <th>From Date</th>
            <th>To Date</th>
            <th>Applicant remarks</th>
            <th>Management Remarks</th>
            <th>Status</th>
            <th>Claim Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                {{ optional($businessTripModel->employee)->getFullName() }}
                <br>
                {{ optional($businessTripModel->employee)->official_email ?? optional($businessTripModel->employee)->personal_email }}

            </td>
            <td>{{ $businessTripModel->title }}</td>
            <td>{{ optional($businessTripModel->type)->title }}</td>

            <td>{{ $businessTripModel->request_days }} Days</td>
            @if (setting('calendar_type') == 'BS')
                <td>{{ $businessTripModel->from_date_nep }}</td>
                <td>{{ $businessTripModel->to_date_nep }}</td>
            @else
                <td>{{ $businessTripModel->from_date }}</td>
                <td>{{ $businessTripModel->to_date }}</td>
            @endif
            <td>{{ $businessTripModel->remarks }}</td>
            <td>{{ $businessTripModel->reject_note }}</td>
            <td class="text-center">
                {{ $businessTripModel->getStatus() ?? 'Pending' }}
            </td>
            <td class="text-center">
                {{ $businessTripModel->getClaimStatus() ?? 'Pending' }}
                @if ($businessTripModel->claim_status == 2)
                    <span>Rs.
                        {{ $businessTripModel->eligible_amount ? $businessTripModel->eligible_amount : 0 }}</span>
                @endif
            </td>

        </tr>
    </tbody>
</table>

<br>
<br>
<br>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col">
            <span class="signature">Signature:</span>
        </div>
    </div>
</div>
