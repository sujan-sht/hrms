@extends('admin::layout')
@section('title') Training Attendees @endSection
@section('breadcrum')
@section('breadcrum')
    <a class="breadcrumb-item" href="{{ route('training.index')}}">Trainings</a>
    <a class="breadcrumb-item active">Training Attendees</a>
@endSection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
    @include('training::training.partial.training-attendees-search')
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Training Attendees</h6>
                All the Training Attendees Information will be listed below.
            </div>
        </div>
    </div>

    <div class="card card-body">

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Training Name</th>
                        <th>Attendee Name</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                        <th>Remarks</th>
                        <th>Feedback</th>
                        <th width="12%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($attendeeDetails))
                        @foreach($attendeeDetails as $key => $attendeeDetail)
                            <tr>
                                <td width="5%">#{{ $key+1 }}</td>
                                <td>{{ optional($attendeeDetail->trainingInfo)->title }}</td>
                                <td>{{ optional($attendeeDetail->employeeModel)->full_name }}</td>
                                <td>{{ $attendeeDetail->contact_no }}</td>
                                <td>{{ $attendeeDetail->email }}</td>
                                <td>{{ $attendeeDetail->remarks }}</td>
                                <td>{{ $attendeeDetail->feedback }}</td>

                                <td>
                                    @if($menuRoles->assignedRoles('training.view-training-certificate'))
                                        <a class="btn btn-outline-secondary btn-icon mx-1" href="{{ route('training.view-training-certificate', ['training_id'=>$training_id, 'id'=>$attendeeDetail->id]) }}" data-popup="tooltip" data-placement="top" data-original-title="View Training Certificate">
                                            <i class="icon-eye"></i>
                                        </a>
                                    @endif

                                    @if($menuRoles->assignedRoles('training.print-training-certificate'))
                                        <a class="btn btn-outline-warning btn-icon print-window" target="_blank" href="{{route('training.print-training-certificate',['training_id'=>$training_id, 'id'=>$attendeeDetail->id])}}" data-popup="tooltip" data-placement="top" data-original-title="Print Training Certificate">
                                            <i class="icon-printer"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Training Attendees Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{-- {{ $attendeeDetail->appends(request()->all())->links() }} --}}
            </span>
        </div>
    </div>
@endsection
