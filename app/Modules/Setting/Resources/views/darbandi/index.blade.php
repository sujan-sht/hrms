@extends('admin::layout')
@section('title') Darbandi Setup @stop
@section('breadcrum')
    <a href="{{ route('darbandi.index') }}" class="breadcrumb-item">Darbandi Setup</a>
    <a class="breadcrumb-item active"> Add Darbandi </a>
@endsection

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Darbandis</h6>
            </div>
            @if ($menuRoles->assignedRoles('darbandi.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('darbandi.create') }}" class="btn btn-success rounded-pill">Add Darbandi</a>
                </div>
            @endif


        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($menuRoles->assignedRoles('bulkupload.uploadDarbandis'))
                <div class="darbandi_imp_exp">
                    <a href="{{ asset('samples/darbandi-sample.xlsx') }}" download="darbandi-sample"
                        class="text-light btn bg-primary btn-labeled btn-labeled-left"><b><i
                                class="icon-download"></i></b>Sample Sheet</a>
                    <form method="POST" action="{{ route('bulkupload.uploadDarbandis') }}" accept-charset="UTF-8"
                        role="form" enctype="multipart/form-data" id="employee-form"
                        style="display: flex;align-items:center;">
                        @csrf
                        <button type="submit" class="text-light btn bg-primary btn-labeled btn-labeled-left"><b><i
                                    class="icon-upload"></i></b>Upload</button>
                        <div class="position-relative">
                            <span class="form-text text-muted mt-o">Accepted formats: xls, xlsx</span>
                            <input type="file" class="h-auto mb-3" name="upload_darbandi" accept=".xlx, .xlsx" required>
                        </div>

                    </form>
                </div>
            @endif

            <div class="table-responsive ">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-light btn-slate">
                            <th>#</th>
                            <th>Organization</th>
                            <th>Designation</th>
                            <th>No. Of Position</th>
                            {{-- <th>Sorting Order</th> --}}

                            @if ($menuRoles->assignedRoles('darbandi.edit') || $menuRoles->assignedRoles('darbandi.delete'))
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($darbandis->count() > 0)
                            @foreach ($darbandis as $key => $darbandi)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $darbandi->organization->name ?? '' }}</td>
                                    <td>{{ $darbandi->designation->title ?? '' }}</td>
                                    <td>{{ $darbandi->no ?? '' }}</td>
                                    {{-- <td>{{ $darbandi->sorting_order }}</td> --}}

                                    @if ($menuRoles->assignedRoles('darbandi.edit') || $menuRoles->assignedRoles('darbandi.delete'))
                                        <td>

                                            @if ($menuRoles->assignedRoles('darbandi.edit'))
                                                <a class="btn btn-outline-primary btn-icon mx-1"
                                                    href="{{ route('darbandi.edit', $darbandi->id) }}" data-popup="tooltip"
                                                    data-placement="bottom" data-original-title="Edit"><i
                                                        class="icon-pencil7"></i></a>
                                            @endif
                                            @if ($menuRoles->assignedRoles('darbandi.delete'))
                                                <a class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                                    link="{{ route('darbandi.delete', $darbandi->id) }}"
                                                    data-placement="bottom" data-popup="tooltip"
                                                    data-original-title="Delete"><i class="icon-trash-alt"></i></a>
                                            @endif



                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>No Device Data Found!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <!-- Warning modal -->
    <div id="modal_theme_warning" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h6 class="modal-title">Are you sure to Delete a Device?</h6>
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



@endsection
