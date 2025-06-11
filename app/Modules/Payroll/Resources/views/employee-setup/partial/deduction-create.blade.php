@inject('deductionRepo', '\App\Modules\Payroll\Repositories\DeductionSetupRepository')
<div class="table-responsive">

    <table class="table table-hover">
        <thead>
            <tr class="text-light btn-slate">
                <th>S.N</th>
                <th>Employee Name</th>
                @foreach ($deductionList as $k => $deduction)
                    <th>{{ $deduction }}</th>
                @endforeach

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
                    @php
                        $statusList = ['10' => 'NO', '11' => 'Yes'];
                    @endphp
                    @if (count($item->employeeDeductionSetup) > 0)
                        @foreach ($item->employeeDeductionSetup as $i)
                            @php
                                $deductionModel = $deductionRepo->find($i['reference_id']);
                                $class = $deductionModel->method == 2 ? 'readonly' : '';
                            @endphp
                            <td>
                                {!! Form::select($item['id'] . '[' . $i['reference_id'] . ']' . '[' . 'status' . ']', $statusList, $i['status'], [
                                    'id' => '',
                                    'class' => 'form-control select2',
                                ]) !!}
                                 {!! Form::text($item['id'] . '[' . $i['reference_id'] . ']' . '[' . 'amount' . ']',  $i['amount'], [
                                    'placeholder' => 'Enter Amount',
                                    'class' => 'form-control numeric',$class,
                                ]) !!}
                            </td>
                        @endforeach
                    @else
                        @foreach ($deductionList as $k => $deduction)
                            <td>
                                {!! Form::select($item->id . '[' . $k . ']', $statusList, null, ['id' => '', 'class' => 'form-control select2']) !!}
                            </td>
                        @endforeach
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>



</div>
<div class="text-right">
    <button type="submit" class="ml-2 mt-1 btn text-white bg-pink btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b> Save</button>
</div>
