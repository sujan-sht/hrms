@extends('admin::layout')
@section('title') Attendee Training Certificate @endSection
@section('breadcrum')
    {{-- <a href="{{route('training.view-training-attendees', $training_id)}}" class="breadcrumb-item">Training Attendees</a> --}}
    <a class="breadcrumb-item active">Training Certificate</a>
@stop

@section('content')
    {!! $final_html !!}
@endsection
