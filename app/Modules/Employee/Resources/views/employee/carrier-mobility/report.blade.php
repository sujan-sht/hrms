@extends('admin::layout')

@section('breadcrum')
    <a class="breadcrumb-item active">Career Mobility Report</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@inject('mobility', '\App\Modules\Employee\Entities\EmployeeCarrierMobility')

@section('content')
    @include('employee::employee.carrier-mobility.partial.report-filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Employee Career Mobility</h6>
                All the Career Mobility Information will be listed below. You can view the data.
            </div>
            {{-- <div class="mt-1">
                <a href="{{ route('exportRegularAttendance', request()->all()) }}" class="btn btn-success rounded-pill"><i
                        class="icon-file-excel"></i> Export</a>
            </div> --}}
        </div>
    </div>
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $key => $report)
                        <tr>
                            <td>#{{ $key + 1 }} </td>
                            <td class="d-flex text-nowrap">
                                <div class="media">
                                    <div class="media-body">
                                        <div class="media-title font-weight-semibold">
                                            {{ $report['fullname'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $report['date'] }}
                            </td>
                            <td>
                                {{ $report['type'] }}
                            </td>
                            <td>
                                @if ($menuRoles->assignedRoles('employee.deleteCarrierMobilityReport'))
                                    <a class="btn btn-outline-danger btn-icon confirmDelete mx-1"
                                        link="{{ route('employee.deleteCarrierMobilityReport', [$report['id'], $report['type']]) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $reports->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
    <div id="modal_map" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-indigo text-white border-0">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div id="map"
                        style="width:870px;height:400px; margin:auto; border: 1px solid #0c0c0c; padding: 10px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
