@extends('admin::layout')
@section('title') Manpower Requisition Forms @endSection
@section('breadcrum')
<a href="{{route('mrf.index')}}" class="breadcrumb-item">Manpower Requisition Forms</a>
<a class="breadcrumb-item active">View</a>
@stop

@section('content')
<div class="row">
    <div class="col-md-3">&nbsp;</div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <img class="" src="{{ asset('admin/letter_head.jpg') }}" width="100%">
                <br><br>
                <h1 class="text-center">{{ $mrfModel->title }}</h1><br>
                <div class="pl-2">
                    <p>We are looking for an developer who is motivated to combine the art of design with the art of programming. Responsibilities will include the translation of the UI/UX design wireframes to actual code that will produce visual elements of the application. You will work with the UI/UX designer and bridge the gap between graphical design and technical implementation, taking an active role on both sides and defining how the application looks as well as how it works.</p>
                    <br>
                    <h5>Responsibilities</h5>
                    {!! $mrfModel->description !!}
                    <h5>Skills and Qualifications</h5>
                    {!! $mrfModel->specification !!}
                    <h5>Last Submission Date : <span class="text-danger blink_me pl-1">{{ date('M d, Y', strtotime($mrfModel->end_date)) }}</span></h5>
                </div>
                @if(date('Y-m-d') < $mrfModel->end_date)
                    <div class="text-center">
                        <a href="{{ route('applicant.create').'?mrf='.$mrfModel->id }}" class="btn btn-success rounded-pill">Apply Now</a>
                    </div>
                @endif
                <img src="{{ asset('admin/letter_foot.jpg') }}" width="100%">
            </div>
        </div>
    </div>
    <div class="col-md-3">&nbsp;</div>
</div>
@endsection
