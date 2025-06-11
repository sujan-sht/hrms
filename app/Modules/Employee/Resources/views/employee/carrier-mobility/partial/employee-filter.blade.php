<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>

<div class="col-md-12 employee_id">
    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
        <label class="d-block font-weight-semibold">Select Employee:</label>
        <div class="input-group">
            @php $selected_emp_id = isset(request()->employee_id) ? request()->employee_id : null ; @endphp
            {!! Form::select('employee_id[]', $employees, $selected_emp_id, [
                'class' => 'form-control multiselect-select-all-filtering',
                'id' => 'employee_id',
                'multiple'
            ]) !!}
        </div>
    </div>
</div>