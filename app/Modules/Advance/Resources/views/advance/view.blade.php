@extends('admin::layout')
@section('title') {{ $title }} @endSection
@section('breadcrum')
<a href="{{ route('advance.index') }}" class="breadcrumb-item">{{ $title }}</a>
<a class="breadcrumb-item active">View</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Advance Detail</legend>
                    <div class="row">
                        <div class="col-md-5">
                            <ul class="media-list">
                                <li class="media mb-1">
                                    <span class="font-weight-semibold">Employee Name :</span>
                                    <div class="ml-auto">{{ optional($advanceModel->employeeModel)->full_name }}</div>
                                </li>
                                <li class="media mb-1">
                                    <span class="font-weight-semibold">Advance Amount :</span>
                                    <div class="ml-auto">Rs. {{ number_format($advanceModel->advance_amount, 2) }}</div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-2">&nbsp;</div>
                        <div class="col-md-5">
                            <ul class="media-list">
                                <li class="media mb-1">
                                    <span class="font-weight-semibold">Issue Date :</span>
                                    <div class="ml-auto">
                                        @if (setting('calendar_type') == 'BS')
                                            @if (!is_null($advanceModel->from_date))
                                                {{ date_converter()->eng_to_nep_convert($advanceModel->from_date) }}
                                            @endif
                                        @else
                                            {{ date('M d, Y', strtotime($advanceModel->from_date)) }}
                                        @endif
                                    </div>
                                </li>
                                <li class="media mb-1">
                                    <span class="font-weight-semibold">Settlement Type :</span>
                                    <div class="ml-auto">{{ $advanceModel->settlement_type_title }}</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <legend class="text-uppercase font-size-sm font-weight-bold mt-2">Settlement Detail</legend>
                    <div class="table-responsive bg-none">
                        <table class="table table-striped">
                            <thead>
                                <tr class="text-light btn-slate">
                                    <th>S.N</th>
                                    <th>Paid Date</th>
                                    <th>Paid Amount</th>
                                    <th>Remaining Amount</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $remainingAmount = 0; @endphp
                                @if(count($advanceModel->settlementPaymentModels))
                                    @foreach($advanceModel->settlementPaymentModels as $key => $settlementPaymentModel)
                                        @php
                                            if($key == 0) {
                                                $remainingAmount = $advanceModel->advance_amount - $settlementPaymentModel->amount;
                                            } else {
                                                $remainingAmount -= $settlementPaymentModel->amount;
                                            }
                                        @endphp
                                        <tr>
                                            <td>#{{ ++$key }}</td>
                                            <td>
                                                @if (setting('calendar_type') == 'BS')
                                                    {{ $settlementPaymentModel->date ? date_converter()->eng_to_nep_convert($settlementPaymentModel->date) : '-' }}
                                                @else
                                                    {{ $settlementPaymentModel->date ? date('M d, Y', strtotime($settlementPaymentModel->date)) : '-' }}
                                                @endif
                                                
                                            </td>
                                            <td>Rs. {{ number_format($settlementPaymentModel->amount) }}</td>
                                            <td>Rs. {{ number_format($remainingAmount) }}</td>
                                            <td>{{ $settlementPaymentModel->remark }}</td>
                                            <td>
                                                <span class="badge badge-{{ $settlementPaymentModel->statusDetail['color'] }}">{{ $settlementPaymentModel->statusDetail['title'] }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="5">No record found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Pay Detail</legend>
                    @if($advanceModel->status !== 3)
                        {!! Form::open(['route'=>'advance.pay','method'=>'POST','class'=>'form-horizontal','id'=>'advanceFormSubmit','role'=>'form','files'=>true]) !!}
                            @include('advance::advance.partial.pay',['btnType'=>'Pay Now'])
                        {!! Form::close() !!}
                    @else
                        Advance is fully settled.
                    @endif
                </div>
            </div>
        </div> -->
    </div>

@endsection

@section('script')
<script src="{{asset('admin/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_inputs.js')}}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js')}}"></script>
@endSection
