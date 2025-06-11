<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="text-light btn-slate">
                <th>S.N</th>
                @foreach($columns as $column)
                    <th>{{$column}}</th>
                @endforeach
                @if (!empty($typeList))
                    @foreach ($typeList as $typeId => $title)
                        <th>{{ $title }}</th>
                    @endforeach
                @endif
            </tr>
        </thead>
        <tbody>
                @foreach ($getSetWiseAllowaceSetups as $key => $emp)
                {{-- @dd($emp) --}}
                @if ($emp)
                    <tr>
                        <td>{{ '#' . ++$key }}</td>
                        @foreach($emp['columns'] as $indexValue=>$colData)
                            <td>{!! @$colData !!}</td>
                        @endforeach
                        @foreach ($emp['types'] as $type_id => $per_day_allowance)
                            <td>
                                {!! Form::number("setups[{$emp['id']}][{$type_id}]", $per_day_allowance, ['class'=> 'form-control numeric', 'placeholder'=>"Enter amount.."]) !!}
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
