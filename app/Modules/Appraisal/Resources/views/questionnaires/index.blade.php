@extends('admin::layout')
@section('title')
    Questionnaires
@endSection

@section('breadcrum')
    <a href="{{ route('questionnaire.index') }}" class="breadcrumb-item">Questionnaires</a>
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
                <h6 class="media-title font-weight-semibold">List of Questionnaires</h6>
                All the Questionnaires Information will be listed below. You can Create and Modify the data.
            </div>
            <div class="mt-1">
                <a href="{{ route('questionnaire.create') }}" class="btn btn-success rounded-pill"><i
                        class="icon-plus2"></i> Add</a>
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
                        <th>Competence Library</th>
                        <th>Total Competencies</th>
                        <th>Roll Out Date</th>
                        <th>Created Date</th>
                        @if ($menuRoles->assignedRoles('questionnaire.edit'))
                            <th width="12%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($questionnaires->count() != 0)
                        @foreach ($questionnaires as $key => $competencyLibrary)
                            <tr>
                                <td width="5%">#{{ ++$key }}</td>
                                <td>{{ $competencyLibrary->title }}</td>
                                <td>{{ optional($competencyLibrary->competencyLibrary)->title }}</td>
                                <td>{{ count(json_decode($competencyLibrary->competency_ids)) }}</td>
                                @if (setting('calendar_type') == 'BS')
                                    <td>
                                        @if (!is_null($competencyLibrary->roll_out_date))
                                            {{ date_converter()->eng_to_nep_convert($competencyLibrary->roll_out_date) }}
                                    </td>
                                @endif
                            @else
                                <td>{{ $competencyLibrary->roll_out_date }}</td>
                        @endif
                        <td>{{ $competencyLibrary->created_at->format('Y-M-d') }}</td>

                        @if ($menuRoles->assignedRoles('questionnaire.edit'))
                            <td class="d-flex">
                                {{-- @if ($menuRoles->assignedRoles('questionnaire.show'))
                                            <a class="btn btn-outline-secondary btn-icon mx-1 viewDetail"
                                                data-toggle="modal" data-target="#modal_theme_notice"
                                                data-id="{{ $competencyLibrary->id }}" data-popup="tooltip" data-placement="top"
                                                data-original-title="View Detail">
                                                <i class="icon-eye"></i>
                                            </a>
                                        @endif --}}
                                @if ($menuRoles->assignedRoles('questionnaire.showForm'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('questionnaire.showForm', $competencyLibrary->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="View Form Preview">
                                        <i class="icon-eye"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('questionnaire.edit'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('questionnaire.edit', $competencyLibrary->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('questionnaire.delete'))
                                    <a class="btn btn-outline-danger btn-icon confirmDelete"
                                        link="{{ route('questionnaire.delete', $competencyLibrary->id) }}"
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
                        <td colspan="7">No Questionnaires Found !!!</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $questionnaires->appends(request()->all())->links() }}
            </span>
        </div>
    </div>

    <div class="modal modal-form fade" id="modal_theme_notice" tabindex="-1" role="dialog" aria-labelledby="noticeLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noticeLabel">Questionnaire Detail</h5>
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
                url: "{{ route('questionnaire.show') }}",
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
