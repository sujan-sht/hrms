{!! Form::select($field.'[]', $monthList, @$selectedMonth ?? null, [
    'class' => 'form-control multiselect-select-all-filtering',
    'multiple',
    'required'
], $createdPayrollMonth) !!}


