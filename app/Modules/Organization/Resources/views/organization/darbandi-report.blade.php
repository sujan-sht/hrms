@extends('admin::layout')
@section('title') Darbandi Report @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Darbandi Report</a>
@endsection

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">Darbandi Report</h6>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive ">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-light btn-slate">
                            <th>#</th>
                            <th>Designation</th>
                            <th>Darbandi Quantity</th>
                            <th>Fulfilled Position</th>
                            <th>Open Position</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($darbandis->count() > 0)
                            @foreach ($darbandis as $key => $darbandi)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $darbandi->designation->title }}</td>
                                    <td>{{ $darbandi->no }}</td>
                                    <td>
                                        @php
                                            $employeeQty = App\Modules\Employee\Entities\Employee::where(
                                                'organization_id',
                                                $darbandi->organization_id,
                                            )
                                                ->where('designation_id', $darbandi->designation_id)
                                                ->count();
                                        @endphp
                                        {{ $employeeQty }}
                                    </td>
                                    <td>
                                        {{ $darbandi->no - $employeeQty }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>No Data Found!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>

@endsection
