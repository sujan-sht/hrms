@extends('admin::layout')
@section('title')Request @stop
@section('breadcrum')
    <a href="{{ route('employeerequest.index') }}" class="breadcrumb-item">Request Management </a>
    <a class="breadcrumb-item active"> All Requests </a>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@stop

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')


    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Requests</h6>

            </div>
            @if ($menuRoles->assignedRoles('employeeRequest.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('employeeRequest.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                        Add</a>
                </div>
            @endif

            @if ($menuRoles->assignedRoles('employeeRequest.stat'))
                <div class="mt-1">
                    <a href="{{ route('employeeRequest.stat') }}" class="btn btn-primary rounded-pill">Show Request
                        Report</a>
                </div>
            @endif
        </div>
    </div>


    <div class="card">

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>#</th>
                        <th>Title</th>
                        @if (strtolower(auth()->user()->user_type) == 'admin' || auth()->user()->user_type == 'super_admin')
                            <th>Requested By</th>
                        @endif
                        <th>Request Type</th>
                        <th>Request Date</th>
                        <th>Status</th>
                        @if (
                            $menuRoles->assignedRoles('employeeRequest.edit') ||
                                $menuRoles->assignedRoles('employeeRequest.view') ||
                                $menuRoles->assignedRoles('employeeRequest.delete'))
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($employeeRequests->total() > 0)
                        @foreach ($employeeRequests as $request)
                            <tr>
                                <td>{{ ++$loop->index }}</td>
                                <td>{{ $request->title }}</td>
                                @if (auth()->user()->user_type == 'admin' ||
                                        auth()->user()->user_type == 'super_admin' ||
                                        auth()->user()->user_type == 'Admin')
                                    <td>{{ $request->employee ? $request->employee->first_name . ' ' . $request->employee->middle_name . ' ' . $request->employee->last_name : null }}
                                    </td>
                                @endif
                                <td>{{ optional($request->requestType)->title }}</td>
                                <td>{{ date('jS M, Y', strtotime($request->created_at)) }}</td>
                                <td class="text-teal">
                                    <span data-popup="tooltip"
                                        data-original-title="{{ $request->status == 1 ? 'Approved' : 'Not Approved' }}"
                                        class="btn btn-outline btn-icon {{ $request->status == 1 ? 'bg-success text-success border-success' : 'bg-danger text-danger border-danger' }} border-2 rounded-round"
                                        data-placement="bottom">
                                        <i
                                            class="text-white {{ $request->status ? 'icon-checkmark-circle2' : 'icon-cross2' }}"></i>
                                    </span>
                                </td>
                                @if (
                                    $menuRoles->assignedRoles('employeeRequest.edit') ||
                                        $menuRoles->assignedRoles('employeeRequest.view') ||
                                        $menuRoles->assignedRoles('employeeRequest.delete'))
                                    <td>
                                        @if ($menuRoles->assignedRoles('employeeRequest.view'))
                                            <a data-toggle="modal" data-target="#modal_view_request"
                                                class="btn bg-warning btn-icon rounded-round view_request"
                                                data-popup="tooltip" data-original-title="View Detail"
                                                data-placement="bottom" request_id="{{ $request->id }}">
                                                <i class="text-white icon-eye"></i>
                                            </a>
                                        @endif
                                        @if ($menuRoles->assignedRoles('employeeRequest.edit') && $request->status == 0)
                                            <a class="btn bg-info btn-icon rounded-round"
                                                href="{{ route('employeeRequest.edit', $request->id) }}"
                                                data-popup="tooltip" data-placement="bottom" data-original-title="Edit"><i
                                                    class="icon-pencil6"></i></a>
                                        @endif
                                        @if ($menuRoles->assignedRoles('employeeRequest.delete') && $request->status != 1)
                                            <a data-toggle="modal" data-target="#modal_theme_warning"
                                                class="btn bg-danger btn-icon rounded-round delete_request"
                                                link="{{ route('employeeRequest.delete', $request->id) }}"
                                                data-placement="bottom" data-popup="tooltip" data-original-title="Delete"><i
                                                    class="icon-bin"></i></a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>No Data Found!!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <span style="margin: 5px;float: right;">
                @if ($employeeRequests->total() != 0)
                    {{ $employeeRequests->links() }}
                @endif
            </span>
        </div>
    </div>

    {{-- view employee details modal --}}
    <div id="modal_view_request" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h6 class="modal-title">Request Details</h6>
                </div>

                <div class="modal-body">
                    <div class="table-responsive result_view_detail">

                    </div><!-- table-responsive -->
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn bg-teal-400" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Warning modal -->
    <div id="modal_theme_warning" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h6 class="modal-title">Are you sure to Delete a Request ?</h6>
                </div>

                <div class="modal-body">
                    <a class="btn btn-success get_link" href="">Yes</a> &nbsp; | &nbsp;
                    <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
                </div>

                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
    <!-- /warning modal -->

    <script type="text/javascript">
        $('document').ready(function() {
            $('.delete_request').on('click', function() {
                var link = $(this).attr('link');
                $('.get_link').attr('href', link);
            });

            $('.view_request').click(function() {
                var request_id = $(this).attr('request_id');
                $.ajax({
                    type: 'GET',
                    url: '/admin/employeeRequest/view',
                    data: {
                        id: request_id
                    },

                    success: function(response) {
                        $('.result_view_detail').html(response);
                    }
                });
            });
        });
    </script>

@endsection
