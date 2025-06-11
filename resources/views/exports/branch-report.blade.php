<table>
    <thead>
        <tr>
            <th>S.N.</th>
            <th>Organization</th>
            <th>Branch</th>
            <th>Location</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Manager(Emp Code)</th>
            <th>Remote Allowance</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($branchModels as $key => $branchModel)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ optional($branchModel->organizationModel)->name }}</td>
                <td>{{ $branchModel->name }}</td>
                <td>{{ $branchModel->location }}</td>
                <td>{{ $branchModel->contact }}</td>
                <td>{{ $branchModel->email }}</td>
                <td>{{ optional($branchModel->managerEmployeeModel)->employee_code }}</td>
                <td>{{ $branchModel->remote_allowance==0 ? 'No' : 'Yes' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
