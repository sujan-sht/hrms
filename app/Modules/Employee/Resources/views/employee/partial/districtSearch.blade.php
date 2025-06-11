@if (isset($districts) && $districts->count() > 0)
    @foreach ($districts as $district)
        <option value="{{ $district->id }}">{{ $district->district_name }}</option>
    @endforeach
@endif
