@extends('admin::layout')
@section('title') Evaluations @endSection  
@section('breadcrum')
<a href="{{route('evaluation.index')}}" class="breadcrumb-item">Evaluations</a>
<a class="breadcrumb-item active">View</a>
@stop

@section('content')

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Questions With Score</legend>
                <div class="row">
                    @foreach($evaluationModel->evaluationDetailModels as $key => $evaluationDetailModel)
                        <div class="col-lg-12 mb-2">
                            <div class="row">
                                <label class="col-lg-8 col-form-label">Q{{ ++$key }}.&emsp;{{ optional($evaluationDetailModel->questionModel)->question }}</label>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <div class="btn-toolbar justify-content-center">
                                            <div class="btn-group mr-2 d-flex">
                                                <button class="btn {{ $evaluationDetailModel->score == '1' ? 'btn-warning' : 'btn-light' }}" type="button">1</button>
                                                <button class="btn {{ $evaluationDetailModel->score == '2' ? 'btn-warning' : 'btn-light' }}" type="button">2</button>
                                                <button class="btn {{ $evaluationDetailModel->score == '3' ? 'btn-warning' : 'btn-light' }}" type="button">3</button>
                                                <button class="btn {{ $evaluationDetailModel->score == '4' ? 'btn-warning' : 'btn-light' }}" type="button">4</button>
                                                <button class="btn {{ $evaluationDetailModel->score == '5' ? 'btn-warning' : 'btn-light' }}" type="button">5</button>
                                                <button class="btn {{ $evaluationDetailModel->score == '0' ? 'btn-warning' : 'btn-light' }}" type="button">Don't Know</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Indexes</legend>
                <table class="table table-striped">
                    <thead>
                        <tr class="text-white">
                            <th>Score</th>
                            <th>Ability</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>5</td>
                            <td>Excellent</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Good</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Average</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Fair</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Poor</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i class="icon-backward2"></i></b>Go Back</a>
</div>

@endSection