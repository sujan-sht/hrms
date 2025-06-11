<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="text-light btn-slate">
                <th>S.N</th>
                <th>Employee Name</th>
                @if (!empty($typeList))
                    @foreach ($typeList as $typeId => $title)
                        <th>{{ $title }}</th>
                    @endforeach
                @endif
            </tr>
        </thead>
        <tbody>
                @foreach ($emps as $key => $emp)
                @if ($emp)
                    <tr>
                        <td>{{ '#' . ++$key }}</td>
                        <td>
                            <div class="media">
                                <div class="mr-3">
                                    <a href="#">
                                        <img src="{{ $emp->getImage() }}"
                                            class="rounded-circle" width="40" height="40" alt="">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-semibold">
                                        {{ $emp->getFullName() }}</div>
                                    <span
                                        class="text-muted">{{ $emp->official_email }}</span>
                                </div>
                            </div>
                        </td>

                        @foreach ($emp['types'] as $type_id => $per_day_allowance)
                            <td>
                                {!! Form::number("setups[$emp->id][$type_id]", $per_day_allowance, ['class'=> 'form-control numeric', 'placeholder'=>"Enter amount.."]) !!}
                            </td>
                        @endforeach
                    </tr>
                @endif
                @endforeach
        </tbody>
    </table>
</div>
<div class="text-right">
    <button type="submit" class="ml-2 mt-2 btn text-white bg-pink btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b> Save</button>
</div>
