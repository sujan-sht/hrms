@extends('admin::layout')

@section('title')
    Insurance Type Details
@endsection

@section('breadcrum')
    <a class="breadcrumb-item active">Insurance Type Details </a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
    @include('insurance::partial.type-advance-filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Insurance Type Details</h6>
                All the Insurance Type Details Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('insurance.type.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('insurance.type.create') }}" class="btn btn-success rounded-pill">Create</a>
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
                        <th>Title</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($insuranceTypes as $key => $insuranceType)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                {{ $insuranceType->title }}
                            </td>
                            <td>
                                <a class="btn btn-sm btn-outline-secondary btn-icon mx-1"
                                    href="{{ route('insurance.type.edit', $insuranceType->id) }}" data-popup="tooltip"
                                    data-placement="top" data-original-title="Edit">
                                    <i class="icon-pencil7"></i>
                                </a>
                                <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete"
                                    link="{{ route('insurance.type.delete', $insuranceType->id) }}" data-popup="tooltip"
                                    data-placement="top" data-original-title="Delete">
                                    <i class="icon-trash-alt"></i>
                                </a>
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
            </span>
        </div>
    </div>
@endsection

@section('script')
    <script></script>
@endsection
