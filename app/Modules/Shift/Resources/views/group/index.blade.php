@extends('admin::layout')

@section('title')
    {{ $title }}s
@endsection

@section('breadcrum')
    <a href="{{ route('shiftGroup.index') }}" class="breadcrumb-item">Shift Group</a>
    <a class="breadcrumb-item active"> List </a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


    @include('shift::shift.partial.advance_search')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Shift Groups</h6>
                All the Shift Groups Information will be listed below. You can Create and Modify the data.

            </div>
            @if ($menuRoles->assignedRoles('shiftGroup.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('shiftGroup.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add Shift
                        Group</a>
                </div>
            @endif

        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Group Name</th>
                    {{-- <th>Group Member</th> --}}
                    <th>Shift</th>
                    <th>Default</th>
                    <th>Date From </th>
                    <th>Date To </th>
                    <th>Grace Period (Check in)</th>
                    <th>Grace Period (Check out)</th>
                    <th>Grace Period (Check in for Penalty)</th>
                    <th>Grace Period (Check out for Penalty)</th>
                    <th>Leave Benchmark Time (For First Half)</th>
                    <th>Leave Benchmark Time (For Second Half)</th>

                    <th style="width: 82px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($shiftGroupModels->total() > 0)
                    @foreach ($shiftGroupModels as $key => $shiftGroupModel)
                        <tr>
                            <td>
                                {{ '#' . ++$key }}
                            </td>
                            <td>
                                {{ $shiftGroupModel->group_name }}
                            </td>
                            {{-- <td>
                    <div class="avatar-group">
                        @foreach ($shiftGroupModel->groupMembers as $groupMember)
                        <a href="javascript: void(0);" class="avatar-group-item">
                            <img src="{{ $groupMember->employee->getImage() }}" style="height: 40px;"
                                class="rounded-circle avatar-sm" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                title="{{ $groupMember->employee->getFullName() }}">
                        </a>
                        @endforeach
                    </div>
                </td> --}}
                            <td>
                                {{ optional($shiftGroupModel->shift)->title }}{{ @$shiftGroupModel->shift->seasonal == 1 ? '(Seasonal)' : '' }}
                            </td>
                            <td>{{ @$shiftGroupModel->default }}</td>
                            <td>
                                {{ @$shiftGroupModel->shiftSeason_info['date_from'] }}
                                <br>
                                @if (isset($shiftGroupModel->shiftSeason_info) && !is_null($shiftGroupModel->shiftSeason_info['date_from']))
                                    ({{ date_converter()->eng_to_nep_convert($shiftGroupModel->shiftSeason_info['date_from']) }})
                                @endif
                            </td>
                            <td>
                                {{ @$shiftGroupModel->shiftSeason_info['date_to'] }}
                                <br>
                                @if (isset($shiftGroupModel->shiftSeason_info) && !is_null($shiftGroupModel->shiftSeason_info['date_to']))
                                    ({{ date_converter()->eng_to_nep_convert($shiftGroupModel->shiftSeason_info['date_to']) }})
                                @endif
                            </td>
                            <td> {{ $shiftGroupModel->ot_grace_period }} </td>
                            <td> {{ $shiftGroupModel->grace_period_checkout }} </td>
                            <td> {{ $shiftGroupModel->grace_period_checkin_for_penalty }} </td>
                            <td> {{ $shiftGroupModel->grace_period_checkout_for_penalty }} </td>
                            <td> {{ date('h:i A', strtotime($shiftGroupModel->leave_benchmark_time_for_first_half)) }}
                            </td>
                            <td> {{ date('h:i A', strtotime($shiftGroupModel->leave_benchmark_time_for_second_half)) }}
                            </td>

                            <td class="d-flex">
                                <!-- Large modal -->
                                <a class="btn btn-outline-success btn-icon mx-1" class="btn btn-success" data-toggle="modal"
                                    data-target=".groupmember-{{ $shiftGroupModel->id }}"><i class="icon-eye"></i></a>
                                <div class="modal fade groupmember-{{ $shiftGroupModel->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <!-- Add a wrapper for the scrollable area -->
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="myLargeModalLabel">Group Members</h5>
                                            </div>
                                            <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col"></th>
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Code</th>
                                                            <th scope="col">Designation</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($shiftGroupModel->groupMembers as $groupMember)
                                                            <tr>
                                                                <th scope="row">{{ $loop->iteration }}</th>
                                                                <td>
                                                                    <a href="javascript:void(0);" class="avatar-group-item">
                                                                        <img src="{{ optional($groupMember->employee)->getImage() }}"
                                                                            style="height: 40px;"
                                                                            class="rounded-circle avatar-sm"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="bottom"
                                                                            title="{{ optional($groupMember->employee)->getFullName() }}">
                                                                    </a>
                                                                </td>
                                                                <td>{{ optional($groupMember->employee)->getFullName() }}
                                                                </td>
                                                                <td>{{ optional($groupMember->employee)->employee_code }}
                                                                </td>
                                                                {{-- <td>{{ optional($groupMember->employee->designation)->title ?? '' }}</td> --}}
                                                                <td> </td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                @if ($menuRoles->assignedRoles('shiftGroup.edit'))
                                    <a class="btn btn-outline-primary btn-icon mx-1" data-popup="tooltip"
                                        data-placement="bottom" href="{{ route('shiftGroup.edit', $shiftGroupModel->id) }}"
                                        class="action-icon" title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('shiftGroup.delete'))
                                    <a class="btn btn-outline-danger btn-icon mx-1 confirmDelete" data-placement="bottom"
                                        data-popup="tooltip" data-original-title="Delete"
                                        link="{{ route('shiftGroup.delete', $shiftGroupModel->id) }}">
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

        <div class="row">
            <div class="col-12">
                <ul class="pagination pagination-rounded justify-content-end mb-3">
                    @if ($shiftGroupModels->total() != 0)
                        {{ $shiftGroupModels->links() }}
                    @endif
                </ul>
            </div>
        </div>

    @endsection

    @section('script')
        <!-- Sweet Alerts js -->
        <script src="{{ asset('admin/assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('admin/js/extra_sweetalert.js') }}"></script>
        <!-- Sweet alert init js-->
        <script src="{{ asset('admin/assets/js/pages/sweet-alerts.init.js') }}"></script>
    @endsection
