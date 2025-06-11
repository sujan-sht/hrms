@extends('admin::layout')
@section('title')
    Competency Library
@endSection

@section('breadcrum')
    <a href="{{ route('competence.index') }}" class="breadcrumb-item">Competency Library</a>
    <a class="breadcrumb-item active">List</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Competence</h6>
                All the Competence Information will be listed below. You can Create and Modify the data.
            </div>
            <div class="mt-1">
                <a href="{{ route('competence.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
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
                        <th>Questions Count</th>
                        <th>Created Date</th>
                        @if ($menuRoles->assignedRoles('competence.edit'))
                            <th width="12%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($competencies->count() != 0)
                        @foreach ($competencies as $key => $competence)
                            <tr>
                                <td width="5%">#{{ ++$key }}</td>
                                <td>{{ $competence->name }}</td>
                                <td>
                                    {{ $competence->questions->count() }}
                                </td>
                                <td>{{ $competence->created_at->format('Y-M-d') }}</td>

                                @if ($menuRoles->assignedRoles('competence.edit'))
                                    <td class="d-flex">
                                        @if ($menuRoles->assignedRoles('competence.show'))
                                            <a class="btn btn-outline-secondary btn-icon mx-1 viewDetail"
                                                data-toggle="modal" data-target="#modal_theme_notice"
                                                data-id="{{ $competence->id }}" data-popup="tooltip" data-placement="top"
                                                data-original-title="View Detail">
                                                <i class="icon-eye"></i>
                                            </a>
                                        @endif
                                        @if ($menuRoles->assignedRoles('competence.edit'))
                                            <a class="btn btn-outline-primary btn-icon mx-1"
                                                href="{{ route('competence.edit', $competence->id) }}" data-popup="tooltip"
                                                data-placement="top" data-original-title="Edit">
                                                <i class="icon-pencil7"></i>
                                            </a>
                                        @endif
                                        @if ($menuRoles->assignedRoles('competence.delete'))
                                            <a class="btn btn-outline-danger btn-icon confirmDelete"
                                                link="{{ route('competence.delete', $competence->id) }}"
                                                data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                                <i class="icon-trash-alt"></i>
                                            </a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Competencies Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $competencies->appends(request()->all())->links() }}
            </span>
        </div>
    </div>

    <div class="modal modal-form fade" id="modal_theme_notice" tabindex="-1" role="dialog" aria-labelledby="noticeLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noticeLabel">Competency Detail</h5>
                    <div class="modal-events-close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </div>
                </div>
                <div class="modal-body databody">

                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).on('click', '.viewDetail', function() {
            $('.databody').empty()

            const id = $(this).data('id')

            $.ajax({
                type: 'GET',
                url: "{{ route('competence.show') }}",
                data: {
                    id
                },
                success: function(res) {
                    console.log(res)
                    $('.databody').append(res)
                    return
                }
            });
        })
    </script>
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
@endSection
