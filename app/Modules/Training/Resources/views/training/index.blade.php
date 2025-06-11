@extends('admin::layout')
@section('title')
    Training
@endSection
@section('breadcrum')
    <a class="breadcrumb-item active">Trainings</a>
@endSection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    @include('training::training.partial.search')
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Training</h6>
                All the Trainings Information will be listed below. You can Create and Modify the data.
            </div>
            <div class="mt-1">
                <a href="{{ route('training.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
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
                        <th>Fiscal Year</th>
                        <th>Organization</th>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>No. of Days</th>
                        <th>Location</th>
                        <th>Facilitator</th>
                        <th>Facilitator's Name</th>
                        <th>Month</th>
                        {{-- <th>Planned Budget</th> --}}
                        {{-- <th>Actual Expense Incurred</th> --}}
                        <th>No. of Participants</th>
                        <th>No. of Mandays</th>
                        {{-- <th>No. of Employees</th> --}}
                        {{-- <th>Status</th> --}}
                        {{-- <th>Created Date</th> --}}
                        <th width="12%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($trainingModels->total() != 0)
                        @foreach ($trainingModels as $key => $trainingModel)
                            {{-- @dd( implode(",",$trainingModel->month)) --}}
                            <tr>
                                <td width="5%">#{{ $trainingModels->firstItem() + $key }}</td>
                                <td>{{ optional($trainingModel->fiscalYearInfo)->fiscal_year }}</td>
                                <td>{{ optional($trainingModel->organization)->name }}</td>
                                <td>{{ $trainingModel->type }}</td>
                                <td>{{ $trainingModel->title }}</td>
                                @php
                                    $fromDate =
                                        setting('calendar_type') == 'BS'
                                            ? date_converter()->eng_to_nep_convert($trainingModel->from_date)
                                            : getStandardDateFormat($trainingModel->from_date);
                                @endphp
                                <td>{{ $trainingModel->from_date ? $fromDate : '-' }}</td>

                                @php
                                    $toDate =
                                        setting('calendar_type') == 'BS'
                                            ? date_converter()->eng_to_nep_convert($trainingModel->to_date)
                                            : getStandardDateFormat($trainingModel->to_date);
                                @endphp
                                <td>{{ $trainingModel->to_date ? $toDate : '-' }}</td>

                                <td>{{ $trainingModel->no_of_days }}</td>
                                <td>{{ $trainingModel->location }}</td>
                                <td>{{ $trainingModel->facilitator }}</td>
                                <td>{{ $trainingModel->facilitator_name }}</td>
                                <td>
                                    {!! $trainingModel->getMonth() !!}
                                </td>
                                {{-- <td>{{ optional($trainingModel->monthInfo)->dropvalue }}</td> --}}
                                {{-- <td>{{ $trainingModel->planned_budget }}</td> --}}
                                {{-- <td>{{ $trainingModel->actual_expense_incurred }}</td> --}}
                                <td>{{ $trainingModel->no_of_participants }}</td>
                                <td>{{ $trainingModel->no_of_mandays }}</td>
                                {{-- <td>{{ $trainingModel->no_of_employee }}</td> --}}
                                {{-- <td>{{ $trainingModel->status }}</td> --}}
                                {{-- <td>{{ $trainingModel->date ? date('M d, Y', strtotime($trainingModel->date)) : '-' }}</td> --}}

                                <td class="d-flex">
                                    @if ($menuRoles->assignedRoles('training.edit'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('training.edit', $trainingModel->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('training.delete'))
                                        <a class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                            link="{{ route('training.delete', $trainingModel->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Delete">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('training.storeTrainer'))
                                        <a class="btn btn-outline-success btn-icon mx-1 btn-trainer" data-popup="tooltip"
                                            data-toggle="modal" data-target="#assignTrainer" data-placement="top"
                                            data-id="{{ $trainingModel->id }}"
                                            data-trainer="{{ json_encode($trainingModel->trainer) }}"
                                            data-original-title="Trainer">
                                            <i class="icon-user-plus"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('training-participant.index'))
                                        <a class="btn btn-outline-indigo btn-icon mx-1"
                                            href="{{ route('training-participant.index', $trainingModel->id) }}"
                                            data-popup="tooltip" data-placement="top"
                                            data-original-title="Training Participant">
                                            <i class="icon-users"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('training-attendance.index'))
                                        <a class="btn btn-outline-success btn-icon mx-1"
                                            href="{{ route('training-attendance.index', $trainingModel->id) }}"
                                            data-popup="tooltip" data-placement="top"
                                            data-original-title="Training Attendance">
                                            <i class="icon-user-check"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('training.view-training-attendees'))
                                        <a class="btn btn-outline-secondary btn-icon mx-1"
                                            href="{{ route('training.view-training-attendees', $trainingModel->id) }}"
                                            data-popup="tooltip" data-placement="top"
                                            data-original-title="View Training Attendees">
                                            <i class="icon-eye"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Training Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $trainingModels->appends(request()->all())->links() }}
            </span>
        </div>
    </div>


    <!-- popup modal -->
    <div id="assignTrainer" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open([
                        'route' => 'training.storeTrainer',
                        'method' => 'POST',
                        'class' => 'form-horizontal assignTrainerForm',
                        'role' => 'form',
                        'id' => 'assignTrainerForm',
                    ]) !!}
                    {!! Form::hidden('training_id', null, ['id' => 'trainingId']) !!}
                    {{-- {!! Form::hidden('id', null, ['id' => 'trainerId']) !!} --}}


                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Full Name:<span class="text-danger"> *</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('full_name', null, ['class' => 'form-control', 'id' => 'full_name']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Email:<span class="text-danger"> *</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Phone:<span class="text-danger"> *</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('phone', null, ['class' => 'form-control numeric', 'id' => 'phone']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Remark:</label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('remark', null, ['class' => 'form-control', 'id' => 'remark']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn bg-success text-white">Save</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    <script src="{{ asset('admin/validation/assignTrainerForm.js') }}"></script>

    <script>
        $(function() {
            $('#assignTrainer').on('show.bs.modal', function(e) {
                var id = $(e.relatedTarget).data('id');
                var trainer = $(e.relatedTarget).data('trainer');
                $(this).find('.modal-title').text('Add Trainer');

                $('#assignTrainerForm').trigger('reset');
                if (trainer != null) {
                    $(this).find('.modal-title').text('Edit Trainer');
                    // $('#trainerId').val(trainer.id);
                    $('#full_name').val(trainer.full_name);
                    $('#email').val(trainer.email);
                    $('#phone').val(trainer.phone);
                    $('#remark').val(trainer.remark);
                }

                $(this).find('#trainingId').val(id);

            })
        })
    </script>
@endSection
