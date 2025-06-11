@extends('admin::layout')
@section('title')
    Fuel Consumption
@endSection
@section('breadcrum')
    <a class="breadcrumb-item active">Fuel Consumptions</a>
@endSection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    @include('fuelconsumption::fuelConsumption.partial.search')
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Fuel Consumption</h6>
                All the Fuel Consumption Information will be listed below.
            </div>
            <div class="mt-1">
                <a href="{{ route('fuelConsumption.create') }}" class="btn btn-success rounded-pill"><i
                        class="icon-plus2"></i> Add</a>
                {{-- <div class="button-group">  --}}
                <a href="{{ route('fuelConsumptionDownload', request()->all()) }}"
                    class="btn btn-success rounded-pill ml-2"><b><i class="icon-add-to-list"></i></b> Download Fuel
                    Consumption</a>
                {{-- </div> --}}
            </div>
        </div>
    </div>

    <div class="card card-body">

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>#</th>
                        <th>Created Date</th>
                        <th>Employee Name</th>
                        <th>Organization</th>
                        <th>Starting Place</th>
                        <th>Destination Place</th>
                        <th>Vehicle No.</th>
                        <th>Start Km</th>
                        <th>End Km</th>
                        <th>Km Travelled</th>
                        <th>Purpose</th>
                        <th>Parking Cost</th>
                        <th>Status</th>
                        <th>Request Verify</th>
                        <th>Created By</th>
                        <th>Verified By</th>
                        <th>Approved BY</th>
                        <th width="13%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($fuelconsumptions->total() != 0)
                        @foreach ($fuelconsumptions as $key => $value)
                            @php
                                $prio_color = '';
                                $icon = ' ';
                                $modal = '';
                                $title = '';
                                $tr_color = '';

                                $tr_color = '';
                                if ($value->status == 'pending') {
                                    if ($value->verified_status == 'No') {
                                        $modal = '';
                                    } else {
                                        $modal = 'modal';
                                    }
                                    $prio_color = 'btn-warning';
                                    $icon = 'icon-history';
                                    $title = 'Pending';
                                }
                                if ($value->status == 'approved') {
                                    $prio_color = 'btn-success';
                                    $icon = 'icon-thumbs-up3';
                                    $modal = '';
                                    $title = 'Approved';
                                }
                            @endphp

                            <tr style="background-color:{{ $tr_color }}">
                                <td>{{ $fuelconsumptions->firstItem() + $key }}</td>
                                <td>{{ setting('calendar_type') == 'BS' ? date_converter()->eng_to_nep_convert($value->fuel_consump_created_date) : $value->fuel_consump_created_date }}
                                </td>
                                <td>{{ optional($value->employeeInfo)->first_name . ' ' . optional($value->employeeInfo)->last_name }}
                                </td>
                                <td>{{ optional(optional($value->employeeInfo)->organizationModel)->name }}</td>
                                <td>{{ $value->starting_place }}</td>
                                <td>{{ $value->destination_place }}</td>
                                <td>{{ $value->vehicle_no }}</td>
                                <td>{{ $value->start_km }}</td>
                                <td>{{ $value->end_km }}</td>
                                <td>{{ $value->km_travelled }}</td>
                                <td>{{ $value->purpose }}</td>
                                <td>{{ $value->parking_cost }}</td>
                                <td>
                                    <a data-toggle="{{ $modal }}" data-target="#modal_theme_status_open"
                                        class="btn {{ $prio_color }} text-default btn-icon btn-sm rounded-round ml-2 update_status"
                                        fuelconsump_id ="{{ $value->id }}" data-popup="tooltip" data-placement="bottom"
                                        data-original-title="{{ $title }}"><i class="{{ $icon }}"></i></a>
                                </td>
                                <td><b>{{ $value->verified_status }}</b></td>


                                <td>{{ optional($value->userInfo)->first_name . ' ' . optional($value->userInfo)->last_name }}
                                </td>
                                <td>{{ optional($value->verifyInfo)->first_name . ' ' . optional($value->verifyInfo)->last_name }}
                                </td>
                                <td>{{ optional($value->approvedUserInfo)->first_name . ' ' . optional($value->approvedUserInfo)->last_name }}
                                </td>

                                <td>
                                    @if ($value->status == 'pending')
                                        <a class="btn btn-info btn-icon rounded-round"
                                            href="{{ route('fuelConsumption.edit', ['fuelconsumption_id' => $value->id]) }}"
                                            data-popup="tooltip" data-placement="bottom" data-original-title="Edit"><i
                                                class="icon-pencil"></i></a>

                                        <a data-toggle="modal" data-target="#delete-modal-fuelconsumption"
                                            class="btn btn-danger btn-icon rounded-round delete_dailytask"
                                            link="{{ route('fuelConsumption.delete', $value->id) }}" data-popup="tooltip"
                                            data-placement="bottom" data-original-title="Delete"><i
                                                class="icon-bin"></i></a>

                                        @if ($value->verified_status == 'No')
                                            <a href="{{ route('fuelConsumption.verifyRequest', $value->id) }}"
                                                class="btn btn-success text-default btn-icon btn-sm rounded-round"
                                                fuelconsumption_id ="{{ $value->id }}"
                                                data-popup="tooltip"data-placement="bottom" data-original-title="Verify"><i
                                                    class="icon-shield-check"></i></a>
                                        @endif
                                    @endif

                                    <a data-toggle="modal" data-target="#modalViewFuelConsumption"
                                        class="btn btn-purple btn-icon rounded-round view_fuelconsumption"
                                        fuelconsumption_id = "{{ $value->id }}"><i class="icon-eye"></i></a>

                                    <a class="btn btn-warning btn-icon rounded-round" target="_blank"
                                        href="{{ route('fuelConsumption.printInvoice', $value->id) }}" data-popup="tooltip"
                                        data-placement="bottom" data-original-title="Print Invoice"><i
                                            class="icon-printer"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="8"><strong>Total KM Travelled:
                                </strong>{{ isset($total_km_travelled) ? number_format((float) $total_km_travelled, 2, '.', '') : 0 }}
                            </td>
                            <td colspan="10"><strong>Total Parking Cost:
                                </strong>{{ isset($total_parking_cost) ? number_format((float) $total_parking_cost, 2, '.', '') : 0 }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="17">No Fuel Consumption Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $fuelconsumptions->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            //update status
            $('.update_status').on('click', function() {
                var fuelconsump_id = $(this).attr('fuelconsump_id');
                $('.fuelconsump_id').val(fuelconsump_id);
            });
            //

            //view fuel consumption template
            $('.view_fuelconsumption').on('click', function() {
                var fuelconsumption_id = $(this).attr('fuelconsumption_id');
                $.ajax({
                    url: "<?php echo route('fuelConsumption.get-fuelConsumption-detail-ajax'); ?>",
                    method: 'POST',
                    data: {
                        fuelconsumption_id: fuelconsumption_id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $(".result_view_detail").html('');
                        $(".result_view_detail").html(data.options);
                    }
                });
            });
            //

            //delete fuel consumption
            $('.delete_dailytask').on('click', function() {
                var link = $(this).attr('link');
                $('.get_link').attr('href', link);
            });
            //
        });
    </script>
@endSection
