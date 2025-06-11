@extends('admin::layout')

@section('title')
    Insurance Details
@endsection

@section('breadcrum')
    <a class="breadcrumb-item active">Insurance Details </a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
    @include('insurance::partial.advance-filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Insurance Details</h6>
                All the Insurance Details Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('insurance.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('insurance.create') }}" class="btn btn-success rounded-pill">Create</a>
                </div>
            @endif
        </div>
    </div>
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Insurance Type</th>
                        <th>Company Name</th>
                        <th>Premium Amount</th>
                        <th>Premium Payment By</th>
                        <th>Policy Number</th>
                        <th>Policy Start</th>
                        <th>Policy Expiry </th>
                        <th>Policy Maturity Date</th>
                        <th>Document Upload</th>
                        <th style="width: 12%;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($insurances as $key => $insurance)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ optional($insurance->type)->title }}</td>
                            <td>{{ $insurance->company_name ?? null }}</td>
                            <td>
                                {{ !is_null($insurance->premium_amount) ? number_format($insurance->premium_amount, 2) : null }}
                            </td>
                            <td>
                                {{ !is_null($insurance->premium_payment_by) ? ucfirst($insurance->premium_payment_by) : '-' }}
                                <br>
                                @if ($insurance->premium_payment_by == 'sharing')
                                    <strong>Employee Amt :
                                    </strong>{{ number_format($insurance->total_employees, 2) ?? null }}
                                    <strong>Employer Amt :
                                    </strong>{{ number_format($insurance->total_employer, 2) ?? null }}
                                @endif
                            </td>
                            <td>
                                {{ $insurance->policy_number ?? '-' }}
                            </td>
                            <td>
                                {{ $insurance->policy_start_date ?? null }}
                            </td>
                            <td>
                                {{ $insurance->policy_end_date ?? null }}
                            </td>
                            <td>
                                {{ $insurance->policy_maturity_date ?? '-' }}
                            </td>
                            <td>
                                @if (isset($insurance->document_upload) && !is_null($insurance->document_upload))
                                    <a href="{{ asset('uploads/insurance/' . $insurance->document_upload) }}"
                                        target="_blank">{{ asset('uploads/insurance/' . $insurance->document_upload) }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="d-flex">
                                @if ($menuRoles->assignedRoles('insurance.edit'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('insurance.edit', $insurance->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif
                                {{-- @if ($menuRoles->assignedRoles('insurance.show'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('insurance.show', $insurance->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="View">
                                        <i class="icon-eye"></i>
                                    </a>
                                @endif --}}
                                @if ($menuRoles->assignedRoles('insurance.delete'))
                                    <a class="btn btn-outline-danger btn-icon confirmDelete mx-1"
                                        link="{{ route('insurance.delete', $insurance->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No record found.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{-- {{ $businessTrips->appends(request()->all())->links() }} --}}
            </span>
        </div>
    </div>
@endsection

@section('script')
    <script></script>
@endsection
