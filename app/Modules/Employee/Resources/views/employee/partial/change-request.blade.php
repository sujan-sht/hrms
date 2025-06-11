@if ($changes)
    @php
        $fields = [
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'mobile' => 'Mobile',
            'phone' => 'Phone',
            'personal_email' => 'Personal Email',
            'permanent_address' => 'Permanent Address',
            'temporary_address' => 'Temporary Address',
            'national_id' => 'National Id No.',
            'passport_no' => 'Passport No.',
            'telephone' => 'Telephone',
            'official_email' => 'Official Email',
            'marital_status' => 'Marital Status',
            'citizenship_no' => 'Citizenship No.',
            'blood_group' => 'Blood Group',
            'ethnicity' => 'Ethnicity',
            'language' => 'Language',
        ];
    @endphp

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
                <tr class="text-light btn-slate">
                    <th>Field</th>
                    <th>Previous Status</th>
                    <th>New Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fields as $key => $label)
                    {{-- @dd($key, $label) --}}
                    <tr>
                        <td>{{ $label }}</td>
                        @if ($key == 'marital_status')
                        @endif
                        <td><span>{{ $changes->{'old_' . $key} }}</span></td>
                        <td><span>{{ $changes->{'new_' . $key} }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
