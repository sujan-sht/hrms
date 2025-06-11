@section('script')
<script src="{{asset('admin/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_inputs.js')}}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js')}}"></script>
<script src="{{ asset('admin/validation/organization.js')}}"></script>
@endSection

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="form-group row">
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Title :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('title', null, ['placeholder'=>'Enter Title','class'=>'form-control']) !!}
                                </div>
                                @if($errors->has('title'))
                                    <div class="error text-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Category :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('category', $categoryList, null, ['class'=>'form-control select-search']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Description :</label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::textarea('description', null, ['rows'=>4, 'placeholder'=>'Write description here..', 'class'=>'form-control']) !!}
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
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i class="icons icon-database-insert"></i></b>{{ $btnType }}</button>
</div>
