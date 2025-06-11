
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>

{{-- <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
    <label class="d-block font-weight-semibold">Select Users:</label>
    <div class="input-group">
        {!! Form::select('employee_id[]', $employees, $value = null, [
            'id' => 'employee_id',
            'class' => 'form-control multiselect-select-all-filtering',
            'multiple'=>'multiple',
            'data-fouc',
        ]) !!}
    </div>
</div> --}}
<div class="col-lg-12 mb-3 employee_id">
    <div class="row form-group mb-0">
        <label class="col-form-label col-lg-4">Select Employees:</label>
        <div class="col-lg-8">
            <div class="input-group">
                {!! Form::select('employee_id[]', $employees, $value = null, [
                    'id' => 'employee_id',
                    'class' => 'form-control multiselect-select-all-filtering employee_id',
                    'multiple',
                    'required',
                ]) !!}
            </div>
        </div>
        @if ($errors->has('employee_id'))
            <div class="error text-danger">{{ $errors->first('employee_id') }}</div>
        @endif
    </div>
</div>