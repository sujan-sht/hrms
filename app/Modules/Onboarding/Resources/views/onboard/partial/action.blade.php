<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">MRF :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('manpower_requisition_form_id', $mrfList, request('mrf') ?? null, [
                                        'placeholder' => 'Select MRF',
                                        'class' => 'form-control select-search',
                                        'disabled',
                                    ]) !!}
                                    {!! Form::hidden('manpower_requisition_form_id', request('mrf'), []) !!}
                                </div>
                                @if ($errors->has('manpower_requisition_form_id'))
                                    <div class="error text-danger">{{ $errors->first('manpower_requisition_form_id') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Applicant :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('applicant_id', $applicantList, request('applicant') ?? null, [
                                        'placeholder' => 'Select Applicant',
                                        'class' => 'form-control select-search',
                                        'disabled',
                                    ]) !!}
                                    {!! Form::hidden('applicant_id', request('applicant'), []) !!}
                                </div>
                                @if ($errors->has('applicant_id'))
                                    <div class="error text-danger">{{ $errors->first('applicant_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($boardingTaskModels as $boardingTaskModelGroup)
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            @foreach ($boardingTaskModelGroup as $key => $boardingTaskModel)
                                @if ($key == 0)
                                    <legend class="text-uppercase font-size-sm font-weight-bold">
                                        {{ $boardingTaskModel->getCategory() }}</legend>
                                @endif
                                <div class="row">
                                    <div class="col-lg-4 mb-1">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label
                                                    class="custom-control custom-control-success custom-checkbox pt-2">
                                                    @php
                                                        $checkValue = isset($boardingTaskModel->onboard_date) ? 'checked' : '';
                                                    @endphp
                                                    <input type="checkbox" name="boardingTasks[]"
                                                        class="custom-control-input"
                                                        value="{{ $boardingTaskModel->id }}" {{ $checkValue }}>
                                                    <span
                                                        class="custom-control-label">{{ $boardingTaskModel->title }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mb-1">
                                        <div class="row">
                                            <label class="col-form-label col-lg-4">Remind Date :</label>
                                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                                <div class="input-group">
                                                    @php
                                                        $dateValue = isset($boardingTaskModel->onboard_date) ? (setting('calendar_type') == 'BS' ? date_converter()->eng_to_nep_convert($boardingTaskModel->onboard_date) : $boardingTaskModel->onboard_date) : null;
                                                    @endphp

                                                    @php
                                                        $remindDate = null;
                                                        if(setting('calendar_type') == 'BS'){
                                                            $clData = 'form-control nepali-calendar';
                                                        }else{
                                                            $clData = 'form-control daterange-single';
                                                        }
                                                    @endphp
                                                    {!! Form::text('dates[]', null, [
                                                        'placeholder' => 'YYYY-MM-DD',
                                                        'class' => $clData,
                                                        'autocomplete' => 'off',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mb-1">
                                        <div class="row">
                                            <label class="col-form-label col-lg-4">Status :</label>
                                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                                <div class="input-group">
                                                    @php
                                                        $statusValue = isset($boardingTaskModel->status) ? $boardingTaskModel->status : null;
                                                    @endphp
                                                    {!! Form::select('statuses[]', $statusList, $statusValue, [
                                                        'placeholder' => 'Select Status',
                                                        'class' => 'form-control select-search',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icons icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icons icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <!-- validation js -->
    <script src="{{ asset('admin/validation/mrf.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/editor_ckeditor_default.js') }}"></script>
    {{-- <script src="https://cdn.tiny.cloud/1/cjrqkjizx7e1ld0p8kcygaj4cvzc6drni6o4xl298c5hl9l1/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> --}}
    <script>
        $(document).ready(function() {

            // tinymce.init({
            //     selector: 'textarea.basicTinymce',
            //     height : '245'
            // });

        });
    </script>
@endSection
