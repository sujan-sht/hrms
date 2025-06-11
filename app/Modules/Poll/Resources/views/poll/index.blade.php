@extends('admin::layout')
@section('title') Poll @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Polls</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('poll::poll.partial.advance-filter', ['route' => route('poll.index')])

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Polls</h6>
            All the Polls Information will be listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1">
            <a href="{{ route('poll.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add</a>
        </div>
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Question</th>
                    {{-- <th>Multiple Option Status</th> --}}
                    <th>Start Date</th>
                    <th>Expiry Date</th>
                    <th>Created Date</th>
                    <th>Created By</th>
                    <th width="12%">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($pollModels->total() != 0)
                    @foreach ($pollModels as $key => $pollModel)
                        <tr>
                            <td width="5%">#{{ ++$key }}</td>
                            <td>{{ $pollModel->question }}</td>
                            {{-- <td>{{ $pollModel->getMultipleOptionStatus() }}</td> --}}
                            <td>{{ setting('calendar_type') == 'BS' ? date_converter()->eng_to_nep_convert($pollModel->start_date) : getStandardDateFormat($pollModel->start_date) }}
                            </td>
                            <td>{{ setting('calendar_type') == 'BS' ? date_converter()->eng_to_nep_convert($pollModel->expiry_date) : getStandardDateFormat($pollModel->expiry_date) }}
                            </td>
                            <td>{{ setting('calendar_type') == 'BS' ? date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($pollModel->created_at))) : getStandardDateFormat($pollModel->created_at) }}
                            </td>

                            <td>{{ optional(optional($pollModel->user)->userEmployer)->full_name }}</td>

                            <td class="d-flex">
                                @if ($menuRoles->assignedRoles('poll.allocateForm'))
                                    <a class="btn btn-outline-info btn-icon mx-1"
                                        href="{{ route('poll.allocateForm', $pollModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Allocate Poll">
                                        <i class="icon-task"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('poll.edit'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('poll.edit', $pollModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif

                                @if ($menuRoles->assignedRoles('poll.delete'))
                                    <a class="btn btn-outline-danger btn-icon confirmDelete mx-1"
                                        link="{{ route('poll.delete', $pollModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Poll Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $pollModels->appends(request()->all())->links() }}
        </span>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
@endSection
