<div class="row clone-div mb-2">
    <div class="col-md-12">
        @php
        // dump($dateData['selectedShift']);
        // dd($dateData['selectedShift']);
          $availableKeys = array_keys($shiftArr);
    $selectedValue = in_array($dateData['selectedShift'], $availableKeys) ? $dateData['selectedShift'] : null;
 if($selectedValue == null ){
    $selectedValue = 'D';
 }
    // Optional fallback
    if (!$selectedValue && in_array(3, $availableKeys)) {
        $selectedValue = 3; // Default to Morning Group
    }
        @endphp

        {!! Form::select('shift_group[' . $empModel->id . '][' . $date . ']',
            $shiftArr,
            $selectedValue,
             [
                'placeholder' => 'Select Shift Group',
                'class' => 'form-control select-search shift-select-col',
            ])
        !!}

        {!! Form::hidden('empId', $empModel->id, ['class'=>'employee']) !!}
        {!! Form::hidden('engDate', $date, ['class'=>'date']) !!}
        {!! Form::hidden('shiftArr', json_encode($shiftArr), ['class'=>'shiftArr']) !!}
    </div>
</div>

<script>
    $(document).ready(function () {
    let isBulkUpdate = false;
    const isDefalut = {{ $isDefault }};

    $(document).on('change', '.shift-select-col', function () {
        if (isBulkUpdate) return;

        const changedSelect = $(this);
        const selectedValue = changedSelect.val();
        const changedTd = changedSelect.closest('td');
        const changedRow = changedTd.closest('tr');
        const $loader = $('#loadingSpinner');

        $loader.show();

        // Get data-key instead of index
        const clickedDataKey = changedTd.data('key'); // e.g. 0, 1, 2...

        if (typeof clickedDataKey === 'undefined') {
            console.warn("No data-key found on clicked TD");
            $loader.hide();
            return;
        }

        isBulkUpdate = true;

        try {
            if (selectedValue === 'D') {
                // Loop through current row's tds and find those with data-key % 7 == clickedDataKey % 7
                const targetDataKeyMod = clickedDataKey % 7;

                changedRow.find('td').each(function () {
                    const td = $(this);
                    const tdDataKey = td.data('key');

                    if (typeof tdDataKey === 'undefined') return;

                    // Apply only if:
                    // - data-key % 7 matches clicked one
                    // - column comes after clicked column
                    if (tdDataKey % 7 === targetDataKeyMod && tdDataKey > clickedDataKey) {
                        const targetSelect = td.find('.shift-select-col');

                        if (targetSelect.length && targetSelect.val() !== 'D') {
                            targetSelect.val('D').trigger('change.select2');
                        }
                    }
                });
            } else {
                // Fill forward: apply to all next <td>s in same row
                changedRow.find('td').each(function () {
                    const td = $(this);
                    const tdDataKey = td.data('key');

                    if (typeof tdDataKey === 'undefined') return;

                    if (tdDataKey > clickedDataKey) {
                        const targetSelect = td.find('.shift-select-col');

                        if (targetSelect.length && targetSelect.val() !== selectedValue) {
                            targetSelect.val(selectedValue).trigger('change.select2');
                        }
                    }
                });
            }
        } finally {
            isBulkUpdate = false;
            $loader.hide();
        }
    });
});
</script>


