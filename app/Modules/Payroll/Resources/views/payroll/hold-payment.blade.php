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
        <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
            style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
    </div>
</div>
<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            @php
                $month =
                    $payrollModel->calendar_type == 'nep'
                        ? date_converter()->_get_nepali_month($payrollModel->month)
                        : date_converter()->_get_english_month($payrollModel->month);
            @endphp
            <h6 class="media-title font-weight-semibold">
                {{ 'List of Hold Payment For The Month' . ' ' . $month . ',' . $payrollModel->year }}</h6>
            All the hold payment Information will listed below.
        </div>
        {{-- <div class="ml-2">
            <a href="{{ route('holdPayment.create') }}" class="btn btn-danger rounded-pill"><i
                    class="icon-plus3"></i>Create New</a>
        </div> --}}

    </div>
</div>
<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>#</th>
                    <th>Employee Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @if (count($holdPayments) > 0)
                    @foreach ($holdPayments as $key => $value)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ optional($value->employeeModel)->getFullName() }}</td>
                            <td>{{ $value->getStatus() }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Hold Payment Found !!!</td>
                    </tr>
                @endif

            </tbody>
            <footer>
                <tr>
                    <td></td>
                    <td></td>
                    @if (count($employeeList) > 0)
                        <td>
                            <a data-toggle="modal" data-target="#modal_hold_update"
                                class="btn bg-orange-700 btn-icon rounded-round update_release_status"
                                hold_payment_id="{{ $value->id }}" payroll_id="{{ $payrollModel->id }}"
                                calendar_type="{{ $value->calendar_type }}" data-popup="tooltip" data-placement="bottom"
                                data-original-title="Update Status"><i class="icon-history"></i></a>
                        </td>
                    @endif

                <tr>
            </footer>
        </table>
    </div>
    {{-- <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $holdPayments->appends(request()->all())->links() }}
        </span>
    </div> --}}
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
                    <label class="col-form-label col-lg-3">Status:<span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        {!! Form::select('status', $statusList, null, [
                            'id' => 'status',
                            'class' => 'form-control',
                            'placeholder' => 'Select Status',
                            'required',
                        ]) !!}
                    </div>
                </div>
                <div id="dates">
                </div>

                {{ Form::hidden('hold_payment_id', '', ['class' => 'hold_payment_id']) }}
                {{ Form::hidden('calendar_type', '', ['class' => 'calendar_type']) }}
                {{ Form::hidden('payroll_id', '', ['class' => 'payroll_id']) }}

                <div class="text-center">
                    <button type="submit" class="btn bg-teal-400 update_btn text-white">Update Status</button>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>
<!-- /warning modal -->
@endsection
@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
<script>
    const updateMonth=()=>{
        var year = $('#released_year').val();
        var calendar_type = $('.calendar_type').val();
        var filterData = payrollData[calendar_type][year];
        var monthHtml = $('#released_month');
        monthHtml.find('option').each(function() {
            if (!filterData || filterData.length <= 0) {
                $(this).prop('disabled', false);
            } else {
                var optionValue = $(this).val();
                if (filterData.includes(optionValue)) {
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }
            }
        });
    }
    $(document).ready(function() {
        $(document).on('click', '.update_release_status', function() {
            var hold_payment_id = $(this).attr('hold_payment_id');
            $('.hold_payment_id').val(hold_payment_id);
            var calendar_type = $(this).attr('calendar_type');
            $('.calendar_type').val(calendar_type);
            var payroll_id = $(this).attr('payroll_id');
            $('.payroll_id').val(payroll_id);

        });
        $('#status').on('change', function() {
            var status = $(this).val();
            var calendar_type = $('.calendar_type').val();
            var payroll_id = $('.payroll_id').val();
            let form_data = {
                calendar_type,
                payroll_id,
                status,
                "_token": "{{ csrf_token() }}"
            }
            var url = "{{ route('holdpayment.getDates') }}";
            $.ajax({
                type: 'POST',
                url: url,
                data: form_data,
                success: function(resp) {
                    console.log(resp);
                    if (resp != 0) {
                        $('#dates').html(resp);
                        $('.multi').multiselect({
                            includeSelectAllOption: true,
                            enableFiltering: true,
                            enableCaseInsensitiveFiltering: true
                        });
                    }
                    updateMonth();
                }
            });
        });

    });
    let payrollData = @json($finalizedPayrollArray);
    $(document).on('change', '#released_year', function() {
        updateMonth();
    });
</script>
@endsection
