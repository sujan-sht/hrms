<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Target Details</legend>
                <div class="form-group row">

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">KRA :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('kra_id', $kraList, null, ['id'=>'kraId', 'placeholder'=>'Select KRA', 'class'=>'form-control select-search']) !!}
                                </div>
                                @if($errors->has('kra_id'))
                                    <div class="error text-danger">{{ $errors->first('kra_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">KPI :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group append_kpi_data">
                                    @if($isEdit)
                                        {!! Form::select('kpi_id', $editKpiData, null, ['id'=>'kpiId', 'placeholder'=>'Select KPI', 'class'=>'form-control select-search']) !!}
                                    @else
                                        {!! Form::select('kpi_id', [], null, ['id'=>'kpiId', 'placeholder'=>'Select KPI', 'class'=>'form-control select-search']) !!}
                                    @endif
                                </div>
                                @if($errors->has('kpi_id'))
                                    <div class="error text-danger">{{ $errors->first('kpi_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                        <label class="col-form-label col-lg-4">Fiscal Year:<span class="text-danger"> *</span></label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('fiscal_year_id', $fiscalYearList, null, ['class'=>'form-control select-search','placeholder'=>'Select Fiscal Year']) !!}
                            </div>
                            @if($errors->has('fiscal_year_id'))
                                <div class="error text-danger">{{ $errors->first('fiscal_year_id') }}</div>
                            @endif
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Title :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('title', null, ['rows'=>5, 'placeholder'=>'Write title here..', 'class'=>'form-control']) !!}
                                </div>
                                @if($errors->has('title'))
                                    <div class="error text-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Frequency :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('frequency', ['yearly'=>'Yearly', 'quarterly'=>'Quarterly', 'monthly' =>'Monthly', 'daily'=>'Daily'], null, ['id'=>'frequency', 'placeholder'=>'Select Frequency', 'class'=>'form-control']) !!}
                                </div>
                                @if($errors->has('frequency'))
                                <div class="error text-danger">{{ $errors->first('frequency') }}</div>
                            @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Category :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('category', null, ['rows'=>5, 'placeholder'=>'Write category name..', 'class'=>'form-control']) !!}
                                </div>
                                @if($errors->has('category'))
                                    <div class="error text-danger">{{ $errors->first('category') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Weightage (in %) :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('weightage', null, ['rows'=>5, 'placeholder'=>'Enter weightage here..', 'class'=>'form-control numeric', 'id'=>'weightageId']) !!}
                                </div>
                                @if($errors->has('weightage'))
                                    <div class="error text-danger">{{ $errors->first('weightage') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">No. of Quarter :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    @if ($isEdit)
                                        {!! Form::text('no_of_quarter', null, ['rows'=>5, 'placeholder'=>'Enter number..', 'class'=>'form-control numeric', 'readonly']) !!}
                                    @else
                                        {!! Form::text('no_of_quarter', null, ['rows'=>5, 'placeholder'=>'Enter number..', 'class'=>'form-control numeric']) !!}
                                    @endif
                                </div>
                                @if($errors->has('no_of_quarter'))
                                    <div class="error text-danger">{{ $errors->first('no_of_quarter') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    {{-- <script src="{{asset('admin/global/js/plugins/forms/styling/uniform.min.js')}}"></script> --}}
    {{-- <script src="{{asset('admin/global/js/demo_pages/form_inputs.js')}}"></script> --}}
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js')}}"></script>
    <script src="{{ asset('admin/validation/target.js')}}"></script>
@endSection
