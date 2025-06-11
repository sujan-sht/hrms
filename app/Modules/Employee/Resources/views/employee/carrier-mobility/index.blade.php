@extends('admin::layout')
@section('title') Career Mobility @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Career Mobility</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('employee::employee.carrier-mobility.partial.advance-filter', [
    'route' => route('employee.carrierMobility'),
])

@if (!empty($employee))
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-elements-inline text-light bg-secondary">
                    <h5 class="card-title">Current Details</h5>
                    <div class="header-elements">

                    </div>
                </div>

                <div class="card-body">
                    @include('employee::employee.carrier-mobility.partial.current_details')
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-elements-inline text-light bg-secondary">
                    <h5 class="card-title">New Details</h5>
                </div>
                {!! Form::open([
                    'route' => 'employee.storeCarrierMobility',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'id' => 'carrierMobilityFormSubmit',
                    'role' => 'form',
                    'files' => false,
                ]) !!}
                <div class="card-body card-temporary-address">
                    @include('employee::employee.carrier-mobility.partial.new_details')

                    <div class="text-center">
                        <button type="button" class="btn btn-success btn-labeled btn-labeled-left" id="submitData"
                            data-employee_id={{ request()->employee_id }}><b><i
                                    class="icon-database-insert"></i></b>Save Record</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @include('employee::employee.carrier-mobility.partial.popup-modal')
@endif



@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('admin/validation/employee-carrier-mobility.js') }}"></script>
<script type="text/javascript">
    $('document').ready(function() {
        $('#submitData').on('click', function() {
            var type = $('.type').val();
            var organization_id = $('#organizationId').val();
            var date = $('#date').val();
            var employee_id = $(this).data('employee_id');
            if (type == 1) {
                if (date && organization_id && type) {
                    $('#date-error-message').hide();
                    $('#organization-error-message').hide();
                    $('#appendLeaveDetail').html("");
                    $('#updateLeave').modal('show');
                    $.ajax({
                        type: "get",
                        url: "{{ route('employee.carrierMobility.appendLeaveDetail') }}",
                        data: {
                            date: date,
                            employee_id: employee_id,
                            organization_id: organization_id,
                            type_id: type
                        },
                        success: function(res) {
                            // console.log(res);
                            $('#appendLeaveDetail').append(res.data);
                        }
                    });
                }
                else{
                    $('#date-error-message').show();
                    $('#organization-error-message').show();
                }

            } else {
                $('#carrierMobilityFormSubmit').submit();
            }
        });
    });
</script>
@endSection
