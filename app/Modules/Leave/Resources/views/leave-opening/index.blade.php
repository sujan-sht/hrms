@extends('admin::layout')
@section('title') Leave Opening @endSection
@section('breadcrum')
    <a class="breadcrumb-item active">Leave Openings</a>
@stop

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Organization</h6>
                All the Organization Information will be listed below.
            </div>
        </div>
    </div>

    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Organization Name</th>
                        <th width="12%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($organizations->total() != 0)
                        @foreach($organizations as $key => $organization)
                            <tr>
                                <td width="5%">#{{ $organizations->firstItem() +$key }}</td>
                                <td>{{ $organization->name }}</td>
                                <td>
                                    <a href="{{ route('leaveOpening.show',['id'=>$organization->id,'leave_year_id'=>getCurrentLeaveYearId()]) }}" class="btn btn-outline-secondary btn-icon rounded-round view_leave_type"  data-placement="bottom" data-popup="tooltip" data-original-title="View Leave openings" ><i class="icon-eye"></i></a>

                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Organization Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $organizations->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
@endsection
