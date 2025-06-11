<table>
    <thead>
        <tr>
            <th>Employee Code</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>User Name</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($users))
            @foreach ($users as $key => $user)
                <tr>
                    <td>{{ $user['employee_code'] }}</td>
                    <td>{{ $user['first_name'] }}</td>
                    <td>{{ $user['middle_name'] }}</td>
                    <td>{{ $user['last_name'] }}</td>
                    <td>{{ $user['username'] }}</td>
                    <td>{{ $user['role'] }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
