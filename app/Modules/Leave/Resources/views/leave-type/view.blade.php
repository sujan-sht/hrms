<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>
    @inject('dropdownModel', '\App\Modules\Dropdown\Repositories\DropdownRepository')
    @inject('leaveTypeModel', '\App\Modules\Leave\Entities\LeaveType')
    <form action="{{ route('leaveType.show', $leaveType->id) }}">
        <div class="row mb-1 p-1">
            <div class="col-lg-3">
                {!! Form::label('valid', 'Select Valid:') !!}
                {!! Form::select('valid', $yesNoList, request('valid'), ['class' => 'form-control']) !!}
            </div>
            <div class="col-lg-3">
                <input type="submit" value="Search" class="btn btn-success mt-4">
            </div>
        </div>
    </form>


    <div class="table-responsive">
        <table class="table table-striped table-primary align-middle">
            <tbody class="table-group-divider">
                <tr>
                    <th>Title</th>
                    <td>{{ $leaveType->name }}</td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td>{{ $leaveType->gender }}</td>
                </tr>
                <tr>
                    <th>Marital Status</th>
                    <td>{{ $leaveType->marital_status }}</td>
                </tr>
                <tr>
                    <th>Sub-Function</th>
                    <td>
                        @php
                            $departments = $leaveType->departments->pluck('department_id')->toArray();
                            foreach ($departments as $key => $value) {
                                $dropValue = $dropdownModel->getDropdownById($value);
                                echo $dropValue->dropvalue . '(' . $value . ')' . ', ';
                            }
                        @endphp
                    </td>
                </tr>
                <tr>
                    <th>Grade</th>
                    <td> @php
                        $levels = $leaveType->levels->pluck('level_id')->toArray();
                        foreach ($levels as $key => $value) {
                            $dropValue1 = $dropdownModel->getDropdownById($value);
                            echo $dropValue1->dropvalue . '(' . $value . ')' . ', ';
                        }
                    @endphp</td>

                </tr>
                <tr>
                    <th>Job Type</th>
                    <td>{{ $leaveTypeModel::JOB_TYPE[$leaveType->job_type] }}</td>

                </tr>
                <tr>
                    <th>Contract Type</th>
                    <td>{{ $leaveTypeModel::CONTRACT[$leaveType->contract_type] }}</td>

                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-responsive">
        <table class="table table-striped
            table-hover
            align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Organization</th>
                    <th>Gender</th>
                    <th>Marital Status</th>
                    <th>Job Type</th>
                    <th>Contract Type</th>
                    <th>Leave Remaining</th>
                    <th>Valid</th>

                </tr>
            </thead>
            <tbody>
            <tbody class="table-group-divider">
                @foreach ($empList as $item)
                    <tr>
                        <td>{{ $item['emp_id'] }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['organization'] }}</td>
                        <td>{{ optional($dropdownModel->getDropdownById($item['gender']))->dropvalue }}</td>
                        <td>{{ optional($dropdownModel->getDropdownById($item['marital_status']))->dropvalue }}</td>
                        <td>{{ $item['job_type'] }}</td>
                        <td>{{ $item['contract_type'] }}</td>
                        <td>{{ $item['leave_remaining'] }}</td>
                        <td>{{ $item['is_valid'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>
