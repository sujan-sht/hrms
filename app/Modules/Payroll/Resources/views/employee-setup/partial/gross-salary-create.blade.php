<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="text-light btn-slate">
                <th>S.N</th>
                <th>Employee Name</th>
                <th>Gross Salary</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employeeList as $key => $item)
                <tr>
                    <td>{{ '#' . ++$key }}</td>
                    <td>
                        <div class="media">
                            <div class="mr-3">
                                <a href="#">
                                    <img src="{{ $item->getImage() }}"
                                        class="rounded-circle" width="40" height="40" alt="">
                                </a>
                            </div>
                            <div class="media-body">
                                <div class="media-title font-weight-semibold">
                                    {{ $item->getFullName() }}</div>
                                <span
                                    class="text-muted">{{ $item->official_email }}</span>
                            </div>
                        </div>
                    </td>


                    @if (isset($item->employeeGrossSalarySetup))
                        <td><input type="string" name="gross_salary[{{ $item->id }}]" value="{{$item->employeeGrossSalarySetup->gross_salary}}" class="form-control numeric" placeholder="Enter Gross Salary"></td>
                        <td><input type="string" name="grade[{{ $item->id }}]"  hidden value="{{$item->employeeGrossSalarySetup->grade}}" class="form-control" placeholder="Enter Grade"></td>

                    @else
                        <td><input type="string" name="gross_salary[{{ $item->id }}]" class="form-control numeric" placeholder="Enter Gross Salary"></td>
                        <td><input type="string" name="grade[{{ $item->id }}]" hidden class="form-control" placeholder="Enter Grade"></td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="text-right">
    <button type="submit" class="ml-2 mt-2 btn text-white bg-pink btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b> Save</button>
</div>
