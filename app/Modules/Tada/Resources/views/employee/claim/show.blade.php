@extends('admin::employee.layout')
@section('title') Claim | View @stop
@section('breadcrum') HR Requisition / Advance Request / View @stop

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@stop

@section('content')
    @php
        $requested_amt = $tada->billAmount() ?? 0;
        if ($tada->status == 'fully settled') {
            $settled_amt = $requested_amt;
        } elseif ($tada->status == 'request closed') {
            $settled_amt = $tada->request_closed_amt ?? 0;
        } else {
            $settled_amt = $tada->partiallySettledAmount() ?? 0;
        }

        $balance = $requested_amt - $settled_amt;
    @endphp

    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header bg-teal-400 header-elements-inline">
                    <h5 class="card-title">Advance Request Information</h5>
                </div>
                <div class="card-body border-top-0">
                    <div class="d-sm-flex flex-sm-wrap mb-3">
                        <div class="font-weight-semibold">Title:</div>
                        <div class="ml-sm-auto mt-2 mt-sm-0">{{ $tada->title }}</div>
                    </div>

                    <div class="d-sm-flex flex-sm-wrap mb-3">
                        <div class="font-weight-semibold">Employee:</div>
                        <div class="ml-sm-auto mt-2 mt-sm-0">{{ optional($tada->employee)->first_name }}
                            {{ optional($tada->employee)->last_name }}</div>
                    </div>
                    <!-- <div class="d-sm-flex flex-sm-wrap mb-3">
                        <div class="font-weight-semibold">Approval From:</div>
                        <div class="ml-sm-auto mt-2 mt-sm-0">{{ optional($tada->approval)->first_name }} {{ optional($tada->approval)->middle_name }} {{ optional($tada->approval)->last_name }}</div>
                    </div> -->
                    <div class="d-sm-flex flex-sm-wrap mb-3">
                        <div class="font-weight-semibold">From Date:</div>
                        <div class="ml-sm-auto mt-2 mt-sm-0">{{ $tada->nep_from_date }}</div>
                    </div>
                    <div class="d-sm-flex flex-sm-wrap mb-3">
                        <div class="font-weight-semibold">To Date:</div>
                        <div class="ml-sm-auto mt-2 mt-sm-0">{{ $tada->nep_to_date }}</div>
                    </div>
                    <div class="d-sm-flex flex-sm-wrap mb-3">
                        <div class="font-weight-semibold">Total Bill Amount:</div>
                        <div class="ml-sm-auto mt-2 mt-sm-0">Rs. {{ $tada->billAmount() ?? 0 }}</div>
                    </div>
                    <div class="d-sm-flex flex-sm-wrap mb-3">
                        <div class="font-weight-semibold">Status:</div>
                        <div class="ml-sm-auto mt-2 mt-sm-0">{{ ucfirst($tada->status) }}</div>
                    </div>
                    <div class="d-sm-flex flex-sm-wrap mb-3">
                        <div class="font-weight-semibold">Total Requested Amount:</div>
                        <div class="ml-sm-auto mt-2 mt-sm-0">Rs. {{ $requested_amt }}</div>
                    </div>
                    <div class="d-sm-flex flex-sm-wrap mb-3">
                        <div class="font-weight-semibold">Total Settled Amount:</div>
                        <div class="ml-sm-auto mt-2 mt-sm-0">Rs. {{ $settled_amt }}</div>
                    </div>
                    <div class="d-sm-flex flex-sm-wrap mb-3">
                        <div class="font-weight-semibold">Balance:</div>
                        <div class="ml-sm-auto mt-2 mt-sm-0">Rs. {{ $balance }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header bg-teal-400 header-elements-inline">
                    <h5 class="card-title">Employee Details</h5>
                </div>
                <div class="card-body">
                    <div class="d-sm-flex flex-sm-wrap mb-3">
                        <div class="font-weight-semibold">Name:</div>
                        <div class="ml-sm-auto mt-2 mt-sm-0">{{ optional($tada->employee)->first_name }}
                            {{ optional($tada->employee)->last_name }}</div>
                    </div>
                    <div class="d-sm-flex flex-sm-wrap mb-3">
                        <div class="font-weight-semibold">Phone(CUG No.):</div>
                        <div class="ml-sm-auto mt-2 mt-sm-0">{{ optional($tada->employee)->phone }}</div>
                    </div>
                    <div class="d-sm-flex flex-sm-wrap mb-3">
                        <div class="font-weight-semibold">Official Email:</div>
                        <div class="ml-sm-auto mt-2 mt-sm-0">{{ optional($tada->employee)->official_email }}</div>
                    </div>
                    <div class="d-sm-flex flex-sm-wrap mb-3">
                        <div class="font-weight-semibold">Designation:</div>
                        <div class="ml-sm-auto mt-2 mt-sm-0">
                            {{ optional($tada->employee)->designation_id > 0 ? optional(optional($tada->employee)->designation)->title : '' }}
                        </div>
                    </div>
                    <div class="d-sm-flex flex-sm-wrap mb-3">
                        <div class="font-weight-semibold">Sub-Function:</div>
                        <div class="ml-sm-auto mt-2 mt-sm-0">
                            {{ optional($tada->employee)->department_id > 0 ? optional(optional($tada->employee)->department)->title : '' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header bg-teal-400">
                    <h5 class="card-title">Advance Request Details</h5>
                </div>
                <div class="card-body">
                    @foreach ($tada->tadaDetails as $detail)
                        <div class="d-sm-flex flex-sm-wrap mb-3">
                            <div class="font-weight-semibold">{{ optional($detail->tadaType)->title }}:</div>
                            <div class="ml-sm-auto mt-2 mt-sm-0">Rs. {{ $detail->amount ?? 0 }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @if ($tada->status == 'partially settled' && !empty($tada->tadaPartiallySettled))

        <div class="card">
            <div class="card-header bg-teal-400">
                <h5 class="card-title">Partially Settled Details</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-{{ config('admin.color-class.table') }}">
                    <thead>
                        <tr class="bg-{{ config('admin.color-class.thead_tr') }}">
                            <th>Settled By</th>
                            <th>Settled Method</th>
                            <th>Settled Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tada->tadaPartiallySettled as $detail)
                            <tr>
                                <td>{{ optional($detail->settledBy)->first_name . ' ' . optional($detail->settledBy)->middle_name . ' ' . optional($detail->settledBy)->last_name }}
                                </td>
                                <td>{{ $detail->settled_method ?? '' }}</td>
                                <td>Rs. {{ $detail->settled_amt ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @endif


@endsection
