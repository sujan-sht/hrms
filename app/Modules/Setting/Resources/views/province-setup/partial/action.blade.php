    <script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
    <script src="{{asset('admin/global/js/demo_pages/form_multiselect.js')}}"></script>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <fieldset class="mb-3">
                        <div class="form-group row">

                            <div class="col-lg-6">
                                <label class="col-form-label">Province Title:<span class="text-danger">*</span></label>
                                <div class="form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-office"></i></span>
                                        </span>

                                        {!! Form::text('title', $province->title ?? '', [
                                            'id' => 'title',
                                            'placeholder' => 'Enter Province Title',
                                            'class' => 'form-control',
                                            'required'
                                        ]) !!}
                                    </div>

                                    @if ($errors->has('title'))
                                        <div class="error text-danger">{{ $errors->first('title') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label class="col-form-label">District:<span class="text-danger">*</span></label>
                                {!! Form::select('district_id[]', $districtList ?? [], null, [
                                    'class'=>'form-control multiselect-filtering',
                                    'multiple' => 'multiple'
                                ]) !!}
                                @if ($errors->has('district_id'))
                                <div class="error text-danger">{{ $errors->first('district_id') }}</div>
                            @endif
                            </div>

                        </div>

                    </fieldset>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                    class="icon-database-insert"></i></b>{{ $btnType }}</button>
    </div>
