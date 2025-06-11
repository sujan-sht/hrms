@extends('admin::layout')
@section('title')Holiday @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Holiday</a>
@stop
@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

@stop
@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')


@section('content')

    @include('holiday::holiday.partial.advance-filter', ['route' => route('holiday.index')])


    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Holidays</h6>
                All the Holiday Informations will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('holiday.create'))
                <div class="mt-1">
                    <a href="{{ route('holiday.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                        Add Holiday</a>
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
                        <th>Organization</th>
                        <th>Branch</th>
                        <th>Province</th>
                        <th>District</th>
                        <th>Holiday Name</th>
                        <th>Gender</th>
                        <th>Religion</th>
                        <th>Status</th>
                        <th>Created By</th>
                        @if ($menuRoles->assignedRoles('holiday.edit') || $menuRoles->assignedRoles('holiday.delete'))
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($holiday->total() != 0)
                        @php
                            if (auth()->user()->user_type == 'hr') {
                                $divisionHr = array_keys(employee_helper()->getParentUserList(['division_hr'], false));
                                array_push($divisionHr, auth()->user()->id);
                            }
                        @endphp
                        @foreach ($holiday as $key => $value)
                            <tr>
                                <td>#{{ $holiday->firstItem() + $key }}</td>
                                <td>{{ optional($value->organizationModel)->name ?? 'All' }}</td>
                                <td>
                                    @php
                                        $branches = ['ALL'];
                                        $provinces = ['ALL'];
                                        $district = ['ALL'];
                                        if ($value['apply_for_all'] == '10') {
                                            $groupId = $value->group_id;
                                            $branches = $groupData->get($groupId)['branch'] ?? [];
                                            $provinces = $groupData->get($groupId)['province'] ?? [];
                                            $district = $groupData->get($groupId)['district'] ?? [];
                                        }
                                    @endphp
                                    {!! implode(
                                        ', ',
                                        array_map(fn($branches) => "<span class='badge badge-success mt-1'>{$branches}</span>", $branches),
                                    ) !!}
                                </td>
                                <td>
                                    {!! implode(
                                        ', ',
                                        array_map(fn($province) => "<span class='badge badge-primary mt-1'>{$province}</span>", $provinces),
                                    ) !!}
                                </td>
                                <td>
                                    {!! implode(
                                        ', ',
                                        array_map(fn($district) => "<span class='badge badge-secondary mt-1'>{$district}</span>", $district),
                                    ) !!}
                                </td>

                                <td>
                                    <ul type="circle">
                                        @foreach ($value->holidayDetail as $item)
                                            <li><b>{{ $item->sub_title }}</b> :
                                                {{ setting('calendar_type') == 'BS' ? $item->nep_date : getStandardDateFormat($item->eng_date) }}
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
                                </td>

                                @if ($menuRoles->assignedRoles('holiday.edit') || $menuRoles->assignedRoles('holiday.delete'))
                                    @php
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

                                    @endphp
                                    <td>

                                        @if ($menuRoles->assignedRoles('holiday.edit'))
                                            @if ($actionFlag)
                                                <a class="btn btn-outline-primary btn-icon mx-1"
                                                    href="{{ route('holiday.edit', $value->id) }}" data-popup="tooltip"
                                                    data-original-title="Edit" data-placement="bottom">
                                                    <i class="icon-pencil7"></i>
                                                </a>
                                            @endif
                                        @endif


                                        {{-- @if ($menuRoles->assignedRoles('holiday.view'))
                                            <a class="btn btn-outline-secondary btn-icon mx-1"
                                                href="{{ route('holiday.view', $value->id) }}" data-popup="tooltip"
                                                data-original-title="View" data-placement="bottom"><i
                                                    class="icon-eye"></i></a>
                                        @endif --}}

                                        @if ($menuRoles->assignedRoles('holiday.delete'))
                                            @if ($actionFlag)
                                                <a data-toggle="modal" data-target="#modal_theme_warning"
                                                    class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                                    link="{{ route('holiday.delete', $value->id) }}" data-popup="tooltip"
                                                    data-original-title="Delete" data-placement="bottom">
                                                    <i class="icon-trash-alt"></i>
                                                </a>
                                            @endif
                                        @endif


                                    </td>
                                @endif

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">No Holiday Found !!!</td>
                        </tr>
                    @endif
                </tbody>

            </table>
            <span style="margin: 5px;float: right;">
                @if ($holiday->total() != 0)
                    {{ $holiday->links() }}
                @endif
            </span>
        </div>
    </div>

@endsection
