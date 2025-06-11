@extends('admin::layout')

@section('title')
    {{ $title }}s
@endsection

@section('breadcrum')
    <a href="{{ route('shift.index') }}" class="breadcrumb-item">Shift </a>
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
                <h6 class="media-title font-weight-semibold">List of Shift</h6>
                All the Shifts Information will be listed below. You can Create and Modify the data.
            </div>
            {{-- <div class="media-body mt-2 mr-2">
                <label for="default">Default Shift:</label>
                <select name="default" id="default" class="form-control-sm" aria-placeholder="Select Default Group">
                    <option value="" {{ @$shiftModels->where('default', 'yes')->count() > 0 ? 'disabled' : '' }}>
                        Select Default Group</option>
                    @if ($shiftModels->total() > 0)
                        @foreach ($shiftModels as $model)
                            <option value="{{ $model->id }}" {{ @$model->default == 'yes' ? 'selected' : '' }}>
                                {{ @$shiftModel->custom_title ?? $model->title }}</option>
                        @endforeach
                    @endif
                </select>
            </div> --}}
            @if ($menuRoles->assignedRoles('shift.create'))
                <div class="mt-1">
                    <a href="{{ route('shift.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                        Add Shift</a>
                </div>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Type</th>
                    <th>Custom Title</th>
                    {{-- <th>Start Time</th>
                    <th>End Time</th> --}}
                    <th style="width: 82px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($shiftModels->total() > 0)
                    @foreach ($shiftModels as $key => $shiftModel)
                        <tr>
                            <td>
                                {{ '#' . ++$key }}
                            </td>
                            <td>
                                {{ $shiftModel->title }}
                            </td>
                            <td>
                                {{ $shiftModel->custom_title }}
                            </td>
                            {{-- <td>
                                {{ $shiftModel->start_time ? date('h:i A', strtotime($shiftModel->start_time)) : '-' }}
                            </td>
                            <td>
                                {{ $shiftModel->end_time ? date('h:i A', strtotime($shiftModel->end_time)) : '-' }}
                            </td> --}}
                            <td class="d-flex">
                                @if ($menuRoles->assignedRoles('shift.edit'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('shift.edit', $shiftModel->id) }}" data-popup="tooltip"
                                        data-placement="bottom" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('shift.delete'))
                                    <a class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                        link="{{ route('shift.delete', $shiftModel->id) }}" data-placement="bottom"
                                        data-popup="tooltip" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No record found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>


    <div class="row">
        <div class="col-12">
            <ul class="pagination pagination-rounded justify-content-end mb-3">
                @if ($shiftModels->total() != 0)
                    {{ $shiftModels->links() }}
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
    <script>
        $(document).ready(function() {
            $('#default').on('change', function() {
                let selectedGroupId = $(this).val();

                $.ajax({
                    url: "{{ route('updateDefaultShift') }}",
                    type: "POST",
                    data: {
                        shift_id: selectedGroupId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Update!',
                                text: 'Default shift updated successfully.',
                                icon: 'success',
                                showCancelButton: false,
                                showConfirmButton: false,
                            });
                            location.reload();
                        } else {
                            alert('Failed to update shift.');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('An error occurred.');
                    }
                });
            });
        });
    </script>
@endsection
