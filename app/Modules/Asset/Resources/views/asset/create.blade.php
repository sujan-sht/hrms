@extends('admin::layout')
@section('title') Asset @endSection
@section('breadcrum')
<a href="{{route('asset.index')}}" class="breadcrumb-item">Assets</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'asset.store','method'=>'POST','class'=>'form-horizontal','id'=>'assetFormSubmit','role'=>'form','files' => true]) !!}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Asset Detail</legend>
                        <div class="form-group row">
                            <div class="col-lg-12 mb-3">
                                <div class="row">
                                    <label class="col-form-label col-lg-2">Title : <span class="text-danger">*</span></label>
                                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
										    <input type="text" name="title" class="form-control tokenfield-teal" placeholder="title">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>

            <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                        class="icon-database-insert"></i></b>Save Record</button>
        </div>

    {!! Form::close() !!}

@endSection

@section('script')
    <script src="{{ asset('admin/validation/asset.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>

    <script src="{{ asset('admin/global/js/demo_pages/form_tags_input.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/tags/tokenfield.min.js') }}"></script>
@endsection
