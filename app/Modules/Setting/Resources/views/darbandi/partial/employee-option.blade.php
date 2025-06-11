<option value='0'>Select Employee</option>
@if (!empty($employeeList))
    @foreach ($employeeList as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
@endif
