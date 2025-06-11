@extends('admin::layout')

@section('title')
    Sub-Function List
@endsection

@section('breadcrum')
    <a class="breadcrumb-item active">Sub-Functions </a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


    {{-- @include('setting::department.partial.filter') --}}

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Sub-Functions</h6>
                All the Sub-Functions Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('department.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('department.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add
                        Sub-Function</a>
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
                        <th>Funtion</th>
                        <th>Title</th>
                        <th>Organization</th>
                        <th>Short Code</th>
                        <th>Display Short Code</th>
                        <th>Category</th>
                        <th style="width: 12%;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($departments->total() > 0)
                        @foreach ($departments as $key => $department)
                            <tr>
                                <td>
                                    #{{ $departments->firstItem() + $key }}
                                </td>
                                <td>{{ @$department->getFunction->title }}</td>
                                <td>{{ $department->title }}</td>

                                <td>
                                    @php
                                        $departmentOrgs = $department->organizations;
                                    @endphp

                                    @if (isset($departmentOrgs) && !empty($departmentOrgs))
                                        <ul>
                                            @foreach ($departmentOrgs as $departmentOrg)
                                                <li>{{ optional($departmentOrg->organization)->name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                    @endif
                                </td>
                                <td>{{ $department->short_code }}</td>

                                <td>{{ isset($department->display_short_code) && $department->display_short_code == 1 ? 'Yes' : 'No' }}
                                </td>

                                <td>{{ optional($department->getCategoryInfo)->dropvalue }}</td>
                                {{-- <td>{{ Str::limit($department->description, 50) }}</td> --}}


                                <td class="text-center d-flex">
                                    {{-- @if ($menuRoles->assignedRoles('department.viewDetail'))
                                        <a class="btn btn-outline-secondary btn-icon mx-1"
                                            href="{{ route('department.viewDetail', $department->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="View">
                                            <i class="icon-eye"></i>
                                        </a>
                                    @endif --}}

                                    @if ($menuRoles->assignedRoles('department.edit'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('department.edit', $department->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('department.delete'))
                                        <a class="btn btn-outline-danger btn-icon mr-1 confirmDelete"
                                            data-placement="bottom" data-popup="tooltip" data-original-title="Delete"
                                            link="{{ route('department.delete', $department->id) }}">
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
                {{ $departments->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
@endsection
