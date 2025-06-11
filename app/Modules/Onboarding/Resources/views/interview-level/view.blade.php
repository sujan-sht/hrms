@extends('admin::layout')
@section('title') Interview Levels @endSection  
@section('breadcrum')
<a href="{{route('interviewLevel.index')}}" class="breadcrumb-item">Interview Levels</a>
<a class="breadcrumb-item active">View</a>
@stop

@section('content')

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <legend class="text-uppercase font-size-sm font-weight-bold">Level of Interview</legend>
                <ul class="media-list">
                    <li class="media mt-2">
                        <span class="font-weight-semibold mr-3">{{ $interviewLevelModel->title }}</span>
                    </li>
                </ul>
                <br>
                <legend class="text-uppercase font-size-sm font-weight-bold">List of Question</legend>
                <ul class="media-list">
                    @foreach($interviewLevelModel->getQuestionModels as $key => $questionModel)
                        <li class="media mt-2">
                            <span class="font-weight-semibold mr-3">Q{{ ++$key }}.</span>
                            <span>{{ $questionModel->question }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i class="icon-backward2"></i></b>Go Back</a>
</div>

@endSection