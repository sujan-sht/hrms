<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <input type="hidden" name="evaluation_id" value="{{ $evaluationId }}">
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Join Date :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('join_date', null, ['placeholder'=>'e.g: YYYY-MM-DD','class'=>'form-control daterange-single','autocomplete'=>'off']) !!}
                                </div>
                                @if($errors->has('join_date'))
                                    <div class="error text-danger">{{ $errors->first('join_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Salary :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('salary', null, ['placeholder'=>'e.g: 15000','class'=>'form-control numeric']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Offer Expiry Date :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('expiry_date', null, ['placeholder'=>'e.g: YYYY-MM-DD','class'=>'form-control daterange-single','autocomplete'=>'off']) !!}
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Status :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('status', $statusList, null, ['class'=>'form-control select-search']) !!}
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
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btns btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')

<!-- validation js -->
<script src="{{ asset('admin/validation/offerLetter.js')}}"></script>

<script src="{{asset('admin/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_inputs.js')}}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js')}}"></script>

@endSection
