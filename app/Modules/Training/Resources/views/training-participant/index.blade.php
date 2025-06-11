@extends('admin::layout')
@section('title') Training Participant @endSection
@section('breadcrum')
@section('breadcrum')
    <a class="breadcrumb-item" href="{{ route('training.index') }}">Trainings</a>
    <a class="breadcrumb-item active">Training Participants</a>
@endSection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    @include('training::training-participant.partial.search')
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Training Participant</h6>
                All the Training Participants Information will be listed below. You can Create and Modify the data.
            </div>
            <div class="mt-1">
                <a href="{{ route('training-participant.create', $training_id) }}" class="btn btn-success rounded-pill"><i
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
                        {{-- <th>Training Name</th> --}}
                        <th>Participant Name</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                        {{-- <th>Remarks</th> --}}
                        {{-- <th>Created Date</th> --}}
                        <th width="12%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($trainingParticipantModels->total() != 0)
                        @foreach ($trainingParticipantModels as $key => $trainingParticipantModel)
                            <tr>
                                <td width="5%">#{{ $trainingParticipantModels->firstItem() + $key }}</td>
                                {{-- <td>{{ optional($trainingParticipantModel->trainingInfo)->title }}</td> --}}

                                <td>{{ optional($trainingParticipantModel->employeeModel)->full_name }}</td>
                                <td>{{ $trainingParticipantModel->contact_no }}</td>
                                <td>{{ $trainingParticipantModel->email }}</td>
                                {{-- <td>{{ $trainingParticipantModel->remarks }}</td> --}}
                                {{-- <td>{{ $trainingParticipantModel->date ? date('M d, Y', strtotime($trainingParticipantModel->date)) : '-' }}</td> --}}

                                <td>
                                    {{-- @if ($menuRoles->assignedRoles('training-participant.edit'))
                                        <a class="btn btn-outline-primary btn-icon mx-1" href="{{ route('training-participant.edit', ['training_id'=>$training_id, 'id'=>$trainingParticipantModel->id]) }}" data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif --}}

                                    {{-- <a class="btn btn-outline-secondary btn-icon confirmDelete"
                                        link="{{ route('training-participant.delete', ['training_id' => $training_id, 'id' => $trainingParticipantModel->id]) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Transfer">
                                        <i class="icon-transfer"></i>
                                    </a> --}}

                                    @if ($menuRoles->assignedRoles('training-participant.delete'))
                                        @if (optional($trainingParticipantModel->getAttendee())->status == null)
                                            <a class="btn btn-outline-danger btn-icon confirmDelete"
                                                link="{{ route('training-participant.delete', ['training_id' => $training_id, 'id' => $trainingParticipantModel->id]) }}"
                                                data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                                <i class="icon-trash-alt"></i>
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Training Participant Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $trainingParticipantModels->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
@endsection

@section('script')
    {{-- <script src="{{asset('admin/global/js/plugins/tables/datatables/datatables.min.js')}}"></script> --}}
    {{-- <script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script> --}}
    {{-- <script src="{{asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script> --}}
    {{-- <script src="{{asset('admin/global/js/demo_pages/form_multiselect.js')}}"></script> --}}
@endSection
