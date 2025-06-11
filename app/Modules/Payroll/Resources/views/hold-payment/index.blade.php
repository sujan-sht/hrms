@extends('admin::layout')
@section('title') Hold Payment @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Payroll</a>
<a class="breadcrumb-item active">Hold Payment</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>
    @include('payroll::hold-payment.partial.advance_filter')
<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Hold Payment</h6>
            All the hold payment Information will listed below.
        </div>
        <div class="ml-2">
            <a href="{{ route('exportHoldPayment', request()->all()) }}"
                class="btn btn-success rounded-pill export-btn"><i class="icon-file-excel"></i> Export</a>
            <a href="{{ route('holdPayment.create') }}" class="btn btn-danger rounded-pill"><i
                    class="icon-plus3"></i>Create New</a>
        </div>

    </div>
</div>
<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>#</th>
                    <th>Organization Name</th>
                    <th>Employee Name</th>
                    <th>Hold Year</th>
                    <th>Hold Month</th>
                    <th>Created by</th>
                    <th>Created at</th>
                    <th>Notes</th>
                    <th>Status</th>
                    <th width="15%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($holdPayments->total() != 0)
                    @foreach ($holdPayments as $key => $value)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ optional($value->organizationModel)->name }}</td>
                            <td>{{ optional($value->employeeModel)->getFullName() }}</td>
                            <td>{{ $value->year }}</td>
                            @if($value->calendar_type == 'nep')
                                <td>{{ date_converter()->_get_nepali_month($value->month) }}</td>
                            @else
                            <td>{{ date_converter()->_get_english_month($value->month) }}</td>
                            @endif
                            <td>{{@$value->created_by}}</td>
                            <td>{{@$value->created_at->format('Y-m-d')}}</td>
                        
                          
                            <td>{{ $value->notes }}</td>
                            <td>

                                <span class="badge badge-danger">{{@$value->hold_status==2 ? 'Cancel':''}}</span>
                            </td>
                            {{-- @if($value->is_released == 0)
                                <td class="d-flex">
                                    <a data-toggle="modal" data-target="#modal_hold_update"
                                        class="btn bg-orange-700 btn-icon rounded-round update_release_status"
                                        hold_payment_id="{{ $value->id }}" calendar_type="{{ $value->calendar_type }}"
                                        data-popup="tooltip" data-placement="bottom" data-original-title="Update Status"><i
                                            class="icon-history"></i></a>
                                </td>
                            @endif --}}
                            
                            <td>
                                @if(!$value->checkCancelStatus())
                            @if ($menuRoles->assignedRoles('holdPayment.edit'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('holdPayment.edit', $value->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif
                                @endif
                                {{-- @if($menuRoles->assignedRoles('holdPayment.destroy'))
                                    <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete" link="{{route('holdPayment.destroy',$value->id)}}" data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif --}}
                                @if($menuRoles->assignedRoles('holdPayment.cancel'))
                                    @if(!$value->checkCancelStatus() && $value->hold_status ==1)
                                    <a class="btn btn-sm btn-outline-danger btn-icon confirmCancel" href="{{route('holdPayment.cancel',$value->id)}}" data-popup="tooltip" data-placement="top" data-original-title="Cancel">
                                        <i class="icon-cross"></i>
                                    </a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Fiscal Year Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $holdPayments->appends(request()->all())->links() }}
        </span>
    </div>
</div>

<!-- Warning modal -->
<div id="modal_hold_update" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h6 class="modal-title">Update Release Status</h6>
            </div>

            <div class="modal-body">
                {!! Form::open([
                    'route' => 'holdPayment.updateStatus',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'role' => 'form',
                    'id' => 'release_submit',
                ]) !!}

                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Is Released?:<span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        {!! Form::select('is_released', ['0' => 'No', '1' => 'Yes'], null, [
                            'id' => 'is_released',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>
                <div id="dates">
                </div>

                {{ Form::hidden('hold_payment_id', '', ['class' => 'hold_payment_id']) }}
                {{ Form::hidden('calendar_type', '', ['class' => 'calendar_type']) }}

                <div class="text-center">
                    <button type="submit" class="btn bg-teal-400 update_btn">Update Status</button>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>
<!-- /warning modal -->
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $(document).on('click', '.update_release_status', function() {
            var hold_payment_id = $(this).attr('hold_payment_id');
            $('.hold_payment_id').val(hold_payment_id);
            var calendar_type = $(this).attr('calendar_type');
            $('.calendar_type').val(calendar_type);

        });
        $('#is_released').on('change', function() {
            var is_released = $(this).val();
            if (is_released == 1) {
                var calendar_type = $('.calendar_type').val();
                var url = "{{ route('holdpayment.getDates') }}";
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: 'calender_type=' + calendar_type + '&_token={{ csrf_token() }}',
                    success: function(resp) {
                        console.log(resp);
                        if (resp != 0) {
                            $('#dates').html(resp);
                        }
                    }
                });
            } else {
                $('#dates').html('');
            }
        });

    });
</script>

@endsection
