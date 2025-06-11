@extends('admin::layout')

@section('title')
    Designation List
@endsection

@section('breadcrum')
    <a class="breadcrumb-item active">Designations </a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


    {{-- @include('setting::designation.partial.filter') --}}

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Designations</h6>
                All the Designations Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('designation.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('designation.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add
                        Designation</a>
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
                        <th>Short Code</th>
                        <th>Display Short Code</th>
                        <th>Organization</th>
                        <th style="width: 12%;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($designations->total() > 0)
                        @foreach ($designations as $key => $designation)
                            <tr>
                                <td>
                                    #{{ $designations->firstItem() + $key }}
                                </td>
                                <td>{{ $designation->title }}</td>

                                <td>{{ $designation->short_code }}</td>
                                <td>{{ isset($designation->display_short_code) && $designation->display_short_code == 1 ? 'Yes' : 'No' }}
                                </td>
                                <td>
                                    @php
                                        $designationOrgs = $designation->organizations;
                                    @endphp

                                    @if (isset($designationOrgs) && !empty($designationOrgs))
                                        <ul>
                                            @foreach ($designationOrgs as $designationOrg)
                                                <li>{{ optional($designationOrg->organization)->name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                    @endif
                                </td>

                                <td class="text-center d-flex">
                                    {{-- @if ($menuRoles->assignedRoles('designation.viewDetail'))
                                        <a class="btn btn-outline-secondary btn-icon mx-1"
                                            href="{{ route('designation.viewDetail', $designation->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="View">
                                            <i class="icon-eye"></i>
                                        </a>
                                    @endif --}}

                                    @if ($menuRoles->assignedRoles('designation.edit'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('designation.edit', $designation->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('designation.delete'))
                                        <a class="btn btn-outline-danger btn-icon mr-1 confirmDelete"
                                            data-placement="bottom" data-popup="tooltip" data-original-title="Delete"
                                            link="{{ route('designation.delete', $designation->id) }}">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">No record found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $designations->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
@endsection
