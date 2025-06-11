@extends('admin::layout')
@section('title')Organizational Structure @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Organizational Structure</a>
@stop
@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')


@section('content')


    {{-- @include('organizationalstructure::organizational-structure.partial.advance-filter', ['route' => route('organizationalStructure.index')]) --}}

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Organizational Structure</h6>
                All the Organizational Structure Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('organizationalStructure.create'))
                <div class="mt-1">
                    <a href="{{ route('organizationalStructure.create') }}" class="btn btn-success"><i
                            class="icon-plus2"></i> Add Org Structure</a>
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
                        <th>KRA</th>
                        <th>KPI</th>
                        <th>Designation</th>
                        <th>Job Role</th>
                        {{-- <th>Status</th> --}}
                        {{-- <th>Created By</th> --}}
                        {{-- @if ($menuRoles->assignedRoles('organizationalStructure.edit') || $menuRoles->assignedRoles('organizationalStructure.delete')) --}}
                        <th>Action</th>
                        {{-- @endif --}}
                    </tr>
                </thead>
                <tbody>
                    @if ($orgStructures->total() != 0)
                        {{-- @php
                            if (auth()->user()->user_type == 'hr') {
                                $divisionHr = array_keys(employee_helper()->getParentUserList(['division_hr'], false));
                                array_push($divisionHr, auth()->user()->id);
                            }
                        @endphp --}}
                        @foreach ($orgStructures as $key => $value)
                            <tr>
                                <td>#{{ $orgStructures->firstItem() + $key }}</td>
                                <td>{{ $value->title }}</td>
                                <td>{{ $value->kra }}</td>
                                <td>{{ $value->kpi }}</td>
                                <td>{{ $value->designation }}</td>
                                <td>{{ $value->job_role }}</td>
                                {{-- <td>status</td> --}}


                                {{-- <td>{{ optional($value->organizationModel)->name ?? 'All' }}</td>
                                <td>{{ optional($value->branchModel)->name ?? 'All' }}</td>
                                <td>
                                    <ul type="circle">
                                        @foreach ($value->holidayDetail as $item)
                                            <li><b>{{ $item->sub_title }}</b> : {{ setting('calendar_type') == "BS" ? $item->nep_date : getStandardDateFormat($item->eng_date) }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $value->getGenderType() }}</td>
                                <td>{{ $value->getReligionType() }}</td>

                                <td>
                                    @php
                                        $color = '';
                                        $status = '';
                                        if ($value->status == 11) {
                                            $status = 'Active';
                                            $color = 'success';
                                        } else {
                                            $status = 'Inactive';
                                            $color = 'danger';
                                        }
                                    @endphp
                                    <span class="badge badge-{{ $color }}">{{ $status }}</span>
                                </td>
                                <td>{{ ucfirst(optional($value->createdBy)->full_name) }}
                                </td> --}}

                                {{-- @if ($menuRoles->assignedRoles('organizationalStructure.edit') || $menuRoles->assignedRoles('organizationalStructure.delete')) --}}
                                {{-- @php
                                        $actionFlag = false;
                                        if (auth()->user()->user_type == 'hr') {
                                            if (in_array($value->created_by, $divisionHr)) {
                                                $actionFlag = true;
                                            }
                                        } elseif (auth()->user()->user_type != 'super_admin') {
                                            if (auth()->user()->id == $value->created_by) {
                                                $actionFlag = true;
                                            }
                                        } else {
                                            $actionFlag = true;
                                        }

                                    @endphp --}}
                                <td>
                                    @if ($menuRoles->assignedRoles('organizationalStructure.view'))
                                        <a class="btn btn-outline-secondary btn-icon mx-1"
                                            href="{{ route('organizationalStructure.view', $value->id) }}"
                                            data-popup="tooltip" data-original-title="View" data-placement="bottom"><i
                                                class="icon-eye"></i></a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('organizationalStructure.edit'))
                                        {{-- @if ($actionFlag) --}}
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('organizationalStructure.edit', $value->id) }}"
                                            data-popup="tooltip" data-original-title="Edit" data-placement="bottom">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                        {{-- @endif --}}
                                    @endif

                                    @if ($menuRoles->assignedRoles('organizationalStructure.delete'))
                                        {{-- @if ($actionFlag) --}}
                                        <a data-toggle="modal" data-target="#modal_theme_warning"
                                            class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                            link="{{ route('organizationalStructure.delete', $value->id) }}"
                                            data-popup="tooltip" data-original-title="Delete" data-placement="bottom">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                        {{-- @endif --}}
                                    @endif


                                </td>
                                {{-- @endif --}}
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">No Organizational Structure Found !!!</td>
                        </tr>
                    @endif
                </tbody>

            </table>
            <span style="margin: 5px;float: right;">
                @if ($orgStructures->total() != 0)
                    {{ $orgStructures->links() }}
                @endif
            </span>
        </div>
    </div>

@endsection
