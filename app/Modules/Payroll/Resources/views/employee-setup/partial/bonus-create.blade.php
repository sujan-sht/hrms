<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="text-light btn-slate">
                <th>S.N</th>
                <th>Employee Name</th>
                {{-- <th>Gross Salary</th> --}}
                @foreach ($bonusList as $k => $bonus)
                    <th>{{ $bonus }}</th>
                @endforeach

            </tr>
        </thead>
        <tbody>
            {{-- @dd($employeeList) --}}
            @foreach ($employeeList as $key => $item)
                <tr>
                    <td>{{ '#' . ++$key }}</td>
                    <td>
                        <div class="media">
                            <div class="mr-3">
                                <a href="#">
                                    <img src="{{ $item->getImage() }}" class="rounded-circle" width="40" height="40"
                                        alt="">
                                </a>
                            </div>
                            <div class="media-body">
                                <div class="media-title font-weight-semibold">
                                    {{ $item->getFullName() }}</div>
                                <span class="text-muted">{{ $item->official_email }}</span>
                            </div>
                        </div>
                    </td>
                    @foreach ($item->employeeBonusSetup as $i)
                        <td><input type="string" name="{{ $item['id'] }}[{{ $i['bonus_setup_id'] }}]"
                                value="{{ $i['amount'] }}" class="form-control numeric" placeholder="Enter bonus"></td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="text-right">
    <button type="submit" class="ml-2 mt-2 btn text-white bg-pink btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b> Save</button>
</div>
