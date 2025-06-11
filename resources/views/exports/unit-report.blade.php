<table>
    <thead>
        <tr>
            <th>S.N.</th>
            <th>Unit Name</th>
            <th>Branch</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($unitModels as $key => $unitModel)
        {{-- @dd($unitModel) --}}
            <tr>
                <td>{{ $key+1}}</td>
                <td>{{ $unitModel->title}}</td>
                <td>{{$unitModel->branch->name}}</td>
                <td>{{$unitModel->status=='1' ? 'Active':'In-Active'}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
