@extends('admin::layout')

@section('title')
    Grade List
@endsection

@section('breadcrum')
    <a class="breadcrumb-item active">Levels </a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


    {{-- @include('setting::level.partial.filter') --}}

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Levels</h6>
                All the Levels Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('level.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('level.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add
                        Grade</a>
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
                        <th>Organization(s)</th>
                        <th>Title</th>
                        <th>Short Code</th>
                        <th>Display Short Code</th>
                        <th>Designation(s)</th>
                        <th style="width: 12%;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($levels->total() > 0)
                        @foreach ($levels as $key => $level)
                            <tr>
                                <td>
                                    #{{ $levels->firstItem() + $key }}
                                </td>
                                <td>
                                    @php
                                        $levelOrgs = $level->organizations;
                                    @endphp

                                    @if (isset($levelOrgs) && !empty($levelOrgs))
                                        <ul>
                                            @foreach ($levelOrgs as $levelOrg)
                                                <li>{{ optional($levelOrg->organization)->name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                    @endif
                                </td>
                                <td>{{ $level->title }}</td>

                                <td>{{ $level->short_code }}</td>

                                <td>{{ isset($level->display_short_code) && $level->display_short_code == 1 ? 'Yes' : 'No' }}
                                </td>

                                <td>
                                    @php
                                        $levelDesignations = $level->designations;
                                    @endphp

                                    @if (isset($levelDesignations) && !empty($levelDesignations))
                                        <ul>
                                            @foreach ($levelDesignations as $levelDesignation)
                                                <li>{{ optional($levelDesignation->designation)->title }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                    @endif
                                </td>

                                <td class="text-center d-flex">
                                    {{-- @if ($menuRoles->assignedRoles('level.viewDetail'))
                                        <a class="btn btn-outline-secondary btn-icon mx-1"
                                            href="{{ route('level.viewDetail', $level->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="View">
                                            <i class="icon-eye"></i>
                                        </a>
                                    @endif --}}

                                    @if ($menuRoles->assignedRoles('level.edit'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('level.edit', $level->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('level.delete'))
                                        <a class="btn btn-outline-danger btn-icon mr-1 confirmDelete"
                                            data-placement="bottom" data-popup="tooltip" data-original-title="Delete"
                                            link="{{ route('level.delete', $level->id) }}">
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
                {{ $levels->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            //
        });
    </script>
@endsection
