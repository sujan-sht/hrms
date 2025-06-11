@php
    $fields = [
        'relation_type_title' => 'Relation',
        'name' => 'Name',
        'contact' => 'Contact',
        'dob' => 'DOB',
        'is_emergency_contact' => ['label' => 'Is Emergency Contact', 'boolean' => true],
        'is_dependent' => ['label' => 'Is Dependent', 'boolean' => true],
        'include_in_medical_insurance' => ['label' => 'Include Medical Insurance', 'boolean' => true],
        'same_as_employee' => ['label' => 'Same as Employee', 'boolean' => true],
        'family_address' => 'Family Address',
        'late_status' => ['label' => 'Late Status', 'boolean' => true],
        'is_nominee_detail' => ['label' => 'Is Nominee', 'boolean' => true],
    ];
@endphp

@foreach ($fields as $key => $config)
    @php
        $label = is_array($config) ? $config['label'] : $config;
        $isBoolean = is_array($config) && !empty($config['boolean']);
        $oldValue = @$oldEntity->$key;
        $newValue = @$newEntity->$key;

        if ($isBoolean) {
            $oldValue = $oldValue === null ? '' : ($oldValue == '1' ? 'Yes' : 'No');
            $newValue = $newValue === null ? '' : ($newValue == '1' ? 'Yes' : 'No');
        }

    @endphp
    <tr>
        <td>{{ $label }}</td>
        <td>{{ $oldValue }}</td>
        <td>{{ $newValue }}</td>
    </tr>
@endforeach
