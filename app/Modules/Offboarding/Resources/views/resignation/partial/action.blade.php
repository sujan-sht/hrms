<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="row">
                    @if (isset($employeeId))
                        {!! Form::hidden('employee_id', $employeeId, []) !!}
                    @else
                        <div class="col-lg-12 mb-3">
                            <div class="row">
                                <label class="col-form-label col-lg-2">Employee :<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('employee_id', $employeeList, null, [
                                            'placeholder' => 'Select Employee',
                                            'class' => 'form-control select-search',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('employee_id'))
                                        <div class="error text-danger">{{ $errors->first('employee_id') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Last Working Date :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    @php
                                        $dateData1 = null;
                                        if(setting('calendar_type') == 'BS'){
                                            $clData = 'form-control nepali-calendar';
                                            if($isEdit && $resignationModel['last_working_date']){
                                                $dateData1 = date_converter()->eng_to_nep_convert($resignationModel['last_working_date']);
                                            }
                                        }else{
                                            $clData = 'form-control daterange-single';
                                            if($isEdit && $resignationModel['last_working_date']){
                                                $dateData1 = $resignationModel['last_working_date'];
                                            }
                                        }
                                    @endphp
                                    {!! Form::text('last_working_date', $dateData1, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => $clData,
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Reason : </label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::textarea('remark', null, ['rows' => 7, 'placeholder' => 'Write reason..', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Additional Detail</legend>
                <div class="row mb-2">
                    <label class="col-lg-12 col-form-label font-weight-semibold">Letter :</label>
                    <div class="col-lg-12">
                        <input type="file" name="attachment" class="file-input-advanced" accept="application/pdf"
                            data-fouc>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btns btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <!-- validation js -->
    <script src="{{ asset('admin/validation/resignation.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <!-- file uploader js -->
    <script src="{{ asset('admin/global/js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/uploader_bootstrap.js') }}"></script>
@endSection
