@extends('admin::layout')
@section('title')TADA Types @stop
@section('breadcrum')
    <a class="breadcrumb-item active"> TADA / Types </a>
@endsection

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Claim & Request Types</h6>
                All the Claim & Request Types Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('tadaType.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('tadaType.create') }}" class="btn btn-success rounded-pill">Add Type</a>
                </div>
            @endif

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive ">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-light btn-slate">
                            <th>#</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Type</th>
                            @if ($menuRoles->assignedRoles('tadaType.edit') || $menuRoles->assignedRoles('tadaType.delete'))
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($tadaTypes->total() > 0)
                            @foreach ($tadaTypes as $key => $type)
                                <tr>
                                    <td>{{ $tadaTypes->firstItem() + $key }}</td>
                                    <td>{{ $type->title }}</td>
                                    {{-- <td class="text-teal">
                                        <span data-popup="tooltip"
                                            data-original-title="{{ $type->status ? 'Active' : 'In-Active' }}"
                                            class="btn btn-outline btn-icon {{ $type->status ? 'bg-success text-success border-success' : 'bg-danger text-danger border-danger' }} border-2 rounded-round">
                                            <i
                                                class="text-white {{ $type->status ? 'icon-checkmark4' : 'icon-cross2' }}"></i>
                                        </span>
                                    </td> --}}
                                    <td>
                                        <span
                                            class="badge badge-{{ $type->getStatusWithColor()['color'] }}">{{ $type->getStatusWithColor()['status'] }}</span>
                                    </td>
                                    <td>{{ $type->type }}</td>
                                    @if ($menuRoles->assignedRoles('tadaType.edit') || $menuRoles->assignedRoles('tadaType.delete'))
                                        <td>
                                            @if ($menuRoles->assignedRoles('tadaType.edit'))
                                                <a class="btn btn-outline-primary btn-icon mx-1"
                                                    href="{{ route('tadaType.edit', $type->id) }}" data-popup="tooltip"
                                                    data-placement="bottom" data-original-title="Edit"><i
                                                        class="icon-pencil7"></i></a>
                                            @endif
                                            @if ($menuRoles->assignedRoles('tadaType.delete'))
                                                <a class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                                    link="{{ route('tadaType.delete', $type->id) }}" data-placement="bottom"
                                                    data-popup="tooltip" data-original-title="Delete"><i
                                                        class="icon-trash-alt"></i></a>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>No Data Found!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <span style="margin: 5px;float: right;">
                    @if ($tadaTypes->total() != 0)
                        {{ $tadaTypes->links() }}
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Warning modal -->
    <div id="modal_theme_warning" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h6 class="modal-title">Are you sure to Delete a TADA type ?</h6>
                </div>

                <div class="modal-body">
                    <a class="btn btn-success get_link" href="">Yes</a> &nbsp; | &nbsp;
                    <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /warning modal -->

    <script type="text/javascript">
        $('document').ready(function() {
            $('.delete_tada_type').on('click', function() {
                var link = $(this).attr('link');
                $('.get_link').attr('href', link);
            });
        });
    </script>

@endsection
