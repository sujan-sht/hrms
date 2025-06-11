{{-- <div class="text-right mb-4 mr-5">
    <a href="{{ route('employeeSetup.showIncomes').'?organization_id=' . $_GET['organization_id'] }}" target="_blank" class="btn btn-success rounded-pill">View Incomes</a>
</div> --}}
<style>
    .widthDefault{
        width: fit-content !important;
    }
    .disabledField{
        background-color: #c1c1c1 !important;
    }
</style>
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="text-light btn-slate">
                <th>S.N</th>
                <th>Employee Name</th>
                @foreach ($incomeList as $k => $income)
                    <th>{{ $income }}</th>
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
                    @php
                        $statusList = ['10' => 'NO', '11' => 'Yes'];
                    @endphp
                    @foreach ($item->employeeIncomeSetup as $i)
                        <td>
                            @if($i['method'] ==2)
                            {!! Form::select($item['id'] . '[' . $i['reference_id'] . ']' . '[' . 'status' . ']', $statusList, $i['status'], [
                                'id' => '',
                                'class' => 'form-control updateStatusField',
                                'data-incomeid'=> $i['reference_id'],
                                'data-empId'=>$i['emp_id'],
                                'data-amountValue'=>$i['amount']
                            ]) !!}
                            @endif
                            {!! Form::text(
                                $item['id'] . '[' . $i['reference_id'] . '][amount]',
                                $i['status']=='10' ? 0 :$i['final_amount'],
                                [
                                    'placeholder' => 'Enter Amount',
                                    'class' => 'numeric form-control widthDefault calculateField updateField'.$i['reference_id']. $i['emp_id'].' hiddenFieldValue' .
                                               $item['id'] . $i['reference_id'] .
                                               ' currentValue' . $item['id'] .
                                               ($i['method'] == '2' ? ' disabledField updateSection' . $item['id'] : ''),
                                    'data-incomeId' => $item['id'],
                                    'data-incomeSetupId' => $i['reference_id'],
                                    'data-employeeId' => $item['employee_id'],
                                    'readonly'=>$i['method'] ==2 ? true : false,
                                    'data-amountValue'=>$i['amount']
                                ]
                            ) !!}
                        </td>
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

@section('script')
    <script>
        $(document).on('change','.updateStatusField',function(){
            const empId=$(this).attr('data-empId');
            const incomeSetupId=$(this).attr('data-incomeid');
            const mainAmount=$(this).attr('data-amountValue');
            const selectedOption=$(this).val();
            if(selectedOption=='10'){
                $(`.updateField${incomeSetupId}${empId}`).val(0);
            }else{
                $(`.updateField${incomeSetupId}${empId}`).val(mainAmount);
            }
            console.log($(this).siblings('.calculateField'));
            $(this).siblings('.calculateField').trigger('click');
        });
        $(document).on('keyup click change blur', '.calculateField', function() {
            var amount = $(this).val();
            var incomeId = $(this).attr('data-incomeId');
            var employeeId = $(this).attr('data-employeeId');
            var incomeSetupId = $(this).attr('data-incomeSetupId');
            var currentValueWithIncomeId = $(`.currentValue${incomeId}`);
            var updateField = $(`.updateSection${incomeId}`);
            var updateFieldValue=[];
            updateField.each(function(index, item) {
                if(incomeSetupId !=$(item).attr('data-incomeSetupId')){
                    var loopIncomeSetupId = $(item).attr('data-incomeSetupId');
                    updateFieldValue.push(loopIncomeSetupId);
                }

            });
            var collectedValue = [];
            currentValueWithIncomeId.each(function(index, item) {
                var loopIncomeSetupId = $(item).attr('data-incomeSetupId');
                collectedValue.push({ id: loopIncomeSetupId, value: $(item).val() });
            });
            $.ajax({
                url: "{{ route('fetchIncomeUpdateCalculation') }}",
                type: "get",
                data: {
                    collectedValue: JSON.stringify(collectedValue),
                    parentIncomeId: incomeId,
                    employeeId: employeeId,
                    updateFieldValue:updateFieldValue
                },
                success: function(response) {
                    if (response.error) {
                        return false;
                    }
                    $.each(response.data, function(key, data) {
                        $(`.hiddenFieldValue${incomeId}${data.reference_id}`).val(data.amount);
                    });
                }
            });
        });

    </script>
@endsection
