@extends('admin::layout')
@section('title') Boarding Task @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Boarding Tasks</a>
@stop

@section('script')
<script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
@endSection

@section('content')


<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Boarding Tasks</h6>
            All the Boarding Tasks Information will be listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1">
            <a href="{{ route('boardingTask.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                Add</a>
        </div>
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Created At</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($boardingTaskModels->total() != 0)
                    @foreach ($boardingTaskModels as $key => $boardingTaskModel)
                        <tr>
                            <td width="5%">#{{ $boardingTaskModels->firstItem() + $key }}</td>
                            <td>{{ $boardingTaskModel->title }}</td>
                            <td>{{ $boardingTaskModel->description }}</td>
                            <td>{{ $boardingTaskModel->getCategory() }}</td>
                            <td>{{ setting('calendar_type') == 'BS' ? date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($boardingTaskModel->created_at))) : date('M d, Y', strtotime($boardingTaskModel->created_at)) }}
                            </td>
                            <td>
                                <a class="btn btn-sm btn-outline-secondary btn-icon mx-1"
                                    href="{{ route('boardingTask.edit', $boardingTaskModel->id) }}"
                                    data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                    <i class="icon-pencil7"></i>
                                </a>
                                <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete"
                                    link="{{ route('boardingTask.delete', $boardingTaskModel->id) }}"
                                    data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                    <i class="icon-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Boarding Task Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $boardingTaskModels->appends(request()->all())->links() }}
        </span>
    </div>
</div>

@endsection
