@extends('admin::layout')
@section('title') Advance Payment Ledger @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Advance Payment Ledger</a>
@stop

@section('content')

@include('advance::advance-payment-ledger.partial.filter')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">Advance Payment Ledger Statement</h6>
            All the payment statement will be listed below.
        </div>

    </div>
</div>

<div class="card card-body">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr class="btn-slate text-light">
                    <th>Date</th>
                    <th>Description</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                @php $totalBalance = 0; @endphp
                @if ($advancePaymentLedgerModels->total() > 0)
                    @foreach ($advancePaymentLedgerModels as $advancePaymentLedgerModel)
                        <tr>
                            <td>{{ $advancePaymentLedgerModel->date }}</td>
                            <td>{{ $advancePaymentLedgerModel->description }}</td>
                            <td>{{ $advancePaymentLedgerModel->debit ? number_format($advancePaymentLedgerModel->debit, 2) : '-' }}
                            </td>
                            <td>{{ $advancePaymentLedgerModel->credit ? number_format($advancePaymentLedgerModel->credit, 2) : '-' }}
                            </td>
                            <td>{{ number_format($advancePaymentLedgerModel->balance, 2) }}</td>
                            @php
                                if ($advancePaymentLedgerModel->debit) {
                                    $totalBalance += $advancePaymentLedgerModel->debit;
                                } else {
                                    $totalBalance -= $advancePaymentLedgerModel->credit;
                                }
                            @endphp
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No statement found.</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th>&nbsp;</th>
                    <th colspan="3">Total Balance</th>
                    <th>{{ number_format($totalBalance, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $advancePaymentLedgerModels->appends(request()->all())->links() }}
        </span>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
@endSection
