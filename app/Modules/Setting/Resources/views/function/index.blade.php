@extends('admin::layout')

@section('title')
    Sub-Function List
@endsection

@section('breadcrum')
    <a class="breadcrumb-item active">Functions </a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


    {{-- @include('setting::function.partial.filter') --}}

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Functions</h6>
                All the Functions Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('function.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('function.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add
                        Function</a>
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
                        <th style="width: 12%;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($functions->total() > 0)
                        @foreach ($functions as $key => $department)
                            <tr>
                                <td>
                                    #{{ $functions->firstItem() + $key }}
                                </td>
                                <td>{{ $department->title }}</td>
                                <td>{{ @$department->code }}</td>
                                {{-- <td>{{ Str::limit($department->description, 50) }}</td> --}}

                                <td class="text-center d-flex">
                                    {{-- @if ($menuRoles->assignedRoles('function.viewDetail'))
                                        <a class="btn btn-outline-secondary btn-icon mx-1"
                                            href="{{ route('function.viewDetail', $department->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="View">
                                            <i class="icon-eye"></i>
                                        </a>
                                    @endif --}}

                                    @if ($menuRoles->assignedRoles('function.edit'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('function.edit', $department->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('function.delete'))
                                        <a class="btn btn-outline-danger btn-icon mr-1 confirmDelete"
                                            data-placement="bottom" data-popup="tooltip" data-original-title="Delete"
                                            link="{{ route('function.delete', $department->id) }}">
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
                {{ $functions->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
@endsection
